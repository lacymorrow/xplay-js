<?php

/**
 * This class reads a directory of media files and generates a
 * XML/XSPF formatted playlist
 * which may be used in concordance with Play.js
 * 
 * @author     lacymorrow
 * @website    www.lacymorrow.com
 * @copyright  Copyright (c) 2012 Lacy Morrow. All rights reserved.
 * @license    Proprietary/Closed Source
*/

########################
# play.js
# Lacy Morrow 2012
# www.lacymorrow.com
# getID3 - HTML5BoilerPlate - MediaElement.js
########################

####################
###   SETTINGS   ###
####################

# GENERATE Play.js
$play = true;

# USE ID3 TAGS TO AUTOMATICALLY FILL TRACK INFORMATION
# (as opposed to specifying in the directory structure, e.g. 'media/artist/album/track.mp3')
$id3 = true;

# CACHE PLAYLIST - boolean
$cache = true;

# CACHE PLAYLIST FILE - path/url
$playlist = 'xplay_generated_playlist.xml';

# RETRIEVE ARTWORK - boolean
$artwork = true;

# MEDIA DIRECTORY - path/url - relative
$media = 'media';

#############################
###   ADVANCED SETTINGS   ###
#############################
$settings = array();

# PLAYER WIDTH
$settings['width'] = 300;
$settings['height'] = 225;
$settings['alphabetize'] = true; // FIX
$settings['autoplay'] = true; // FIX
$settings['autoresume'] = true; // FIX


# PLAYLIST FORMAT - string
# %c% - creator
# %a% - album
# %t% - title
# %o% - annotation
# %i% - info
# %f% - filename
# %n% - track number
# %N% - track number w/ leading zero
$settings['format'] = '%t%';

# SWF FALLBACK URL
//$swfurl = 'xspf_jukebox.swf';



#####################################
###  DO NOT EDIT BELOW THIS LINE  ###
#####################################


#####################################
###   BEGIN PLAYLIST GENERATION   ###
#####################################
// include id3
include_once('./getid3/getid3.php');

/*
 * Create playlist array
 */
// Create variables
global $playArr, $imgArr, $gloArr;
$playArr = array();
$imgArr = array();
$gloArr = array();
// use cached playlist if exists and valid
if ($cache === true && file_exists ( $playlist ) && (date("z")-date("z", filemtime($playlist)) >= 7)){
	$playFile = file_get_contents( $playlist );
}
// no valid playlist - begin playlist generation
else {
	// initiate directory scan
	$trackArr = scanMedia($media,$id3);
	$playFile = generateXML($trackArr);
	if($cache === true){
		// Save playlist
		$fh = fopen($playlist, 'w') or die("can't write playlist file");
		fwrite($fh, $playFile);
		fclose($fh);
	}
}
if($play == true){
	(!isset($trackArr)) ? generateJS($playFile,$settings) : generateJS($playFile, $settings, $trackArr);
} else {
	// Output Playlist
	echo $playFile;
}

#####################################
###    END PLAYLIST GENERATION    ###
#####################################


#####################################
###           FUNCTIONS           ###
#####################################
#####################################
/* 
 * generateJS
 * generates the play-js controls
 */
#####################################
function generateJS($playFile,$settings,$trackArr = null){
	//!!!WHAT IF LOADING FROM CACHE - NO TRACKARR
	$out = '';
	foreach($trackArr as $trackVal){
		$f = $settings['format'];
		$f = str_replace('%c%',$trackVal['creator'],$f);
		$f = str_replace('%a%',$trackVal['album'],$f);
		$f = str_replace('%t%',$trackVal['title'],$f);
		$f = str_replace('%o%',$trackVal['annotation'],$f);
		$f = str_replace('%i%',$trackVal['info'],$f);
		$f = str_replace('%f%',$trackVal['location'],$f);
		$f = str_replace('%n%',($i+1),$f);
		$f = str_replace('%N%',str_pad(($i+1),2,"0",STR_PAD_LEFT),$f);
		$out .= '<li><a href="#" onclick="return false;" rel="';
		$out .= $trackVal['creator'].'|';
		$out .= $trackVal['album'].'|';
		$out .= $trackVal['title'].'|';
		$out .= $trackVal['annotation'].'|';
		$out .= $trackVal['info'].'|';
		$out .= $trackVal['duration'].'|';
		$out .= $trackVal['image'].'|';
		$out .= $trackVal['type'].'|';
		foreach($trackVal['location'] as $l){
			$out .= $trackVal['path'].'/'.$l.'|';
		}
		$out .= '">'.$f.'</a></li>';
	}
	$out .= '<li id="playjs-reset"><a href="#" onclick="return false;">Reset</a></li>';
	$js = 'jQuery(function() { '.PHP_EOL;
	$js .= 'jQuery(\'#playjs-playlist\').html(\''.$out.'\');'.PHP_EOL;
	$js .= 'jQuery("[id^=playjs]").each(function(){'.PHP_EOL;
	$js .= 'if(jQuery(this).html()){'.PHP_EOL;
	$js .= 'jQuery(this).attr("rel",jQuery(this).html());'.PHP_EOL;
	$js .= '}});'.PHP_EOL;
	$js .= 'jQuery(document).delegate("#playjs-playlist li a", "click", function() {'.PHP_EOL;
	$js .= 'var attributes = jQuery(this).attr("rel");'.PHP_EOL;
	$js .= 'var attr = attributes.split("|");'.PHP_EOL;
	//$js .= 'jQuery("#playjs-reset a").click();'.PHP_EOL;
	$js .= 'if(attr[7] == "audio"){'.PHP_EOL;
	$js .= 'jQuery("#playjs-creator").html(attr[0]);'.PHP_EOL;
	$js .= 'jQuery("#playjs-album").html(attr[1]);'.PHP_EOL;
	$js .= 'jQuery("#playjs-title").html(attr[2]);'.PHP_EOL;
	$js .= 'jQuery("#playjs-annotation").html(attr[3]);'.PHP_EOL;
	$js .= 'jQuery("#playjs-info").html(attr[4]);'.PHP_EOL;
	$js .= 'jQuery("#playjs-duration").html(attr[5]);'.PHP_EOL;
	$js .= 'jQuery("#playjs-image").html("<img src=\""+attr[6]+"\" alt=\"\" />");'.PHP_EOL;
	$js .= 'jQuery("#playjs-player").html("<audio id=\"playjs-audio\" controls=\"controls\" width=\"'.$settings['width'].'\"><source src=\""+attr[8]+"\" type=\"audio/"+attr[8].split(".").pop()+"\" />Your browser does not support audio.</audio>");'.PHP_EOL;
	$js .= 'jQuery("audio").mediaelementplayer({ success: function(player, node){} });'.PHP_EOL;
	$js .= '} else if(attr[7] == "video") {'.PHP_EOL;
	$js .= 'jQuery("#playjs-image").html("");'.PHP_EOL;
	$js .= 'jQuery("#playjs-creator").html(attr[0]);'.PHP_EOL;
	$js .= 'jQuery("#playjs-album").html(attr[1]);'.PHP_EOL;
	$js .= 'jQuery("#playjs-title").html(attr[2]);'.PHP_EOL;
	$js .= 'var out="<video width=\"'.$settings['width'].'\" height=\"'.$settings['height'].'\" poster=\""+attr[6]+"\" controls=\"controls\">";'.PHP_EOL;
	$js .= 'var i = 8; while(attr[i]){'.PHP_EOL;
		$js .= 'out += "<source src=\""+attr[i]+"\" type=\"video/"+attr[i].split(".").pop()+"\" />";'.PHP_EOL;
	$js .= 'i++; }'.PHP_EOL;
	$js .= 'out += "Your browser does not support the video tag.</video>";'.PHP_EOL;
	$js .= 'jQuery("#playjs-player").html(out);'.PHP_EOL;
	$js .= 'jQuery("video").mediaelementplayer({ success: function(player, node){} });'.PHP_EOL;
	$js .= '}'.PHP_EOL;
	$js .= '});'.PHP_EOL;
	$js .= 'jQuery("#playjs-reset a").click(function() {'.PHP_EOL;
	$js .= 'jQuery("[id^=playjs]").each(function(){'.PHP_EOL;
	$js .= 'jQuery(this).html(jQuery(this).attr("rel"));'.PHP_EOL;
	$js .= '});'.PHP_EOL;
	$js .= '});'.PHP_EOL;
	$js .= '});'.PHP_EOL;
	echo $js;
}
#####################################
/* 
 * generateXML
 * generates an XSPF/XML document to be processed
 */
#####################################

function generateXML($trackArr){
	$out = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
	$out .= '<playlist version="1" xmlns="http://xspf.org/ns/0/">'.PHP_EOL;
	$out .= '  <trackList>'.PHP_EOL;
	foreach($trackArr as $l){
		$out .= '    <track>'.PHP_EOL;
		foreach($l['location'] as $i){
			$out .= '      <location>'.$l['path'].'/'.htmlentities($i).'</location>'.PHP_EOL;
		}
		$out .= '      <creator>'.$l['creator'].'</creator>'.PHP_EOL;
		$out .= '      <album>'.$l['album'].'</album>'.PHP_EOL;
		$out .= '      <title>'.$l['title'].'</title>'.PHP_EOL;
		$out .= '      <annotation>'.$l['annotation'].'</annotation>'.PHP_EOL;
		$out .= '      <duration>'.$l['duration'].'</duration>'.PHP_EOL;
		$out .= '      <image>'.$l['image'].'</image>'.PHP_EOL;
		$out .= '      <info>'.$l['info'].'</info>'.PHP_EOL;
		$out .= '      <type>'.$l['info'].'</type>'.PHP_EOL;
		$out .= '    </track>'.PHP_EOL;
	}
	$out .= '  </trackList>'.PHP_EOL;
	$out .= '</playlist>';
	return $out;
}


#####################################
/* 
 * scanMedia
 * scans directory structure for media files 
 * and generates a playlist array
 */
#####################################

function scanMedia( $path = '.', $id3, $level = 0, $dir = ''){ 
	global $playArr, $imgArr, $gloArr;
    $ignore = array( 'cgi-bin', '.', '..' ); 
    // Directories to ignore
    $dh = @opendir( $path ); 
    // Open the directory to the handle $dh 
    while( false !== ( $file = readdir( $dh ) ) ){ 
    // Loop through the directory 
     
        if( !in_array( $file, $ignore ) ){ 
        // Check that this file is not to be ignored 
            if( is_dir( "$path/$file" ) ){ 
            	if( $level == 1 ){ $creator = $file; } else { $creator = ''; }
				if( $level == 2 ){ $album = $file; } else { $album = ''; }
				// Store images for Creator/Album
	            scanMedia( "$path/$file",$id3, ($level+1), (($dir == '')?$path:$dir)); 
                // Re-call this same function but on a new directory. 
                // this is what makes function recursive. 
             
            } else { 
            	$ext = pathinfo($file, PATHINFO_EXTENSION);
				// generate file id - COULD BE MORE INTUITIVE (removes extension)
				$filename = pathinfo($file, PATHINFO_FILENAME);
				if(strtolower($filename) == 'artwork' && checkType($ext)){
					if($level == 0){ 
						$globalImg = "$path/$file"; 
					} else { 
						$gloArr[] = array('path' => $path, 'file' => $file);
					}
				}
				
				// if image file
				$ct = checkType($ext);
				if($ct == 'image'){
					// have not logged associated track, save for later
					$imgArr[] = array('image' => "$path/$file", 'filename' => $filename);
				// if track file
				} else if($ct == 'audio') {
					// create track object
					// Initialize getID3 engine //
					$getID3 = new getID3;
					// Analyze file and store returned data in $id3Info
					$id3Info = $getID3->analyze("$path/$filename.$ext"); //!!! Could need absolute path					
					/*
					 Optional: copies data from all subarrays of [tags] into [comments] so
					 metadata is all available in one location for all tag formats
					 metainformation is always available under [tags] even if this is not called
					*/
					getid3_lib::CopyTagsToComments($id3Info);
					$iDuration = (isset($id3Info['playtime_string'])) ? $id3Info['playtime_string'] : '';
					if($id3 == true){
						$iCreator = (isset($id3Info['comments_html']['artist'][0]) ? $id3Info['comments_html']['artist'][0] : $creator);
						$iAlbum = (isset($id3Info['comments_html']['artist'][0]) ? $id3Info['comments_html']['album'][0] : $album);
						$iTitle = (isset($id3Info['comments_html']['title'][0]) ? $id3Info['comments_html']['title'][0] : $filename);
						$iAnnotation = (isset($id3Info['comments_html']['comment'][0]) ? $id3Info['comments_html']['comment'][0] : '');
					}
					$playArr[$filename] = array('filename' => $filename, 'type' => checkType($ext), 'creator' => $iCreator, 'album' => $iAlbum, 'title' => $iTitle, 'annotation' => $iAnnotation, 'duration' => $iDuration, 'location' => array($file), 'image' => '', 'info' => '', 'path' => $path);
				} else if($ct == 'video') {
					//IF VIDEO
					if($playArr[$filename]['location'] === null){
						$l = array($file);
					} else {
						$l = $playArr[$filename]['location'];
						array_push($l,$file);
					}
					$playArr[$filename] = array('filename' => $filename, 'type' => checkType($ext), 'creator' => $creator, 'album' => $album, 'title' => $filename, 'annotation' => '', 'duration' => '', 'location' => $l, 'image' => '', 'info' => '', 'path' => $path);
				}
             
            }
         
        } 
     
    } 
    closedir( $dh ); 
    // Close the directory handle 

    // Merge loose image array with associated tracks
    foreach($playArr as &$playVal){
	    for ($i=0;$i<sizeOf($imgArr);$i++){
	    	//Apply track image
	    	if ($imgArr[$i]['filename'] == $playVal['filename']){
	    		$playVal['image'] = $imgArr[$i]['image'];
	    	} else {
	    	// Apply album/creator image
    		    for ($k=0;$k<sizeOf($gloArr);$k++){
	    		    if ($gloArr[$k]['path'] == $playVal['path']){
		    			$playVal['image'] = ''.$gloArr[$k]['path'].'/'.$gloArr[$k]['file'];
		    		}
    		    }
	    	}
    	}
    	//echo $globalImg;
    	// Apply global image
    	if($playVal['image'] == '' && $globalImg != ''){ $playVal['image'] = $globalImg; }
    	
    }
    
    // Directory Scan Complete
	if($dir == ''){
		return $playArr;
	}
} 


#####################################
/* 
 * checkType
 * checks for valid filetype
 */
#####################################

function checkType($ext){
	$musTypes = array( 'mp3','wav','ogg' );
	$vidTypes = array( 'mp4','webm','ogv' );
	$pixTypes = array( 'jpg', 'jpeg', 'gif', 'png' );
	if( in_array($ext, $musTypes) ){
		return 'audio';
	} else if( in_array($ext, $pixTypes) ){
		return 'image';
	} else if( in_array($ext, $vidTypes) ){
		return 'video';
	} else {
		return 'invalid';
	}
}


?>

<?php
///////////////////////////////////////////////////////////////////////////////
/// Name: AdManager															///
/// Version: 3.0															///
/// Author: Allembru														///
/// Website: <http://www.allembru.com/>										///
/// Credits: jQuery <http://jquery.com/>, Fancybox <http://fancybox.net/>,	///
///			 jQuery Cycle <http://jquery.malsup.com/cycle/>,				///
///			 Tutorialzine <http://tutorialzine.com/>						///
///////////////////////////////////////////////////////////////////////////////
/// AdManager v3.0 Ad Management Solution									///
/// Copyright (C) 2012  Allembru											///
///																			///
/// This program is free software: you can redistribute it and/or modify	///
/// it under the terms of the GNU General Public License as published by	///
/// the Free Software Foundation, either version 3 of the License.			///
///																			///
/// This program is distributed in the hope that it will be useful,			///
/// but WITHOUT ANY WARRANTY; without even the implied warranty of			///
/// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the			///
/// GNU General Public License for more details.							///
///																			///
/// You should have received a copy of the GNU General Public License		///
/// along with this program.  If not, see <http://www.gnu.org/licenses/>	///
///////////////////////////////////////////////////////////////////////////////

header ("Content-type: image/jpeg");
if(isset($_GET['i'])) {
	$file_name=$_GET['i'];
	$file_type= explode('.', $file_name);
	$file_type = $file_type[count($file_type) -1];
	$file_type=strtolower($file_type);
	if($file_type=='jpg'){
		$original_image_gd = imagecreatefromjpeg($file_name);
	}
	if($file_type=='gif'){ 
		$original_image_gd = imagecreatefromgif($file_name);
	}
	if($file_type=='png'){
		$original_image_gd = imagecreatefrompng($file_name);
	}
	imagejpeg($original_image_gd);
}
if(isset($_GET['c'])) {
	$file_name=urldecode($_GET['c']);
	$crop_height=$_GET['h'];
	$crop_width=$_GET['w'];
	$file_type= explode('.', $file_name);
	$file_type = $file_type[count($file_type) -1];
	$file_type=strtolower($file_type);
	$original_image_size = getimagesize($file_name);
	$original_width = $original_image_size[0];
	$original_height = $original_image_size[1];
	if($file_type=='jpg'){
		$original_image_gd = imagecreatefromjpeg($file_name);
	}
	if($file_type=='gif'){ 
		$original_image_gd = imagecreatefromgif($file_name);
	}
	if($file_type=='png'){
		$original_image_gd = imagecreatefrompng($file_name);
	}
	$cropped_image_gd = imagecreatetruecolor($crop_width, $crop_height);
	$wm = $original_width /$crop_width;
	$hm = $original_height /$crop_height;
	$h_height = $crop_height/2;
	$w_height = $crop_width/2;
	if($original_width > $original_height ){
		$adjusted_width =$original_width / $hm;
		$half_width = $adjusted_width / 2;
		$int_width = $half_width - $w_height;
		imagecopyresampled($cropped_image_gd ,$original_image_gd ,-$int_width,0,0,0, $adjusted_width, $crop_height, $original_width , $original_height );
	} else
	if(($original_width < $original_height ) || ($original_width == $original_height )){
		$adjusted_height = $original_height / $wm;
		$half_height = $adjusted_height / 2;
		$int_height = $half_height - $h_height;
		imagecopyresampled($cropped_image_gd , $original_image_gd ,0,-$int_height,0,0, $crop_width, $adjusted_height, $original_width , $original_height );
	} else {
		imagecopyresampled($cropped_image_gd , $original_image_gd ,0,0,0,0, $crop_width, $crop_height, $original_width , $original_height );
	}
	imagejpeg($cropped_image_gd);
}
?>
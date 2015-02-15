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

require_once("config.php");

function get_campaigns(){
	$dir = '../campaigns/';
	$listDir = array();
	$i=0;
	if(is_dir($dir)) {
		if($handler = opendir($dir)) {
			while (($sub = readdir($handler)) !== FALSE) {
				if ($sub != "." && $sub != "..") {
					if(is_dir($dir."/".$sub)){
						$settings = $dir.$sub."/settings.txt";
						$handle = fopen($settings, 'r');
						$data = fread($handle, filesize($settings));
						$campaign = explode("|",$data);

						$listDir[$i]['name'] = $campaign[0];
						$listDir[$i]['folder'] = $sub;
						$listDir[$i]['transEffect'] = $campaign[1];
						$listDir[$i]['transSpeed'] = $campaign[2];
						$listDir[$i]['transDelay'] = $campaign[3];
						$i++;
					}
				}
			}
			closedir($handler);
		}
		if(count($listDir)>0) {
			foreach($listDir as $key=>$value) {
				$sort_path[] = $value['folder'];
			}
			array_multisort($sort_path, SORT_ASC, $listDir);
			return $listDir;
		} else {
			return false;	
		}
	}
}
function get_ads($campaign){
	$dir = '../campaigns/'.$campaign.'/';
	$listDir = array();
	$i=0;
	if(is_dir($dir)) {
		if($handler = opendir($dir)) {
			while (($sub = readdir($handler)) !== FALSE) {
				if ($sub != "Thumb.db" && $sub != "Thumbs.db" && $sub != "settings.txt") {
					if(is_file($dir."/".$sub)) {
						$adpath = $dir.$sub;
						$handle = fopen($adpath, 'r');
						$data = fread($handle, filesize($adpath));
						$adName = explode("|",$data);

						$listDir[$i]['name'] = $adName[0];
						$listDir[$i]['file'] = str_replace(".txt","",$sub);
						
						fclose($handle);
						$i++;
					}
				}
			}
			closedir($handler);
		}
		if(count($listDir)>0) {
			foreach($listDir as $key=>$value) {
				$sort_path[] = $value['file'];
			}
			array_multisort($sort_path, SORT_ASC, $listDir);
			return $listDir;
		} else {
			return false;	
		}
	}
}
function campaignName($campaignid) {
	$settings = '../campaigns/'.$campaignid."/settings.txt";
	$handle = fopen($settings, 'r');
	$data = fread($handle, filesize($settings));
	$campaign = explode("|",$data);
	return $campaign[0];
}
function adName($campaignid, $adid) {
	$adpath = '../campaigns/'.$campaignid."/".$adid.".txt";
	$handle = fopen($adpath, 'r');
	$data = fread($handle, filesize($adpath));
	$adName = explode("|",$data);
	return $adName[0];
}
function countCampaigns() {
	$dir = '../campaigns/';
	$i=0;
	if(is_dir($dir)) {
		if($handler = opendir($dir)) {
			while (($sub = readdir($handler)) !== FALSE) {
				if ($sub != "." && $sub != "..") {
					if(is_dir($dir."/".$sub)){
						$i++;
					}
				}
			}
		}
	}
	return $i;
}
function getEffects() {
	$effects = array(
				"blindX"=>"Blind X",
				"blindY"=>"Blind Y",
				"blindZ"=>"Blind Z",
				"cover"=>"Cover",
				"curtainX"=>"Curtain X",
				"curtainY"=>"Curtain Y",
				"fade"=>"Fade",
				"fadeZoom"=>"Fade Zoom",
				"growX"=>"Grow X",
				"growY"=>"Grow Y",
				"scrollUp"=>"Scroll Up",
				"scrollDown"=>"Scroll Down",
				"scrollLeft"=>"Scroll Left",
				"scrollRight"=>"Scroll Right",
				"scrollHorz"=>"Scroll Horz",
				"scrollVert"=>"Scroll Vert",
				"shuffle"=>"Shuffle",
				"slideX"=>"Slide X",
				"slideY"=>"Slide Y",
				"toss"=>"Toss",
				"turnUp"=>"Turn Up",
				"turnDown"=>"Turn Down",
				"turnLeft"=>"Turn Left",
				"turnRight"=>"Turn Right",
				"uncover"=>"Uncover",
				"wipe"=>"Wipe",
				"zoom"=>"Zoom"
				);
	return $effects;
}
function sasImport($type, $merchantId="") {
	$myTimeStamp = gmdate(DATE_RFC1123);
	$APIVersion = 1.5;
	if($type=="apicount") {
		$actionVerb = "apitokencount";
	}
	if($type=="merchants") {
		$actionVerb = "merchantStatus";
	}
	if($type=="creatives") {
		$actionVerb = "merchantCreative";
	}
	$sig = SAS_TOKEN.':'.$myTimeStamp.':'.$actionVerb.':'.SAS_SECRET;
	$sigHash = hash("sha256",$sig);
	$myHeaders = array("x-ShareASale-Date: $myTimeStamp","x-ShareASale-Authentication: $sigHash");
	$ch = curl_init();
	if($type=="apicount") {
		curl_setopt($ch, CURLOPT_URL, "https://shareasale.com/x.cfm?action=$actionVerb&affiliateId=".SAS_AFFID."&token=".SAS_TOKEN."&version=$APIVersion");
	}
	if($type=="merchants") {
		curl_setopt($ch, CURLOPT_URL, "https://shareasale.com/x.cfm?action=$actionVerb&affiliateId=".SAS_AFFID."&token=".SAS_TOKEN."&programStatus=onlineNotLowFunds&version=$APIVersion");
	}
	if($type=="creatives") {
		curl_setopt($ch, CURLOPT_URL, "https://shareasale.com/x.cfm?action=$actionVerb&affiliateId=".SAS_AFFID."&token=".SAS_TOKEN."&merchantId=$merchantId&version=$APIVersion");
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER,$myHeaders);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$returnResult = curl_exec($ch);
	if ($returnResult) {
		if (stripos($returnResult,"Error Code ")) {
			// error occurred
		} else{
			// success
			return $returnResult;
		}
	} else {
		// connection error
	}
	curl_close($ch);	
}
function adsNewSubmit($campaign, $aname, $acode) {
	$ads = get_ads($campaign);
	if($ads) {
		$count = count($ads);
	} else {
		$count=0;	
	}
	$file = "../campaigns/".$campaign."/".str_pad((int) $count+1,3,"0",STR_PAD_LEFT).".txt";
	$handle = fopen($file, 'w');
	$data = $aname."|".$acode."\n";
	fwrite($handle, $data);
	fclose($handle);	
}
function adsEdit($id) {
	$fileid = explode("-",$id);
	$file = "../campaigns/".$fileid[0]."/".$fileid[1].".txt";
	$handle = fopen($file, 'r');
	$data = fread($handle, filesize($file));
	$info = explode("|",$data);
	return $info;
}
function adsEditSubmit($aid, $aname, $acode) {
	$file = explode("-",$aid);
	$fileName = "../campaigns/".$file[0]."/".$file[1].".txt";
	$handle = fopen($fileName, 'w') or die("can't open file");
	$data = $aname."|".$acode."\n";
	fwrite($handle, $data);
	fclose($handle);
}
function adsDelete($aid) {
	$file = explode("-",$aid);
	$adfile = '../campaigns/'.$file[0].'/'.$file[1].'.txt';
	if(file_exists($adfile)) {
		unlink($adfile);
	}
	$ads = get_ads($campaign);
	if($ads) {
		$i=1;
		foreach($ads as $value) {
			$number = str_pad((int) $i,3,"0",STR_PAD_LEFT);
			$now = '../campaigns/'.$campaign.'/'.$value['file'].'.txt';
			$new = '../campaigns/'.$campaign.'/'.$number.'.txt';
			rename($now, $new);
			$i++;
		}
	}
}
function adsPreview($aid) {
	$file = explode("-",$aid);
	$adfile = "../campaigns/".$file[0]."/".$file[1].".txt";
	$handle = fopen($adfile, 'r');
	$data = fread($handle, filesize($adfile));
	$info = explode("|",$data);
	return $info;
}
function adsSort($aid, $direction) {
	$file = explode("-",$aid);
	$adfile = '../campaigns/'.$file[0].'/'.$file[1].'.txt';
	$adfileTmp = '../campaigns/'.$file[0].'/'.$file[1].'.txt.tmp';
	if($direction=="down") {
		$newad = str_pad((int) $file[1]+1,3,"0",STR_PAD_LEFT);
	}
	if($direction=="up") {
		$newad = str_pad((int) $file[1]-1,3,"0",STR_PAD_LEFT);
	}
	$adfileNew = '../campaigns/'.$file[0].'/'.$newad.'.txt';
	$adfileNewTmp = '../campaigns/'.$file[0].'/'.$newad.'.txt.tmp';
	if(file_exists($adfile)) {
		rename($adfile, $adfileTmp);
	}
	if(file_exists($adfileNew)) {
		rename($adfileNew, $adfileNewTmp);
	}
	if(file_exists($adfileTmp)) {
		rename($adfileTmp, $adfileNew);
	}
	if(file_exists($adfileNewTmp)) {
		rename($adfileNewTmp, $adfile);
	}
}
function campaignsNewSubmit($cname, $transEffect, $transSpeed, $transDelay, $order) {
	$campaigns = get_campaigns();
	if($campaigns) {
		$count = count($campaigns);
	} else {
		$count=0;	
	}
	$folderName = str_pad((int) $count+1,3,"0",STR_PAD_LEFT);
	$newDir = "../campaigns/".$folderName;
	mkdir($newDir, 0777);
	$fileName = $newDir."/settings.txt";
	$handle = fopen($fileName, 'w');
	$data = $cname."|".$transEffect."|".$transSpeed."|".$transDelay."|".$order."\n";
	fwrite($handle, $data);
	fclose($handle);	
}
function campaignsEdit($campaign) {
	$settings = "../campaigns/".$campaign."/settings.txt";
	$handle = fopen($settings, 'r');
	$data = fread($handle, filesize($settings));
	$info = explode("|",$data);
	return $info;
}
function campaignsEditSubmit($campaign, $cname, $transEffect, $transSpeed, $transDelay, $order) {
	$settings = "../campaigns/".$campaign."/settings.txt";
	$handle = fopen($settings, 'w') or die("can't open file");
	$data = $cname."|".$transEffect."|".$transSpeed."|".$transDelay."|".$order."\n";
	fwrite($handle, $data);
	fclose($handle);
}
function campaignsDelete($campaign) {
	$ads = get_ads($campaign);
	if($ads) {
		$info = 'notempty';
	} else {
		$settings = '../campaigns/'.$campaign.'/settings.txt';
		if(file_exists($settings)) {
			unlink($settings);
		}
		$campaignfile = '../campaigns/'.$campaign;
		if(is_dir($campaignfile)) {
			rmdir($campaignfile);
		}
		$campaigns = get_campaigns();
		if($campaigns) {
			$i=1;
			foreach($campaigns as $value) {
				$number = str_pad((int) $i,3,"0",STR_PAD_LEFT);
				
				$now = '../campaigns/'.$value['folder'];
				$new = '../campaigns/'.$number;
				rename($now, $new);
				$i++;
			}
		}
		$info = 'success';
	}
	return $info;
}
	
function cjGetMerchants() {
	$targeturl = "https://advertiser-lookup.api.cj.com/v3/advertiser-lookup?advertiser-ids=joined";
	$ch = curl_init($targeturl);
	curl_setopt($ch, CURLOPT_POST, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.CJ_DEVKEY)); // send development key
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = json_decode(json_encode((array) simplexml_load_string( curl_exec($ch) )),1);;
	curl_close($ch);
	
	return $response['advertisers']['advertiser'];
}

function cjGetAds($advertiserid,$pagnumber='') {
	if($pagnumber=='') {
		$targeturl = "https://linksearch.api.cj.com/v2/link-search?website-id=".CJ_SITEID."&advertiser-ids=".$advertiserid."&records-per-page=100";
	} else {
		$targeturl = "https://linksearch.api.cj.com/v2/link-search?website-id=".CJ_SITEID."&advertiser-ids=".$advertiserid."&page-number=".$pagnumber."&records-per-page=100";
	}
	$ch = curl_init($targeturl);
	curl_setopt($ch, CURLOPT_POST, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.CJ_DEVKEY)); // send development key
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$response = json_decode(json_encode((array) simplexml_load_string( curl_exec($ch) )),1);;
	curl_close($ch);
	
	$creatives_total = $response['links']['@attributes']['total-matched'];
	$creatives_returned = $response['links']['@attributes']['records-returned'];
	$creatives_page = $response['links']['@attributes']['page-number'];
	
	if($creatives_total>$creatives_returned) {
		$pages = $creatives_total/100;
		if($creatives_page<$pages) {
			$newResponse = cjGetAds($advertiserid,$creatives_page+1);
			$response = array_merge($response['links']['link'],$newResponse['links']['link']);
		}
	} else {
		$response = $response['links']['link'];
	}
	return $response;
}
?>
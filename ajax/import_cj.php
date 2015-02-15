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

require_once("../inc/functions.php");

if( defined('SAS_AFFID') && defined('SAS_TOKEN') && defined('SAS_SECRET') ) {
	if($_POST['action']=='merchants') {
		$merchants = cjGetMerchants();
		if(count($merchants)==0) {
			die('importerror');	
		}
?>
    <form>
        <label for="merchant">
            CJ Merchant
            <span class="small">Select a CJ merchant to import from.</span>
        </label>
        <select id="merchant">
            <option value="">Select a Merchant...</option>
            <?php
            if(count($merchants)>1) {
                foreach($merchants as $merchant) {
                    echo '<option value="'.$merchant['advertiser-id'].'">'.$merchant['advertiser-name'].'</option>';
                }
            } else {
                echo '<option value="'.$merchant['advertiser-id'].'">'.$merchant['advertiser-name'].'</option>';
            }
            ?>
        </select>
        <div id="creatives" class="clear"></div>
        <div id="confirmButtons">
            <a href="#" class="button gray" id="cancel" onclick="return false;">Close Import<span></span></a>
        </div>
    </form>
<?php 
	}
} else {
	echo '<form>AdManager has integrated with the Commission Junction affiliate network to allow you to easily import your affiliate banners and ads directly into your AdManager campaigns.<br /><br />Once you have an account setup with Commission Junction, update your AdManager Settings with your Commission Junction developer information to unlock the import functionality.</form>';	
}
 
if($_POST['action']=='creatives') {
	$creatives = cjGetAds($_POST['merchantid']);
	if(count($creatives)==0) {
		die('importerror');	
	}
?>
<link rel="stylesheet" type="text/css" href="js/fancybox/source/jquery.fancybox.css">
<style type="text/css">
.image {
	display:block;
	float:left;
	margin:2px;
	padding:2px;
	border:1px solid #999;
	text-align:center;
}
.image a {
	display:block;
	margin:2px;
	padding:3px;
	background-color:#666;
	color:#FFF;
	border:1px solid #CCC;
	text-decoration:none;
}
.image a:hover {
	background-color:#CCC;
	color:#333;
	border:1px solid #FFF;
	text-decoration:underline;
}
</style>
<?php
	if(count($creatives)>1) {
		foreach($creatives as $link) {
			if($link['creative-width']>0 && $link['creative-height']>0) {
				$pattern = '/src="([^"]*)"/';
				preg_match($pattern, $link['link-code-html'], $matches);
				echo '<div class="image">';
				echo '<img src="'.$matches[1].'" width="97" height="97"><br />';
				echo 'Width: '.$link['creative-width'].'<br />';
				echo 'Height: '.$link['creative-height'];
				echo '<a class="fancybox" rel="" href="'.$matches[1].'" title="">Preview</a>';
				echo '<a onclick="return false;" href="#" class="import" id="'.$link['link-id'].'">Import</a>';
				echo '<input type="hidden" id="data'.$link['link-id'].'" value="'.urlencode($link['link-code-html']).'">';
				echo '<input type="hidden" id="name'.$link['link-id'].'" value="'.urlencode($link['link-name']).'">';
				echo '</div>';
			}
		}
	}
}

if($_POST['action']=='import') {
	$linkdata = urldecode($_POST['linkdata']);
	$linkname = urldecode($_POST['linkname']);
	$ads = get_ads($_POST['campaign']);
	if($ads) {
		$count = count($ads);
	} else {
		$count=0;	
	}
	$newFile = str_pad((int) $count+1,3,"0",STR_PAD_LEFT);
	$ourFileName = "../campaigns/".$_POST['campaign']."/".$newFile.".txt";
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	$stringData = $linkname."|".$linkdata."\n";
	fwrite($ourFileHandle, $stringData);
	fclose($ourFileHandle);
}
?>
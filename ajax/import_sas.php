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
		$file = "../cache/sas_merchants.txt";
		if($_POST['update']=='true') {
			$merchants = sasImport("merchants");
			$data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $merchants);
			if($data!='') {
				if(file_exists($file)) {
					unlink($file);
				}
				$handle = fopen($file, 'w') or die("can't open file");
				fwrite($handle, $data);
				fclose($handle);
				$merchantData = file($file);
				$filetime = date ("F d, Y @ H:i:s", filemtime($file));
			} else {
				die('importerror');	
			}
		}
		if(file_exists($file)) {
			$fh = fopen($file, 'r');
			$merchantData = file($file);
			$filetime = date ("F d, Y @ H:i:s", filemtime($file));
		} else {
			die('nodata');	
		}
	}
	if($_POST['action']=='creatives') {
		$file = "../cache/sas_creatives_".$_POST['merchantid'].".txt";
		if($_POST['update']=='true') {
			$creatives = sasImport("creatives", $_POST['merchantid']);
			$data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $creatives);
			if($data!='') {
				if(file_exists($file)) {
					unlink($file);
				}
				$handle = fopen($file, 'w') or die("can't open file");
				fwrite($handle, $data);
				fclose($handle);
				$creativesData = file($file);
				$filetime = date ("F d, Y @ H:i:s", filemtime($file));
			} else {
				die('importerror');	
			}
		}
		if(file_exists($file)) {
			$fh = fopen($file, 'r');
			$creativesData = file($file);
			$filetime = date ("F d, Y @ H:i:s", filemtime($file));
		} else {
			die('nodata');	
		}
	}
}
?>
<?php 
if( defined('SAS_AFFID') && defined('SAS_TOKEN') && defined('SAS_SECRET') ) {
	if($_POST['action']=='merchants') {	
?>
    <form>
        <label for="merchant">
            Update Merchant Cache
            <span class="small">Retreaive an updated merchant list from Shareasale.</span>
        </label>
        <a href="#" style="margin:0px 0px 0px 15px;" id="merchantUpdate"><strong>Click Here</strong></a> to update.<br />
		<span style="margin:0px 0px 0px 15px; font-style:italic;">Last Updated: <?php echo $filetime; ?></span>
        <label for="merchant">
            SAS Merchant
            <span class="small">Select a Shareasale merchant to import from.</span>
        </label>
        <select id="merchant">
        	<option value="">Select a Merchant...</option>
            <?php
            $i=0;
            foreach($merchantData as $merchant) {
                //skip the first line with the column headers
                if($i>0) {
                    $line = explode('|',$merchant);
                    echo '<option value="'.$line[0].'">'.$line[1].' ('.$line[0].')</option>';
                }
                $i++;
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
	echo '<form>AdManager has integrated with the Sharasale Affiliate Network to allow you to easily import your affiliate banners and ads directly into your AdManager campaigns.<br /><br />If you do not have a Shareasale affiliate account, <a href="http://www.shareasale.com/r.cfm?b=44&u=434244&m=47&urllink=&afftrack=am3campaign" target="_blank">Click Here</a> to learn more and sign up for a FREE account today!<br/><br/>Once you have an account setup with Shareasale, update your AdManager Settings with your Shareasale account information to unlock the import functionality.</form>';	
}

if($_POST['action']=='creatives') {
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
<label for="merchant">
    Update Creatives Cache
    <span class="small">Retreive an updated creatives list from Shareasale for this merchant.</span>
</label>
<a href="#" style="margin:0px 0px 0px 15px;" id="creativesUpdate"><strong>Click Here</strong></a> to update.<br />
<span style="margin:0px 0px 0px 15px; font-style:italic;">Last Updated: <?php echo $filetime; ?></span>
<div class="clear"></div>

<?php
	$i=0;
	$j=0;
	foreach($creativesData as $creative) {
		if($i>0) {
			$line = explode('|',$creative);
			if($line[2]!='') {
				echo '<div class="image">';
				echo '<img src="inc/img.php?c='.urlencode($line[2]).'&h=97&w=97" title="'.$line[1].'" alt="'.$line[8].'" width="97" height="97"><br />';
				echo 'Width: '.$line[6].'<br />';
				echo 'Height: '.$line[7];
				echo '<a class="fancybox" rel="'.trim($line[10]).'" href="inc/img.php?i='.urlencode($line[2]).'" title="'.$line[1].'">Preview</a>';
				echo '<a onclick="return false;" href="#" class="import" id="'.trim($line[10]).'-'.$i.'">Import</a>';
				echo '</div>';
				$j++;
			}
		}
		$i++;
	}
	if($j==0) {
		echo "<p>We could not find any creatives for this merchant.</p>";	
	}
	echo '<div class="clear"></div>';
}
if($_POST['action']=='import') {
	$adinfo = explode("-", $_POST['adinfo']);
	$merchant = $adinfo[0];
	$linenumber = $adinfo[1];
	$file = "../cache/sas_creatives_".$merchant.".txt";
	$fh = fopen($file, 'r');
	$creativesData = file($file);
	$i=0;
	foreach($creativesData as $creative) {
		if($i==$linenumber) {
			$line = explode('|',$creative);
			$adName = $line[1];
			$adData = '<a href="'.$line[3].'" target="_blank"><img src="'.$line[2].'" title="'.$line[1].'" alt="'.$line[8].'" width="'.$line[6].'" height="'.$line[7].'"></a>';
		}
		$i++;
	}
	$ads = get_ads($_POST['campaign']);
	if($ads) {
		$count = count($ads);
	} else {
		$count=0;	
	}
	$newFile = str_pad((int) $count+1,3,"0",STR_PAD_LEFT);
	$ourFileName = "../campaigns/".$_POST['campaign']."/".$newFile.".txt";
	$ourFileHandle = fopen($ourFileName, 'w') or die("can't open file");
	$stringData = $adName."|".$adData."\n";
	fwrite($ourFileHandle, $stringData);
	fclose($ourFileHandle);
}
?>
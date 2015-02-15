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

if($_POST['action']=="submitSettings") {	
	if($_POST['adminuser']=='' || $_POST['adminpass']=='' || $_POST['adminpass2']=='') {
		die('required');	
	} else
	if($_POST['adminpass']!=$_POST['adminpass2']) {
		die('passmatch');
	} else {
		$configFile = "../inc/config.php";
		$fh = fopen($configFile, 'w') or die("can't open file");
		$stringData = "<?php\n";
		$stringData .= "define('ADMIN_USER','".$_POST['adminuser']."');\n";
		$stringData .= "define('ADMIN_PASS', '".$_POST['adminpass']."');\n";
		if($_POST['sasaffid']!='' && $_POST['sastoken']!='' && $_POST['sassecret']!='') {
			$stringData .= "define('SAS_AFFID','".$_POST['sasaffid']."');\n";
			$stringData .= "define('SAS_TOKEN','".$_POST['sastoken']."');\n";
			$stringData .= "define('SAS_SECRET','".$_POST['sassecret']."');\n";
		}
		if($_POST['cjdevkey']!='' && $_POST['cjsiteid']!='') {
			$stringData .= "define('CJ_DEVKEY','".$_POST['cjdevkey']."');\n";
			$stringData .= "define('CJ_SITEID','".$_POST['cjsiteid']."');\n";
		}
		if($_POST['gaaccount']!='') {
			$stringData .= "define('GA_ACCOUNT','".$_POST['gaaccount']."');\n";
		}
		$stringData .= "?>\n";
		fwrite($fh, $stringData);
		fclose($fh);
		die('success');
	}
}
if($_POST['action']=="settings") {
?>
    <form id="settings">
        <h1>Admin Settings</h1>
        <div class="inner">
            <label for="adminuser">
                Username *
                <span class="small">Your admin username</span>
            </label>
            <input type="text" id="adminuser" value="<?php if(defined('ADMIN_USER')) { echo ADMIN_USER; } ?>" />
            
            <label for="adminpass">
                Password *
                <span class="small">Your admin password</span>
            </label>
            <input type="password" id="adminpass" value="<?php if(defined('ADMIN_PASS')) { echo ADMIN_PASS; } ?>" />
    
            <label for="adminpass2">
                Confirm Password *
                <span class="small">Your admin password again</span>
            </label>
            <input type="password" id="adminpass2" value="<?php if(defined('ADMIN_PASS')) { echo ADMIN_PASS; } ?>" />
        </div>
       
        <h1 style="clear:both;">Shareasale Settings</h1>
        <div class="inner">
            <p style="border:none;">AdManager has integrated with the Sharasale Affiliate Network to allow you to easily import your affiliate banners and ads directly into your AdManager campaigns.</p>
            <p><em>If you do not have a Shareasale affiliate account, <a href="http://www.shareasale.com/r.cfm?b=44&u=434244&m=47&urllink=&afftrack=am3setup" target="_blank">Click Here</a> to learn more and sign up for a FREE account today!</em></p>
            <label for="sasaffid">
                SAS Affiliate ID
                <span class="small">Your Shareasale affiliate ID</span>
            </label>
            <input type="text" id="sasaffid" value="<?php if(defined('SAS_AFFID')) { echo SAS_AFFID; } ?>" />
    
            <label for="sastoken">
                SAS API Token
                <span class="small">Your Shareasale API token</span>
            </label>
            <input type="text" id="sastoken" value="<?php if(defined('SAS_TOKEN')) { echo SAS_TOKEN; } ?>" />
    
            <label for="sassecret">
                SAS API Secret
                <span class="small">Your Shareasale API secret</span>
            </label>
            <input type="text" id="sassecret" value="<?php if(defined('SAS_SECRET')) { echo SAS_SECRET; } ?>" />
		</div>
        
        <h1 style="clear:both;">Commission Junction Settings</h1>
        <div class="inner">
            <p>AdManager has integrated with the Comission Junction Network to allow you to easily import your affiliate banners directly into your AdManager campaigns.</p>
            <label for="cjdevkey">
                CJ Developer Key
                <span class="small">Your CJ Developer Key</span>
            </label>
            <input type="text" id="cjdevkey" value="<?php if(defined('CJ_DEVKEY')) { echo CJ_DEVKEY; } ?>" />
            
            <label for="cjsiteid">
                CJ Website ID
                <span class="small">Your CJ Website ID</span>
            </label>
            <input type="text" id="cjsiteid" value="<?php if(defined('CJ_SITEID')) { echo CJ_SITEID; } ?>" />
        </div>


        <h1 style="clear:both;">Google Analytics Settings</h1>
        <div class="inner">
            <p>AdManager has integrated Google Analytics event tracking. By entering your analytics account number below you will be able to track your campaign and ad clicks through your Google Analytics account.</p>
            <label for="gaaccount">
                Google Analytics
                <span class="small">To enable click tracking enter your full analytics UA-XXXXXXXX-X account number here.</span>
            </label>
            <input type="text" id="gaaccount" value="<?php if(defined('GA_ACCOUNT')) { echo GA_ACCOUNT; } ?>" />
        </div>


        <div id="confirmButtons">
            <a href="#" class="button blue" id="submit" onclick="return false;">Submit<span></span></a>
            <a href="#" class="button gray" id="cancel" onclick="return false;">Cancel<span></span></a>
        </div>
    </form>
<?php } ?> 
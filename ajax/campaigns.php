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

if($_POST['action']=="newSubmit") {
	campaignsNewSubmit($_POST['cname'], $_POST['transEffect'], $_POST['transSpeed'], $_POST['transDelay'], $_POST['order']);
} else
if($_POST['action']=="editSubmit") {
	campaignsEditSubmit($_POST['campaign'], $_POST['cname'], $_POST['transEffect'], $_POST['transSpeed'], $_POST['transDelay'], $_POST['order']);
} else
if($_POST['action']=="delete") {
	echo campaignsDelete($_POST['file']);
} else
if($_POST['action']=="new") {
	$fx = getEffects();
?>
    <link rel="stylesheet" type="text/css" href="js/confirm/jquery.confirm.css"/>
    <form id="cSample">
        <div class="inner">
            <label for="cname">
                Campaign Name
                <span class="small">Used to identify your campaign in the admin.</span>
            </label>
            <input type="text" id="cname" value="" />
            
            <label for="transEffect" style="position:relative;">
                Transition Effect
                <span class="small">The effect used from one ad to another.</span>
                <div id="sample"></div>
            </label>
            <select id="transEffect">
                <?php foreach($fx as $key=>$value) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>

            <label for="transSpeed">
                Transition Speed
                <span class="small">How long the transition between ads takes, in milliseconds<br />(1000 milliseconds = 1 second).</span>
            </label>
            <input type="text" id="transSpeed" value="" />

            <label for="transDelay">
                Transition Delay
                <span class="small">How long each ad is displayed for, in milliseconds<br />(1000 milliseconds = 1 second).</span>
            </label>
            <input type="text" id="transDelay" value="" />

            <label for="transDelay">
                Sort Order
                <span class="small">Display ads in sorted order.</span>
            </label>
            <input name="order" type="radio" value="sort" id="order1" />

            <label for="transDelay">
                Random Order
                <span class="small">Display ads in random order.</span>
            </label>
            <input name="order" type="radio" value="random" id="order2" />

            <input type="hidden" id="ccampaign" value="<?php echo $_POST['campaign']; ?>" />
            
            <div id="confirmButtons">
                <a href="#" class="button blue" id="submit" onclick="return false;">Submit<span></span></a>
                <a href="#" class="button gray" id="cancel" onclick="return false;">Cancel<span></span></a>
            </div>
        </div>
    </form>    
<?php
} else
if($_POST['action']=="edit") {
	$campaign = campaignsEdit($_POST['campaign']);
	$fx = getEffects();
?>
    <link rel="stylesheet" type="text/css" href="js/confirm/jquery.confirm.css"/>
    <form id="cSample">
        <div class="inner">
            <label for="cname">
                Campaign Name
                <span class="small">Used to identify your campaign in the admin.</span>
            </label>
            <input type="text" id="cname" value="<?php echo $campaign['0']; ?>" />
            
            <label for="transEffect" style="position:relative;">
                Transition Effect
                <span class="small">The effect used from one ad to another.</span>
                <div id="sample"></div>
            </label>
            <select id="transEffect">
                <?php foreach($fx as $key=>$value) { ?>
                    <option value="<?php echo $key; ?>"<?php if($campaign['1']==$key) { echo ' selected="selected"'; } ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>

            <label for="transSpeed">
                Transition Speed
                <span class="small">How long the transition between ads takes, in milliseconds<br />(1000 milliseconds = 1 second).</span>
            </label>
            <input type="text" id="transSpeed" value="<?php echo $campaign['2']; ?>" />

            <label for="transDelay">
                Transition Delay
                <span class="small">How long each ad is displayed for, in milliseconds<br />(1000 milliseconds = 1 second).</span>
            </label>
            <input type="text" id="transDelay" value="<?php echo $campaign['3']; ?>" />

            <label for="transDelay">
                Sort Order
                <span class="small">Display ads in sorted order.</span>
            </label>
            <input name="order" type="radio" value="sort" id="order1" <?php if(trim($campaign['4'])=="0") { ?>checked="checked" <?php } ?>/>

            <label for="transDelay">
                Random Order
                <span class="small">Display ads in random order.</span>
            </label>
            <input name="order" type="radio" value="random" id="order2" <?php if(trim($campaign['4'])=="1") { ?>checked="checked" <?php } ?>/>

            <input type="hidden" id="ccampaign" value="<?php echo $_POST['campaign']; ?>" />
            
            <div id="confirmButtons">
                <a href="#" class="button blue" id="submit" onclick="return false;">Submit<span></span></a>
                <a href="#" class="button gray" id="cancel" onclick="return false;">Cancel<span></span></a>
            </div>
        </div>
    </form>    
<?php
} else
if($_POST['action']=="code") {
?>
    <form>
        <h1>Preview:</h1>
        <script type="text/javascript">admanager('','preview<?php echo $_POST['file']; ?>','<?php echo $_POST['file']; ?>');</script>
        <div id="preview<?php echo $_POST['file']; ?>"></div>
        
        <h1>Usage:</h1>
        <p>
            Insert the following with in the &lt;head&gt;...&lt;/head&gt; tags of your page.<br />
            <strong>Replace "PATH/TO/ADMANAGER/" with the relative path to where you have uploaded the admanager folder.</strong>
        </p>
        <textarea style="width:95%; height:50px;">&lt;script type="text/javascript" src="PATH/TO/ADMANAGER/js/admanager.js"&gt;&lt;/script&gt;</textarea>
        
        <p>
            Insert the following within the &lt;body&gt;...&lt;/body&gt; tags of your page, where you would like your campaign to be displayed.<br />
            <strong>Replace "PATH/TO/ADMANAGER/" with the relative path to where you have uploaded the admanager folder.</strong>
        </p>
        <textarea style="width:95%; height:50px;">&lt;script type="text/javascript"&gt;admanager('PATH/TO/ADMANAGER/','admanager<?php echo $_POST['file']; ?>','<?php echo $_POST['file']; ?>');&lt;/script&gt;
&lt;div id="admanager<?php echo $_POST['file']; ?>"&gt;&lt;/div&gt;</textarea>
        <div class="clear"></div>
        <h1>Details:</h1>
        <p>
            <strong>ADMANAGER_PATH</strong>: Path to admanager, relative to the page your campaign is being displayed on.<br />
            <strong>DIV_ID</strong>: The unique id of the div used to display your campaign.<br />
            <strong>CAMPAIGN_ID</strong>: This is the unique ID for the campaign you would like to display.<br /><br />
            &lt;script type="text/javascript"&gt;admanager('<strong>ADMANAGER_PATH</strong>', '<strong>DIV_ID</strong>', '<strong>CAMPAIGN_ID</strong>');&lt;/script&gt;<br />
            &lt;div id="<strong>DIV_ID</strong>"&gt;&lt;/div&gt;
        </p>
        <div id="confirmButtons">
            <a href="#" class="button gray" id="cancel" onclick="return false;">Close Preview<span></span></a>
        </div>
    </form>
<?php
} else
if($_POST['action']=="campaigns") {
	$campaigns = get_campaigns();
?>
    <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox.css">
    <div class="action logout" title="Logout"></div>
    <div class="action help" title="Help/Comments/Questions"></div>
    <div class="action settings" title="AdManager Settings"></div>
    <div class="action cNew" title="New Campaign"></div>    
    <h1>Campaigns</h1>
    <div id="form"></div>
    <?php if($campaigns) { ?>
        <?php foreach($campaigns as $campaign) { ?>
            <div class="campaign <?php echo $campaign['folder']; ?>">
                <?php echo $campaign['name']; ?>
                <div class="action cDelete" id="<?php echo $campaign['folder']; ?>" title="Delete Campaign"></div>
                <div class="action cCode" id="<?php echo $campaign['folder']; ?>" title="Preview/Get Code"></div>
                <div class="action cEdit" id="<?php echo $campaign['folder']; ?>" title="Edit Campaign"></div>
                <div class="action aImport" id="<?php echo $campaign['folder']; ?>" title="Import Ads"></div>
				<div class="action aNew" id="<?php echo $campaign['folder']; ?>" title="New Ad"></div>
            </div>
            <div class="clear <?php echo $campaign['folder']; ?>"></div>
            <div id="import<?php echo $campaign['folder']; ?>" class="aImportSub campaign">
                <a href="#" class="import-sas" onClick="return false;">Shareasale</a> | 
                <a href="#" class="import-cj" onClick="return false;">Comission Junction</a>
            </div>
            <div class="<?php echo $campaign['folder']; ?>" id="form<?php echo $campaign['folder']; ?>"></div>
            <?php $ads = get_ads($campaign['folder']); ?>
            <?php if($ads) { ?>
                <?php $i=1; ?>
                <?php foreach($ads as $ad) { ?>
                    <div class="ad <?php echo $campaign['folder']; ?>-<?php echo $ad['file']; ?>">
                        <?php echo $ad['name']; ?>
                        <div class="action aDelete" id="<?php echo $campaign['folder']; ?>-<?php echo $ad['file']; ?>" title="Delete Ad"></div>
                        <div class="action aPreview" id="<?php echo $campaign['folder']; ?>-<?php echo $ad['file']; ?>" title="Preview Ad"></div>
                        <div class="action aEdit" id="<?php echo $campaign['folder']; ?>-<?php echo $ad['file']; ?>" title="Edit Ad"></div>
                        <?php if($i!=count($ads)) { ?>
                            <div class="action sortDown" id="<?php echo $campaign['folder']; ?>-<?php echo $ad['file']; ?>" title="Move Down"></div>
                        <?php } else { ?>
                            <div class="action empty"></div>
                        <?php } ?>
                        <?php if($i>1) { ?>
                            <div class="action sortUp" id="<?php echo $campaign['folder']; ?>-<?php echo $ad['file']; ?>" title="Move Up"></div>
                        <?php } else { ?>
                            <div class="action empty"></div>
                        <?php } ?>
                        <?php $i++; ?>
                    </div>
                    <div class="clear"></div>
                    <div id="form<?php echo $campaign['folder']; ?>-<?php echo $ad['file']; ?>"></div>
                <?php } ?>
            <?php } else { ?>
                <form>
                    <div class="inner">
                        <strong>You do not currently have any ads in this campaign.</strong><br />
                        Please click on the "New Ad" icon above to get started.
                    </div>
                </form>            
            <?php } ?>
        <?php } ?>
    <?php } else { ?>
    	<form>
        	<div class="inner">
            	<strong>You do not currently have any campaigns.</strong><br />
				Please click on the "New Campaign" folder above to get started.
            </div>
        </form>
    <?php } ?>
<?php 
}
?>
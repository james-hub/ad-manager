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
	adsNewSubmit($_POST['campaign'], $_POST['aname'], $_POST['acode']);	
} else
if($_POST['action']=="editSubmit") {
	adsEditSubmit($_POST['aid'], $_POST['aname'], $_POST['acode']);
} else
if($_POST['action']=="delete") {
	adsDelete($_POST['aid']);
} else
if($_POST['action']=="sort") {
	adsSort($_POST['aid'], $_POST['direction']);
} else
if($_POST['action']=="new") {
?>
    <form>
        <div class="inner">
            <label for="aname">
                Ad Name
                <span class="small">Used to identify your ad in the admin.</span>
            </label>
            <input type="text" id="aname" value="" />
            
            <label for="acode">
                Ad Code
                <span class="small">Paste your ad code here.</span>
            </label>
            <textarea id="acode"></textarea>
            <div id="confirmButtons">
                <a href="#" class="button blue" id="submit" onclick="return false;">Submit<span></span></a>
                <a href="#" class="button gray" id="cancel" onclick="return false;">Cancel<span></span></a>
            </div>
        </div>
    </form>    
<?php 
} else
if($_POST['action']=="edit") {
	$info = adsEdit($_POST['id']);
?>
    <form>
        <div class="inner">
            <label for="aname">
                Ad Name
                <span class="small">Used to identify your ad in the admin.</span>
            </label>
            <input type="text" id="aname" value="<?php if($info[0]) { echo $info[0]; } ?>" />
            
            <label for="acode">
                Ad Code
                <span class="small">Paste your ad code here.</span>
            </label>
            <textarea id="acode"><?php if($info[1]) { echo stripslashes($info[1]); } ?></textarea>
            <div id="confirmButtons">
                <a href="#" class="button blue" id="submit" onclick="return false;">Submit<span></span></a>
                <a href="#" class="button gray" id="cancel" onclick="return false;">Cancel<span></span></a>
            </div>
        </div>
    </form>    
<?php 
} else
if($_POST['action']=="preview") {
	$adCode = adsPreview($_POST['aid']);
?>
    <form>
        <?php echo stripslashes($adCode[1]); ?>
        <div id="confirmButtons">
            <a href="#" class="button gray" id="cancel" onclick="return false;">Close Preview<span></span></a>
        </div>
    </form>
<?php } ?>
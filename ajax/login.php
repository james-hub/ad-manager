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

if($_POST['action']=="submitLogin") {	
	if($_POST['adminuser']=='' || $_POST['adminpass']=='') {
		die('required');	
	} else
	if($_POST['adminuser']!=ADMIN_USER) {
		die('invalid');
	} else 
	if($_POST['adminpass']!=ADMIN_PASS) {
		die('invalid');
	} else {
		session_start();
		$_SESSION['ADMIN'] = md5(ADMIN_USER);
		die('success');
	}
}
if($_POST['action']=="logout") {
	session_start();
	unset($_SESSION['ADMIN']);
}
if($_POST['action']=="login") {	
?>
    <form id="login">
        <div class="inner">
            <h1>Admin Login</h1>
            <label for="adminuser">
                Username
                <span class="small">Your admin username</span>
            </label>
            <input type="text" id="adminuser" value="" />
            
            <label for="adminpass">
                Password
                <span class="small">Your admin password</span>
            </label>
            <input type="password" id="adminpass" value="" />
    
            <div id="confirmButtons">
                <a href="#" class="button blue" id="submit" onclick="return false;">Submit<span></span></a>
            </div>
        </div>
    </form>
<?php } ?>
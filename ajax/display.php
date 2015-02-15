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

if($_POST['action']=="getcampaign") {
	$info = explode("-",$_POST['info']);
	$campaign = $info[0];
	$ad = $info[1];
	$campaignName = campaignName($campaign);
	die($campaignName);
}
if($_POST['action']=="getad") {
	$info = explode("-",$_POST['info']);
	$campaign = $info[0];
	$ad = $info[1];
	$campaignName = campaignName($campaign);
	$adName = adName($campaign, $ad);
	die($adName);
}
if($_POST['action']=="showCampaign") {
	$ads = get_ads($_POST['campaign']);
	$settings = "../campaigns/".$_POST['campaign']."/settings.txt";
	if(file_exists($settings)) {
		$fh = fopen($settings, 'r');
		$theData = fread($fh, filesize($settings));
		$campaign = explode("|",$theData);
		if( count($ads)>0 ) {
			if(trim($campaign[4])==1) {
				$random = 1;
			} else {
				$random = 0;
			}
			foreach($ads as $value) {
				$file = "../campaigns/".$_POST['campaign']."/".$value['file'].".txt";
				$fh = fopen($file, 'r');
				$theData = fread($fh, filesize($file));
				$adCode = explode("|",$theData);				
				echo '<div id="'.$_POST['campaign'].'-'.$value['file'].'">'.stripslashes($adCode[1]).'</div>';
			}
		}
		$campaignName = campaignName($_POST['campaign']);
	}
?>
	<script type="text/javascript">
    $(document).ready(function() {
        $.getScript("<?php echo $_POST['ampath']; ?>js/jquery.cycle.all.js", function() {
            $("#<?php echo $_POST['divid']; ?>").cycle({
                fx:     "<?php echo $campaign[1]; ?>", 
                speed:	<?php echo $campaign[2]; ?>, 
                timeout: <?php echo $campaign[3]; ?>,
                random: <?php echo $random; ?>,
                pause: true,
                containerResize: 1,
                slideResize: 0
            });
            $("#<?php echo $_POST['divid']; ?> div").click(function() {
                <?php if( defined('GA_ACCOUNT') ) { ?>
                    var _gaq = _gaq || [];
                    _gaq.push(['_setAccount', '<?php echo GA_ACCOUNT; ?>']);
                    (function() {
                        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                    })();
                <? } ?>
                var thisInfo = this.id;
                $.post("<?php echo $_POST['ampath']; ?>ajax/display.php", { 
                    action: "getcampaign",
                    info: thisInfo
                }, function(data) {
                    <?php if( defined('GA_ACCOUNT') ) { ?>
                        _gaq.push(['_trackEvent', 'AdManager', 'Campaign Clicked', data]);
                    <? } ?>
                    $.post("<?php echo $_POST['ampath']; ?>ajax/display.php", { 
                        action: "getad",
                        info: thisInfo
                    }, function(data2) {
                        <?php if( defined('GA_ACCOUNT') ) { ?>
                            _gaq.push(['_trackEvent', 'AdManager', 'Ad Clicked', data2]);
                        <? } ?>
                    });
                });
            });
        });
    });
    </script>
<?php } ?>
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

$(document).ready(function() {
	loadIndex();
});

function loadIndex() {
	$.post("ajax/loader.php", { 
		action: "load"
	}, function(data) {
		if(data=='settings') {
			$.post("ajax/settings.php", { 
				action: "settings"
			}, function(data) {
				$("#main").empty().append(data);
				$("#submit").click(function() {
					$.post("ajax/settings.php", { 
						action: "submitSettings",
						adminuser: $("#adminuser").val(),
						adminpass: $("#adminpass").val(),
						adminpass2: $("#adminpass2").val(),
						sasaffid: $("#sasaffid").val(),
						sastoken: $("#sastoken").val(),
						sassecret: $("#sassecret").val(),
						cjdevkey: $("#cjdevkey").val(),
						cjsiteid: $("#cjsiteid").val(),
						gaaccount: $("#gaaccount").val()
					}, function(data) {
						if(data=="required") {
							modal('Please fill in all required fields.', 3000);
						}
						if(data=="passmatch") {
							modal('Your passwords do not match.', 3000);
						}
						if(data=="success") {
							modal('Your settings have been saved.', 3000);
							loadIndex();
						}						
					});
				});
			});			
		} else
		if(data=='login') {
			$.post("ajax/login.php", { 
				action: "login"
			}, function(data) {
				$("#main").empty().append(data);
				$("#submit").click(function() {
					modal('Verifying your information...', 3000);
					$.post("ajax/login.php", { 
						action: "submitLogin",
						adminuser: $("#adminuser").val(),
						adminpass: $("#adminpass").val()
					}, function(data) {
						if(data=="required") {
							modal('Please fill in all required fields.', 3000);
						}
						if(data=="invalid") {
							modal('Invalid username/password.', 3000);
						}
						if(data=="success") {
							modal('Logging you in...', 2000);
							loadIndex();
						}						
					});
				});
			});
		} else
		if(data=='campaigns') {
			$.post("ajax/campaigns.php", { 
				action: "campaigns"
			}, function(data) {
				$("#main").empty().append(data);
				loadActions();						
				loadSorting();
			});
		}
	});
}

function loadSorting() {
	$(".sortUp").click(function() {
		var aid = this.id;
		$("."+aid).animate({
			opacity: .1,
			height: '1%'
		}, "slow");
		$.post("ajax/ads.php", { 
			action: "sort",
			direction: "up",
			aid: aid
		}, function(data) {
			clearForms();
			loadIndex();
		});
	});
	$(".sortDown").click(function() {
		var aid = this.id;
		$("."+aid).animate({
			opacity: .1,
			height: '1%'
		}, "slow");
		$.post("ajax/ads.php", { 
			action: "sort",
			direction: "down",
			aid: aid
		}, function(data) {
			clearForms();
			loadIndex();
		});
	});	
}

function loadActions() {
	//Campaign Ajax
	$(".settings").click(function() {
		$.post("ajax/settings.php", { 
			action: "settings"
		}, function(data) {
			$("#form").empty().append(data);
			$("#submit").click(function() {
				$.post("ajax/settings.php", { 
					action: "submitSettings",
					adminuser: $("#adminuser").val(),
					adminpass: $("#adminpass").val(),
					adminpass2: $("#adminpass2").val(),
					sasaffid: $("#sasaffid").val(),
					sastoken: $("#sastoken").val(),
					sassecret: $("#sassecret").val(),
					cjdevkey: $("#cjdevkey").val(),
					cjsiteid: $("#cjsiteid").val(),
					gaaccount: $("#gaaccount").val()
				}, function(data) {
					if(data=="required") {
						modal('Please fill in all required fields.', 3000);
					}
					if(data=="passmatch") {
						modal('Your passwords do not match.', 3000);
					}
					if(data=="success") {
						modal('Your settings have been saved.', 3000);
						loadIndex();
					}						
				});
			});
			$("#cancel").click(function() {
				$("#form").slideUp("slow").empty();
				loadIndex();
			});
		});			
	});
	$(".logout").click(function() {
		$.post("ajax/login.php", { 
			action: "logout"
		}, function(data) {
			loadIndex();
		});
	});
	$(".help").click(function() {
		$.getScript("js/fancybox/jquery.fancybox.js?v=2.0.6", function() {
			$.fancybox.open({
				href : 'http://www.allembru.com/web-tools-ad-manager.html#help',
				type : 'iframe',
				padding : 5,
				width:1000
			});

		});
	});
	$(".cNew").click(function() {
		$.post("ajax/campaigns.php", { 
			action: "new"
		}, function(data) {
			if(data!='') {
				clearForms();
				$("#form").empty().append(data).slideDown("slow");
				loadSample();
				$("#submit").click(function() {
					if ($('#order1').is(':checked')) {
						var order = "0";
					} else
					if ($('#order2').is(':checked')) {
						var order = "1";
					} else {
						var order = "0";
					}
					if($("#cname").val()=='' || $("#transSpeed").val()=='' || $("#transDelay").val()=='') {
						modal('<strong>A fields has been left blank!</strong><br/>Please fill in <strong>ALL</strong> of the fields and try again.', 3000);
					} else {
						$.post("ajax/campaigns.php", { 
							action: "newSubmit",
							cname: $("#cname").val(),
							transEffect: $("#transEffect").val(),
							transSpeed: $("#transSpeed").val(),
							transDelay: $("#transDelay").val(),
							order: order
						}, function(data) {
								$("#form").slideUp("slow").empty();
								modal('Your campaign has been created!',3000);
								loadIndex();
						});
					}
				});
				$("#transEffect").change(function() {
					loadSample();
				});
				$("#transSpeed").change(function() {
					loadSample();
				});
				$("#transDelay").change(function() {
					loadSample();
				});
				$("#order1").change(function() {
					loadSample();
				});
				$("#order2").change(function() {
					loadSample();
				});
				$("#cancel").click(function() {
					$("#form").slideUp("slow").empty();
					loadIndex();
				});
			}
		});
	});
	$(".cEdit").click(function() {
		var file = this.id;
		$.post("ajax/campaigns.php", { 
			action: "edit",
			campaign: file
		}, function(data) {
			if(data!='') {
				clearForms();
				$("#form"+file).empty().append(data).slideDown("slow");
				loadSample();
				$("#submit").click(function() {
					if ($('#order1').is(':checked')) {
						var order = "0";
					} else
					if ($('#order2').is(':checked')) {
						var order = "1";
					} else {
						var order = "0";
					}
					if($("#cname").val()=='' || $("#transSpeed").val()=='' || $("#transDelay").val()=='') {
						modal('<strong>A fields has been left blank!</strong><br/>Please fill in <strong>ALL</strong> of the fields and try again.',3000);
					} else {
						$.post("ajax/campaigns.php", { 
							action: "editSubmit",
							cname: $("#cname").val(),
							transEffect: $("#transEffect").val(),
							transSpeed: $("#transSpeed").val(),
							transDelay: $("#transDelay").val(),
							order: order,
							campaign: $("#ccampaign").val()
						}, function(data) {
								$("#form"+file).slideUp("slow").empty();
								modal('Your campaign has been updated!',3000);
								loadIndex();
						});
					}
				});
				$("#transEffect").change(function() {
					loadSample();
				});
				$("#transSpeed").change(function() {
					loadSample();
				});
				$("#transDelay").change(function() {
					loadSample();
				});
				$("#order1").change(function() {
					loadSample();
				});
				$("#order2").change(function() {
					loadSample();
				});
				$("#cancel").click(function() {
					$("#form"+file).slideUp("slow").empty();
					loadIndex();
				});
			}
		});
	});
	$(".cDelete").click(function() {
		var file = this.id;
		$.confirm({
			'title'		: 'Delete Confirmation',
			'message'	: 'You are about to delete this campaign. <br />This action CANNOT be undone! Continue?',
			'buttons'	: {
				'Yes'	: {
					'class'	: 'blue',
					'action': function(){
						$.post("ajax/campaigns.php", { 
							action: "delete",
							file: file
						}, function(data) {
							if($.trim(data)=="notempty") {
								modal("You must first delete all of the ads within this campaign!",3000);
							} else {
								clearForms();
								$("."+file).slideUp("slow").empty();
								modal('Your campaign has been deleted!',3000);
								loadIndex();
							}
						});
					}
				},
				'No'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});	
	$(".cCode").click(function() {
		var file = this.id;
		$.post("ajax/campaigns.php", { 
			action: "code",
			file: file
		}, function(data) {
			if(data!='') {
				clearForms();
				$("#form"+file).empty().append(data).slideDown("slow");
				$("#cancel").click(function() {
					$("#form"+file).slideUp("slow").empty();
					loadIndex();
				});
			}
		});
	});
	
	$(".aNew").click(function() {
		var campaign = this.id;
		$.post("ajax/ads.php", { 
			action: "new",
			campaign: campaign
		}, function(data) {
			if(data!='') {
				clearForms();
				$("#form" + campaign).empty().append(data).slideDown("slow");
				$("#submit").click(function() {
					$.post("ajax/ads.php", { 
						action: "newSubmit",
						aname: $("#aname").val(),
						acode: $("#acode").val(),
						campaign: campaign
					}, function(data) {
						$("#form" + campaign).slideUp("slow").empty();
						modal('Your ad has been created!',3000);
						loadIndex();
					});
				});
				$("#cancel").click(function() {
					$("#form" + campaign).slideUp("slow").empty();
					loadIndex();
				});
			}
		});
	});
	
	$(".aImport").click(function() {
		var campaign = this.id;
		$("#import"+campaign).slideToggle("slow", function() {
			
			$(".import-sas").click(function() {
				sasMerchants(campaign, "false");
			});
			$(".import-cj").click(function() {
				cjMerchants(campaign, "false");
			});
			
		});
	});
	
	$(".aEdit").click(function() {
		var id = this.id;
		$.post("ajax/ads.php", { 
			action: "edit",
			id: id
		}, function(data) {
			if(data!='') {
				clearForms();
				$("#form"+id).empty().append(data).slideDown("slow");
				$("#submit").click(function() {
					$.post("ajax/ads.php", { 
						action: "editSubmit",
						aname: $("#aname").val(),
						acode: $("#acode").val(),
						aid: id
					}, function(data) {
							$("#form"+id).slideUp("slow").empty();
							modal('Your ad has been updated!',3000);
							loadIndex();
					});
				});
				$("#cancel").click(function() {
					$("#form"+id).slideUp("slow").empty();
					loadIndex();
				});
			}
		});
	});
	$(".aDelete").click(function() {
		var aid = this.id;
		$.confirm({
			'title'		: 'Delete Confirmation',
			'message'	: 'You are about to delete this ad. <br />This action CANNOT be undone! Continue?',
			'buttons'	: {
				'Yes'	: {
					'class'	: 'blue',
					'action': function(){
						$.post("ajax/ads.php", { 
							action: "delete",
							aid: aid
						}, function(data) {
							clearForms();
							$("."+aid).slideUp("slow").empty();
							modal('Your ad has successfully been deleted!',3000);
							loadIndex();
						});
					}
				},
				'No'	: {
					'class'	: 'gray',
					'action': function(){}	// Nothing to do in this case. You can as well omit the action property.
				}
			}
		});
	});
	$(".aPreview").click(function() {
		var aid = this.id;
		clearForms();
		$.post("ajax/ads.php", { 
			action: "preview",
			aid: aid
		}, function(data) {
			if(data!='') {
				$("#form"+aid).empty().append(data).slideDown("slow");
				$("#cancel").click(function() {
					$("#form"+aid).slideUp("slow").empty();
					loadIndex();
				});
			}
		});
	});	
}

function loadSample() {
	$("#sample").empty().append('<div class="red"></div><div class="orange"></div><div class="yellow"></div><div class="green"></div><div class="blue"></div><div class="indigo"></div><div class="violet"></div>');
	if ($('#order1').is(':checked')) {
		var order = "0";
	} else
	if ($('#order2').is(':checked')) {
		var order = "1";
	} else {
		var order = "0";
	}
	$.getScript("js/jquery.cycle.all.js", function() {
		$("#sample").cycle({
			fx: $("#transEffect").val(), 
			speed: $("#transSpeed").val(), 
			timeout: $("#transDelay").val(),
			random: order,
			pause: true,
			containerResize: 1,
			slideResize: 0
		});
	});
}

function sasMerchants(campaign, update) {
	$.post("ajax/import_sas.php", { 
		action: "merchants",
		update: update
	}, function(data) {
		if(data!='importerror' && data!='nodata') {
			clearForms();
			$("#form" + campaign).empty().append(data).slideDown("slow");
			$("#merchantUpdate").click(function() {
				sasMerchants(campaign, "true");
			});
			$("#merchant").change(function() {
				sasCreatives(campaign);
			});
		} else {
			if(data=='importerror') {
				modal('There was an error retreiving your merchant list.<br/><br/>Please check your Shareasale account information in the AdManager Settings and make sure you have available API request credits within your Shareasale account (200/month limit).',5000);
			}
			if(data=='nodata') {
				var disText = '<form>We do not have any merchant data cached.<br/><br/><a href="#" id="merchantUpdate">Click Here</a> to retreive merchant data from your Shareasale account.<br/><br/> <strong>Note:</strong> Make sure you have available API request credits within your Shareasale account (200/month limit).</form>';
				$("#form" + campaign).empty().append(disText).slideDown("slow");
				$("#merchantUpdate").click(function() {
					sasMerchants(campaign, "true");
				});
			}
		}
		$("#cancel").click(function() {
			$("#form").slideUp("slow").empty();
			loadIndex();
		});
	});
}

function sasCreatives(campaign, update) {
	$.post("ajax/import_sas.php", { 
		action: "creatives",
		merchantid: $("#merchant").val(),
		update: update
	}, function(data) {
		if(data!='importerror' && data!='nodata') {
			$("#creatives").empty().append(data).slideDown("slow");
			$.getScript("js/fancybox/jquery.fancybox.js?v=2.0.6", function() {
				$(".fancybox").fancybox({
					openEffect	: 'none',
					closeEffect	: 'none'
				});
				$("#creativesUpdate").click(function() {
					sasCreatives(campaign, "true");
				});
				$(".import").click(function() {
					var adinfo = this.id;
					$.post("ajax/import_sas.php", { 
						action: "import",
						campaign: campaign,
						adinfo: adinfo
					}, function(data) {
						modal('Your ad has been imported from Shareasale!',3000);
					});
				});
			});
		} else {
			if(data=='importerror') {
				modal('There was an error retreiving the creatives list.<br/><br/>Please check your Shareasale account information in the AdManager Settings and make sure you have available API request credits within your Shareasale account (200/month limit).', 5000);
			}
			if(data=='nodata') {
				var disText = '<p>We do not have any creatives cached for this merchant.<br/><br/><a href="#" id="creativesUpdate">Click Here</a> to retreive merchant data from your Shareasale account.<br/><br/> <strong>Note:</strong> Make sure you have available API request credits within your Shareasale account (200/month limit).</p>';
				$("#creatives").empty().append(disText).slideDown("slow");
				$("#creativesUpdate").click(function() {
					sasCreatives(campaign, "true");
				});
			}			
		}
	});
}

function cjMerchants(campaign, update) {
	$.post("ajax/import_cj.php", { 
		action: "merchants",
		update: update
	}, function(data) {
		if(data=='importerror') {
			modal('There was an error retreiving your merchant list.<br/><br/>Please check your Comission Junction account information in the AdManager Settings.',5000);
		} else {
			clearForms();
			$("#form" + campaign).empty().append(data).slideDown("slow");
			$("#merchant").change(function() {
				cjCreatives(campaign);
			});
		}
		$("#cancel").click(function() {
			$("#form").slideUp("slow").empty();
			loadIndex();
		});
	});
}
function cjCreatives(campaign, update) {
	$.post("ajax/import_cj.php", { 
		action: "creatives",
		merchantid: $("#merchant").val(),
		update: update
	}, function(data) {
		if(data=='importerror') {
			modal('There was an error retreiving the creatives list.<br/><br/>Please check your Commission Junction account information in the AdManager Settings.',5000);
		} else {
			$("#creatives").empty().append(data).slideDown("slow");
			$.getScript("js/fancybox/jquery.fancybox.js?v=2.0.6", function() {
				$(".fancybox").fancybox({
					openEffect	: 'none',
					closeEffect	: 'none',
					type: 'iframe'
				});
				$(".import").click(function() {
					var linkid = this.id;
					$.post("ajax/import_cj.php", { 
						action: "import",
						campaign: campaign,
						linkname: $("#name"+linkid).val(),
						linkdata: $("#data"+linkid).val()
					}, function(data) {
						modal('Your ad has successfully been imported from Commission Junction!',3000);
					});
				});
			});
		}
	});
}

function modal(msg,delay) {
	$("body").addClass("loading");
	if(msg) {
		if(msg=='unload') {
			$("body").removeClass("loading");
		} else {
			$("#modal").empty().append("<div class='modalinner'>"+msg+"</div>");
		}
	}
	if(delay) {
		setTimeout(function() {
			$("body").removeClass("loading");
		},delay);		
	}
}

function clearForms() {
	$("form").slideUp("slow").empty();
}
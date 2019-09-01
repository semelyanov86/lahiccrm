 /*+*******************************************************************************
 * The content of this file is subject to the CRMTiger Pro license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is vTiger
 * The Modified Code of the Original Code owned by https://crmtiger.com/
 * Portions created by CRMTiger.com are Copyright(C) CRMTiger.com
 * All Rights Reserved.
  ***************************************************************************** */

jQuery(document).ready(function(){
	
	jQuery(".menuBar").children(".span9").css("width","60%");
    jQuery(".menuBar").children(".span3").css("width","40%");
	count = 0;
	var url = "index.php?module=CTMobileSettings&action=chkPermission&mode=GetRequirement";
	var params = {
		"url":url
	};
	app.request.post(params).then(
		  function(err, data){
			if(err === null) {
				count = data.count;
				var CurrentUserModel = data.CurrentUserModel;
				if(CurrentUserModel.is_admin === 'on'){
					if(count > 0){
						 var bgColor='FF0000';
						 var msg='Extension installation has not been completed.';
						 var btn='<button class="btn btn-danger" style="margin-right:5px;" onclick="location.href=\'index.php?module=CTMobileSettings&parent=Settings&view=Details&mode=step1\'">Complete Install</button>';
						 var VTPremiumIcon = ['<li class="dropdown">',
													  '<div style="margin-top: 13px;" class="">',
														'<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="padding: 10px;">',
														  '<img style="width:25px; height:20px; border-radius: 50%; background-color: #'+bgColor+'" src="layouts/v7/modules/CTMobileSettings/img/CRMTiger.png" >',
														'</a>',
														'<div class="dropdown-menu" role="menu">',
														  '<div class="row">',
															'<div class="col-lg-12" style="min-width: 350px; padding: 10px 30px;">'+msg+'</div>',
														  '</div>',
														  '<div class="clearfix">',
															'<hr style="margin: 10px 0 !important">',
															  '<div class="text-center">'+btn+'</div>',
															'</div>',
														  '</div>',
														'</div>',
													'</li>'].join('');

							var headerIcons = $('#navbar ul.nav.navbar-nav');
							if (headerIcons.length > 0){
								headerIcons.first().prepend(VTPremiumIcon);
							}
					}else{
						var url = "index.php?module=CTMobileSettings&action=chkPermission&mode=GetLicense";
						var params = {
							"url":url
						};
						app.request.post(params).then(
						  function(err, data){
							if(err === null) {
								if(data.result === 0){
									 var bgColor='FFFF00';
									 var msg='License Key Setup has not been completed.';
									 var btn='<button class="btn btn-warning" style="margin-right:5px;" onclick="location.href=\'index.php?module=CTMobileSettings&parent=Settings&view=LicenseDetail\'">Setup License Key</button>';
									 var VTPremiumIcon = ['<li class="dropdown">',
															  '<div style="margin-top: 13px;" class="">',
																'<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="padding: 10px;">',
																  '<img style="width:25px; height:20px; border-radius: 50%; background-color: #'+bgColor+'" src="layouts/v7/modules/CTMobileSettings/img/CRMTiger.png" >',
																'</a>',
																'<div class="dropdown-menu" role="menu">',
																  '<div class="row">',
																	'<div class="col-lg-12" style="min-width: 350px; padding: 10px 30px;">'+msg+'</div>',
																  '</div>',
																  '<div class="clearfix">',
																	'<hr style="margin: 10px 0 !important">',
																	  '<div class="text-center">'+btn+'</div>',
																	'</div>',
																  '</div>',
																'</div>',
															'</li>'].join('');

									var headerIcons = $('#navbar ul.nav.navbar-nav');
									if (headerIcons.length > 0){
										headerIcons.first().prepend(VTPremiumIcon);
									}
								}else{
									var bgColor='008000';
									 var msg='License Key Setup and Extension Installation has been completed.';
									 var btn='<button class="btn btn-success" style="margin-right:5px;" onclick="location.href=\'index.php?module=CTMobileSettings&parent=Settings&view=Details\'">CTMobileSettings Dashboard</button>';
									 var VTPremiumIcon = ['<li class="dropdown">',
															  '<div style="margin-top: 13px;" class="">',
																'<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" style="padding: 10px;">',
																  '<img style="width:25px; height:20px; border-radius: 50%; background-color: #'+bgColor+'" src="layouts/v7/modules/CTMobileSettings/img/CRMTiger.png" >',
																'</a>',
																'<div class="dropdown-menu" role="menu">',
																  '<div class="row">',
																	'<div class="col-lg-12" style="min-width: 350px; padding: 10px 30px;">'+msg+'</div>',
																  '</div>',
																  '<div class="clearfix">',
																	'<hr style="margin: 10px 0 !important">',
																	  '<div class="text-center">'+btn+'</div>',
																	'</div>',
																  '</div>',
																'</div>',
															'</li>'].join('');

									var headerIcons = $('#navbar ul.nav.navbar-nav');
									if (headerIcons.length > 0){
										headerIcons.first().prepend(VTPremiumIcon);
									}
									
								}
							}
						});
					}	
				}
			}
	});
	
});



		

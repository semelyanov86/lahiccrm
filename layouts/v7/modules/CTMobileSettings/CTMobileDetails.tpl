{*<!--
 /*+*******************************************************************************
 * The content of this file is subject to the CRMTiger Pro license.
 * ("License"); You may not use this file except in compliance with the License
 * The Initial Developer of the Original Code is vTiger
 * The Modified Code of the Original Code owned by https://crmtiger.com/
 * Portions created by CRMTiger.com are Copyright(C) CRMTiger.com
 * All Rights Reserved.
  ***************************************************************************** */
-->*}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="layouts/v7/modules/CTMobileSettings/CustomStyle.css" rel="stylesheet" type="text/css">
<div class="main_div">
	<div class="left_div">
		<label style="font:24px solid black;margin-top:10px;margin-left:10px;display:inline-block;">{vtranslate("MODULE_LBL",$MODULE)}</label>
	</div>
	<div class="right_div">
		<button style="float:right;margin-right:10px;" class="btn-2" title="{vtranslate("Help",$MODULE)}" id="help_btn" data-url="{CTMobileSettings_Module_Model::$CTMOBILE_HELP_URL}"><img src="layouts/v7/modules/CTMobileSettings/icon/help.png" width="25"/></button>
		<button style="float:right;margin-right:10px;" class="btn-2" title="{vtranslate("Apple Store",$MODULE)}" id="ios_btn" data-url="{CTMobileSettings_Module_Model::$CTMOBILE_APPLE_STORE_URL}"><img src="layouts/v7/modules/CTMobileSettings/icon/ios.png" width="25"/></button>
		<button style="float:right;margin-right:10px;" class="btn-2" title="{vtranslate("Android Store",$MODULE)}" id="android_btn" data-url="{CTMobileSettings_Module_Model::$CTMOBILE_ANDROID_STORE_URL}"><img src="layouts/v7/modules/CTMobileSettings/icon/android.png" width="25"/></button>
	</div>
</div>
<hr>
<div class="marquee">
</div>
<div class="container-fluid">
  <!-- section for upper boxes -->
	<div class="row">
		<div class="col-md-3">
			<div class="dash-box dash-box-color-1">
				<div class="dash-box-body">
					<span class="dash-box-title">{vtranslate("Active Users",$MODULE)}</span>
					<span class="dash-box-count">{$ACTIVE_USER}</span>
				</div>						
			</div>
		</div>
		<div class="col-md-3">
			<div class="dash-box dash-box-color-2">
				<div class="dash-box-body">
					<span class="dash-box-title">{vtranslate("Mobile Users",$MODULE)}</span>
					<span class="dash-box-count">{$MOBILE_USER}</span>
				</div>				
			</div>
		</div>
		<div class="col-md-3">
			<div class="dash-box dash-box-color-3">	
				<div class="dash-box-body">
					<span class="dash-box-title">{vtranslate("Checked-In Meetings",$MODULE)}</span>
					<span class="dash-box-count">{$MEETING_RECORDS}</span>
				</div>					
			</div>
		</div>
		<div class="col-md-3">
			<div class="dash-box dash-box-color-4">
				<div class="dash-box-body">
					<span class="dash-box-title">{vtranslate("Checked-Out Meetings",$MODULE)}</span>
					<span class="dash-box-count">{$CHECKOUT_RECORDS}</span>
				</div>								
			</div>
		</div>
	</div>
	<!-- section for upper boxes -->
	
	<!-- section for below boxes -->
	<div class="row mrgn_top">
		<div class="col-md-4">
			<div class="box">
				<div class="ct_panel">
					<div class="under_box">
						<div class="cttitle">{vtranslate("LBL_ACCOUNT_SUMMARY",$MODULE)}</div>
						<a href="{CTMobileSettings_Module_Model::$CTMOBILE_HELP_URL}" target="_blank"><div style="position:absolute;right:55px;"><img src="layouts/v7/modules/CTMobileSettings/icon/help.png"></div></a>
						<div class="icn_right"><img src="layouts/v7/modules/CTMobileSettings/img/account_summary.png"></div>
					</div>
				</div>
				<div class="box_cont">
					  <div class="row form-group mrgn_btm">
						<label class="col-md-4">{vtranslate("LBL_ORDER",$MODULE)} :</label>
						<p class="col-md-8">{$LICENSE_DATA['ORDER_ID']}</p>
					  </div>
					  <div class="row form-group mrgn_btm">
						<label class="col-md-4">{vtranslate("LBL_MY_PLAN",$MODULE)} :</label>
						<p class="col-md-8">{$LICENSE_DATA['Plan']}</p>
					  </div>
					  <div class="row form-group mrgn_btm">
						<label class="col-md-4">{vtranslate("Expire Date",$MODULE)} :</label>
						<p class="col-md-8">{$LICENSE_DATA['NextPaymentDate']}</p>
					  </div>
					  <div class="">
							<button class="btn-5" title="{vtranslate("LBL_LICENSE_CONFIGURATION",$MODULE)}" id="license_btn" data-url="index.php?module=CTMobileSettings&parent=Settings&view=LicenseDetail"><span class="btn_line"><img src="layouts/v7/modules/CTMobileSettings/icon/eight.png" width="25"/></span><span class="ct-title">{vtranslate("LBL_LICENSE_CONFIGURATION",$MODULE)}</span></button>
							<br/>
							<br/>
							<button class="btn-5" title="{vtranslate("BTN_CTMOBILE_ACCESS_USER",$MODULE)}" id="ctmobileAccessUser" data-url="{CTMobileSettings_Module_Model::$CTMOBILE_ACCESSUSER_URL}"><span class="btn_line"><img src="layouts/v7/modules/CTMobileSettings/icon/seven.png" width="25"/></span><span class="ct-title">{vtranslate("BTN_CTMOBILE_ACCESS_USER",$MODULE)}</span></button>
							<br/>
							<br/>
							<button class="btn-5 unInstallCTMobile" title="{vtranslate("LBL_CLOSE_ACCOUNT",$MODULE)}" id="unInstallCTMobile"><span class="btn_line"><img src="layouts/v7/modules/CTMobileSettings/icon/six.png" width="25"></span><span class="ct-title">{vtranslate("LBL_CLOSE_ACCOUNT",$MODULE)}</span></button>
				      </div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="box">
				<div class="ct_panel">
					<div class="under_box">
						<div class="cttitle">{vtranslate("LBL_MAP_CONFIGURATION",$MODULE)}</div>
						<a href="{CTMobileSettings_Module_Model::$CTMOBILE_HELP_URL}" target="_blank"><div style="position:absolute;right:55px;"><img src="layouts/v7/modules/CTMobileSettings/icon/help.png"></div></a>
						<div class="icn_right"><img src="layouts/v7/modules/CTMobileSettings/img/config.png"></div>
					</div>
				</div>
				<div class="box_cont">
					<a href="index.php?module=CTMobileSettings&parent=Settings&view=Settings">
					<p class="main_text"><center><b>{vtranslate("LBL_CTMOBILE_LIMITED_OFFER",$MODULE)}</b></center></p>
					</a>
					<center><p style="color:red;"><b>{vtranslate("Available Only for Premium Plan",$MODULE)}</b></p></center>
					
					{vtranslate("CRMTiger provides the following Map related features",$MODULE)}
					<ul>
					<li>{vtranslate("Nearby Contacts view in Mobile app",$MODULE)}</li>
					<li>{vtranslate("Live Tracking of Team(users) who enable their GPS",$MODULE)}</li>
					<li>{vtranslate("Calculate Distance between two Location",$MODULE)}</li>
					</ul>
					</p>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="box">
				<div class="ct_panel">
					<div class="under_box">
						<div class="cttitle">{vtranslate("LBL_APP_UPDATES",$MODULE)}</div>
						<a href="{CTMobileSettings_Module_Model::$CTMOBILE_HELP_URL}" target="_blank"><div style="position:absolute;right:55px;"><img src="layouts/v7/modules/CTMobileSettings/icon/help.png"></div></a>
						<div class="icn_right"><img src="layouts/v7/modules/CTMobileSettings/img/update.png"></div>
					</div>
				</div>
				<div class="box_cont">
					  <div class="row form-group mrgn_btm">
						<label class="col-md-6">{vtranslate("Your Version",$MODULE)} :</label>
						<p class="col-md-6">{$VERSION}</p>
					  </div>
					  <div class="row form-group mrgn_btm">
						<label class="col-md-6">{vtranslate("LBL_LATEST_VERSION",$MODULE)} :</label>
						<p class="col-md-6">{$ext_ver}</p>
					  </div>
					   <div class="">
							{if $VERSION neq $ext_ver}
								<button class="btn-5" title="{vtranslate("LBL_CLICK_UPDATE",$MODULE)}" id="updatectmobile" data-url="{CTMobileSettings_Module_Model::$CTMOBILE_UPGRADEVIEW_URL}"><span class="btn_line"><img src="layouts/v7/modules/CTMobileSettings/icon/five.png" width="25"/></span><span class="ct-title">{vtranslate("LBL_CLICK_UPDATE",$MODULE)}</span></button>
								<br/>
							{else}
								<center><label class="text text-success">{vtranslate("LBL_UPDATED_VERSION",$MODULE)}</label></center>
							{/if}
								<br/>
								<a target="_blank" href="{CTMobileSettings_Module_Model::$CTMOBILE_RELEASE_NOTE_URL}"><button class="btn-5" title="{vtranslate("View Release Note",$MODULE)}" data-url="{CTMobileSettings_Module_Model::$CTMOBILE_RELEASE_NOTE_URL}"><span class="btn_line"><img src="layouts/v7/modules/CTMobileSettings/icon/four.png" width="20"/></span><span class="ct-title">{vtranslate("View Release Note",$MODULE)}</span></button></a>
				      </div>
				</div>
			</div>
		</div>
	</div>


	<div class="row mrgn_top">
		<div class="col-md-4">
			<div class="box">
				<div class="ct_panel">
					<div class="under_box">
						<div class="cttitle">{vtranslate("LBL_NOTIFICATIONS",$MODULE)}</div>
						<a href="{CTMobileSettings_Module_Model::$CTMOBILE_HELP_URL}" target="_blank"><div style="position:absolute;right:55px;"><img src="layouts/v7/modules/CTMobileSettings/icon/help.png"></div></a>
						<div class="icn_right"><img src="layouts/v7/modules/CTMobileSettings/img/bell.png"></div>
					</div>
				</div>
				<div class="box_cont">
					{if $CTPUSHNOTIFICATION_DATA['totalRecords'] neq 0}
						{foreach item=DATA from=$CTPUSHNOTIFICATION_DATA['data']}
							<a href="{$CTPUSHNOTIFICATION_MODULEMODEL->getDetailViewUrl({$DATA['id']})}">
								<div>
									<span class="glyphicon glyphicon-bell"></span>{$DATA['title']}
									<span class="txt_inline">{$DATA['modifiedtime']}</span>		
									<p>{$DATA['description']}</p>
								</div>
							</a>
							<hr/>
						{/foreach}
					{else}
						<center>
							{vtranslate('LBL_NO_RECORDS_FOUND',$MODULE)}
						</center>
					{/if}
					{if $CTPUSHNOTIFICATION_DATA['totalRecords'] ge 3}
						<a class="text text-info pull-right" href="{$CTPUSHNOTIFICATION_URL}">{vtranslate("LBL_VIEWMORE",$MODULE)}</a>	
					{/if}
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="box">
				<div class="ct_panel">
					<div class="under_box">
						<div class="cttitle">{vtranslate("LBL_TEAM_TRACKING",$MODULE)}</div>
						<a href="{CTMobileSettings_Module_Model::$CTMOBILE_HELP_URL}" target="_blank"><div style="position:absolute;right:55px;"><img src="layouts/v7/modules/CTMobileSettings/icon/help.png"></div></a>
						<div class="icn_right"><img src="layouts/v7/modules/CTMobileSettings/img/location.png"></div>
					</div>
				</div>
				<div class="box_cont">
						<button class="btn-5" title="{vtranslate("BTN_LIVETRACKING_USER",$MODULE)}" id="livetrackingUser" data-url="{CTMobileSettings_Module_Model::$CTMOBILE_LIVETRACKINGUSER_URL}"><span class="btn_line"><img src="layouts/v7/modules/CTMobileSettings/icon/three.png" width="25"/></span><span class="ct-title">{vtranslate("BTN_LIVETRACKING_USER",$MODULE)}</span></button>
						<br/>
						<br/>
						<button class="btn-5" title="{vtranslate("LBL_CLICK_TEAM_TRACKING",$MODULE)}" id="teamtracking" data-url="{CTMobileSettings_Module_Model::$CTMOBILE_TEAMTRACKING_URL}"><span class="btn_line"><img src="layouts/v7/modules/CTMobileSettings/icon/two.png" width="25"/></span><span class="ct-title">{vtranslate("LBL_CLICK_TEAM_TRACKING",$MODULE)}</span></button>
				 </div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="box">
				<div class="ct_panel">
					<div class="under_box">
						<div class="cttitle">{vtranslate("LBL_MEETING_ATTENDANCE",$MODULE)}</div>
						<a href="{CTMobileSettings_Module_Model::$CTMOBILE_HELP_URL}" target="_blank"><div style="position:absolute;right:55px;"><img src="layouts/v7/modules/CTMobileSettings/icon/help.png"></div></a>
						<div class="icn_right"><img src="layouts/v7/modules/CTMobileSettings/img/meeting.png"></div>
					</div>
				</div>
				<div class="box_cont">
						<button class="btn-5" title="{vtranslate("LBL_ATTENDANCE_RPT",$MODULE)}" id="attendance_rpt" data-url="{$CTATTENDANCE_URL}"><span class="btn_line"><img src="layouts/v7/modules/CTMobileSettings/icon/one.png" width="25"/></span><span class="ct-title">{vtranslate("LBL_ATTENDANCE_RPT",$MODULE)}</span></button>
				 </div>
			</div>
		</div>
	</div>

</div>

<div id="myModal" class="modal fade" role="dialog" style="">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">{vtranslate('Check Permission',$MODULE)}</h4>
				</div>
				<div class="modal-body" style="width: 100%">
					<div id="section-1">
					  <h2>{vtranslate('PHP Extensions',$MODULE)}</h2>
					    <div class="table table-responsive">
					       <table id="table1" border="1" style="border-collapse: collapse;width: 100%" align="center">
								<thead>
								<tr id ="tableheader">	
									<th>{vtranslate('Extensions Name',$MODULE)}</th>
									<th>{vtranslate('Present Value',$MODULE)}</th>
									<th>{vtranslate('Required Value',$MODULE)}</th>
								</tr>
								
								</thead>
								<tbody>
									
								
								</tbody>
							</table>
						</div>
					</div>
					<div id="section-2">
					  <h2>{vtranslate('File & Folder Permission',$MODULE)}</h2>
					     <div class="table table-responsive">
							<table id="table2" border="1" style="border-collapse: collapse;width: 100%" align="center">
								<thead>
								<tr id ="tableheader">	
									<th>{vtranslate('File Name',$MODULE)}</th>
									<th>{vtranslate('Required Value',$MODULE)}</th>
									<th>{vtranslate('Required Value',$MODULE)}</th>
								</tr>
								</thead>
								<tbody>
									
								
								</tbody>
							</table>
						</div>
					</div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">{vtranslate('Close',$MODULE)}</button>
				</div>
			</div>
		</div>
	</div>


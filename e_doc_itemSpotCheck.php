<script>
function get_itemSpot_list(category) {
	document.getElementById("auto_itemSpotList").style.display = "";
	document.getElementById("self_itemSpotList").style.display = "none";
	document.forms.itemSpotCheck.sub_mode.value = "";
	document.getElementById("itemSpotList").src = "?page=itemSpotList&item=" + category;
	if(category == 'random') {
		document.forms.itemSpotCheck.category.value = "";
		document.forms.itemSpotCheck.random_select.value = "랜덤추출";
	}
}

function self_insert() {
	document.getElementById("auto_itemSpotList").style.display = "none";
	document.getElementById("itemSpotList").src = "?page=itemSpotList";
	document.getElementById("self_itemSpotList").style.display = "";
	document.forms.itemSpotCheck.sub_mode.value = "직접입력";
	document.forms.itemSpotCheck.random_select.value = "";
}

function doc_submit() {
	var target = document.forms.itemSpotCheck;
	target.doc_date.required = "required";

	for(var i = 1; i < 10; i++) {
		if(document.getElementById("appUser" + i).value != "") {
			var is_app_set = "true";
			break;
		} else {
			var is_app_set = "false";
		}
	}

	for(var i = 1; i <= 20; i++) {
		if(document.getElementById("self_item_code_" + i).value != "" && document.getElementById("self_item_description_" + i).value != "" && document.getElementById("self_item_avgCost_" + i).value != "" && document.getElementById("self_item_qty_" + i).value != "") {
			var is_item_set = "true";
		} else {
			if(document.getElementById("self_item_code_" + i).value == "" && document.getElementById("self_item_description_" + i).value == "" && document.getElementById("self_item_avgCost_" + i).value == "" && document.getElementById("self_item_qty_" + i).value == "") {
				var is_item_set = "true";
			} else {
				var item_no = i;
				var is_item_set = "false";
				break;
			}
		}
	}

	if(is_app_set == "false") {
		alert("결재자를 선택하십시요.");
	} else if(is_item_set == "false") {
		alert(item_no + "번 아이템에 빈칸이 있습니다.");
	} else {
		if(target.sub_mode.value != "") {
			var answer = confirm("상신 하시겠습니까?");
			if(answer) {
				target.mode.value = "submit";
				target.submit();
			}
		} else {
			if(target.category.value != "" || target.random_select.value != "") {
				var answer = confirm("상신 하시겠습니까?");
				if(answer) {
					target.mode.value = "submit";
					target.submit();
				}
			} else {
				alert("카테고리를 선택하십시요.");
			}
		}
	}
}
</script>
<!-- e-doc Item_Spot_Check START -->
<form name="itemSpotCheck" action="upload/upload_itemSpotCheck.php" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="">
<input type="hidden" name="sub_mode" value="">
<input type="hidden" name="item_code" value="">
<input type="hidden" name="item_description" value="">
<input type="hidden" name="item_avgCost" value="">
<input type="hidden" name="item_qty" value="">
<input type="hidden" name="random_select" value="">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">Item Spot Check 작성</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc TITLE END -->

		<!-- e-doc Item_Spot_Check MAIN START -->
		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><input type="button" class="doc_submit_btn_style" onClick="doc_submit()" value="상신하기"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Submit/Save BTN END -->

		<!-- Item_Spot_Check FORM START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<!-- Item_Spot_Check FORM TITLE START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td width="140"></td>
									<td align="center" class="doc_title">Item Spot Check</td>
									<td width="100" align="right" style="padding-top:10px;"><input type="button" id="fApproval" value="결재자 검색"></td>
									<td width="40" align="right" style="padding-top:10px;"><input type="button" id="doc_approval_btn" value="★"></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Item_Spot_Check FORM TITLE END -->

					<!-- Item_Spot_Check FORM CONTENT START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;">
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content" style="border-right: 0;"></td>
									<td width="95" class="doc_field_content" style="border-left: 0; border-right: 0;"></td>
									<td class="doc_field_content" style="border-left: 0;"></td>
									<td width="365" rowspan="6" align="center" valign="top" style="padding:0;border-bottom:1px #afafaf solid;">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="5%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;">작성자</td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1"></td><input type="hidden" id="appUser1" name="appUser1" >
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"></td><input type="hidden" id="appUser2" name="appUser2" >
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"></td><input type="hidden" id="appUser3" name="appUser3" >
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"></td><input type="hidden" id="appUser4" name="appUser4" >
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;"><?=Br_iconv($_SESSION['memberName'])?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName1"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName2"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName3"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName4"></td>
											</tr>
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app5"></td><input type="hidden" id="appUser5" name="appUser5" >
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app6"></td><input type="hidden" id="appUser6" name="appUser6" >
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app7"></td><input type="hidden" id="appUser7" name="appUser7" >
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app8"></td><input type="hidden" id="appUser8" name="appUser8" >
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app9"></td><input type="hidden" id="appUser9" name="appUser9" >											
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName5"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName6"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName7"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName8"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName9"></td>
											</tr>
										</table>
									</td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서종류</b></td>
									<td class="doc_field_content" colspan="3">Item Spot Check</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content" colspan="3">작성전 문서입니다.</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
									<td height="30" align="center" class="doc_field_name"><b>검사회사</b></td>
									<td class="doc_field_content"><?=get_company_name($_SESSION['memberCID']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>일자</b></td>
									<td class="doc_field_content"><input id="doc_calendar" name="doc_date" type="text" style="width:85px;" maxlength="10" value="<?=date("Y-m-d");?>"></td>
									<td height="30" align="center" class="doc_field_name"><b>검사자</b></td>
									<td class="doc_field_content">미정</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>카테고리</b></td>
									<td class="doc_field_content">
									<?
										include_once "includes/db_configms_WS.php";
										
										$query = "SELECT mf2.c_code,mf.type2, mf2.s_code, mf2.kname as detailname, mf.kname as grpname FROM mfProd_type2 as mf2 inner join mfProd_type as mf on mf.c_code = mf2.c_code ORDER BY mf2.c_code ASC";
										
										$row2 = mssql_query($query);
										
									?>
										<select name="category" style="width:200px;" onChange="get_itemSpot_list(this.value)">
											<option value="">CATEGORY</option>
									<?	while($rst = mssql_fetch_array($row2)) { ?>
											<? if($rst['s_code'] == 1) { ?>
												<optgroup label="<?=Br_iconv($rst['grpname']); ?>">
											<? } ?>
												<option value="<?=Br_iconv($rst['detailname']); ?>"><?=Br_iconv($rst['detailname']); ?></option>
											
									<?	} ?>
										</select>
									<? mssql_close($conn_WS); ?>
										<input type="button" onClick="self_insert()" value="직접입력하기">
										<? if($_SESSION['memberCID'] == 1) { ?>
											<input type="button" onClick="get_itemSpot_list('random')" value="랜덤추출">
										<? } ?>
									</td>
									<td height="30" align="center" class="doc_field_name"><b>검사시간</b></td>
									<td class="doc_field_content">미정</td>
								</tr>

								<tr id="auto_itemSpotList">
									<td colspan="5" height="400px;"><iframe id="itemSpotList" src="?page=itemSpotList" width="100%" height="100%"></iframe></td>
								</tr>

								<tr id="self_itemSpotList" style="display:none;">
									<td colspan="5">
										<table width="100%" style="margin-top:10px;" cellspacing="0">
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td width="5%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;">No</td>
												<td width="15%" align="left" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">Item Code</td>
												<td width="30%" align="left" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">Item Description</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#084B8A; color:#FFFFFF;">Avg. Cost ($)</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#084B8A; color:#FFFFFF;">AcctEdge 재고</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#DBA901; border-right:1px solid #FFFFFF;">실재고</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#C9C799; border-right:1px solid #FFFFFF;">Difference</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#C9C799;">Diff. Amount ($)</td>
											</tr>
										<?	for($i = 1; $i <= 20; $i++) { ?>
												<tr class="doc_border" height="20px"  style="font-size:13px;">
													<td align="center" style="padding-top:3px;"><?=$i; ?></td>
													<td><input id="<?="self_item_code_".$i ?>" name="<?="self_item_code_".$i ?>" style="width:100%;"></td>
													<td><input id="<?="self_item_description_".$i ?>" name="<?="self_item_description_".$i ?>" style="width:100%;"></td>
													<td><input id="<?="self_item_avgCost_".$i ?>" name="<?="self_item_avgCost_".$i ?>" style="width:100%; text-align:right;" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)'></td>
													<td><input id="<?="self_item_qty_".$i ?>" name="<?="self_item_qty_".$i ?>" style="width:100%; text-align:right;" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)'></td>
													<td></td>
													<td></td>
													<td></td>
												</tr>
										<?	} ?>
										</table>
									</td>
								</tr>
							</table>
						</td>						
					</tr>
					<!-- Item_Spot_Check FORM CONTENT END -->
				</table>
			</td>
		</tr>
		<!-- e-doc Item_Spot_Check MAIN END -->

		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><input type="button" class="doc_submit_btn_style" onClick="doc_submit()" value="상신하기"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
		<!-- Submit/Save BTN END -->
		<!-- e-doc Item_Spot_Check MAIN END -->
	</table>
</td>
</form>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Item_Spot_Check END -->

<!-- Favorite Approval User Add START -->
<script>
$(document).ready(function(){
	$("#fApproval").click(function(){
		var pos = $(this).position();
		var _left = pos.left;
		var _top = pos.top + 10;
		var _width = $("#AllUserList").width() - $(this).width();
		$("#AllUserList").css("left", _left - _width - 310);
		$("#AllUserList").css("top", _top+$(this).height());
		$("#UserSearch").attr("src", "?page=userSearch");
		$("#AllUserList").show();
		$("#doc_approval_user").hide();
	});
});
</script>

<div id="AllUserList" style="border:2px #666666 solid; background-color:#ffffff; position:absolute; z-index:10; display:none; width:300px; left:0px; top:0px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="middle" style="padding:14px 0 0 20px; background-color:#F6CECE;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr height="25">
						<td style="letter-spacing:-1px;"><b>결재자 검색하기</b></td>
						<td width="22" align="left"><a href="javascript:"><img src="css/img/bt_closelayer.gif" onClick="jQuery('#AllUserList').hide()"></a></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td><iframe id="UserSearch" src="?page=userSearch" height="400"></iframe></td>
		</tr>
	</table>
</div>
<!-- Favorite Approval User Add END -->

<!-- Approval Person Select jQuery & HTML START -->
<script>
$(document).ready(function(){
	$("#doc_approval_btn").click(function(){
		var pos = $(this).position();
		var _left = pos.left;
		var _top = pos.top + 10;
		var _width = $("#doc_approval_user").width() - $(this).width();
		$("#doc_approval_user").css("left", _left - _width - 350);
		$("#doc_approval_user").css("top", _top+$(this).height());
		$("#fUserSearch").attr("src", "?page=fUserSearch");
		$("#doc_approval_user").show();
		$("#AllUserList").hide();
	});
});
</script>

<div id="doc_approval_user" style="border:2px #666666 solid; background-color:#ffffff; position:absolute; z-index:10; display:none; width:300px; left:0px; top:0px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="middle" style="padding:14px 0 0 20px; background-color:#CECEF6;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr height="25">
						<td style="letter-spacing:-1px;"><b>결재자 즐겨찾기</b></td>
						<td width="22" align="left"><a href="javascript:"><img src="css/img/bt_closelayer.gif" onClick="jQuery('#doc_approval_user').hide()"></a></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td><iframe id="fUserSearch" src="?page=fUserSearch" height="400"></iframe></td>
		</tr>
	</table>
</div>
<!-- Approval Person Select jQuery & HTML END -->

<!-- Delete Approval User START -->
<script>
function delete_from_doc(i) {
	document.getElementById("app" + i).innerHTML = "";
	document.getElementById("appUser" + i).value = "";
	document.getElementById("appUserName" + i).innerHTML = "";
}
</script>
<!-- Delete Approval User END -->
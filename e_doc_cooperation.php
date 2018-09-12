<?
include_once "includes/general.php";
?>
<!-- Submit Button Javascript START -->
<script>
	function doc_submit() {
		var target = document.forms.form_proposal;
		target.doc_date.required = "required";
		target.doc_subject.required = "required";

		for(var i = 1; i < 10; i++) {
			if(document.getElementById("appUser" + i).value != "") {
				var is_app_set = "true";
				break;
			} else {
				var is_app_set = "false";
			}
		}

		if(is_app_set == "false") {
			alert("결재자를 선택하십시오.");
		} else {
			if(target.doc_date.value != "" && target.doc_subject.value != "" && target.runcompid.value != "" && target.rundeptid.value != "#") {
				var answer = confirm("상신 하시겠습니까?");
				if(answer) {
					target.mode.value = "insert";
					saveContent();
				}
			}
		}
	}

	function doc_save() {
		var target = document.forms.form_proposal;
		target.doc_date.required = "required";
		target.doc_subject.required = "required";

		if(target.doc_date.value != "" && target.doc_subject.value != "") {
			var answer = confirm("저장 하시겠습니까?");
			if(answer) {
				target.mode.value = "save";
				saveContent();
			}
		}
	}

	function change_Field(getVal) {
		var objTwo = document.form_proposal.rundeptid;
		var i;

		for (i = document.form_proposal.rundeptid.options.length; i >= 0; i--) {
			document.form_proposal.rundeptid.options[i] = null; 
		}

		switch (getVal) {

		case '1': 
			objTwo.options[0] = new Option (':::부서 선택:::','#');
			objTwo.options[1] = new Option ('Sales','1');
			objTwo.options[2] = new Option ('구매','2');
			objTwo.options[3] = new Option ('물류','3');
			objTwo.options[4] = new Option ('Hardware','4');
			objTwo.options[5] = new Option ('업무지원','5');
			objTwo.options[6] = new Option ('임원진','8');
			return;
		case '2': 
			objTwo.options[0] = new Option (':::부서 선택:::','#');
			objTwo.options[1] = new Option ('Sales','1');
			objTwo.options[2] = new Option ('구매','2');
			objTwo.options[3] = new Option ('물류','3');
			objTwo.options[4] = new Option ('업무지원','4');
			objTwo.options[5] = new Option ('본부','9');
			return;
		case '3': 
		case '4': 
			objTwo.options[0] = new Option (':::부서 선택:::','#');
			objTwo.options[1] = new Option ('매장','1');
			objTwo.options[2] = new Option ('야채','2');
			objTwo.options[3] = new Option ('정육','3');
			objTwo.options[4] = new Option ('반찬','4');
			objTwo.options[5] = new Option ('생선','5');
			objTwo.options[6] = new Option ('하우스웨어','6');
			objTwo.options[7] = new Option ('C/S','7');
			objTwo.options[8] = new Option ('본부','9');
			return;
		case '5': 
			objTwo.options[0] = new Option (':::부서 선택:::','#');
			objTwo.options[1] = new Option ('Accounts','1');
			objTwo.options[2] = new Option ('IT','2');
			objTwo.options[3] = new Option ('총무','3');
			objTwo.options[4] = new Option ('PR','4');
			objTwo.options[5] = new Option ('경영지원','5');
			return;
		}
	}
</script>
<!-- Submit Button Javascript END -->

<!-- e-doc Proposal START -->
<form name="form_proposal" action="upload/upload_Cooperation.php" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">협조문서 작성</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc TITLE END -->

		<!-- e-doc Proposal MAIN START -->
		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="doc_submit()">상신하기</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="doc_save()">임시저장</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Submit/Save BTN END -->

		<!-- Proposal FORM START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<!-- Proposal FORM TITLE START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td width="140"></td>
									<td align="center" class="doc_title">협 조 문</td>
									<td width="100" align="right" style="padding-top:10px;"><button id="fApproval">결재자 검색</button></td>
									<td width="40" align="right" style="padding-top:10px;"><button id="doc_approval_btn">★</button></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Proposal FORM TITLE END -->

					<!-- Proposal FORM CONTENT START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;">
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content"></td>
									<td width="365" rowspan="6" align="center" valign="top">
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
									<td class="doc_field_content">협조문</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content">작성전 문서입니다.</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안부서</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_company_sname($_SESSION['memberCID']))." - ".Br_iconv(get_coop_Dept($_SESSION['memberCID'], $_SESSION['memberDID']))?></td>
									<input type="hidden" name="runcompid" value="<?=$_SESSION['memberCID']; ?>">
									<input type="hidden" name="rundeptid" value="<?=$_SESSION['memberDID']; ?>">
								</tr>
								<!--
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>협조회사</b></td>
									<td  align="left" class="doc_field_content">
										<select name="runcompid" style="width:100px;" onchange='change_Field(this.value)'>
<?										$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
										$row2 = mssql_query($query);
										while($rst = mssql_fetch_array($row2)) { ?>
											<option value='<?=$rst['companyID']?>'<?if($rst['companyID'] == $_SESSION['memberCID']) echo "selected"; ?>><?=$rst['companyDesc']?></option>
<?										}	?>
										</select>
									<td width="95" align="center" class="doc_field_name"><b>협조부서</b></td>
									<td class="doc_field_content">
										<select name="rundeptid" style="width:100px;"></select>
									</td>
								</tr>
								-->
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성일</b></td>
									<td class="doc_field_content""><input id="doc_calendar" name="doc_date" type="text" style="width:85px;" maxlength="10" value="<?=date("Y-m-d");?>"></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>협조부서</b></td>
									<td class="doc_field_content" id="coopAdded" colspan="2"><button id="coop">협조부서 선택</button></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content" colspan="2"><input name="doc_subject" type="text" style="width:630px;"></input></td>
								</tr>

								<!-- Editor START -->
								<tr>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내용</b></td>
									<td align="center" style="border: 1px solid #c9c9c9;" colspan="2">
										<? include_once "editor/editor.html"; ?>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content" colspan="2">
										<input name="userfile" type="file" />
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content" colspan="2">
										<input name="userfile2" type="file" />
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content" colspan="2">
										<input name="userfile3" type="file" />
									</td>
								</tr>
								<!-- Editor END -->
							</table>
						</td>						
					</tr>
					<!-- Proposal FORM CONTENT END -->
				</table>
			</td>
		</tr>
		<!-- e-doc Proposal MAIN END -->

		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="doc_submit()">상신하기</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="doc_save()">임시저장</td>
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
		<!-- e-doc Proposal MAIN END -->
	</table>
</td>
</form>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Proposal END -->

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

<!-- Coop ADD START -->
<script>
$(document).ready(function(){
	$("#coop").click(function(){
		var pos = $(this).position();
		var _left = pos.left + 110;
		var _top = pos.top;
		var _width = $("#CoopList").width() - $(this).width();
		$("#CoopList").css("left", _left);
		$("#CoopList").css("top", _top);
		$("#CoopSearch").attr("src", "iframe_coopList.php");
		$("#CoopList").show();
	});
});
</script>

<div id="CoopList" style="border:2px #666666 solid; background-color:#ffffff; position:absolute; z-index:10; display:none; width:300px; left:0px; top:0px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="middle" style="padding:14px 0 0 20px; background-color:#5FB404;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr height="25">
						<td style="letter-spacing:-1px;"><b>협조부서 선택하기</b></td>
						<td width="22" align="left"><a href="javascript:"><img src="css/img/bt_closelayer.gif" onClick="jQuery('#CoopList').hide()"></a></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td><iframe id="CoopSearch" src="iframe_coopList.php" height="400"></iframe></td>
		</tr>
	</table>
</div>
<!-- Coop ADD END -->

<!-- Delete Coop START -->
<script>
function delete_coopList(companyID, departmentID) {
	//var target = document.getElementById(companyID + "_" + departmentID).innerHTML = "";
	$("#" + companyID + "_" + departmentID).remove();
	$("#" + "coopList_" + companyID + "_" + departmentID).remove();
}
</script>
<!-- Delete Coop END -->
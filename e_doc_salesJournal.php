<script>
function resize(obj) {
	//alert(obj.parentNode.offsetHeight);
	obj.style.height = obj.style.minHeight;
	if((obj.scrollHeight + 2) > parseInt(obj.style.minHeight)) {
		obj.style.height = (obj.scrollHeight + 2)+"px";
	} else {
		obj.style.height = obj.style.minHeight;
	}
}

function doc_submit() {
	var target = document.forms.salesJournal;

	for(var i = 1; i < 10; i++) {
		if(document.getElementById("appUser" + i).value != "") {
			var is_app_set = "true";
			break;
		} else {
			var is_app_set = "false";
		}
	}

	if(is_app_set == "false") {
		alert("결재자를 선택하십시요.");
	} else {
		var answer = confirm("상신 하시겠습니까?");
		if(answer) {
			target.approval.value = 2;
			target.mode.value = "submit";
			target.submit();
		}
	}
}

function doc_save() {
	var target = document.forms.salesJournal;
	var answer = confirm("저장 하시겠습니까?");
	if(answer) {
		target.approval.value = 3;
		target.mode.value = "save";
		target.submit();
	}
}
</script>

<!-- e-doc Sales Activities Journal START -->
<form name="salesJournal" action="upload/upload_salesJournal.php" method="post" enctype="multipart/form-data" accept-charset="utf-8">
<input type="hidden" name="mode" value="">
<input type="hidden" name="approval" value="">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">Sales Activities Journal</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc TITLE END -->

		<!-- e-doc Sales Activities Journal MAIN START -->
		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><input type="button" class="doc_submit_btn_style" onClick="doc_submit()" value="상신하기"></td>
									<td width="5"></td>
									<td><input type="button" class="doc_submit_btn_style" onClick="doc_save()" value="임시저장"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Submit/Save BTN END -->

		<!-- Sales Activities Journal FORM START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<!-- Sales Activities Journal FORM TITLE START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td width="140"></td>
									<td align="center" class="doc_title">Sales Activities Journal</td>
									<td width="100" align="right" style="padding-top:10px;"><input type="button" id="fApproval" value="결재자 검색"></td>
									<td width="40" align="right" style="padding-top:10px;"><input type="button" id="doc_approval_btn" value="★"></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Sales Activities Journal FORM TITLE END -->

					<!-- Sales Activities Journal FORM CONTENT START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;">
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content"></td>
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
									<td class="doc_field_content">Sales Activities Journal</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content">작성전 문서입니다.</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>상신일</b></td>
									<td class="doc_field_content"><?=date("Y-m-d");?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>소속회사</b></td>
									<td class="doc_field_content"><?=get_company_name($_SESSION['memberCID']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name" colspan="3"><b>작업일지</b></td>
								</tr>

								<tr>
									<td colspan="3">
										<table width="100%" cellspacing="0">
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td width="8%" align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;">시각(Time)</td>
												<td width="46%" align="center" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">업체명(Customer)</td>
												<td width="46%" align="center" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">특이사항(Remark)</td>
		
											</tr>
										<?	for($i = 1; $i <= 11; $i++) { ?>
												<tr class="doc_border" height="60px"  style="font-size:13px;">
													<td align="center" style="vertical-align:middle;"><?=($i+7).":00"; ?></td>
													<td><textarea wrap="hard" name="customer[]" style="width:100%; height:59px; min-height:59px; resize:none; background-color:#e2e2e2;" maxlength="300" onkeyup="resize(this)" onmouseover="this.style.border = '2px solid red'" onmouseout="this.style.border = '1px solid #A9A9A9'"></textarea></td>
													<td><textarea wrap="hard" name="remark[]" style="width:100%; height:59px; min-height:59px; resize:none;" maxlength="300" onkeyup="resize(this)" onmouseover="this.style.border = '2px solid red'" onmouseout="this.style.border = '1px solid #A9A9A9'"></textarea></td>
												</tr>
												<? if($i < 11) { ?>
													<tr class="doc_border" height="60px"  style="font-size:13px;">
														<td align="center" style="vertical-align:middle;"><?=($i+7).":30"; ?></td>
														<td><textarea wrap="hard" name="customer_30[]" style="width:100%; height:59px; min-height:59px; resize:none; background-color:#e2e2e2;" maxlength="300" onkeyup="resize(this)" onmouseover="this.style.border = '2px solid red'" onmouseout="this.style.border = '1px solid #A9A9A9'"></textarea></td>
														<td><textarea wrap="hard" name="remark_30[]" style="width:100%; height:59px; min-height:59px; resize:none;" maxlength="300" onkeyup="resize(this)" onmouseover="this.style.border = '2px solid red'" onmouseout="this.style.border = '1px solid #A9A9A9'"></textarea></td>
													</tr>
												<? } ?>
										<?	} ?>
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td width="100%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;" colspan="3">시장동향 (Market Tendency)</td>
											</tr>
											<tr class="doc_border" height="120px"  style="font-size:13px;">
												<td colspan="3"><textarea wrap="hard" name="marketTendency" style="width:100%; height:119px; min-height:119px; resize:none;" maxlength="500" onkeyup="resize(this)" onmouseover="this.style.border = '2px solid red'" onmouseout="this.style.border = '1px solid #A9A9A9'"></textarea></td>
											</tr>
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td width="100%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;" colspan="3">특이사항 (Remark)</td>
											</tr>
											<tr class="doc_border" height="120px"  style="font-size:13px;">
												<td colspan="3"><textarea wrap="hard" name="remark_last" style="width:100%; height:119px; min-height:119px; resize:none;" maxlength="500" onkeyup="resize(this)" onmouseover="this.style.border = '2px solid red'" onmouseout="this.style.border = '1px solid #A9A9A9'"></textarea></td>
											</tr>
<!--파일첨부 요기서부터 시작 -->
											<tr class="doc_border">
												<td style = "background-color: #084B8A;color:white;" height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
												<td class="doc_field_content" colspan="2">
													<input name="userfile1" type="file" />
												</td>
											</tr>
											<tr class="doc_border">
												<td style = "background-color: #084B8A;color:white;" height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
												<td class="doc_field_content" colspan="2">
													<input name="userfile2" type="file" />
												</td>
											</tr>
											<tr class="doc_border">
												<td style = "background-color: #084B8A;color:white;" height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
												<td class="doc_field_content" colspan="2">
													<input name="userfile3" type="file" />
												</td>
											</tr>	
										</table>
									</td>
								</tr>
							</table>
						</td>						
					</tr>
					<!-- Sales Activities Journal FORM CONTENT END -->
				</table>
			</td>
		</tr>
		<!-- e-doc Sales Activities Journal MAIN END -->

		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><input type="button" class="doc_submit_btn_style" onClick="doc_submit()" value="상신하기"></td>
									<td width="5"></td>
									<td><input type="button" class="doc_submit_btn_style" onClick="doc_save()" value="임시저장"></td>
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
		<!-- e-doc Sales Activities Journal MAIN END -->
	</table>
</td>
</form>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Sales Activities Journal END -->

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

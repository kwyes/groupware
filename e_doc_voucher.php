<?
include_once "includes/general.php";
$CompanyID =  $_SESSION['memberCID'];
?>
<script>
function doc_submit() {
	var target = document.forms.form_proposal;
	target.doc_date.required = "required";
	target.doc_subject.required = "required";
	target.runcompid.required = "required";

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

		if(target.doc_date.value != "" && target.doc_subject.value != "" && target.runcompid.value != "") {
			var answer = confirm("상신 하시겠습니까?");
			if(answer) {
				target.mode.value = "submit";
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
</script>
<?
	$today = date("Y-m-d");
?>
<!-- e-doc right 내용 START -->
<td width="" align="left" valign="top">
	<form name="form_proposal" method="post" action="upload/upload_Voucher.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="today" value="<?=$today?>">
	<table width="100%">
		<!-- e-doc right TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">지출결의서 작성</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc right TITLE END -->

		<!-- e-doc right CONTENT START -->
		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="doc_submit()">상신하기</button></a></td>
									<td width="5"></td>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="doc_save()">임시저장</button></a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Submit/Save BTN END -->

		<!-- doc main START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<!-- doc title START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td width="140"></td>
									<td align="center" class="doc_title">지출 결의서</td>
									<td width="100" align="right" style="padding-top:10px;"><input type="button" id="fApproval" value="결재자 검색"></input></td>
									<td width="40" align="right" style="padding-top:10px;"><input type="button" id="doc_approval_btn" value="★"></input></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- doc title END -->

					<!-- doc content START -->
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
												<td width="7%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;">결의자</td>
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
									<td class="doc_field_content">지출결의서</td>
									<td width="95" align="center" class="doc_field_name"><b>실행 회사</b></td>
									<td align="left" class="doc_field_content">
									<select name="runcompid">
								<?	
										$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
										$row2 = mssql_query($query);
										while($rst = mssql_fetch_array($row2)) {
								?>
										<option value='<?=$rst['companyID']?>'<?if($rst['companyID']== $CompanyID) echo "selected"; ?>>&raquo; <?=$rst['companyDesc']?></option>
								<?		}	?>
									</select>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content">작성전 문서입니다.</td>
									<td width="95" align="center" class="doc_field_name"><b>Pay To</b></td>
									<td class="doc_field_content"><input name="payto" type="text" style="width:100%; max-width:160;"></input></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
									<td width="95" align="center" class="doc_field_name"><b>Amount</b></td>
									<td class="doc_field_content"><input name="amount" type="text" style="width:100%; max-width:160;" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)'></input></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>부서명</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_Dept($_SESSION['memberDID']))?></td>
									<td width="95" align="center" class="doc_field_name"><b>Payment Method</b></td>
									<td class="doc_field_content">
										<input type="radio" name="PaymentMethod" value="CASH">CASH
										<input type="radio" name="PaymentMethod" value="CHEQUE" checked="checked">CHEQUE
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성일자</b></td>
									<td class="doc_field_content"><input name="doc_date" type="text" style="width:85px;" maxlength="10" value="<?=$today?>"></td>
									<td width="95" align="center" class="doc_field_name"><b>Currency Type</b></td>
									<td class="doc_field_content">
										<input type="radio" name="Currency" value="CAD$" checked="checked">CAD $
										<input type="radio" name="Currency" value="USD$">USD $
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>참조문서</b></td>
									<td class="doc_field_content" colspan="4" id="doc_link">
										<button id="doc_link_btn">참조문서 선택</button>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content" colspan="4"><input name="doc_subject" type="text" style="width:630px;"></input></td>
								</tr>
								<tr>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내용</b></td>
									<td align="center" colspan="4" style="border: 1px solid #c9c9c9;">
										<? include_once "editor/editor.html"; ?>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
										<input name="userfile" type="file" />
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
										<input name="userfile2" type="file" />
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
										<input name="userfile3" type="file" />
									</td>
								</tr>
							</table>
						</td>						
					</tr>
					<!-- doc content END -->
				</table>
			</td>
		</tr>
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="doc_submit()">상신하기</button></a></td>
									<td width="5"></td>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="doc_save()">임시저장</button></a></td>
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
		<!-- doc main END -->
		<!-- e-doc right CONTENT END -->
	</table>
	</form>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc right 내용 END -->
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

<!-- Proposal Link ADD START -->
<script>
$(document).ready(function(){
	$("#doc_link_btn").click(function(){
		var pos = $(this).position();
		var _left = pos.left;
		var _top = pos.top + 10 + $(this).height();
		var _width = $("#docLink").width() - $(this).width();
		$("#docLink").css("left", _left);
		$("#docLink").css("top", _top);
		$("#docSearch").attr("src", "iframe_docLink.php");
		$("#docLink").show();
	});
});
</script>

<div id="docLink" style="border:2px #666666 solid; background-color:#ffffff; position:absolute; z-index:10; display:none; width:500px; left:0px; top:0px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="middle" style="padding:14px 0 0 20px; background-color:#5FB404;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr height="25">
						<td style="letter-spacing:-1px;"><b>참조문서 첨부하기</b></td>
						<td width="22" align="left"><a href="javascript:"><img src="css/img/bt_closelayer.gif" onClick="jQuery('#docLink').hide()"></a></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td><iframe id="docSearch" src="iframe_docLink.php" width="100%" height="400"></iframe></td>
		</tr>
	</table>
</div>
<!-- Proposal Link ADD END -->

<!-- Proposal Link Delete START -->
<script>
function delete_linkedDoc(ID, Seq) {
	var content = ID + "_" + Seq;
	$("#" + content).remove();
	$("#" + "linkList_" + content).remove();
}

function preview_doc1(ID, Seq, Type) {
	var popUrl = "print_preview_proposal.php?ID="+ID+"&Type="+Type+"&Seq="+Seq+"&mode=preview";
	var popOption = "width=800, height=600, toolbar=0, location=0, directories=0, resizable=1, menubar=0, scrollbars=yes, status=no";

	window.open(popUrl,"",popOption);
}
</script>
<!-- Proposal Link Delete END -->
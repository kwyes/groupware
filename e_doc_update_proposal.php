<?
// approval: 3.임시저장문서, 4.회수문서
// 임시저장/회수문서 이벤트 처리 페이지
// re_approval: 1.상신, 2.수정, 3.삭제
$UserID = ($_GET['UserID']) ? $_GET['UserID'] : $_POST['UserID'];
$approval = ($_GET['approval']) ? (int)$_GET['approval'] : (int)$_POST['approval'];
$re_approval = ($_GET['re_approval']) ? (int)$_GET['re_approval'] : (int)$_POST['re_approval'];
$today = date('Y-m-d H:i:s');

/*
echo "ID: ".$ID."<br>";
echo "Type: ".$Type."<br>";
echo "Seq: ".$Seq."<br>";
echo "UserID: ".$UserID."<br>";
echo "approval: ".$approval."<br>";
echo "re_approval: ".$re_approval."<br>";
*/

if($approval == 4 && $re_approval == 1) {
	// 회수문서 재상신
	$DocStatus = 2;
	$query = "UPDATE E_DOC_Header SET Status = $DocStatus, RegDate = GETDATE() ".
			 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
	mssql_query($query);

	$query = "UPDATE Doc SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	echo "<script>location.href='?page=e_doc&menu=offer&sub=submit'</script>";

} else if($re_approval == 3) {
	// 임시저장/회수문서 삭제
	$attachPath = "upload/DocAttach/";

	$query = "DELETE FROM E_DOC_Header WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM Doc WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM ApprovalList WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	// 첨부파일 삭제후 DB 삭제
	$query = "SELECT NewFilename FROM DocAttach WHERE DocID = $ID AND DocSeq = $Seq";
	$query_result = mssql_query($query);
	while($row = mssql_fetch_array($query_result)) {
		$fullpath = $attachPath.$row['NewFilename'];
		unlink($fullpath);
	}
	$query = "DELETE FROM DocAttach WHERE DocID = $ID AND DocSeq = $Seq";
	mssql_query($query);

	if($approval == 3) {
		echo "<script>location.href='?page=e_doc&menu=offer&sub=save'</script>";
	} else if($approval == 4) {
		echo "<script>location.href='?page=e_doc&menu=offer&sub=recovery'</script>";
	}

} else {
	// 임시저장/회수문서 수정
	$query = "SELECT ApprovalUserID, ApprovalUserSeq, ApprovalStatus, ApprovalComment, CONVERT(char(20), ApprovalDate, 120) AS ApprovalDate, is_read ".
			 "FROM ApprovalList ".
			 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
			 "ORDER BY ApprovalUserSeq ASC";
	$result2 = mssql_query($query);

	while($row2 = mssql_fetch_array($result2)) {
		$ListVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalUserID'];
		if($ListVariable[$row2['ApprovalUserSeq']]) {
			$CountApprovalUser[$row2['ApprovalUserSeq']] = "결재자".$row2['ApprovalUserSeq'];
		}

		//$DateVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalStatus'];
		//$comments[$row2['ApprovalUserSeq']] = $row2['ApprovalComment'];
		//$logTime[$row2['ApprovalUserSeq']] = $row2['ApprovalDate'];
		//$is_read[$row2['ApprovalUserSeq']] = $row2['is_read'];
	}

	$query = "SELECT DocCompanyID, CONVERT(char(10), DocSubmitDate, 126) AS DocSubmitDate, UserID, Subject, Contents, ApprovalStatus, CONVERT(char(20), RegDate, 120) AS SubmitTime, CONVERT(char(19), ApprovalDate, 120) AS ApprovalDate ".
			 "FROM Doc ".
			 "WHERE DocID = $ID AND DocSeq = $Seq ".
			 "ORDER BY DocSeq ASC";
	$query_result = mssql_query($query);
	$row = mssql_fetch_array($query_result);
}
?>

<!-- Submit Button Javascript START -->
<script>
function printPage(Id, Type, Seq) {
	var popUrl = "e_doc_print_preview.php?ID="+Id+"&Type="+Type+"&Seq="+Seq;
	var popOption = "width=800, height=600, toolbar=0, location=0, directories=0, resizable=1, menubar=0, scrollbars=yes, status=no";

	window.open(popUrl,"",popOption);
}
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
				window.top.editAttach.document.edit_attach.submit();
				target.mode.value = "update_submit";
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
			window.top.editAttach.document.edit_attach.submit();
			target.mode.value = "update_save";
			saveContent();
		}
	}
}
function doc_delete() {
	var answer = confirm("삭제 하시겠습니까?");
	if(answer) {
		document.forms.form_proposal.re_approval.value = 3;
		document.forms.form_proposal.action = "?page=e_doc&menu=offer&sub=view_save";
		document.forms.form_proposal.submit();
	}
}
</script>
<!-- Submit Button Javascript END -->

<!-- e-doc Proposal START -->
<form name="form_proposal" action="upload/upload_Doc.php" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="">
<input type="hidden" name="ID" value="<?=$ID; ?>">
<input type="hidden" name="Type" value="<?=$Type; ?>">
<input type="hidden" name="Seq" value="<?=$Seq; ?>">
<input type="hidden" name="approval" value="<?=$approval; ?>">
<input type="hidden" name="re_approval" value="">
<input type="hidden" name="DocStatus" value="<?=$approval; ?>">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">결재문서 작성</td>
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
						<td align="left"><input type="button" class="doc_submit_btn_style" onClick="printPage('<?=$ID?>','<?=$Type?>','<?=$Seq?>')" value="인쇄"></td>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="doc_submit()">상신하기</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="doc_save()">저장하기</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="doc_delete()">삭제하기</td>
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
									<td align="center" class="doc_title">기안서</td>
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
									<td class="doc_field_content"><?=create_DocID($ID, $Seq); ?></td>
									<td width="365" rowspan="6" align="center" valign="top">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="5%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;">기안자</td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1"><?=$CountApprovalUser[1]; ?></td><input type="hidden" id="appUser1" name="appUser1" value="<?=$ListVariable[1]; ?>">
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"><?=$CountApprovalUser[2]; ?></td><input type="hidden" id="appUser2" name="appUser2" value="<?=$ListVariable[2]; ?>">
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"><?=$CountApprovalUser[3]; ?></td><input type="hidden" id="appUser3" name="appUser3" value="<?=$ListVariable[3]; ?>">
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"><?=$CountApprovalUser[4]; ?></td><input type="hidden" id="appUser4" name="appUser4" value="<?=$ListVariable[4]; ?>">
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;"><?=Br_iconv($_SESSION['memberName'])?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName1">
													<?=get_user_name($ListVariable[1]); ?><br>
													<?=($ListVariable[1]) ? '<input type="button" onClick="delete_from_doc(1)" value="취소">' : "" ?>
												</td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName2">
													<?=get_user_name($ListVariable[2]); ?><br>
													<?=($ListVariable[2]) ? '<input type="button" onClick="delete_from_doc(2)" value="취소">' : "" ?>
												</td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName3">
													<?=get_user_name($ListVariable[3]); ?><br>
													<?=($ListVariable[3]) ? '<input type="button" onClick="delete_from_doc(3)" value="취소">' : "" ?>
												</td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName4">
													<?=get_user_name($ListVariable[4]); ?><br>
													<?=($ListVariable[4]) ? '<input type="button" onClick="delete_from_doc(4)" value="취소">' : "" ?>
												</td>
											</tr>
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app5"><?=$CountApprovalUser[5]; ?></td><input type="hidden" id="appUser5" name="appUser5" value="<?=$ListVariable[5]; ?>">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app6"><?=$CountApprovalUser[6]; ?></td><input type="hidden" id="appUser6" name="appUser6" value="<?=$ListVariable[6]; ?>">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app7"><?=$CountApprovalUser[7]; ?></td><input type="hidden" id="appUser7" name="appUser7" value="<?=$ListVariable[7]; ?>">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app8"><?=$CountApprovalUser[8]; ?></td><input type="hidden" id="appUser8" name="appUser8" value="<?=$ListVariable[8]; ?>">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app9"><?=$CountApprovalUser[9]; ?></td><input type="hidden" id="appUser9" name="appUser9" value="<?=$ListVariable[9]; ?>">											
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName5">
													<?=get_user_name($ListVariable[5]); ?><br>
													<?=($ListVariable[5]) ? '<input type="button" onClick="delete_from_doc(5)" value="취소">' : "" ?>
												</td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName6">
													<?=get_user_name($ListVariable[6]); ?><br>
													<?=($ListVariable[6]) ? '<input type="button" onClick="delete_from_doc(6)" value="취소">' : "" ?>
												</td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName7">
													<?=get_user_name($ListVariable[7]); ?><br>
													<?=($ListVariable[7]) ? '<input type="button" onClick="delete_from_doc(7)" value="취소">' : "" ?>
												</td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName8">
													<?=get_user_name($ListVariable[8]); ?><br>
													<?=($ListVariable[8]) ? '<input type="button" onClick="delete_from_doc(8)" value="취소">' : "" ?>
												</td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName9">
													<?=get_user_name($ListVariable[9]); ?><br>
													<?=($ListVariable[9]) ? '<input type="button" onClick="delete_from_doc(9)" value="취소">' : "" ?>
												</td>
											</tr>
										</table>
									</td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서종류</b></td>
									<td class="doc_field_content">기안서</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_ApprovalStatus($approval)); ?> 문서입니다.</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>실행회사</b></td>
									<td  align="left" class="doc_field_content">
										<select name="runcompid"  style="width:100px;">
<?										$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
										$row2 = mssql_query($query);
?>
<?										while($rst = mssql_fetch_array($row2)) { ?>
											<option value='<?=$rst['companyID']?>'<?if($rst['companyID'] == $row['DocCompanyID']) echo "selected"; ?>><?=$rst['companyDesc']?></option>
<?										}	?>
										</select>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안일</b></td>
									<td class="doc_field_content"><input id="doc_calendar" name="doc_date" type="text" style="width:85px;" maxlength="10" value="<?=$row['DocSubmitDate'];?>"></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content" colspan="2"><input name="doc_subject" type="text" style="width:630px;" value="<?=Br_iconv($row['Subject']);?>"></input></td>
								</tr>

								<!-- Editor START -->
								<tr>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내용</b></td>
									<td align="center" colspan="2" style="border: 1px solid #c9c9c9;">
										<? include_once "editor/editor.html"; ?>
									</td>
								</tr>
								<!-- Editor END -->
								<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>파일첨부</b></td>
									<td colspan="2"><iframe id="editAttach" name="editAttach" src="iframe_editAttach.php?ID=<?=$ID;?>&Seq=<?=$Seq;?>&Type=<?=$Type;?>" onload="autoResize(this)" scrolling="no"></iframe></td>
								</tr>
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
									<td><button class="doc_submit_btn_style" onClick="doc_save()">저장하기</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="doc_delete()">삭제하기</td>
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

<!-- iframe_editAttach AUTO RESIZE START -->
<script>
	function autoResize(i) {
		var iframeHeight=
		(i).contentWindow.document.body.scrollHeight;
		(i).height=iframeHeight+10;
	}
</script>
<!-- iframe_editAttach AUTO RESIZE END -->

<script>
	function resizeTopIframe(dynheight) {
		document.getElementById("editAttach").height = parseInt(dynheight) + 10;
	}
</script>
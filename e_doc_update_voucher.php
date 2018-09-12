<?
$UserID = ($_GET['UserID']) ? $_GET['UserID'] : $_POST['UserID'];
$approval = ($_GET['approval']) ? $_GET['approval'] : $_POST['approval'];
$re_approval = ($_GET['re_approval']) ? $_GET['re_approval'] : $_POST['re_approval'];
?>
<script>
function doc_submit() {
	var target = document.forms.form_proposal;
	target.doc_date.required = "required";
	target.doc_subject.required = "required";
	target.runcompid.required = "required";
	target.Status.value = "2";

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
				target.mode.value = "update";
				saveContent();
			}
		}
	}
}
function doc_save() {
	var target = document.forms.form_proposal;
	target.doc_date.required = "required";
	target.doc_subject.required = "required";
	target.Status.value = "3";

	if(target.doc_date.value != "" && target.doc_subject.value != "") {
		var answer = confirm("저장 하시겠습니까?");
		if(answer) {
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
<?
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
	$attachPath = "upload/VouAttach/";

	$query = "DELETE FROM E_DOC_Header WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM Voucher WHERE VoucherID = $ID AND VoucherSeq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM ApprovalList WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	// 첨부파일 삭제후 DB 삭제
	$query = "SELECT NewFilename FROM VoucherAttach WHERE VouAttachID = $ID AND VouSeq = $Seq";
	$query_result = mssql_query($query);
	while($row = mssql_fetch_array($query_result)) {
		$fullpath = $attachPath.$row['NewFilename'];
		unlink($fullpath);
	}
	$query = "DELETE FROM VoucherAttach WHERE VouAttachID = $ID AND VouSeq = $Seq";
	mssql_query($query);

	if($approval == 3) {
		echo "<script>location.href='?page=e_doc&menu=offer&sub=save'</script>";
	} else if($approval == 4) {
		echo "<script>location.href='?page=e_doc&menu=offer&sub=recovery'</script>";
	}

} else {

	$today = date("Y-m-d");

	$query = "SELECT ApprovalUserID,ApprovalUserSeq,CONVERT(char(20),ApprovalDate,120) AS ApprovalDate,ApprovalComment,ApprovalStatus, is_read ".
			"FROM ApprovalList ".
			 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
			 "ORDER BY ApprovalUserSeq ASC";
	$result2 = mssql_query($query);
	while($row2 = mssql_fetch_array($result2)) {
		$ListVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalUserID'];
		if($ListVariable[$row2['ApprovalUserSeq']]) {
			$CountApprovalUser[$row2['ApprovalUserSeq']] = "결재자".$row2['ApprovalUserSeq'];
		}
		//$StatusVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalStatus'];
		//$comments[$row2['ApprovalUserSeq']] = $row2['ApprovalComment'];
		//$logTime[$row2['ApprovalUserSeq']] = $row2['ApprovalDate'];
		//$is_read[$row2['ApprovalUserSeq']] = $row2['is_read'];
	}

	if($Type == 1) {
		$SEL_TAB = ", doc b ";
		$SEL_FIE = "";
		$SEL_WHE =" and b.DocID = ".$ID." and b.DocSeq = ".$Seq." and b.DocType = ".$Type;
	} else if($Type == 2){

	} else if($Type == 3){
		$SEL_TAB = ", voucher b ";
		$SEL_FIE = ", b.PayTo, b.PaymentMethod, b.CurrencyType, b.Amount, b.LinkedDoc ";
		$SEL_WHE =" and b.VoucherID = ".$ID." and b.VoucherSeq = ".$Seq." and b.VoucherType = ".$Type;
	} else {

	}

	$today = date("Y-m-d");
	$query = "select a.ID, a.Type, a.Seq, a.Status, a.CompanyID, a.UserID, CONVERT(char(20), a.SubmitDate, 120) AS SubmitDate, a.Subject, CONVERT(char(20), a.RegDate, 120) AS RegDate, b.Contents ".
					$SEL_FIE.
					"from E_DOC_Header a ". $SEL_TAB.
					"where a.ID = ".$ID." and a.Seq = ".$Seq." and a.Type = ".$Type.
					$SEL_WHE;
	$rst = mssql_query($query);
    $row = mssql_fetch_array($rst);

	if($row['LinkedDoc'] != NULL) {
		$temp = explode("/", $row['LinkedDoc']);
		for($i = 0; $i < sizeof($temp); $i++) {
			$temp2 = explode("_", $temp[$i]);
			$link_doc_id[] = $temp2[0];
			$link_doc_seq[] = $temp2[1];
			$link_doc_type[] = $temp2[2];
		}
	}
}
?>
<!-- e-doc right 내용 START -->
<td width="" align="left" valign="top">
	<form name="form_proposal" method="post" action="upload/upload_Voucher.php" enctype="multipart/form-data">
	<input type="hidden" name="mode">
	<input type="hidden" name="ID" value="<?=$ID?>">
	<input type="hidden" name="Type" value="<?=$Type?>">
	<input type="hidden" name="Seq" value="<?=$Seq?>">
	<input type="hidden" name="Status">
	<input type="hidden" name="approval" value="<?=$approval; ?>">
	<input type="hidden" name="re_approval">
	<table width="100%">
		<!-- e-doc right TITLE START -->
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
									<td><button class="doc_submit_btn_style" onclick="doc_submit()">상신하기</button></td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onclick="doc_save()">저장하기</button></td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onclick="doc_delete()">삭제하기</button></td>
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
									<td class="doc_field_content" style="border-right: 0;"><b><?=create_DocID($row['ID'], $row['Seq']); ?></b></td>
									<td width="95" class="doc_field_content" style="border-left: 0; border-right: 0;"></td>
									<td class="doc_field_content" style="border-left: 0;"></td>
									<td width="365" rowspan="6" align="center" valign="top" style="padding:0;border-bottom:1px #afafaf solid;">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="7%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;">작성자</td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1"><?=$CountApprovalUser[1]; ?></td><input type="hidden" id="appUser1" name="appUser1" value="<?=$ListVariable[1]; ?>">
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"><?=$CountApprovalUser[2]; ?></td><input type="hidden" id="appUser2" name="appUser2" value="<?=$ListVariable[2]; ?>">
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"><?=$CountApprovalUser[3]; ?></td><input type="hidden" id="appUser3" name="appUser3" value="<?=$ListVariable[3]; ?>">
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"><?=$CountApprovalUser[4]; ?></td><input type="hidden" id="appUser4" name="appUser4" value="<?=$ListVariable[4]; ?>">
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;"><?=get_user_name($row['UserID']);?></td>
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
									<td class="doc_field_content"><?=get_docName($Type)?></td>
									<td width="95" align="center" class="doc_field_name"><b>실행 회사</b></td>
									<td align="left" class="doc_field_content">
									<select name="runcompid">
								<?	
										$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
										$rst2 = mssql_query($query);
										while($row2 = mssql_fetch_array($rst2)) {
								?>
										<option value='<?=$row2['companyID']?>'<?if($row2['companyID']== $row['CompanyID']) echo "selected"; ?>>&raquo; <?=$row2['companyDesc']?></option>
								<?		}	?>
									</select>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_ApprovalStatus($row['Status']))?></td>
									<td width="95" align="center" class="doc_field_name"><b>Pay To</b></td>
									<td class="doc_field_content"><input name="payto" type="text" style="width:100%; max-width:160;" value="<?=$row['PayTo']?>"></input></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>부서명</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_Dept($_SESSION['memberDID']))?></td>
									<td width="95" align="center" class="doc_field_name"><b>Amount</b></td>
									<td class="doc_field_content"><input name="amount" type="text" style="width:100%; max-width:160;" value="<?=$row['Amount']?>" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)'></input></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
									<td width="95" align="center" class="doc_field_name"><b>Payment Method</b></td>
									<td class="doc_field_content">
										<input type="radio" name="PaymentMethod" value="CASH" <?if($row['PaymentMethod'] == 'CASH') {?> checked="checked" <?}?>>CASH
										<input type="radio" name="PaymentMethod" value="CHEQUE" <?if($row['PaymentMethod'] == 'CHEQUE') {?> checked="checked" <?}?>>CHEQUE
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성일자</b></td>
									<td class="doc_field_content"><input name="doc_date" type="text" style="width:85px;" maxlength="10" value="<?=$row['RegDate']?>"></td>
									<td width="95" align="center" class="doc_field_name"><b>Currency Type</b></td>
									<td class="doc_field_content">
										<input type="radio" name="Currency" value="CAD$" <?if($row['CurrencyType'] == 'CAD$') {?> checked="checked" <?}?>>CAD$
										<input type="radio" name="Currency" value="USD$" <?if($row['CurrencyType'] == 'USD$') {?> checked="checked" <?}?>>USD$
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>참조문서</b></td>
									<td class="doc_field_content" colspan="4" id="doc_link">
										<button id="doc_link_btn">참조문서 선택</button>
<?										for($i = 0; $i < sizeof($link_doc_id); $i++) { ?>
<?
											if($link_doc_seq[$i] < 10) {
												$link_doc_name = $link_doc_id[$i]."-0".$link_doc_seq[$i];
											} else {
												$link_doc_name = $link_doc_id[$i]."-".$link_doc_seq[$i];
											}
?>
											<div id="<?=$link_doc_id[$i]."_".$link_doc_seq[$i] ?>" style="padding-top:5px; padding-left:30px; display:inline-block;">
												<a href="javascript:preview_doc1(<?=$link_doc_id[$i]?>, <?=$link_doc_seq[$i]?>, <?=$link_doc_type[$i]?>);" style="color:#2E9AFE; font-size:15px; font-weight: bold;">
													<?=$link_doc_name ?>
												</a>
												<a href="javascript:delete_linkedDoc(<?=$link_doc_id[$i]?>, <?=$link_doc_seq[$i]?>)">
													<img style="margin-top:3px;" src="../css/img/bt_del.gif"></img>
												</a>
											</div>

											<input id="<?="linkList_".$link_doc_id[$i]."_".$link_doc_seq[$i]?>" name="doc_linkList[]" type="hidden" value="<?=$link_doc_id[$i]."_".$link_doc_seq[$i]."_".$link_doc_type[$i] ?>">
<?										} ?>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content" colspan="4"><input name="doc_subject" type="text" style="width:630px;" value="<?=Br_iconv($row['Subject'])?>"></input></td>
								</tr>
								<tr>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내용</b></td>
									<td align="center" colspan="4" style="border: 1px solid #c9c9c9;">
										<? include_once "editor/editor.html"; ?>
									</td>
								</tr>
								<tr>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>파일첨부</b></td>
									<td colspan="4"><? include_once ("loadImage.php"); ?>
						<?			
									// 업로드 할 수 있는 이미지 갯수 3개로 한정 
									$aImageCount = $countImage;
									while($countImage < 3) {
										$countImage++;
						?>
										<br>&nbsp;<input type="file" name="aImage[]">
									<? } ?>
									</td>
									<input type="hidden" name="MAX_FILE_SIZE" value="5242880">
									<!-- 저장되어 있는 이미지 갯수 -->
									<input type="hidden" name="aImageCount" value="<?=$aImageCount; ?>">
								</tr>

<?
$ImgVariable = array();
/*
if ($Type == 1) {
} else if($Type == 3) {
	$ImgPath = "upload/VouAttach/";
	$query = "SELECT  VouAttachID, VouSeq, VouNum, NewFilename FROM VoucherAttach ".
			 "WHERE VouAttachID = $ID AND VouSeq = $Seq ".
			 "ORDER BY VouNum ASC";
	$result3 = mssql_query($query);
	while($row3 = mssql_fetch_array($result3)) {
		$ImgVariable[$row3['VouNum']] = $row3['NewFilename'];
	}
}
?>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<input name="userfile" type="file"/>&nbsp;&nbsp;<A href="<?=Br_iconv($ImgPath.$ImgVariable[1])?>" target='pdf'><?=Br_iconv($ImgVariable[1])?></A>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<input name="userfile2" type="file"/>&nbsp;&nbsp;<A href="<?=Br_iconv($ImgPath.$ImgVariable[2])?>" target='pdf'><?=Br_iconv($ImgVariable[2])?></A>
									</td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<input name="userfile3" type="file"/>&nbsp;&nbsp;<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" target='pdf'><?=Br_iconv($ImgVariable[3])?></A>
									</td>
								</tr>
<?*/?>
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
									<td><button class="doc_submit_btn_style" onclick="doc_submit()">상신하기</button></td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onclick="doc_save()">저장하기</button></td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onclick="doc_delete()">삭제하기</button></td>
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
		parent.document.getElementById("app" + i).innerHTML = "";
		parent.document.getElementById("appUser" + i).value = "";
		parent.document.getElementById("appUserName" + i).innerHTML = "";
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
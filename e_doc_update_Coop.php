<script>
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
			objTwo.options[1] = new Option ('회계','1');
			objTwo.options[2] = new Option ('전산','2');
			objTwo.options[3] = new Option ('총무','3');
			objTwo.options[4] = new Option ('홍보','4');
			objTwo.options[5] = new Option ('경영지원','5');
			return;
	}
}

function doc_submit() {
	var target = document.forms.form_proposal;
//	target.doc_date.required = "required";
	target.doc_subject.required = "required";
	target.runcompid.required = "required";
	target.Status.value = "2";

	//var target1 = document.forms.delete_image_form
	//var target2 = document.forms.delete_image_form2
	//var target3 = document.forms.delete_image_form3
	//target.userfile.value  = target1.userfile.value;
	//target.userfile2.value = target2.userfile2.value;
	//target.userfile3.value = target3.userfile3.value;

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

//		if(target.doc_date.value != "" && target.doc_subject.value != "" && target.runcompid.value != "") {
		if(target.doc_subject.value != "" && target.runcompid.value != "") {
			var answer = confirm("상신 하시겠습니까?");
			if(answer) {
				target.mode.value = "update";
				saveContent();
			}
		}
	}
}
function doc_save(status) {
	var target = document.forms.form_proposal;
//	target.doc_date.required = "required";
	target.doc_subject.required = "required";
	target.Status.value = "2";
	if (status == 4) {
		target.rStatus.value = "4";
	} else {
		target.rStatus.value = "3";
	}

//	if(target.doc_date.value != "" && target.doc_subject.value != "") {
	if(target.doc_subject.value != "") {
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
$approval = ($_GET['approval']) ? (int)$_GET['approval'] : (int)$_POST['approval'];
$re_approval = ($_GET['re_approval']) ? (int)$_GET['re_approval'] : (int)$_POST['re_approval'];
$today = date("Y-m-d");

if($approval == 4 && $re_approval == 1) {
	// 회수문서 재상신
	$DocStatus = 2;
	$query = "UPDATE E_DOC_Header SET Status = $DocStatus, RegDate = GETDATE(), SubmitDate = GETDATE() ".
			 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
	mssql_query($query);

	$query = "UPDATE Cooperation SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE(), SubmitDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	echo "<script>location.href='?page=e_doc&menu=offer&sub=submit'</script>";
 
} else if($re_approval == 3) {
	// 임시저장/회수문서 삭제
	$attachPath = "upload/CooAttach/";

	$query = "DELETE FROM E_DOC_Header WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM Cooperation WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM ApprovalList WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	// 첨부파일 삭제후 DB 삭제
	$query = "SELECT NewFilename FROM CoopAttach WHERE CoopAttachID = $ID AND CoopSeq = $Seq";
	$query_result = mssql_query($query);
	while($row = mssql_fetch_array($query_result)) {
		$fullpath = $attachPath.$row['NewFilename'];
		unlink($fullpath);
	}
	$query = "DELETE FROM CoopAttach WHERE CoopAttachID = $ID AND CoopSeq = $Seq";
	mssql_query($query);

	if($approval == 3) {
		echo "<script>location.href='?page=e_doc&menu=offer&sub=save'</script>";
	} else if($approval == 4) {
		echo "<script>location.href='?page=e_doc&menu=offer&sub=recovery'</script>";
	}

} else {

	$query = "SELECT ApprovalUserID,ApprovalUserSeq,CONVERT(char(20),ApprovalDate,120) AS ApprovalDate,ApprovalComment,ApprovalStatus ".
			"FROM ApprovalList ".
			 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
			 "ORDER BY ApprovalUserSeq ASC";
	$result2 = mssql_query($query);
	while($row2 = mssql_fetch_array($result2)) {
		$ListVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalUserID'];
		if($ListVariable[$row2['ApprovalUserSeq']]) {
			$CountApprovalUser[$row2['ApprovalUserSeq']] = "결재자".$row2['ApprovalUserSeq'];
		}
		
		if($row2['ApprovalDate']) {
			$DateVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalDate'];
			$StatusVariable[$row2['ApprovalUserSeq']] = get_doc_approval($row2['ApprovalStatus']);
			$comments[$row2['ApprovalUserSeq']] = $row2['ApprovalComment'];
			$logTime[$row2['ApprovalUserSeq']] = $row2['ApprovalDate'];
		} else {
			$DateVariable[$row2['ApprovalUserSeq']] = "미결";
			$StatusVariable[$row2['ApprovalUserSeq']] = "";
		}
	}

	if($Type == 1) {
		$SEL_TAB = ", doc b ";
		$SEL_FIE = "";
		$SEL_WHE =" and b.DocID = ".$ID." and b.DocSeq = ".$Seq." and b.DocType = ".$Type;
	} else if($Type == 2){
		$SEL_TAB = ", Cooperation b ";
		$SEL_FIE = ", b.DeptID ";
		$SEL_WHE =" and b.DocID = ".$ID." and b.DocSeq = ".$Seq." and b.DocType = ".$Type;
	} else if($Type == 3){
		$SEL_TAB = ", voucher b ";
		$SEL_FIE = ", b.PayTo, b.PaymentMethod, b.CurrencyType, b.Amount ";
		$SEL_WHE =" and b.VoucherID = ".$ID." and b.VoucherSeq = ".$Seq." and b.VoucherType = ".$Type;
	} else {

	}

	$today = date("Y-m-d");
	$query = "select a.ID, a.Type, a.Seq, a.Status, a.CompanyID, a.UserID, CONVERT(char(20), a.SubmitDate, 120) AS SubmitDate, a.Subject, CONVERT(char(20), a.RegDate, 120) AS RegDate, b.Contents, b.ApprovalStatus ".
					$SEL_FIE.
					"from E_DOC_Header a ". $SEL_TAB.
					"where a.ID = ".$ID." and a.Seq = ".$Seq." and a.Type = ".$Type." and b.CoopList = 1 ".
					$SEL_WHE;
	$rst = mssql_query($query);
    $row = mssql_fetch_array($rst);
	$Status = $row['ApprovalStatus'];
}
?>
<!-- e-doc right 내용 START -->
<td width="" align="left" valign="top">
	<form name="form_proposal" method="post" action="upload/upload_Cooperation.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="ID" value="<?=$ID?>">
	<input type="hidden" name="Type" value="<?=$Type?>">
	<input type="hidden" name="Seq" value="<?=$Seq?>">
	<input type="hidden" name="Status">
	<input type="hidden" name="rStatus">
	<input type="hidden" name="approval" value="<?=$approval; ?>">
	<input type="hidden" name="re_approval" value="">
	<input type="hidden" name="DocStatus" value="<?=$approval; ?>">
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
									<td><button class="doc_submit_btn_style" onclick="doc_save('<?=$Status?>')">저장하기</button></td>
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
									<td align="center" class="doc_title">협 조 문</td>
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
							<table width="100%" class="doc_border" style="table-layout:fixed;">
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content" style="border-right: 0;"><b><?=create_DocID($row['ID'], $row['Seq']); ?></b></td>
									<td width="365" rowspan="6" align="center" valign="top" style="padding:0;border-bottom:1px #afafaf solid;">
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
									<td class="doc_field_content">협조문</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_ApprovalStatus($row['ApprovalStatus']))?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안자</b></td>
									<td class="doc_field_content"><?=get_user_name($row['UserID'])?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안부서</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_company_sname($row['CompanyID']))." - ".Br_iconv(get_coop_Dept($row['CompanyID'], $row['DeptID']))?></td>
									<input type="hidden" name="runcompid" value="<?=$row['CompanyID']; ?>">
									<input type="hidden" name="rundeptid" value="<?=$row['DeptID']; ?>">
								</tr>
								<!--
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>협조회사</b></td>
									<td  align="left" class="doc_field_content">
										<select name="runcompid" style="width:100px;" onchange='change_Field(this.value)'>
<?										$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
										$row2 = mssql_query($query);
										while($rst = mssql_fetch_array($row2)) { ?>
											<option value='<?=$rst['companyID']?>'<?if($rst['companyID'] == $row['CompanyID']) echo "selected"; ?>><?=$rst['companyDesc']?></option>
<?										}	?>
										</select>
									<td height="30" align="center" class="doc_field_name"><b>협조부서</b></td>
									<td  align="left" class="doc_field_content">
										<select name="rundeptid" style="width:100px;">
<?										$comp = $row['CompanyID'];
										$query = "SELECT companyID, deptID, deptName FROM Department WHERE companyID = $comp ORDER BY deptID";
										$rst3 = mssql_query($query);
										while($row3 = mssql_fetch_array($rst3)) { ?>
											<option value='<?=$row3['deptID']?>'<?if($row3['deptID'] == $row['deptID']) echo "selected"; ?>><?=Br_iconv($row3['deptName'])?></option>
											
<?										}?>										
										</select>
									</td>
								</tr>
								-->
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>작성일</b></td>
									<td class="doc_field_content"><input type="hidden" name="doc_date" value="<?=$today; ?>"><?=$row['RegDate']?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>협조부서</b></td>
									<td class="doc_field_content" id="coopAdded" colspan="2">
										<button id="coop">협조부서 선택</button>
<?
										$coopList_query = "SELECT CompanyID, DeptID FROM Cooperation WHERE DocID = $ID AND DocSeq = $Seq AND CoopList != 1";
										$coopList_query_result = mssql_query($coopList_query);
										while($coopList_row = mssql_fetch_array($coopList_query_result)) {
?>
<?											if($coopList_row['DeptID'] != 9) { ?>
												<div id="<?=$coopList_row['CompanyID']."_".$coopList_row['DeptID']?>" style="padding-left:5; margin-top:3;">
													<?echo Br_iconv(get_company_sname($coopList_row['CompanyID']))." - ".Br_iconv(get_coop_Dept($coopList_row['CompanyID'], $coopList_row['DeptID'])); ?>
													<img onClick="delete_coopList(<?=$coopList_row['CompanyID']?>, <?=$coopList_row['DeptID']?>)" src="../css/img/bt_del.gif" style="cursor:pointer;">
													<input id="<?='coopList_'.$coopList_row['CompanyID']."_".$coopList_row['DeptID']?>" type="hidden" value="<?=$coopList_row['CompanyID']."_".$coopList_row['DeptID']?>" name="doc_coopList[]">
												</div>
<?											} ?>
<?										} ?>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content" colspan="2"><input name="doc_subject" type="text" style="width:630px;" value="<?=Br_iconv($row['Subject'])?>"></input></td>
								</tr>
								<tr>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내용</b></td>
									<td align="center" colspan="2" style="border: 1px solid #c9c9c9;">
										<? include_once "editor/editor.html"; ?>
									</td>
								</tr>
								<tr>
									<td align="center" class="doc_field_name"><b>파일첨부</b></td>
									<td colspan="2"><? include_once ("loadImage.php"); ?>
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
									<td><button class="doc_submit_btn_style" onclick="doc_save('<?=$Status?>')">저장하기</button></td>
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
	var target = document.getElementById(companyID + "_" + departmentID).innerHTML = "";
	$("#"+ companyID + "_" + departmentID).remove();
	$("#coopList_"+ companyID + "_" + departmentID).remove();
}
</script>
<!-- Delete Coop END -->
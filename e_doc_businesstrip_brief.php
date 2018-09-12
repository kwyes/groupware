<?
// approval: 3.임시저장문서, 4.회수문서
// 임시저장/회수문서 이벤트 처리 페이지
// re_approval: 1.상신, 2.수정, 3.삭제
$UserID = ($_GET['UserID']) ? $_GET['UserID'] : $_POST['UserID'];
$approval = ($_GET['approval']) ? (int)$_GET['approval'] : (int)$_POST['approval'];
$re_approval = ($_GET['re_approval']) ? (int)$_GET['re_approval'] : (int)$_POST['re_approval'];
$today = date('Y-m-d H:i:s');


$DocStatus = 4;

$query = "UPDATE E_DOC_Header SET Status = $DocStatus, RegDate = GETDATE() ".
			 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
mssql_query($query);

$query = "UPDATE Businesstrip SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
		 "WHERE DocID = $ID AND DocSeq = $Seq";
mssql_query($query);

$query = "UPDATE Businesstrip2 SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocSeq = $Seq";
mssql_query($query);
	
$query = "UPDATE Businesstrip3 SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocSeq = $Seq";
mssql_query($query);
	
$query = "UPDATE BusinessEm SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocSeq = $Seq";
mssql_query($query);	

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

	$query = "UPDATE Businesstrip SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocSeq = $Seq";
	mssql_query($query);
	
	$query = "UPDATE Businesstrip2 SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocSeq = $Seq";
	mssql_query($query);
	
	$query = "UPDATE Businesstrip3 SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocSeq = $Seq";
	mssql_query($query);
	
	$query = "UPDATE BusinessEm SET ApprovalStatus = $DocStatus, ApprovalDate = NULL, RegDate = GETDATE() ".
			 "WHERE DocID = $ID AND DocSeq = $Seq";
	mssql_query($query);	




	echo "<script>location.href='?page=e_doc&menu=offer&sub=submit'</script>";

} else if($re_approval == 3) {
	// 임시저장/회수문서 삭제
	$attachPath = "upload/BusinessAttach/";

	$query = "DELETE FROM E_DOC_Header WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM Businesstrip WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM Businesstrip2 WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM Businesstrip3 WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM BusinessEm WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	$query = "DELETE FROM ApprovalList WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	// 첨부파일 삭제후 DB 삭제
	$query = "SELECT NewFilename FROM BusinessAttach WHERE DocID = $ID AND DocSeq = $Seq";
	$query_result = mssql_query($query);
	while($row = mssql_fetch_array($query_result)) {
		$fullpath = $attachPath.$row['NewFilename'];
		unlink($fullpath);
	}
	$query = "DELETE FROM BusinessAttach WHERE DocID = $ID AND DocSeq = $Seq";
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

	//수정해야댐여기

	$query = "SELECT DocCompanyID, CONVERT(char(10), DocSubmitDate, 126) AS DocSubmitDate, UserID, Subject,Hotelpurpose,Hotelpay,Airpurpose,Airpay,Transpurpose,Transpay,Mealpurpose,Mealpay,Etcpurpose,Etcpay,Totalpurpose,Totalpay,ApprovalStatus, CONVERT(char(20), RegDate, 120) AS SubmitTime, CONVERT(char(19), ApprovalDate, 120) AS ApprovalDate ".
		 "FROM Businesstrip ".
		 "WHERE DocID = $ID AND DocSeq = $Seq ".
		 "ORDER BY DocSeq ASC";
	$query_result = mssql_query($query);
	$row = mssql_fetch_array($query_result);

	$Subject = Br_iconv($row['Subject']);
	$Hotelpurpose = Br_iconv($row['Hotelpurpose']);
	$Hotelpay = Br_iconv($row['Hotelpay']);
	$Airpurpose = Br_iconv($row['Airpurpose']);
	$Airpay = Br_iconv($row['Airpay']);
	$Transpurpose = Br_iconv($row['Transpurpose']);
	$Transpay = Br_iconv($row['Transpay']);
	$Mealpurpose = Br_iconv($row['Mealpurpose']);
	$Mealpay = Br_iconv($row['Mealpay']);
	$Etcpurpose = Br_iconv($row['Etcpurpose']);
	$Etcpay = Br_iconv($row['Etcpay']);
	$Totalpurpose = Br_iconv($row['Totalpurpose']);
	$Totalpay = Br_iconv($row['Totalpay']);

	$query3 = "SELECT Mainpurpose, Duration, Specificpurpose, Acheive, Result, Listnum ".
		 "FROM Businesstrip2 ".
		 "WHERE DocID = $ID AND DocSeq = $Seq ".
		 "ORDER BY DocSeq ASC";
	$query_result3 = mssql_query($query3);
	$query_result31 = mssql_query($query3);

	$row31 = mssql_fetch_array($query_result31);

	$Mainpurpose = Br_iconv($row31['Mainpurpose']);
	$Duration = Br_iconv($row31['Duration']);	


	$query4 = "SELECT BusinessDate, CompanyVisit, ResultForVisit ".
			 "FROM Businesstrip3 ".
			 "WHERE DocID = $ID AND DocSeq = $Seq ".
			 "ORDER BY DocSeq ASC";
	$query_result4 = mssql_query($query4);

	
	$query12 = "SELECT Bname1, Bpos1, Bdep1, Bname2, Bpos2, Bdep2, Bname3, Bpos3, Bdep3 ".
		 "FROM BusinessEm ".
		 "WHERE DocID = $ID AND DocSeq = $Seq ".
		 "ORDER BY DocSeq ASC";
	$query_result12 = mssql_query($query12);

	$row12 = mssql_fetch_array($query_result12);

	$bname1 = Br_iconv($row12['Bname1']);
	$bpos1 = Br_iconv($row12['Bpos1']);	
	$bdep1 = Br_iconv($row12['Bdep1']);	
	$bname2 = Br_iconv($row12['Bname2']);
	$bpos2 = Br_iconv($row12['Bpos2']);	
	$bdep2 = Br_iconv($row12['Bdep2']);	
	$bname3 = Br_iconv($row12['Bname3']);
	$bpos3 = Br_iconv($row12['Bpos3']);	
	$bdep3 = Br_iconv($row12['Bdep3']);	





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
	//target.doc_date.required = "required";
	//target.doc_subject.required = "required";
	//target.runcompid.required = "required";

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

		//if(target.doc_date.value != "" && target.doc_subject.value != "" && target.runcompid.value != "") {
			var answer = confirm("상신 하시겠습니까?");
			if(answer) {
				window.top.editAttach.document.edit_attach.submit();
				target.mode.value = "update_submit";
				
			}
		//}
	}
}
function doc_save() {
	var target = document.forms.form_proposal;
	/*target.doc_date.required = "required";
	target.doc_subject.required = "required";*/
	//if(target.doc_date.value != "" && target.doc_subject.value != "") {
		var answer = confirm("저장 하시겠습니까?");
		if(answer) {
			//window.top.editAttach.document.edit_attach.submit();
			target.mode.value = "result_save";
			
			
		}
	//}
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

<form name="form_proposal" action="upload/upload_businesstrip.php" enctype="multipart/form-data" method="post" accept-charset="utf-8">
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
						<td align="left"><!-- <input type="button" class="doc_submit_btn_style" onClick="printPage('<?=$ID?>','<?=$Type?>','<?=$Seq?>')" value="인쇄"> --></td>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="doc_submit()">상신하기</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="doc_save()">저장하기</td>
									<td width="5"></td>
									<!-- <td><button class="doc_submit_btn_style" onClick="doc_delete()">삭제하기</td> -->
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
									<td align="center" class="doc_title">출장 계획서/보고서</td>
									<td width="100" align="right" style="padding-top:10px;"><input type='button' id="fApproval" value='결재자 검색'></td>
									<td width="40" align="right" style="padding-top:10px;"><input type='button' id="doc_approval_btn" value='★'></td>
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
									<td colspan = "2" class="doc_field_content"><?=create_DocID($ID, $Seq); ?></td>
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
									<td class="doc_field_content" colspan="2"><?=get_docName($Type); ?></td>
								</tr>
								<tr class="doc_border">
									<?	if($row['ApprovalStatus'] == 1) {
											$font_color = "#0000FF";
										} else if($row['ApprovalStatus'] == 2){
											$font_color = "#088A08";
										} else if($row['ApprovalStatus'] == 5) {
											$font_color = "#DF0101";
										} ?>
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content" colspan="2" style="color:<?=$font_color; ?>"><b><?=get_doc_approval($row['ApprovalStatus']); ?></b></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안자</b></td>
									<td class="doc_field_content" colspan="2" ><?=get_user_name($row['UserID']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>실행회사</b></td>
									<td  align="left" class="doc_field_content" colspan="2" ><?=get_company_name($row['DocCompanyID']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>계획서 작성일</b></td>
<?									if($row['ApprovalDate']) { ?>
										<td class="doc_field_content" colspan="2"><?=$row['DocSubmitDate']." (".$row['ApprovalDate'].")"; ?></td>
<?									} else { ?>
										<td class="doc_field_content" colspan="2"><?=$row['DocSubmitDate']; ?></td>
<?									} ?>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content" colspan="3"><?=Br_iconv($row['Subject']); ?></td>
								</tr>

								<!-- Editor START -->
								<tr>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>출장대상자<br /><br />경비내역</b></td>
								
									<td style="border: 1px solid #c9c9c9;border-right:0px;">
										<table style = "border-collapse: separate;border: 1px solid #c9c9c9;margin: 19px 25px;">
											
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">이름</th>
													<td style = "width:293px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bname1" type="text" style="width:100%; text-align:left;" readonly value=<?=$bname1?>></td>
													
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">직급</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bpos1" type="text" style="width:100%; text-align:left;" readonly value=<?=$bpos1?>></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">부서</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bdep1" type="text" style="width:100%; text-align:left;" readonly  value=<?=$bdep1?>></td>
												</tr>
												
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3C3C3;">이름</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bname2" type="text" style="width:100%; text-align:left;" readonly  value=<?=$bname2?>></td>
													
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3C3C3;">직급</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bpos2" type="text" style="width:100%; text-align:left;" readonly value=<?=$bpos2?>></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3C3C3;">부서</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bdep2" type="text" style="width:100%; text-align:left;" readonly  value=<?=$bdep2?>></td>
												</tr>
												
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#FF6666;">이름</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bname3" type="text" style="width:100%; text-align:left;" readonly value=<?=$bname3?>></td>
													
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#FF6666;">직급</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bpos3" type="text" style="width:100%; text-align:left;" readonly value=<?=$bpos3?>></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#FF6666;">부서</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bdep3" type="text" style="width:100%; text-align:left;" readonly  value=<?=$bdep3?>></td>
												</tr>	

												
											</tbody>
										</table>	
												
										
									</td>
									<td colspan="2" style="border: 1px solid #c9c9c9;border-left:0px;">
										

										<table style = "border-collapse: separate;border: 1px solid #c9c9c9;margin: 19px 0px;width:63%;">
											
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">항목</th>
													<th style = "width:280px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">계획</th>
													<th style = "width:130px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">실비</th>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">숙박비</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="hotelpurpose" type="text" style="width:100%; text-align:left;" readonly value=<?=$Hotelpurpose?>></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="hotelpay" type="text" style="width:100%; text-align:left;" value=<?=$Hotelpay?>></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">항공비</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="airpurpose" type="text" style="width:100%; text-align:left;" readonly value=<?=$Airpurpose?>></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="airpay" type="text" style="width:100%; text-align:left;" value=<?=$Airpay?>></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">교통비</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="transpurpose" type="text" style="width:100%; text-align:left;" readonly value=<?=$Transpurpose?>></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="transpay" type="text" style="width:100%; text-align:left;"  value=<?=$Transpay?>></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">식비</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="mealpurpose" type="text" style="width:100%; text-align:left;" readonly value=<?=$Mealpurpose?>></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="mealpay" type="text" style="width:100%; text-align:left;"  value=<?=$Mealpay?>></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">그 외</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="etcpurpose" type="text" style="width:100%; text-align:left;" readonly value=<?=$Etcpurpose?>></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="etcpay" type="text" style="width:100%; text-align:left;"  value=<?=$Etcpay?>></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">Total</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="totalpurpose" type="text" style="width:100%; text-align:left;" readonly value=<?=$Totalpurpose?>></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="totalpay" type="text" style="width:100%; text-align:left;"  value=<?=$Totalpay?>></td>
												</tr>
											</tbody>
										</table>




									</td>
									
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>출장목적</b></td>
									<td colspan = "3" style="border: 1px solid #c9c9c9;">
								
										<table id="business" style = "border-collapse: separate;border: 1px solid #c9c9c9;margin: 19px 25px;width:75.5%;">
											<tbody>
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">목적 :</th>
													<th colspan = "4" style = "width:280px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;"><input name="mainbusinesspurpose" type="text" style="width:100%;height:120%; text-align:left;" readonly value = <?=$Mainpurpose?>></th>

												</tr>
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">기간 :</th>
													<th colspan = "4" style = "width:280px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;"><input name="businessduration" type="text" style="width:100%;height:120%;text-align:left;" readonly value = <?=$Duration?>></th>
												</tr>
												<tr style="height: 24px;">
													<th colspan ="2" style = "padding-left:28px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;text-align:left;background-color:#F3E9E9;">출장목표(중요도 순으로 기재)</th>
													<th style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;width:76px;text-align:center;background-color:#F3E9E9;">달성율</th>
													<th style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;width:323px;text-align:center;border-right:0px;background-color:#F3E9E9;">결과(성과 또는 문제점)</th>
													<th style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;width:43px;border-left:0px;background-color:#F3E9E9;">
													<!-- <input type="button" value="추가" onClick="purposeadd()">  -->
													</th>
												</tr>
												
												<!-- <tr style="height: 24px;">
													<td colspan ="4" style="border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;text-align:left;">등록된 정보가 없습니다 <input type="button" value="추가" onClick="file_add()"></td>
													
												</tr> -->
												<!-- <tr style="height: 24px;">
													<td colspan ="2" style="border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;text-align:left;"></td>
													<td style="border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;width:76px;text-align:center;"></td>
													<td style="border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;width:76px;text-align:center;"></td>
												</tr> -->
						
<?										
										while($row3 = mssql_fetch_array($query_result3)){
										
												$Specificpurpose = Br_iconv($row3['Specificpurpose']);
												$Acheive = Br_iconv($row3['Acheive']);
												$Result = Br_iconv($row3['Result']);
																				
												echo "<tr class='doc_border' style='height:24px;'>";
												echo "<td colspan='2' style='text-align:center; width: 445px;'>";
												echo "<input type='text' name = 'businesspurpose[]' style='width:100%;text-align:left;' readonly value = $Specificpurpose>";
												echo "</td>";
												echo "<td style='text-align:center; width: 78px;'>";
												echo "<input type='text' name = 'acheive[]' style='width:100%;text-align:left;' value = $Acheive>";
												echo "</td>";
												echo "<td colspan='2' style='text-align:center; width: 335px;'>";
												echo "<input type='text' name = 'resultandproblem[]' style='width:100%;text-align:left;' value = $Result>";
												echo "</td>";


											}
?>
										
										</table>		
										
									</td>
								</tr>
								
							

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>출장지역경로<br /><br />업무</b></td>
									<td colspan = "3" style="border: 1px solid #c9c9c9;">
								
										<table id="businesspath" style = "border-collapse: separate;border: 1px solid #c9c9c9;margin: 19px 25px;width:75.5%;">
											<tbody>
												<tr style = "height: 24px;">
													<th style = "width:115px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">일자</th>
													<th style = "width:650px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">출장일정 및 방문업체</th>
													<th colspan = "2" style = "width:650px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">결과</th>
													
													
												</tr>
<?										
														while($row4 = mssql_fetch_array($query_result4)){
														
																$Businessdate = Br_iconv($row4['BusinessDate']);
																$Companyvisit = Br_iconv($row4['CompanyVisit']);
																$Resultforvisit = Br_iconv($row4['ResultForVisit']);
																								
																echo "<tr class='doc_border' style='height:24px;'>";
																echo "<td style='text-align:center; width: 115px;'>";
																echo "<input type='text' name = 'businessdate[]' style='width:100%;text-align:left;' readonly value = $Businessdate>";
																echo "</td>";
																echo "<td style='text-align:center; width: 650px;'>";
																echo "<input type='text' name = 'companyvisit[]' style='width:100%;text-align:left;' readonly value=$Companyvisit >";
																echo "</td>";
																echo "<td colspan = '2' style='text-align:center; width: 650px;'>";
																echo "<input type='text' name = 'resultforvisit[]' style='width:100%;text-align:left;'  value=$Resultforvisit >";
																echo "</td>";
																


															}
?>
														
												
											
											
											</tbody>
										</table>		
										
									</td>
								</tr>

								<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>파일첨부</b></td>
									<td colspan="3"><iframe id="editAttach" name="editAttach" src="iframe_editAttach.php?ID=<?=$ID;?>&Seq=<?=$Seq;?>&Type=<?=$Type;?>" onload="autoResize(this)" scrolling="no"></iframe></td>
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
									<!-- <td><button class="doc_submit_btn_style" onClick="doc_delete()">삭제하기</td> -->
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
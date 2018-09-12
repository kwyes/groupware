<link href="../css/style.css" rel="stylesheet" type="text/css" />
<?
include_once "includes/setup.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

$ListVariable = array();
$StatusVariable = array();
$comments = array();
$logTime = array();
$is_read = array();
$ListVariable2 = array();
$StatusVariable2 = array();

$query = "SELECT ApprovalUserID,ApprovalUserSeq,CONVERT(char(20),ApprovalDate,120) AS ApprovalDate,ApprovalComment,ApprovalStatus,is_read,CONVERT(char(20),RegDate,120) as RegDate ".
			"FROM ApprovalList ".
			 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
			 "ORDER BY RegDate ASC";
	$result2 = mssql_query($query);
	$iCount = 0;
	while($row2 = mssql_fetch_array($result2)) {
		$LastApproval[$row2['ApprovalUserSeq']] = $row2['ApprovalUserID'];
		$ListVariable[$row2['ApprovalUserSeq']] = get_user_name($row2['ApprovalUserID']);
		$StatusVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalStatus'];
		$is_read[$row2['ApprovalUserSeq']] = $row2['is_read'];
		
		$ListVariable2[$iCount] = get_user_name($row2['ApprovalUserID']);
		$StatusVariable2[$iCount] = Br_iconv(get_ApprovalStatus($row2['ApprovalStatus']));
		$comments[$iCount] = Br_iconv($row2['ApprovalComment']);
		if($row2['ApprovalDate']) {
			$logTime[$iCount] = $row2['ApprovalDate'];
		} else {
			if($row2['is_read'])	$logTime[$iCount] = $row2['RegDate'];
		}
		$iCount++;

		if($StatusVariable[$row2['ApprovalUserSeq']] == 1) {
			$color[$row2['ApprovalUserSeq']] = "#0000FF";
		} else if($StatusVariable[$row2['ApprovalUserSeq']] == 2) {
			if($is_read[$row2['ApprovalUserSeq']] == 0) {
				$color[$row2['ApprovalUserSeq']] = "#FF8000";
			} else {
				$color[$row2['ApprovalUserSeq']] = "#088A08";
			}
		} else if($StatusVariable[$row2['ApprovalUserSeq']] == 5) {
			$color[$row2['ApprovalUserSeq']] = "#DF0101";
		}
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
	$query = "select a.ID, a.Type, a.Seq, a.Status, a.CompanyID, a.UserID, CONVERT(char(20), a.SubmitDate, 120) AS SubmitDate, a.Subject, CONVERT(char(20), a.RegDate, 120) AS RegDate, b.Contents, b.ApprovalStatus, b.ApprovalDate ".
					$SEL_FIE.
					"from E_DOC_Header a ". $SEL_TAB.
					"where a.ID = ".$ID." and a.Seq = ".$Seq." and a.Type = ".$Type.
					$SEL_WHE;
	$rst = mssql_query($query);
    $row = mssql_fetch_array($rst);
	$Subject = Br_iconv($row['Subject']);

	if($row['LinkedDoc'] != NULL) {
		$temp = explode("/", $row['LinkedDoc']);
		for($i = 0; $i < sizeof($temp); $i++) {
			$temp2 = explode("_", $temp[$i]);
			$link_doc_id[] = $temp2[0];
			$link_doc_seq[] = $temp2[1];
			$link_doc_type[] = $temp2[2];
		}
	}
?>

<!DOCTYPE html>
<html>
<body onload="window.focus();window.print();">

	<table width="100%" style="min-width:700px">
		<!-- e-doc Proposal MAIN START -->
					<!-- Proposal FORM TITLE START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td align="center" class="doc_title"><?=get_docName($row['Type'])?></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Proposal FORM TITLE END -->
<?
	$strR="<img width='54' height='54' style='padding-top: 9px;' src='/images/09_img.png'>";
?>
					<!-- Proposal FORM CONTENT START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="table-layout:fixed;" class="doc_border_print" cellpadding="0" cellSpacing="0">
								<tr class="doc_border">
									<td width="90" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content" style="border-right: 0;"><b><?=create_DocID($row['ID'], $row['Seq'])?></b></td>
									<td width="90" class="doc_field_content" style="border-left: 0; border-right: 0;"></td>
									<td class="doc_field_content" style="border-left: 0;"></td>
									<td width="300" rowspan="6" align="center" valign="top">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="7%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;"><?=get_user_name($row['UserID']);?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1"><?=$ListVariable[1]?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"><?=$ListVariable[2]?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"><?=$ListVariable[3]?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"><?=$ListVariable[4]?></td>
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid;"><img width="54" height="54" style="padding-top: 9px;" src="/images/00_img.png"></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName1"><?=(($StatusVariable[1] == 2 && $is_read[1] == 0) ? $strR : get_docimg_approval($StatusVariable[1])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName2"><?=(($StatusVariable[2] == 2 && $is_read[2] == 0) ? $strR : get_docimg_approval($StatusVariable[2])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName3"><?=(($StatusVariable[3] == 2 && $is_read[3] == 0) ? $strR : get_docimg_approval($StatusVariable[3])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName4"><?=(($StatusVariable[4] == 2 && $is_read[4] == 0) ? $strR : get_docimg_approval($StatusVariable[4])); ?></td>
											</tr>
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app5"><?=$ListVariable[5]?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app6"><?=$ListVariable[6]?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app7"><?=$ListVariable[7]?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app8"><?=$ListVariable[8]?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app9"><?=$ListVariable[9]?></td>
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName5"><?=(($StatusVariable[5] == 2 && $is_read[5] == 0) ? $strR : get_docimg_approval($StatusVariable[5])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName6"><?=(($StatusVariable[6] == 2 && $is_read[6] == 0) ? $strR : get_docimg_approval($StatusVariable[6])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName7"><?=(($StatusVariable[7] == 2 && $is_read[7] == 0) ? $strR : get_docimg_approval($StatusVariable[7])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName8"><?=(($StatusVariable[8] == 2 && $is_read[8] == 0) ? $strR : get_docimg_approval($StatusVariable[8])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName9"><?=(($StatusVariable[9] == 2 && $is_read[9] == 0) ? $strR : get_docimg_approval($StatusVariable[9])); ?></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서종류</b></td>
									<td class="doc_field_content"><?=get_docName($row['Type'])?></td>
									<td  width="90" align="center" class="doc_field_name"><b>실행 회사</b></td>
									<td  align="left" class="doc_field_content"><?=get_company_sname($row['CompanyID'])?></td>
								</tr>
								<tr class="doc_border">
									<?	if($row['Status'] == 1) {
											$font_color = "#0000FF";
										} else if($row['Status'] == 2){
											$font_color = "#088A08";
										} else if($row['Status'] == 5) {
											$font_color = "#DF0101";
										} ?>
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content" style="color:<?=$font_color; ?>"><b><?=Br_iconv(get_ApprovalStatus($row['Status']))?></b></td>
									<td  width="90" align="center" class="doc_field_name"><b>Pay To</b></td>
									<td class="doc_field_content"><?if($Type == 3) { echo $row['PayTo']; }?></input></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>부서명</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_Dept(get_user_dept($row['UserID'])))?></td>
									<td  width="90" align="center" class="doc_field_name"><b>Amount</b></td>
									<td class="doc_field_content"><?if($Type == 3) { echo $row['Amount']; }?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
									<td  width="90" align="center" class="doc_field_name"><b>Payment Method</b></td>
									<td class="doc_field_content"><?if($Type == 3) { echo $row['PaymentMethod']; }?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성일자</b></td>
									<td class="doc_field_content"><?=$row['RegDate']?></td>
									<td  width="90" align="center" class="doc_field_name"><b>Currency Type</b></td>
									<td class="doc_field_content"><?if($Type == 3) { echo $row['CurrencyType']; }?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>참조문서</b></td>
									<td class="doc_field_content" colspan="4">
<?										for($i = 0; $i < sizeof($link_doc_id); $i++) { ?>
<?
											if($link_doc_seq[$i] < 10) {
												$link_doc_name = $link_doc_id[$i]."-0".$link_doc_seq[$i];
											} else {
												$link_doc_name = $link_doc_id[$i]."-".$link_doc_seq[$i];
											}
?>
											<div id="<?=$link_doc_id[$i]."_".$link_doc_seq[$i] ?>" style="padding-top:5px; padding-right:20px; display:inline-block;">
												<a href="javascript:preview_doc1(<?=$link_doc_id[$i]?>, <?=$link_doc_seq[$i]?>, <?=$link_doc_type[$i]?>);" style="color:#2E9AFE; font-size:15px; font-weight: bold;">
													<?=$link_doc_name ?>
												</a>
											</div>
<?										} ?>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content" colspan="4"><?=Br_iconv($row['Subject']); ?></td>
								</tr>

								<!-- Editor START -->
								<tr>
									<td align="center" class="doc_field_name"><b>내용</b></td>
									<td height="350" colspan="4" style="padding: 10px 12px;"><?=str_replace('-ms-word-break:','word-break:' , str_replace('\"', '"', Br_iconv($row['Contents']))); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>결재자 의견</b></td>
									<td class="doc_field_content" style="padding-top:5px; padding-bottom:5px;" colspan="4">
										<?="<span style='line-height:20px;'>".$row['RegDate']." - ".get_user_name($row['UserID'])." 상신"."</span>"; ?><br>
									<!-- Log START -->
<?									$j=0;
									for($i = 0; $i < 30; $i++) {
										if($logTime[$i]) {
											$j++;
											if($comments[$i])	$display = " &lt;&lt; <font color=green><b>".$comments[$i]." </b></font>&gt;&gt;<br>";
											else				$display = "<br>";

											if ($j%2 == 0)	$setColor = "<font color='black'>";
											else			$setColor = "<font color='blue'>";

											echo "<span style='line-height:20px;'>".$setColor.$logTime[$i]." - ".$ListVariable2[$i]." ".$StatusVariable2[$i]."</font>".$display."</span>";
										}
									} ?>
									</td>
								</tr>
							</table>
						</td>						
					</tr>
				</table>


<div style='page-break-before:always'></div> 
<table width="100%" style="min-width:700px">
	<tr>
		<td align="center" valign="top">
			<table width="100%" style="table-layout:fixed;" class="doc_border_print" cellpadding="0" cellSpacing="0">
<?
$ImgVariable = array();

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

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[1])));
	if($ImgVariable[1] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls")) { ?>
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[1])?>" target='pdf'><?=Br_iconv($ImgVariable[1])?></A>
									</td>
								</tr>
<?		} else if($ImgVariable[1]) { ?>
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[1])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[1])?>" width="600" height="900" style="max-width: 100%; height: auto;"></A>
									</td>
								</tr>
<?		} ?>

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[2])));
	if($ImgVariable[2] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls")) { ?>
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[2])?>" target='pdf'><?=Br_iconv($ImgVariable[2])?></A>
									</td>
								</tr>
<?	}  else if($ImgVariable[2]) { ?>
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[2])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[2])?>" width="600" height="900" style="max-width: 100%; height: auto;"></A>
									</td>
								</tr>
<?	} ?>

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[3])));
	if($ImgVariable[3] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls")) { ?>
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" target='pdf'><?=Br_iconv($ImgVariable[3])?></A>
									</td>
								</tr>
<?	}  else if($ImgVariable[3]) { ?>
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" width="600" height="900" style="max-width: 100%; height: auto;"></A>
									</td>
								</tr>
<?	} ?>								<!-- Editor END -->
			</table>
		</td>						
	</tr>
	<!-- Proposal FORM CONTENT END -->
</table>
<!-- e-doc Proposal MAIN END -->

</body>
</html>
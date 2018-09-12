<link href="../css/style.css" rel="stylesheet" type="text/css" />
<?
include_once "includes/setup.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

$query = "SELECT TOP 1 CompanyID, UserID, CONVERT(char(10), SubmitDate, 120) AS SubmitDate, Status, Subject, CONVERT(char(20), RegDate, 120) AS RegDate ".
		 "FROM E_DOC_Header ".
		 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq ";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);

$company_cd = $row['CompanyID'];
$submitDate = $row['SubmitDate'];
$submitUserID = $row['UserID'];
$submitTime = $row['RegDate'];
$status = $row['Status'];
$Subject = Br_iconv($row['Subject']);

if($status == 1) {
	$font_color = "#0000FF";
	$doc_status = "결제완료";
} else if($status == 2){
	$font_color = "#088A08";
	$doc_status = "결재진행중";
} else if($status == 4) {
	$font_color = "#DF0101";
	$doc_status = "회수";
} else if($status == 5) {
	$font_color = "#DF0101";
	$doc_status = "반려";
}

$UserID = $_SESSION['memberID'];

$query = "SELECT ApprovalUserID, ApprovalUserSeq, ApprovalStatus, ApprovalComment, CONVERT(char(20), ApprovalDate, 120) AS ApprovalDate, is_read, CONVERT(char(20), RegDate, 120) AS RegDate ".
		 "FROM ApprovalList ".
		 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
		 "ORDER BY RegDate ASC, ApprovalUserSeq ASC";
$result2 = mssql_query($query);

$iCount = 0;
$rejected = FALSE;
$i = 1;
while($row2 = mssql_fetch_array($result2)) {
	$LastApproval[$row2['ApprovalUserSeq']] = $row2['ApprovalUserID'];
	$ListVariable[$row2['ApprovalUserSeq']] = get_user_name($row2['ApprovalUserID']);
	$DateVariable[$row2['ApprovalUserSeq']] = ($rejected == TRUE) ? 0 : $row2['ApprovalStatus'] ;
	$is_read[$row2['ApprovalUserSeq']] = $row2['is_read'];

	$ListVariable2[$iCount] = get_user_name($row2['ApprovalUserID']);
	$StatusVariable2[$iCount] = Br_iconv(get_ApprovalStatus($row2['ApprovalStatus']));
	$UserSeq[$iCount] = $row2['ApprovalUserSeq'];
	if($row2['is_read'] == 1) {
		$comments[$iCount] = Br_iconv($row2['ApprovalComment']);
		//echo $comments[$iCount]."<br>";
	}
	if($row2['ApprovalDate']) {
		$logTime[$iCount] = $row2['ApprovalDate'];
	} else {
		if($row2['is_read'])	$logTime[$iCount] = $row2['RegDate'];
	}
	$iCount++;

	if($DateVariable[$row2['ApprovalUserSeq']] == 1) {
		$color[$row2['ApprovalUserSeq']] = "#0000FF";
	} else if($DateVariable[$row2['ApprovalUserSeq']] == 2) {
		if($is_read[$row2['ApprovalUserSeq']] == 0) {
			$color[$row2['ApprovalUserSeq']] = "#FF8000";
		} else {
			$color[$row2['ApprovalUserSeq']] = "#088A08";
		}
	} else if($DateVariable[$row2['ApprovalUserSeq']] == 5) {
		$color[$row2['ApprovalUserSeq']] = "#DF0101";
		$rejected = TRUE;
	}
}

$strR = "<img width='54' height='54' style='padding-top: 9px;' src='/images/09_img.png'>";
?>

<!DOCTYPE html>
<html>
<?if($mode == "preview") { ?>
	<body>
<?} else { ?>
	<body onload="window.focus();window.print();">
<?} ?>

<table width="100%" style="min-width:700px">
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td align="center" class="doc_title">Sales Activities Journal</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td align="center" valign="top">
			<table width="100%" style="table-layout:fixed;" class="doc_border_print" cellpadding="0" cellSpacing="0">
				<tr>
					<td width="95" height="30" align="center" class="doc_field_name" style="border-right:0"><b>문서번호</b></td>
					<td class="doc_field_content"><b><?=create_DocID($ID, $Seq); ?></b></td>
					<td width="300" rowspan="6" align="center" valign="top">
						<table width="100%" cellpadding="0" cellSpacing="0">
							<tr height="20" align="center" style="background-color:#f6f6f6;">
								<td width="7%" rowspan="4" style="padding:60px 0 0 0; border:0"><b>결<br></br><br></br>재</b></td>
								<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0; border-top:0;"><?=get_user_name($submitUserID); ?></td>
								<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0; border-top:0;" id="app1"><?=$ListVariable[1]; ?></td>
								<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0; border-top:0;" id="app2"><?=$ListVariable[2]; ?></td>
								<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0; border-top:0;" id="app3"><?=$ListVariable[3]; ?></td>
								<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0; border-top:0; border-right:0;" id="app4"><?=$ListVariable[4]; ?></td>
							</tr>
							<tr height="70" align="center">
								<td style="border-bottom:1px #eaeaea solid;"><img width="54" height="54" style="padding-top: 9px;" src="/images/00_img.png"></td>
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName1"><?=(($DateVariable[1] == 2 && $is_read[1] == 0) ? $strR : get_docimg_approval($DateVariable[1])); ?></td>
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName2"><?=(($DateVariable[2] == 2 && $is_read[2] == 0) ? $strR : get_docimg_approval($DateVariable[2])); ?></td>
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName3"><?=(($DateVariable[3] == 2 && $is_read[3] == 0) ? $strR : get_docimg_approval($DateVariable[3])); ?></td>
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName4"><?=(($DateVariable[4] == 2 && $is_read[4] == 0) ? $strR : get_docimg_approval($DateVariable[4])); ?></td>
							</tr>
							<tr height="20" align="center" style="background-color:#f6f6f6;">
								<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app5"><?=$ListVariable[5]; ?></td>
								<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app6"><?=$ListVariable[6]; ?></td>
								<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app7"><?=$ListVariable[7]; ?></td>
								<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app8"><?=$ListVariable[8]; ?></td>
								<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app9"><?=$ListVariable[9]; ?></td>										
							</tr>
							<tr height="70" align="center">
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName5"><?=(($DateVariable[5] == 2 && $is_read[5] == 0) ? $strR : get_docimg_approval($DateVariable[5])); ?></td>
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName6"><?=(($DateVariable[6] == 2 && $is_read[6] == 0) ? $strR : get_docimg_approval($DateVariable[6])); ?></td>
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName7"><?=(($DateVariable[7] == 2 && $is_read[7] == 0) ? $strR : get_docimg_approval($DateVariable[7])); ?></td>
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName8"><?=(($DateVariable[8] == 2 && $is_read[8] == 0) ? $strR : get_docimg_approval($DateVariable[8])); ?></td>
								<td style="border-bottom:1px #eaeaea solid;" id="appUserName9"><?=(($DateVariable[9] == 2 && $is_read[9] == 0) ? $strR : get_docimg_approval($DateVariable[9])); ?></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td height="30" align="center" class="doc_field_name"><b>문서종류</b></td>
					<td class="doc_field_content">Sales Activities Journal</td>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
					<td class="doc_field_content" style="color:<?=$font_color; ?>"><b><?=$doc_status; ?></b></td>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
					<td class="doc_field_content"><?=get_user_name($submitUserID); ?></td>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name"><b>상신일</b></td>
					<td class="doc_field_content"><?=$submitDate; ?></td>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name"><b>실행회사</b></td>
					<td class="doc_field_content"><?=get_company_name($company_cd); ?></td>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name" colspan="3"><b>작업일지</b></td>
				</tr>

				<tr>
					<td colspan="3">
						<table width="100%" cellspacing="0">
							<tr height="30px" style="font-size:15px; font-weight:bold;">
								<td width="6%" align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF; border-top:0; border-left:0;">시각(Time)</td>
								<td width="47%" align="center" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF; border-top:0;">업체명(Customer)</td>
								<td width="47%" align="center" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF; border-top:0; border-right:0;">특이사항(Remark)</td>

							</tr>
						
							<?	for($i = 1; $i <= 11; $i++) { ?>
								<?	
									$content_query = "SELECT customer, remark FROM salesJournal WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND sub_seq = $i";
									$content_query_result = mssql_query($content_query);
								?>
									<tr height="60px"  style="font-size:13px;">
										<td width="6%" align="center" style="vertical-align:middle; border-left:0;"><?=($i+7).":00"; ?></td>
									<?	if(!mssql_num_rows($content_query_result)) { ?>
											<td></td>
											<td></td>
									<?	} else { ?>
										<?	$content_row = mssql_fetch_array($content_query_result); ?>
											<td width="47%" style="padding:5px; word-break:break-all;"><?=Br_iconv($content_row['customer']); ?></td>
											<td width="47%" style="padding:5px; word-break:break-all;"><?=Br_iconv($content_row['remark']); ?></td>
									<?	} ?>
									</tr>
							<?	} ?>

							<?	
								$content_query = "SELECT customer, remark FROM salesJournal WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND sub_seq = 12";
								$content_query_result = mssql_query($content_query);
								$content_row = mssql_fetch_array($content_query_result);
							?>
							<tr height="30px" style="font-size:15px; font-weight:bold;">
								<td width="100%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF; border-left:0;" colspan="3">시장동향 (Market Tendency)</td>
							</tr>
							<tr height="120px"  style="font-size:13px;">
								<?	if(!empty($$content_row['customer'])) { ?>
										<td colspan="3" style="padding:5px; border-left:0;"></td>
								<?	} else { ?>
										<td colspan="3" style="padding:5px; border-left:0;"><pre><?=Br_iconv($content_row['customer']); ?></pre></td>
								<?	} ?>
							</tr>
							<tr height="30px" style="font-size:15px; font-weight:bold;">
								<td width="100%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF; border-left:0;" colspan="3">특이사항 (Remark)</td>
							</tr>
							<tr height="120px"  style="font-size:13px;">
								<?	if(!empty($$content_row['remark'])) { ?>
										<td colspan="3" style="padding:5px; border-left:0;"></td>
								<?	} else { ?>
										<td colspan="3" style="padding:5px; border-left:0; border-bottom:0;"><pre><?=Br_iconv($content_row['remark']); ?></pre></td>
								<?	} ?>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td height="30" align="center" class="doc_field_name"><b>결재자 의견</b></td>
					<td class="doc_field_content" style="padding-top:5px; padding-bottom:5px;" colspan="2">
						<?="<span style='line-height:20px;'>".$submitTime." - ".get_user_name($submitUserID)." 상신"."</span>"; ?><br>
					<!-- Log START -->
<?									$j = 0;
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

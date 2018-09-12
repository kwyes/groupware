<link href="../css/style.css" rel="stylesheet" type="text/css" />
<?
include_once "includes/setup.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

$ListVariable = array();
$DateVariable = array();
$comments = array();
$logTime = array();
$ListVariable2 = array();
$StatusVariable2 = array();

$strR="<img width='54' height='54' style='padding-top: 9px;' src='/images/09_img.png'>";

for($i=0; $i<30; $i++) {
	$DateVariable[$i] = "";
}

$query = "SELECT ApprovalUserID, ApprovalUserSeq, ApprovalStatus, ApprovalComment, CONVERT(char(20), ApprovalDate, 120) AS ApprovalDate, is_read,CONVERT(char(20), RegDate, 120) AS RegDate ".
		 "FROM ApprovalList ".
		 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
		 "ORDER BY RegDate ASC";
$result2 = mssql_query($query);
$iCount = 0;
while($row2 = mssql_fetch_array($result2)) {
	$LastApproval[$row2['ApprovalUserSeq']] = $row2['ApprovalUserID'];
	$ListVariable[$row2['ApprovalUserSeq']] = get_user_name($row2['ApprovalUserID']);
	$DateVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalStatus'];
//	$comments[$row2['ApprovalUserSeq']] = $row2['ApprovalComment'];
//	$logTime[$row2['ApprovalUserSeq']] = $row2['ApprovalDate'];
	$is_read[$row2['ApprovalUserSeq']] = $row2['is_read'];

	$ListVariable2[$iCount] = get_user_name($row2['ApprovalUserID']);
	$StatusVariable2[$iCount] = Br_iconv(get_ApprovalStatus($row2['ApprovalStatus']));
	if($row2['is_read'] == 1) {
		$comments[$iCount] = Br_iconv($row2['ApprovalComment']);
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
	}
}

$query = "SELECT DocCompanyID, CONVERT(char(10), DocSubmitDate, 126) AS DocSubmitDate, UserID, Subject, Contents, ApprovalStatus, CONVERT(char(20), RegDate, 120) AS SubmitTime, CONVERT(char(19), ApprovalDate, 120) AS ApprovalDate ".
		 "FROM Doc ".
		 "WHERE DocID = $ID AND DocSeq = $Seq ".
		 "ORDER BY DocSeq ASC";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);

?>

<!DOCTYPE html>
<html>
<?if($mode == "preview") { ?>
	<body>
<?} else { ?>
	<body onload="window.focus();window.print();">
<?} ?>
<table width="100%" style="min-width:700px">
	<!-- Proposal FORM TITLE START -->
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td align="center" class="doc_title">기안서</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- Proposal FORM TITLE END -->

	<!-- Proposal FORM CONTENT START -->
	<tr>
		<td align="center" valign="top">
			<table width="100%" style="table-layout:fixed;" class="doc_border_print" cellpadding="0" cellSpacing="0">
				<tr>
					<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
					<td class="doc_field_content"><b><?=create_DocID($ID, $Seq); ?></b></td>
					<td width="300" align="center" valign="top" rowspan="6">
						<table width="100%" cellpadding="0" cellSpacing="0">
							<tr height="20" align="center" style="background-color:#f6f6f6;">
								<td width="7%" rowspan="4" style="padding:60px 0 0 0; border:0"><b>결<br></br><br></br>재</b></td>
								<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0; border-top:0;"><?=get_user_name($row['UserID']); ?></td>
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
					<td class="doc_field_content"><?=get_docName($Type); ?></td>
				</tr>
				<tr>
					<?	if($row['ApprovalStatus'] == 1) {
							$font_color = "#0000FF";
						} else if($row['ApprovalStatus'] == 2){
							$font_color = "#088A08";
						} else if($row['ApprovalStatus'] == 5) {
							$font_color = "#DF0101";
						} ?>
					<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
					<td class="doc_field_content" style="color:<?=$font_color; ?>"><b><?=get_doc_approval($row['ApprovalStatus']); ?></b></td>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name"><b>기안자</b></td>
					<td class="doc_field_content"><?=get_user_name($row['UserID']); ?></td>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name"><b>실행회사</b></td>
					<td class="doc_field_content"><?=get_company_name($row['DocCompanyID']); ?></td>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name"><b>기안일</b></td>
<?									if($row['ApprovalDate']) { ?>
						<td class="doc_field_content"><?=$row['DocSubmitDate']." (".$row['ApprovalDate'].")"; ?></td>
<?									} else { ?>
						<td class="doc_field_content"><?=$row['DocSubmitDate']; ?></td>
<?									} ?>
				</tr>
				<tr>
					<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
					<td class="doc_field_content" colspan="2"><?=Br_iconv($row['Subject']); ?></td>
				</tr>

				<!-- Editor START -->
				<tr>
					<td align="center" class="doc_field_name"><b>내용</b></td>
					<td height="200" colspan="2" style="padding: 10px 12px; line-height:1.5; word-break:break-all;"><?=str_replace('\"', '"', Br_iconv($row['Contents'])); ?></td>
				</tr>
				<!-- Editor END -->

				<tr>
					<td height="30" align="center" class="doc_field_name"><b>결재자 의견</b></td>
					<td class="doc_field_content" style="padding-top:5px; padding-bottom:5px;" colspan="2">
						<?="<span style='line-height:20px;'>".$row['SubmitTime']." - ".get_user_name($row['UserID'])." 상신"."</span>"; ?><br>
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
	<!-- Proposal FORM CONTENT END -->
</table>

<div style='page-break-before:always'></div> 

<table width="100%" style="min-width:700px">
	<tr>
		<td align="center" valign="top">
			<table width="100%" style="table-layout:fixed;" class="doc_border_print" cellpadding="0" cellSpacing="0">
<?
$ImgVariable = array();

if ($Type == 1) {
$ImgPath = "upload/DocAttach/";
$query = "SELECT DocID, DocSeq, FileSeq, NewFilename FROM DocAttach ".
"WHERE DocID = $ID AND DocSeq = $Seq ".
"ORDER BY FileSeq ASC";
$result3 = mssql_query($query);
while($row3 = mssql_fetch_array($result3)) {
$ImgVariable[$row3['FileSeq']] = $row3['NewFilename'];
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
					<td class="doc_field_content" colspan="2">
						<A href="<?=Br_iconv($ImgPath.$ImgVariable[1])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[1])?>" style="max-width: 100%; height: auto;"></A>
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
<?		}  else if($ImgVariable[2]) { ?>
				<tr>
					<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
					<td class="doc_field_content">
						<A href="<?=Br_iconv($ImgPath.$ImgVariable[2])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[2])?>" style="max-width: 100%; height: auto;"></A>
					</td>
				</tr>
<?		} ?>

<?
$ext = array_pop(explode(".", strtolower($ImgVariable[3])));
if($ImgVariable[3] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls")) { ?>
				<tr>
					<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
					<td class="doc_field_content">
						<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" target='pdf'><?=Br_iconv($ImgVariable[3])?></A>
					</td>
				</tr>
<?		}  else if($ImgVariable[3]) { ?>
				<tr>
					<td width="95" height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
					<td class="doc_field_content">
						<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" style="max-width: 100%; height: auto;"></A>
					</td>
				</tr>
<?		} ?>
			</table>
		</td>
	</tr>
</table>

</body>
</html>
<?
include_once "includes/general.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];
$Subject = ($_GET['Subject']) ? $_GET['Subject'] : $_POST['Subject'];
$note = ($_GET['note']) ? $_GET['note'] : $_POST['note'];
$UserID = ($_GET['UserID']) ? $_GET['UserID'] : $_POST['UserID'];
$Subject = ($_GET['Subject']) ? $_GET['Subject'] : $_POST['Subject'];
$url = ($_GET['url']) ? $_GET['url'] : $_POST['url'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

$url = urldecode($url);
$url = $url."&ID=$ID&Type=$Type&Seq=$Seq";
?>
<html>
<title>결재자 의견 입력</title>
<head>
<script language="javascript">
function myconfirm() {
	chk = false;
	chk = confirm("저장 하시겠습니까?");
	if (chk == true)
	{
		form_note.submit();
	} else {
		return;
	}
}
</script>
</head>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<body TOPMARGIN="0" LEFTMARGIN="0" MARGINHEIGHT="0" MARGINWIDTH="0" style="background-color:#ffeddd">
<form name="form_note" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
<input type="hidden" name="mode" value="save">
<input type="hidden" name="ID" value="<?=$ID?>">
<input type="hidden" name="Type" value="<?=$Type?>">
<input type="hidden" name="Seq" value="<?=$Seq?>">
<input type="hidden" name="Subject" value="<?=$Subject?>">
<input type="hidden" name="UserID" value="<?=$UserID?>">
<input type="hidden" name="note" value="<?=$note?>">
<input type="hidden" name="url" value="<?=urlencode($url)?>">
<?
if($mode == "save" && $note !="") {

	$query = "SELECT ProcessSeq+1 as SEQ, ApprovalUserSeq FROM ApprovalList WHERE DocID=$ID AND DocType=$Type AND DocSeq=$Seq AND ApprovalUserID='$UserID' ".
			"order by seq desc";
	$rst = mssql_query($query);
	$row = mssql_fetch_array($rst);
	if($row) {
		$pSEQ = $row['SEQ'];
		$uSEQ = $row['ApprovalUserSeq'];
	} else {
		$pSEQ = 0;
		$uSEQ = 0;
	}
	$note = Br_dconv($note);
	$query = "INSERT INTO ApprovalList (DocID, DocType, DocSeq, ApprovalUserSeq, ProcessSeq, ApprovalUserID, ApprovalStatus, ApprovalComment, is_read) ".
			 "VALUES ($ID, $Type, $Seq, $uSEQ, $pSEQ, '$UserID',8,'$note', 1)";
	mssql_query($query);
?>
	<script>
		opener.document.location.href="<?=$url?>";
		self.close();
	</script>
<?
} else if($mode == "save") {
?>
	<script>
		alert("결재자 의견을 입력 하세요.");
	</script>
<?
}
?>
<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0" STYLE="border:1 solid #629BCF; background-color:#EBF5FF">
	<TR>
		<TD STYLE="PADDING-BOTTOM: 5px; PADDING-TOP: 5px; PADDING-LEFT: 5px; PADDING-RIGHT: 5px;">
			<TABLE WIDTH="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0">
				<TR>
					<TD width="20">
						<TABLE WIDTH="12" HEIGHT="12" CELLSPACING="0" CELLPADDING="0" BORDER="0">
							<TR>
								<TD ROWSPAN="2" COLSPAN="2" BGCOLOR="#9966ff"><IMG SRC="images/transparent.gif" WIDTH="2" HEIGHT="2" ALT="" BORDER="0"></TD>
								<TD><IMG SRC="images/transparent.gif" WIDTH="2" HEIGHT="2" ALT="" BORDER="0"></TD>
							</TR>
							<TR>
								<TD BGCOLOR="#999999"><IMG SRC="images/transparent.gif" WIDTH="2" HEIGHT="10" ALT="" BORDER="0"></TD>
							</TR>
							<TR>
								<TD><IMG SRC="images/transparent.gif" WIDTH="2" HEIGHT="2" ALT="" BORDER="0"></TD>
								<TD BGCOLOR="#999999"><IMG SRC="images/transparent.gif" WIDTH="10" HEIGHT="2" ALT="" BORDER="0"></TD>
								<TD BGCOLOR="#999999"><IMG SRC="images/transparent.gif" WIDTH="2" HEIGHT="2" ALT="" BORDER="0"></TD>
							</TR>
						</TABLE>
					</TD>
					<TD CLASS="subtitle">
						<FONT COLOR="#30649D" size="3"><b>결재자 의견</b></FONT>
						- <FONT COLOR="#FF3300"><b>의견 입력</b></FONT>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>
<table width="100%" bgcolor="#C2C2C2" style="padding-top:10px;">
	<tr height="20"><td></td></tr>
	<tr height="50">
		<td style="padding-left:15px;">
			<table align="center" width="95%">
				<tr class="doc_border">
					<td class="doc_field_name" align="center" width="85" style="padding-left:10px;"><font size="3">문서번호 :</font></td>
					<td class="doc_field_content"><font size="3"><?=create_DocID($ID, $Seq); ?></font></td>
				</tr>
				<tr><td height="5"></td></tr>
				<tr class="doc_border">
					<td class="doc_field_name" align="center" style="padding-left:10px;"><font size="3">문서종류 :</font></td>
					<td class="doc_field_content"><font size="3"><?=get_docName($Type)?></font></td>
				</tr>
				<tr><td height="5"></td></tr>
				<tr class="doc_border">
					<td class="doc_field_name" align="center" style="padding-left:10px;"><font size="3">문서제목 :</font></td>
					<td class="doc_field_content"><font size="3"><?=$Subject?></font></td>
				</tr>
				<tr><td height="5"></td></tr>
				<tr class="doc_border">
					<td class="doc_field_name" align="center" style="padding-left:10px;"><font size="3">작&nbsp;성&nbsp;자 :</font></td>
					<td class="doc_field_content"><font size="3"><?=get_user_name($UserID)?></font></td>
				</tr>

				<tr>
					<td height="20"></td>
				</tr>
				<tr class="doc_border">
					<td class="doc_field_name" align="center" style="padding-left:10px;"><font size="3">의&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;견 :</font></td>
				</tr>
				<tr>
					<td style="padding-top:10px; padding-left:35px;" colspan="2">
						<textarea name="note" rows="5" cols="40"><?=$note?></textarea>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<TABLE cellspacing="1" cellpadding="0" width="100%" style="padding-top:10px;">
	<tr>
		<td height="20"></td>
	</tr>
	<tr valign="middle">
		<td width="25%"></td>
		<td align="center" width="25%">
			<div class="menu_button2" style="width:70px" onclick="javascript:myconfirm()">저장</div>
		</td>
		<td align="center" width="25%">
			<div class="menu_button2" style="width:70px" onclick="window.close()">취소</div>		
		</td>
		<td width="25%"></td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
</table>
<!--
<TABLE cellspacing="1" cellpadding="0" width="100%" bgcolor="#C2C2C2">
	<tr height="10">
		<td></td>
		<td></td>
	</tr>
	<tr valign="middle">
		<td width="90" align="right" class="tabletitle01">문서번호:</td>
		<td align="left" class="tabletd02">&nbsp;<?=$ID?>-<?=$Seq?></td>
	</tr>
	<tr height="20">
		<td></td>
		<td></td>
	</tr>
	<tr valign="middle">
		<td width="90" align="right" class="tabletitle01">문서종류:</td>
		<td align="left" class="tabletd02">&nbsp;<?=get_docName($Type)?></td>
	</tr>
	<tr valign="middle">
		<td width="90" align="right" class="tabletitle01">문서제목:</td>
		<td align="left" class="tabletd02">&nbsp;<?=$Subject?></td>
	</tr>
	<tr height="20">
		<td></td>
		<td></td>
	</tr>
	<tr valign="middle">
		<td width="90" align="right" class="tabletitle01">작성자:</td>
		<td align="left" class="tabletd02">&nbsp;<?=get_user_name($UserID)?></td>
	</tr>
	<tr height="20">
		<td></td>
		<td></td>
	</tr>
	<tr valign="middle">
		<td valign="top" width="90" align="right" class="tabletitle01">의견:</td>
		<td><textarea name="note" rows="5" cols="35"><?=$note?></textarea></td>
	</tr>
	<tr height="20">
		<td></td>
		<td></td>
	</tr>
</table>
-->
</body>
</html>
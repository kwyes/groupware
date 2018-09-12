<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ko" xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HTM Groupware</title>
<?
include_once "includes/general.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$UserID =	$UserID = $_SESSION['memberID'];

?>
<script>
function post_to_url(path, params, method) {
    method = method || "post";

	var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }
	hiddenField = document.createElement("input");
	hiddenField.setAttribute("type", "hidden");
	hiddenField.setAttribute("name", "Comment");
	hiddenField.setAttribute("value", document.getElementById("comment").innerHTML);
	form.appendChild(hiddenField);

    document.body.appendChild(form);
    form.submit();

	opener.document.location.href = "http://184.70.148.122/?page=e_doc";
	
	self.close()
}
</script>
</head>

<body>
<table width="100%">
	<tr>
		<td colspan="3" align="left" class="content_title"><b>받은 결재 문서함 > 미결재 문서</b></td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td width="95" align="left">문서번호</td>
		<td colspan="2" width="95" align="left"><?=create_DocID($ID, $Seq); ?></td>
	</tr>
	<tr>
		<td align="left">문서종류</td>
		<td colspan="2" align="left"><?=get_docName($Type)?></td>
	</tr>
	<tr>
		<td height="30"></td>
	</tr>
	<tr>
		<td colspan = "3" align="left">결재자 의견</td>
	</tr>
	<tr>
		<td colspan="3"><textarea name="comment" id="comment" rows="5" cols="41"></textarea></td>
	</tr>
</table>
<table>
	<tr><td height="30"></td></tr>
	<tr>
		<td><input type="button" class="doc_submit_btn_style" onClick="javascript:post_to_url('e_doc_approval_submit.php', {'ID':'<?=$ID?>', 'Seq':'<?=$Seq?>','Type':'<?=$Type?>','UserID':'<?=$UserID?>','approval':'1'});" value="결재"></td>
		<td width="10"></td>
		<td><input type="button" class="doc_submit_btn_style" onClick="javascript:post_to_url('e_doc_approval_submit.php', {'ID':'<?=$ID?>', 'Seq':'<?=$Seq?>','Type':'<?=$Type?>','UserID':'<?=$UserID?>','approval':'6'});" value="보류"></td>
		<td width="10"></td>
		<td><input type="button" class="doc_submit_btn_style" onClick="javascript:post_to_url('e_doc_approval_submit.php', {'ID':'<?=$ID?>', 'Seq':'<?=$Seq?>','Type':'<?=$Type?>','UserID':'<?=$UserID?>','approval':'5'});" value="반려"></td>
	</tr>
</table>
</body>
</html>
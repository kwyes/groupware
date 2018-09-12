<script>
function checked(userID) {
	opener.document.form_registration.duplicationID.value = userID;

	self.close();
}
</script>

<?
include_once "includes/general.php";
$userID = $_GET['check'];

$query = "SELECT memID FROM Member WHERE memID = '$userID'";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result)
?>

<? if($row['memID']) { ?>
	<table width="340" height="120">
		<tr>
			<td align="center">
				<font color="red"><b>사용 불가능한 아이디입니다.</b></font>
			</td>
		</tr>
	</table>

<? } else { ?>
	<table width="340" height="120">
		<tr height="80">
			<td align="center">
				<font color="red"><b>사용 가능한 아이디입니다.</b></font>
			</td>
		</tr>

		<tr height="35">
			<td align="center">
				<button onClick="checked('<?=$userID; ?>');">사용하기</button>
			</td>
		</tr>
	</table>
<? } ?>

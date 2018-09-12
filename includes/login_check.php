<?
session_start();
if (($_SESSION['memberID']=='')||($_SESSION['memberPW']=='')) {
print"
<script language='javascript'>
	alert('접속이 종료되었습니다. 다시 로그인 해 주십시요.');
	parent.document.location.replace('login.php');
</script>
";
}
?>
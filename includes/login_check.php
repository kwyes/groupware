<?
session_start();
if (($_SESSION['memberID']=='')||($_SESSION['memberPW']=='')) {
print"
<script language='javascript'>
	alert('������ ����Ǿ����ϴ�. �ٽ� �α��� �� �ֽʽÿ�.');
	parent.document.location.replace('login.php');
</script>
";
}
?>
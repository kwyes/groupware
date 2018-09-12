<?
include_once "includes/general.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];

if($Type == 4) {
	include_once "e_doc_businesstrip_brief.php";
}
?>

<?
include_once "includes/general.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];

if($Type == 1) {
	include_once "e_doc_proposal_view.php";
} else if($Type == 2) {
	include_once "e_doc_coop_view.php";
} else if($Type == 3) {
	include_once "e_doc_voucher_view.php";
} else if($Type == 4) {
	include_once "e_doc_businesstrip_view.php";
} else if($Type == 8) {
	include_once "e_doc_salesJournal_view.php";
} else if($Type == 9) {
	include_once "e_doc_itemSpotCheck_view.php";
}
?>
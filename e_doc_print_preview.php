<?
include_once "includes/general.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];

if($Type == 1) {
	include_once "print_preview_proposal.php";
} else if($Type == 2) {
	//include_once "e_doc_update_Coop.php";
} else if($Type == 3) {
	include_once "print_preview_voucher.php";
} else if($Type == 4) {
	//include_once "e_doc_update_business.php";
} 
  else if($Type == 8) {
	include_once "print_preview_salesJournal.php";
}
?>

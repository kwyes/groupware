<?
include_once "includes/general.php";

$ID = ($_GET['ID']) ? $_GET['ID'] : $ID;
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $Seq;
$Type = ($_GET['Type']) ? $_GET['Type'] : $Type;
$imageIndex = ($_GET['imageIndex']) ? $_GET['imageIndex'] : $imageIndex;
$imagePath = ($_GET['imagePath']) ? $_GET['imagePath'] : $imagePath;

// 실제 파일 존재여부 체크
if(!is_file($imagePath)) {
	exit("이미지 파일이 존재하지 않음");
}

//파일 삭제
unlink($imagePath);

// 수정모드에서 이미지만 삭제한 경우 DB에서 이미지 정보 삭제
if($Type == 1) {
	$query = "DELETE FROM DocAttach WHERE DocID=$ID AND DocSeq = $Seq AND FileSeq = $imageIndex ";
} else if($Type == 2) {
	$query = "DELETE FROM CoopAttach WHERE CoopAttachID=$ID AND CoopSeq = $Seq AND CoopNum = $imageIndex ";
} else if($Type == 3) {
	$query = "DELETE FROM VoucherAttach WHERE VouAttachID=$ID AND VouSeq = $Seq AND VouNum = $imageIndex ";
} else if($Type == 4 || $Type == 6) {
	$query = "DELETE FROM board_Attach WHERE DocID=$ID AND FileSeq = $imageIndex ";
}
mssql_query($query);

// 삭제 처리후 이동할 페이지 지정
echo "<script>location.href='javascript:history.go(-1)'</script>";
?>

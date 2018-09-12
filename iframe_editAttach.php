<script>
	function image_view(type, image, width, height) {
		width = width + 20;
		hegiht = height + 20; 

		var op = "location=no, scrollbars=no, menubars=no, toolbars=no, resizeble=yes, left=0, top=0, width="+width+",height="+height;

		var url = "viewImage.php?image="+image+"&type="+type;

		popup = window.open(url,"ImageWindow", op);
		popup.focus();
	}

	function delImageConfirm(mode, id, seq, type, countImage, imagePath) {
		var answer = confirm("첨부 파일을 삭제 하시겠습니까?");
		if(answer) {
			//alter("삭제 되었습니다.");
			location.href = "iframe_editAttach.php?mode="+mode+"&ID="+id+"&Seq="+seq+"&Type="+type+"&imageIndex="+countImage+"&imagePath="+imagePath;
		} else {
			alter("삭제가 취소 되었습니다.");
			location.reload();
		}
	}
</script>

<?
include_once "includes/general.php";

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];

if($mode == "delete") {
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

	} else if($Type == 3) {
		$query = "DELETE FROM VoucherAttach WHERE VouAttachID=$ID AND VouSeq = $Seq AND VouNum = $imageIndex ";
	} else if($Type == 4) {
		$query = "DELETE FROM BusinessAttach WHERE DocID=$ID AND DocSeq = $Seq AND FileSeq = $imageIndex ";
	} else if($Type == 8) {
		$query = "DELETE FROM salesJournalAttach WHERE ID=$ID AND Seq = $Seq AND FileSeq = $imageIndex ";
	}

	mssql_query($query);
}

// DB에 저장 된 이미지 정보 가져 오기
if($Type == 1) {
	$ImgPath = "upload/DocAttach/";
	$query = "SELECT NewFilename FROM DocAttach ".
			 "WHERE DocID = $ID AND DocSeq = $Seq ".
			 "ORDER BY FileSeq ASC";
} else if($Type == 2) {
} else if($Type == 3) {
	$ImgPath = "upload/VouAttach/";
	$query = "SELECT  VouAttachID, VouSeq, VouNum, NewFilename FROM VoucherAttach ".
			 "WHERE VouAttachID = $ID AND VouSeq = $Seq ".
			 "ORDER BY VouNum ASC";
} else if($Type == 4) {
	$ImgPath = "upload/BusinessAttach/";
	$query = "SELECT NewFilename FROM DocAttach ".
			 "WHERE DocID = $ID AND DocSeq = $Seq ".
			 "ORDER BY FileSeq ASC";
} else if($Type == 8) {
	$ImgPath = "upload/saleAttach/";
	$query = "SELECT NewFilename FROM salesJournalAttach ".
			 "WHERE ID = $ID AND Seq = $Seq ".
			 "ORDER BY FileSeq ASC";
}

$result = mssql_query($query);
while($row = mssql_fetch_array($result)) {
	$assetPHOTO[] = $ImgPath.$row['NewFilename'];
}

// 이미지 파일 갯수 저장 변수
$countImage = 0;
for($i = 0; $i < count($assetPHOTO); $i++) {
	if(!empty($assetPHOTO[$i])) {
		// 이미지 파일 갯수 저장
		$countImage++;

		// 이미지 경로
		$imagePath = explode("/", $assetPHOTO[$i]);
		$imageName = $imagePath[2];
		$imagePath = "/".$imagePath[0]."/".$imagePath[1]."/".$imagePath[2];

		$imagesize = getimagesize($assetPHOTO[$i]);

		$ext = array_pop(explode(".", strtolower($assetPHOTO[$i])));
		if($assetPHOTO[$i] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls")) {
			echo "<a href='".Br_iconv($assetPHOTO[$i])."' target='pdf'>$assetPHOTO[$i]</a>";
									
		} else {
			// 화면에 링크 출력 (누르면 팝업으로 이미지 출력)
			echo "<a href='javascript:image_view(\"$Type\", \"{$imageName}\", {$imagesize[0]}, {$imagesize[1]});'><img src='".$imagePath."' width='150' height='100' border='1'></a>";
		}
		echo "<a href='javascript:delImageConfirm(\"delete\", \"$ID\", \"$Seq\", \"$Type\", \"$countImage\", \"$assetPHOTO[$i]\");'> 삭제 </a>";
	}	
}
?>
<!-- 업로드 할 수 있는 이미지 갯수 3개로 한정 -->
<form name="edit_attach" action="upload/upload_Doc.php" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<? 
$aImageCount = $countImage;
	while($countImage < 3) {
		$countImage++;
?>
		<br><input type="file" name="aImage[]">
<? } ?>

<!-- 저장되어 있는 이미지 갯수 -->
<input type="hidden" name="aImageCount" value="<?=$aImageCount; ?>">
<input type="hidden" name="ID" value="<?=$ID; ?>">
<input type="hidden" name="Type" value="<?=$Type; ?>">
<input type="hidden" name="Seq" value="<?=$Seq; ?>">
<input type="hidden" name="mode" value="edit_attach">
</form>
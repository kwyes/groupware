<script>
	function image_view(type, image, width, height) {
		width = width + 20;
		hegiht = height + 20; 

		var op = "location=no, scrollbars=no, menubars=no, toolbars=no, resizeble=yes, left=0, top=0, width="+width+",height="+height;

		var url = "viewImage.php?image="+image+"&type="+type;

		popup = window.open(url,"ImageWindow", op);
		popup.focus();
	}

	function delImageConfirm(id, seq, type, countImage, imagePath) {
		var answer = confirm("첨부 파일을 삭제 하시겠습니까?");
		if(answer) {
			//alter("삭제 되었습니다.");
			location.href = "deleteImage.php?ID="+id+"&Seq="+seq+"&Type="+type+"&imageIndex="+countImage+"&imagePath="+imagePath;
		} else {
			alter("삭제가 취소 되었습니다.");
			location.reload();
		}
	}
</script>

<?
	// DB에 저장 된 이미지 정보 가져 오기
	if($Type == 1) {
		$ImgPath = "upload/DocAttach/";
		$query = "SELECT NewFilename FROM DocAttach ".
				 "WHERE DocID = $ID AND DocSeq = $Seq ".
				 "ORDER BY FileSeq ASC";
	} else if($Type == 2) {
		$ImgPath = "upload/CooAttach/";
		$query = "SELECT CoopAttachID, CoopSeq, CoopNum, NewFilename FROM CoopAttach ".
				 "WHERE CoopAttachID = $ID AND CoopSeq = $Seq ".
				 "ORDER BY CoopNum ASC";
	} else if($Type == 3) {
		$ImgPath = "upload/VouAttach/";
		$query = "SELECT VouAttachID, VouSeq, VouNum, NewFilename FROM VoucherAttach ".
				 "WHERE VouAttachID = $ID AND VouSeq = $Seq ".
				 "ORDER BY VouNum ASC";
	} else if($Type == 4 || $Type == 6) {
		$ImgPath = "upload/BoardAttach/";
		$query = "SELECT DocID, FileSeq, NewFilename FROM Board_Attach ".
				 "WHERE DocID = $ID ".
				 "ORDER BY FileSeq ASC";
	}
	$result3 = mssql_query($query);
	while($row3 = mssql_fetch_array($result3)) {
		$assetPHOTO[] = $ImgPath.$row3['NewFilename'];
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
			echo "<a href='javascript:delImageConfirm(\"$ID\", \"$Seq\", \"$Type\", \"$countImage\", \"$assetPHOTO[$i]\");'> 삭제 </a>";
		}	
	}
?>
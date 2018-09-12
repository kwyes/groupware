<?
header("Content-Type: text/html; charset=euc-kr;");
$Type = $_GET['type'];

// 전송된 이미지 여부 확인
if(isset($_GET['image'])) {

	// 이미지 경로
	if($Type == 1) {
		$imagePath = 'upload/DocAttach/'.$_GET['image'];
	} else if($Type == 2) {
	} else if($Type == 3) {
		$imagePath = 'upload/VouAttach/'.$_GET['image'];
	}

	// 넘어온 이미지경로의 존재여부와 파일여부 확인
	if(file_exists($imagePath) && is_file($imagePath)) {

		// 넘어온 파일 확장자 추출
		$tmp_name = pathinfo($imagePath);
		$ext = strtolower($tmp_name['extension']);

		// 지정된 확장자만 보여주도록 필터링
		if($ext == 'jpg' || $ext='gif' || $ext='png' || $ext='bmp') {

			//이미지 크기정보와 사이즈를 얻어옴
			$img_info = getimagesize($imagePath);
			$filesize = filesize($imagePath);

			// 이미지 전송을 위한 헤더설정
			header("Content-Type: {$img_info['mime']}\n");
			header("Content-Disposition: inline;filename='1'\n");
			header("Content-Length: $filesize\n");
			
			// 이미지 내용을 읽어들임
			readfile($imagePath);
		
		}
	}
}
?>
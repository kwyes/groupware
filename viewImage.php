<?
header("Content-Type: text/html; charset=euc-kr;");
$Type = $_GET['type'];

// ���۵� �̹��� ���� Ȯ��
if(isset($_GET['image'])) {

	// �̹��� ���
	if($Type == 1) {
		$imagePath = 'upload/DocAttach/'.$_GET['image'];
	} else if($Type == 2) {
	} else if($Type == 3) {
		$imagePath = 'upload/VouAttach/'.$_GET['image'];
	}

	// �Ѿ�� �̹�������� ���翩�ο� ���Ͽ��� Ȯ��
	if(file_exists($imagePath) && is_file($imagePath)) {

		// �Ѿ�� ���� Ȯ���� ����
		$tmp_name = pathinfo($imagePath);
		$ext = strtolower($tmp_name['extension']);

		// ������ Ȯ���ڸ� �����ֵ��� ���͸�
		if($ext == 'jpg' || $ext='gif' || $ext='png' || $ext='bmp') {

			//�̹��� ũ�������� ����� ����
			$img_info = getimagesize($imagePath);
			$filesize = filesize($imagePath);

			// �̹��� ������ ���� �������
			header("Content-Type: {$img_info['mime']}\n");
			header("Content-Disposition: inline;filename='1'\n");
			header("Content-Length: $filesize\n");
			
			// �̹��� ������ �о����
			readfile($imagePath);
		
		}
	}
}
?>
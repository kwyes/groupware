<script>
function album_save() {
	var target = document.forms.form_proposal;
	target.album_subject.required = "required";

	if(target.album_subject.value != "") {
		var answer = confirm("저장 하시겠습니까?");
		if(answer) {
			target.mode.value = "update";
			target.submit();
		}
	}
}

function autoResize(i) {
	var iframeHeight=
	(i).contentWindow.document.body.scrollHeight;
	(i).height=iframeHeight+10;
}
</script>

<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$album_ID = ($_GET['albumID']) ? $_GET['albumID'] : $_POST['albumID'];

if($mode == "update") {
	$album_company = ($_GET['album_company']) ? $_GET['album_company'] : $_POST['album_company'];
	$album_subject = ($_GET['album_subject']) ? $_GET['album_subject'] : $_POST['album_subject'];
	$userID = ($_GET['userID']) ? $_GET['userID'] : $_POST['userID'];

	$album_subject = Br_dconv($album_subject);
	$query = "UPDATE album_header SET ".
			 "albumAllowCompany = $album_company, ".
			 "albumSubject = '$album_subject' ".
			 "WHERE albumID = $album_ID";
	mssql_query($query);

	for($i = 0; $i < count($_FILES['image']['name']); $i++) {
		if ($_FILES['image']['error'][$i] > 0) {
			break;
		}

		// 업로드 허용 확장자 체크(jpg,jpeg,gif,png,bmp 확장자만 필터링)
		$ableExt = array('jpg','jpeg','gif','png','bmp');
		$path = pathinfo($_FILES['image']['name'][$i]);
		$ext = strtolower($path['extension']);

		if(!in_array($ext, $ableExt)) {
			exit("허용되지 않는 확장자입니다.");
		}

		$ableImage = array('image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', 'image/gif','image/bmp','image/pjpeg');
		if(!in_array($_FILES['image']['type'][$i], $ableImage)) {
			exit("지정된 이미지만 허용됩니다.");
		}

		$query = "SELECT max(imgSeq)+1 as imgSeq FROM album_Attach ".
				 "WHERE albumID = $album_ID";
		$result = mssql_query($query);
		$row = mssql_fetch_array($result);
		$imgSeq = $row['imgSeq'];
		echo "imgSeq: ".$imgSeq."<br>";

		$originalFileName = $_FILES['image']['name'][$i];
		$fileName = $album_ID."_".$imgSeq.".".$ext;
		$filePath = "upload/AlbumAttach/";
		$fullPath = $filePath.$fileName;

		$query = "INSERT INTO album_Attach (albumID, imgSeq, originalFileName, newFileName) ".
				 "VALUES ($album_ID, $imgSeq, '$originalFileName', '$fileName')";
		mssql_query($query);

		move_uploaded_file($_FILES['image']['tmp_name'][$i], $fullPath);
	}
?>
	<script type="text/javascript">
		location.href="<?=ABSOLUTE_PATH?>?page=community&menu=album&sub=view&albumID=<?=$album_ID; ?>";
	</script>
<?
} else if($mode == "delete") {
	// 해더 삭제
	$query = "DELETE FROM album_header WHERE albumID = $album_ID";
	mssql_query($query);

	// 첨부파일 삭제
	$imgPath = "upload/AlbumAttach/";
	$query = "SELECT newFileName FROM album_Attach WHERE albumID = $album_ID";
	$query_result = mssql_query($query);
	while($row = mssql_fetch_array($query_result)) {
		$fullpath = $imgPath.$row['newFileName'];
		unlink($fullpath);
	}
	$query = "DELETE FROM album_Attach WHERE albumID = $album_ID";
	mssql_query($query);

	// 댓글 삭제
	$brBdId = 5;
	$query = "DELETE FROM board_reply WHERE brId = $album_ID AND brBdId = $brBdId";
	mssql_query($query);
?>
	<script type="text/javascript">
		location.href="<?=ABSOLUTE_PATH?>?page=community&menu=album";
	</script>
<?
}


$query = "SELECT albumID, albumAllowCompany, albumSubject, uploadUserID, viewCount, CONVERT(char(19), uploadDate, 120) AS uploadDate ".
		 "FROM album_header WHERE albumID = $album_ID";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);
?>

<!-- community_album START -->
<form name="form_proposal" action="?page=community&menu=album&sub=update" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode">
<input type="hidden" name="albumID" value="<?=$row['albumID']; ?>">
<input type="hidden" name="userID" value="<?=$_SESSION['memberID']; ?>">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- community_album TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">커뮤니티 > 앨범게시판</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- community_album TITLE END -->

		<!-- community_album MAIN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="album_save();">저장</td>
								</tr>								
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td height="10"></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;">
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>관련회사</b></td>
									<td class="doc_field_content">
										<select name="album_company" style="width:150px;">
											<option value='0' <?if($row['albumAllowCompany'] == 0) echo "selected"; ?>>&raquo; 전체</option>
<?	
											$comp_query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
											$comp_query_result = mssql_query($comp_query);
											while($comp_row = mssql_fetch_array($comp_query_result)) {
?>
												<option value='<?=$comp_row['companyID']?>' <?if($comp_row['companyID'] == $row['albumAllowCompany']) echo "selected"; ?>>&raquo; <?=$comp_row['companyDesc']?></option>
<?											} ?>
										</select>
									</td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content"><input name="album_subject" type="text" style="width:630px;" value="<?=Br_iconv($row['albumSubject']); ?>"></td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>이미지첨부</b></td>
									<td class="doc_field_content">
										<input name="image[]" type="file" multiple>
									</td>
								</tr>
								
								<tr>
									<td colspan="2"><iframe id="editAlbumAttach" name="editAlbumAttach" src="iframe_editAlbumAttach.php?albumID=<?=$album_ID;?>" width="100%"></iframe></td>
								</tr>

							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="album_save();">저장</td>
								</tr>								
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30"></td>
		</tr>
		<!-- community_album MAIN END -->
	</table>
</td>
</form>

				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- community_album END -->
<script>
function album_upload() {
	var target = document.forms.form_proposal;
	target.album_subject.required = "required";

	if(target.album_subject.value != "") {
		var answer = confirm("등록 하시겠습니까?");
		if(answer) {
			target.mode.value = "write";
			target.submit();
		}
	}
}
</script>

<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

if($mode == "write") {
	echo "<div style='text-align:center; padding-top:100px; font-size:12px;'><img src='../images/ajax-loader.gif'> 처리중입니다. 잠시만 기다려 주세요..</div>";

	$album_company = ($_GET['album_company']) ? $_GET['album_company'] : $_POST['album_company'];
	$album_subject = ($_GET['album_subject']) ? $_GET['album_subject'] : $_POST['album_subject'];
	$userID = ($_GET['userID']) ? $_GET['userID'] : $_POST['userID'];

/*
	echo "mode: ".$mode."<br>";
	echo "album_company: ".$album_company."<br>";
	echo "album_subject: ".$album_subject."<br>";
	echo "userID: ".$userID."<br>";
*/

	for($i = 0; $i < count($_FILES['image']['name']); $i++) {
		echo "FILE_".$i.": ".$_FILES['image']['name'][$i]."<br>";
	}

	$query = "SELECT max(albumID)+1 as albumID FROM album_header";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['albumID']) {
		$album_ID = $row['albumID'];
	} else { 
		$album_ID = 1;
	}

	$album_subject = Br_dconv($album_subject);
	$query = "INSERT INTO album_header (albumID, albumAllowCompany, albumSubject, uploadUserID) ".
			 "VALUES ($album_ID, $album_company, '$album_subject', '$userID')";
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

		$originalFileName = $_FILES['image']['name'][$i];
		$fileName = $album_ID."_".$i.".".$ext;
		$filePath = "upload/AlbumAttach/";
		$fullPath = $filePath.$fileName;

		$query = "INSERT INTO album_Attach (albumID, imgSeq, originalFileName, newFileName) ".
				 "VALUES ($album_ID, $i, '$originalFileName', '$fileName')";
		mssql_query($query);

		move_uploaded_file($_FILES['image']['tmp_name'][$i], $fullPath);
	}
?>
	<script type="text/javascript">
		location.href="<?=ABSOLUTE_PATH?>?page=community&menu=album&sub=view&albumID=<?=$album_ID; ?>";
	</script>
<?
}
?>
<!-- community_album_view START -->
<form name="form_proposal" action="?page=community&menu=album&sub=write" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode">
<input type="hidden" name="userID" value="<?=$_SESSION['memberID']; ?>">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- community_album_view TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">앨범게시판 > 새글 작성</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- community_album_view TITLE END -->

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><input type="button" class="doc_submit_btn_style" onClick="album_upload()" value="등록"></td>
								</tr>								
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<!-- community_album_view MAIN START -->
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
											<option value='0'<?if($rst['companyID']== 0) echo "selected"; ?>>&raquo; 전체</option>
<?	
											$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
											$row2 = mssql_query($query);
											while($rst = mssql_fetch_array($row2)) {
?>
												<option value='<?=$rst['companyID']?>'<?if($rst['companyID']== $CompanyID) echo "selected"; ?>>&raquo; <?=$rst['companyDesc']?></option>
<?											} ?>
										</select>
									</td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content"><input name="album_subject" type="text" style="width:630px;"></input></td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>이미지첨부</b></td>
									<td class="doc_field_content">
										<input name="image[]" type="file" multiple>
									</td>
								</tr>

							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<!-- community_album_view MAIN END -->

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><input type="button" class="doc_submit_btn_style" onClick="album_upload()" value="등록"></td>
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
	</table>
</td>
</form>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- community_album_view END -->
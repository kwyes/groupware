<script>
function confirm_del(id) {
	var answer = confirm("삭제 하시겠습니까?");
		if(answer) {
			location.href = "?page=community&menu=album&sub=update&albumID="+id+"&mode=delete";
		}
}
</script>

<?
$userID = $_SESSION['memberID'];
$album_ID = ($_GET['albumID']) ? $_GET['albumID'] : $_POST['albumID'];

// 조회수(viewCount) 증가
$query = "UPDATE album_header SET viewCount = viewCount + 1 WHERE albumID = $album_ID";
mssql_query($query);


$query = "SELECT albumID, albumAllowCompany, albumSubject, uploadUserID, viewCount, CONVERT(char(19), uploadDate, 120) AS uploadDate ".
		 "FROM album_header WHERE albumID = $album_ID";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);

// 이미지 쿼리
$imgPath = "upload/AlbumAttach/";
$img_query = "SELECT newFileName FROM album_Attach WHERE albumID = $album_ID ORDER BY imgSeq ASC";
$img_query_result = mssql_query($img_query);
?>

<!-- community_album_view START -->
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- community_album_view TITLE START -->
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
		<!-- community_album_view TITLE END -->

		<!-- community_album_view MAIN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="left" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><a href="?page=community&menu=album"><input type="button" class="doc_submit_btn_style" value="목록"></a></td>
								</tr>								
							</table>
						</td>
<?						if($userID == $row['uploadUserID']) { ?>
							<td align="right" style="padding: 0 12px 0 0;">
								<table>
									<tr>
										<td><a href="?page=community&menu=album&sub=update&albumID=<?=$album_ID; ?>&mode=edit"><input type="button" class="doc_submit_btn_style" value="수정"></a></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="confirm_del(<?=$album_ID; ?>)" value="삭제"></td>
									</tr>								
								</table>
							</td>
<?						} ?>
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
									<td width="95" height="30" align="center" class="doc_field_name"><b>번호</b></td>
									<td class="doc_field_content"><?=$row['albumID']; ?></td>
								</tr>
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>관련회사</b></td>
									<td class="doc_field_content"><?=($row['albumAllowCompany'] == 0) ? "전체" : get_company_name($row['albumAllowCompany']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
									<td class="doc_field_content"><?=get_user_name($row['uploadUserID']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성일</b></td>
									<td class="doc_field_content"><?=$row['uploadDate']; ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>조회수</b></td>
									<td class="doc_field_content"><?=$row['viewCount']; ?></td>
								</tr>
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content"><?=Br_iconv($row['albumSubject']); ?></td>
								</tr>

								<tr class="doc_border">
									<td colspan="2" class="doc_field_content" style="padding: 10px 12px;">
<?										while($img_row = mssql_fetch_array($img_query_result)) { ?>
<?											$fullPath = $imgPath.$img_row['newFileName']; ?>
											<img src='<?=$fullPath; ?>' style="max-width: 100%; height: auto;"><br><br>
<?										} ?>
									</td>
								</tr>

							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

<?		$ID = $row['albumID'];
		$Type = 5;
?>

		<tr>
			<td style="padding: 0px 13px;">
				<div>
				<!-- reply -->
					<script language="JavaScript">
					function resizeHeight(id) 
					{
						var the_height = document.getElementById(id).contentWindow.document.body.scrollHeight;
						document.getElementById(id).height = the_height;
					}
					</script>
					<iframe id="contentIframe" onLoad="resizeHeight('contentIframe');" src="board_reply.php?page=community&menu=free&bdId=<?=$ID?>&Type=<?=$Type?>&txtarea_size1=<?=$txtarea_size1?>>" width="100%" marginwidth="0" marginheight="0" scrolling="no" frameborder="0"></iframe>
				</div>
			</td>
		</tr>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="left" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><a href="?page=community&menu=album"><input type="button" class="doc_submit_btn_style" value="목록"></a></td>
								</tr>								
							</table>
						</td>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><a href="?page=community&menu=album&sub=update&albumID=<?=$album_ID; ?>&mode=edit"><input type="button" class="doc_submit_btn_style" value="수정"></a></td>
										<td width="5"></td>
										<td><a href="?page=community&menu=album&sub=update&albumID=<?=$album_ID; ?>&mode=delete"><input type="button" class="doc_submit_btn_style" value="삭제"></a></td>
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
		<!-- community_album_view MAIN END -->
	</table>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- community_album_view END -->
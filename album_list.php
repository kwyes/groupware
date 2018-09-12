<script>
function page_navigation(list) {
	document.forms.form_search.list.value = list;
	document.forms.form_search.submit();
}
</script>

<?
$list = ($_GET['list']) ? $_GET['list'] : $_POST['list'];
$userID = $_SESSION['memberID'];
$userComp = $_SESSION['memberCID'];

$query = "SELECT albumID, albumAllowCompany, albumSubject, uploadUserID, viewCount,  CONVERT(char(19), uploadDate, 120) AS uploadDate ".
		 "FROM album_header ".
		 "ORDER BY uploadDate DESC";

$query_result = mssql_query($query);
$query_row = mssql_num_rows($query_result);

// page navigation
if(!isset($list)) {
	$list = 1;
}
$total_row = $query_row;
$page_per_list = 15;
$page_total = ceil($total_row/$page_per_list);
if($total_row > 0) {
	mssql_data_seek($query_result, ($list-1)*$page_per_list);
}
?>


<!-- community_album_list START -->
<form name="form_search" action="?page=community&menu=album&sub=list" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="list" value=1>
</form>
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- community_album_list TITLE START -->
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
		<!-- community_album_list TITLE END -->

		<!-- community_album_list MAIN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><a href="?page=community&menu=album&sub=write"><input type="button" class="doc_submit_btn_style" value="글쓰기"></a></td>
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

		<!-- TEST -->
		<tr>
			<td>
				<table>
					<tr>
						<td>
							<ul style="display:block;">
<?								$i = 1; ?>
<?								while($row = mssql_fetch_array($query_result)) { ?>
<?									$album_ID = $row['albumID'];
									$Type = 5;
									$reply_query = "SELECT brBoardId FROM board_reply WHERE brId = $album_ID AND brBdId = $Type";
									$reply_query_result = mssql_query($reply_query);
									$reply_count = mssql_num_rows($reply_query_result);

									$imgPath = "upload/AlbumAttach/";
									$album_ID = $row['albumID'];
									$thumb_query = "SELECT TOP 1 newFileName FROM album_Attach WHERE albumID = $album_ID ORDER BY imgSeq ASC";
									$thumb_query_result = mssql_query($thumb_query);
									$thumb_row = mssql_fetch_array($thumb_query_result);
									$fullPath = $imgPath.$thumb_row['newFileName'];
?>
									<li style="display:block; float:left; width:150px; height:250px; padding:0 0 0 15px;">
										<a href="?page=community&menu=album&sub=view&albumID=<?=$album_ID; ?>">
											<img src='<?=$fullPath; ?>' width='145' height='150' style="border:4px solid #848484"><br>
										</a>
										<div style="padding:5px 0 0 0; width:145px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis "><?=Br_iconv($row['albumSubject']); ?></div>
										<div style="padding:5px 0 0 0;"><font color="blue"><?=get_user_name($row['uploadUserID']); ?></font> / <font color="grey"><?=$row['uploadDate']; ?></font></div>
										<div style="padding:5px 0 0 0;">조회: <?=$row['viewCount']; ?><?if($reply_count > 0) { ?> / <font color="red">댓글: <?=$reply_count;?></font><? } ?></div>
									</li>
<?									if($i++ == $page_per_list) {
										break;
									} ?>
<?								} ?>
							</ul>
						</td>
					</tr>

					<!-- community_album_list PAGE NAVIGATION START -->
					<tr>
						<td align="center">
<?
							for($i = 1+(floor(($list-1)/10)*10) ; $i <= $page_total ; $i++) {
								if(($i-1)%10 == '0' && $i != '1') {
									$temp_page = floor(($list-1)/10)*10;
									echo "&nbsp<a href='javascript:page_navigation($temp_page)'>◀</a> \n";
								}
								if($i == 1+(floor(($list-1)/10)*10)) {
									echo "&nbsp<font color='#A4A4A4'> | </font>&nbsp";
								}
								if($i == $list) {
									echo "<b><a style='color:red; text-decoration:underline;' href='javascript:page_navigation($i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
								} else {
									echo "<b><a href='javascript:page_navigation($i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
								}
								if($i%10 == '0') {
									$i++;
									echo "<a href='javascript:page_navigation($i)'>▶</a> \n";
									break;
								}
							}	
?>
						</td>
					</tr>
					<!-- community_album_list PAGE NAVIGATION END -->
				</table>
			</td>
		</tr>
		<!-- TEST -->


		<tr>
			<td height="30"></td>
		</tr>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><a href="?page=community&menu=album&sub=write"><input type="button" class="doc_submit_btn_style" value="글쓰기"></a></td>
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
		<!-- community_album_list MAIN END -->
	</table>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- community_album_list END -->
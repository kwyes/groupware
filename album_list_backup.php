<script>
function page_navigation(mode, list) {
	if(mode == "search") {
		document.forms.form_search.list.value = list;
	} else {
		document.forms.form_search.list.value = list;
		document.forms.form_search.mode.value = "";
	}
	document.forms.form_search.submit();
}
</script>

<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$list = ($_GET['list']) ? $_GET['list'] : $_POST['list'];
$userID = $_SESSION['memberID'];
$userComp = $_SESSION['memberCID'];

if($mode == "search") {
	$search_album = Br_dconv($_POST['search_album']);

	if($_POST['search_type'] == 1) {
		$query = "SELECT albumID, albumAllowCompany, albumSubject, uploadUserID, viewCount,  CONVERT(char(19), uploadDate, 120) AS uploadDate ".
				 "FROM album_header ".
				 "WHERE (albumAllowCompany = $userComp OR albumAllowCompany = 0) AND albumSubject LIKE '%$search_album%' ".
				 "ORDER BY uploadDate DESC";
/*	} else if($_POST['search_type'] == 2) {
		$query = "SELECT albumID, albumAllowCompany, albumSubject, uploadUserID, viewCount,  CONVERT(char(19), uploadDate, 120) AS uploadDate ".
				 "FROM album_header ".
				 "WHERE (albumAllowCompany = $userComp OR albumAllowCompany = 0) AND albumContent LIKE '%$search_album%' ".
				 "ORDER BY uploadDate DESC";*/
	} else if($_POST['search_type'] == 2) {
		$query = "SELECT albumID, albumAllowCompany, albumSubject, uploadUserID, viewCount,  CONVERT(char(19), uploadDate, 120) AS uploadDate ".
				 "FROM album_header ".
				 "WHERE (albumAllowCompany = $userComp OR albumAllowCompany = 0) AND uploadUserID IN (SELECT memID FROM Member WHERE memName LIKE '%$search_album%') ".
				 "ORDER BY uploadDate DESC";
	}

} else {
	$query = "SELECT albumID, albumAllowCompany, albumSubject, uploadUserID, viewCount,  CONVERT(char(19), uploadDate, 120) AS uploadDate ".
			 "FROM album_header ".
			 "WHERE albumAllowCompany = $userComp OR albumAllowCompany = 0 ".
			 "ORDER BY uploadDate DESC";
}

$query_result = mssql_query($query);
$query_row = mssql_num_rows($query_result);

// page navigation
if(!isset($list)) {
	$list = 1;
}
$total_row = $query_row;
$page_per_list = 10;
$page_total = ceil($total_row/$page_per_list);
if($total_row > 0) {
	mssql_data_seek($query_result, ($list-1)*$page_per_list);
}
?>


<!-- community_album_list START -->
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

		<!-- community_album_list SEARCH BOX START -->
		<form name="form_search" action="?page=community&menu=album&sub=list" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="mode" value="search">
		<input type="hidden" name="list" value=1>
		<tr>
			<td style="padding:12px 25px">
				<table width="500px" class="doc_border">
					<tr height="30">
						<td width="90" class="doc_field_name" style="padding:0 0 0 10px;">
							<select name="search_type" style="width:80px;">
								<option value="1" <?if($_POST['search_type'] == 1) echo "selected"; ?>> 제목 </option>
								<option value="2" <?if($_POST['search_type'] == 3) echo "selected"; ?>> 작성자 </option>
							</select>
						</td>
						<td width="320px" style="padding: 5px 0 0 11px; border-right: 0;"><input name="search_album" type="text" style="width:300px; font-size:12px;" value="<?=$_POST['search_album']; ?>"></td>
						<td style="padding: 3px 0 0 9px;"><input type="submit" value="검색"></td>
					</tr>
				</table>
			</td>
		</tr>
		</form>
		<!-- community_album_list SEARCH BOX END -->

		<!-- TEST -->
		<tr>
			<td>
				<ul style="display:block;">
					<li style="display:block; float:left; width:400px; height: 150px;">
						haha
					</li>
					<li style="display:block; float:left; width:400px; height: 150px;">
						haha2
					</li>
					<li style="display:block; float:left; width:400px; height: 150px;">
						haha3
					</li>
					<li style="display:block; float:left; width:400px; height: 150px;">
						haha4
					</li>
					<li style="display:block; float:left; width:400px; height: 150px;">
						haha5
					</li>
					<li style="display:block; float:left; width:400px; height: 150px;">
						haha6
					</li>
				</ul>
			</td>
		</tr>
		<!-- TEST -->

<? /* ?>				
		<tr>
			<td>
				<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
					<tr height="20">
						<td width="70" class="title bb br">번호</td>
						<td width="80" class="title bb br">썸네일</td>
						<td class="title bb br">제목</td>
						<td width="80" class="title bb br">작성자</td>
						<td width="130" class="title bb br">작성일</td>
						<td width="60" class="title bb">조회수</td>
					</tr>
<?					if($query_row == 0) { ?>
						<tr height="100">
							<td align="center" class="bb" colspan="6" style="padding-top:25px;">
								<b>등록된 글이 없습니다</b>
							</td>
						</tr>
<?					} else { ?>
<?						$i = 1; ?>
<?						while($row = mssql_fetch_array($query_result)) { ?>
<?
							$imgPath = "upload/AlbumAttach/";
							$album_ID = $row['albumID'];
							$thumb_query = "SELECT TOP 1 newFileName FROM album_Attach WHERE albumID = $album_ID ORDER BY imgSeq ASC";
							$thumb_query_result = mssql_query($thumb_query);
							$thumb_row = mssql_fetch_array($thumb_query_result);
							$fullPath = $imgPath.$thumb_row['newFileName'];
?>
							<tr height="100">
								<td class="docid bb" style="vertical-align:middle"><a href="?page=community&menu=album&sub=view&albumID=<?=$album_ID; ?>"><?=$row['albumID']; ?></a></td>
								<td class="content bb"><a href="?page=community&menu=album&sub=view&albumID=<?=$album_ID; ?>"><img src='<?=$fullPath; ?>' width='70' height='90' border='1'></a></td>
								<td class="content bb" style="vertical-align:middle"><a href="?page=community&menu=album&sub=view&albumID=<?=$album_ID; ?>"><?=Br_iconv($row['albumSubject']); ?></a></td>
								<td class="content bb" style="vertical-align:middle"><?=get_user_name($row['uploadUserID']); ?></td>
								<td class="date bb" style="vertical-align:middle"><?=$row['uploadDate']; ?></td>
								<td class="content bb" style="vertical-align:middle"><?=$row['viewCount']; ?></td>
							</tr>
<?							if($i++ == $page_per_list) {
								break;
							} ?>
<?						} ?>
<?					} ?>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30"></td>
		</tr>

		<!-- community_album_list PAGE NAVIGATION START -->
		<tr>
			<td align="center">
<?
				for($i = 1+(floor(($list-1)/10)*10) ; $i <= $page_total ; $i++) {
					if(($i-1)%10 == '0' && $i != '1') {
						$temp_page = floor(($list-1)/10)*10;
						if($mode == "search") {
							echo "&nbsp<a href='javascript:page_navigation(\"$mode\", $temp_page)'>◀</a> \n";
						} else {
							echo "&nbsp<a href='javascript:page_navigation(\"$mode\", $temp_page)'>◀</a> \n";
						}
					}
					if($i == 1+(floor(($list-1)/10)*10)) {
						echo "&nbsp<font color='#A4A4A4'> | </font>&nbsp";
					}
					if($i == $list) {
						if($mode == "search") {
							echo "<b><a style='color:red; text-decoration:underline;' href='javascript:page_navigation(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						} else {
							echo "<b><a style='color:red; text-decoration:underline;' href='javascript:page_navigation(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						}
					} else {
						if($mode == "search") {
							echo "<b><a href='javascript:page_navigation(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						} else {
							echo "<b><a href='javascript:page_navigation(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						}
					}
					if($i%10 == '0') {
						$i++;
						if($mode == "search") {
							echo "<a href='javascript:page_navigation(\"$mode\", $i)'>▶</a> \n";
						} else {
							echo "<a href='javascript:page_navigation(\"$mode\", $i)'>▶</a> \n";
						}
						break;
					}
				}	
?>
			</td>
		</tr>
		<!-- community_album_list PAGE NAVIGATION END -->
<? */ ?>
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
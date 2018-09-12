<script>
function delImageConfirm(mode, id, seq, imagePath) {
	var answer = confirm("첨부 파일을 삭제 하시겠습니까?");
	if(answer) {
		//alter("삭제 되었습니다.");
		location.href = "iframe_editAlbumAttach.php?mode="+mode+"&albumID="+id+"&imgSeq="+seq+"&imagePath="+imagePath;
	} else {
		alter("삭제가 취소 되었습니다.");
		location.reload();
	}
}
</script>

<?
include_once "includes/general.php";

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$album_ID = ($_GET['albumID']) ? $_GET['albumID'] : $_POST['albumID'];

if($mode == "delete") {
	$imgSeq = ($_GET['imgSeq']) ? $_GET['imgSeq'] : $_POST['imgSeq'];
	$imagePath = ($_GET['imagePath']) ? $_GET['imagePath'] : $_POST['imagePath'];

	if(!is_file($imagePath)) {
		exit("이미지 파일이 존재하지 않음");
	}
	unlink($imagePath);

	$query = "DELETE FROM album_Attach WHERE albumID = $album_ID AND imgSeq = $imgSeq";
	mssql_query($query);

}

$imgPath = "upload/AlbumAttach/";
$img_query = "SELECT newFileName, imgSeq FROM album_Attach WHERE albumID = $album_ID ORDER BY imgSeq ASC";
$img_query_result = mssql_query($img_query);
?>

<table width="100%">

<?	while($img_row = mssql_fetch_array($img_query_result)) { ?>
<?		$imgSeq = $img_row['imgSeq']; ?>
<?		$fullPath = $imgPath.$img_row['newFileName']; ?>
<?		$imagesize = getimagesize($fullPath); ?>
		<tr>
			<td>
				<img src="css/img/icon_file.gif">
				<?echo $img_row['newFileName']; ?>
				<a href="javascript:delImageConfirm('delete', '<?=$album_ID; ?>', '<?=$imgSeq; ?>', '<?=$fullPath; ?>');"><img src="css/img/bt_del.gif"></a>
			</td>
		</tr>
<?	} ?>
</table>
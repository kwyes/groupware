<script>
function create_folder() {
	var target = document.forms.form_folder_create;
	target.folder_location.required = "required";
	target.folder_name.required = "required";

	if(target.folder_location.value != "" && target.folder_name.value != "") {
		var answer = confirm("폴더를 생성 하시겠습니까?");
		if(answer) {
			target.submit();
		}
	}

}

function delete_folder(seq, type) {
	var target = document.forms.form_folder_delete;
	target.folder_location.value = type;
	target.folder_seq.value = seq;

	if(target.folder_location.value != "" && target.folder_seq.value != "") {
		var answer = confirm("폴더를 삭제 하시겠습니까? \n(폴더안에 있던 문서들은 결재완료 문서로 이동됩니다)");
		if(answer) {
			target.submit();
		}
	}

}
</script>

<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$userID = $_SESSION['memberID'];

if($mode == "folder_create") {
	$folderLocation = ($_GET['folder_location']) ? $_GET['folder_location'] : $_POST['folder_location'];
	$folderName = ($_GET['folder_name']) ? Br_dconv($_GET['folder_name']) : Br_dconv($_POST['folder_name']);
	$contentSeq = 0;

	$query = "SELECT max(FolderSeq)+1 AS FolderSeq FROM PersonalFolder WHERE UserID = '$userID' AND FolderLocation = $folderLocation";
	$query_result = mssql_query($query);
	$row = mssql_fetch_array($query_result);

	if($row['FolderSeq'] == NULL) {
		$folderSeq = 1;
	} else {
		$query = "SELECT max(FolderSeq)+1 AS FolderSeq FROM PersonalFolder WHERE UserID = '$userID' AND FolderLocation = $folderLocation ".
				 "AND '$folderName' NOT IN (SELECT FolderName FROM PersonalFolder WHERE UserID = '$userID' AND FolderLocation = $folderLocation)";
		$query_result = mssql_query($query);
		$row = mssql_fetch_array($query_result);
		$folderSeq = $row['FolderSeq'];
	}

	/*
	echo "userID: ".$userID."<br>";
	echo "folderLocation: ".$folderLocation."<br>";
	echo "folderName: ".Br_iconv($folderName)."<br>";
	echo "folderSeq: ".$folderSeq."<br>";
	echo "contentSeq: ".$contentSeq."<br>";
	*/

	if($folderSeq == NULL) {
		echo "<script>alert('폴더명이 이미 존재합니다.\\n다른 폴더명을 지정해주십시요.')</script>";
	} else {
		$create_folder_query = "INSERT INTO PersonalFolder (UserID, FolderLocation, FolderSeq, ContentSeq, FolderName) ".
							   "VALUES ('$userID', $folderLocation, $folderSeq, $contentSeq, '$folderName')";
		mssql_query($create_folder_query);
	}
?>
	<script type="text/javascript">
		location.href="<?=ABSOLUTE_PATH?>?page=e_doc&menu=personalFolder";
	</script>
<?
} else if($mode == "folder_delete") {
	$folderLocation = ($_GET['folder_location']) ? $_GET['folder_location'] : $_POST['folder_location'];
	$folderSeq = ($_GET['folder_seq']) ? $_GET['folder_seq'] : $_POST['folder_seq'];

	$delete_folder_query = "DELETE FROM PersonalFolder WHERE UserID = '$userID' AND FolderLocation = $folderLocation AND FolderSeq = $folderSeq";
	mssql_query($delete_folder_query);
?>
	<script type="text/javascript">
		location.href="<?=ABSOLUTE_PATH?>?page=e_doc&menu=personalFolder";
	</script>
<?
}

// FolderLocation 1.받은결재함 / 2.올린결재함
$receive_folder = "SELECT FolderSeq, FolderName FROM PersonalFolder WHERE UserID = '$userID' AND FolderLocation = 1 AND ContentSeq = 0 ORDER BY FolderSeq ASC";
$receive_folder_result = mssql_query($receive_folder);
$receive_folder_count = mssql_num_rows($receive_folder_result);

$offer_folder = "SELECT FolderSeq, FolderName FROM PersonalFolder WHERE UserID = '$userID' AND FolderLocation = 2 AND ContentSeq = 0 ORDER BY FolderSeq ASC";
$offer_folder_result = mssql_query($offer_folder);
$offer_folder_count = mssql_num_rows($offer_folder_result);
?>

<td width="" align="left" valign="top">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">개인폴더 관리</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<form name="form_folder_create" action="?page=e_doc&menu=personalFolder" method="post">
		<input type="hidden" name="mode" value="folder_create">
		<tr>
			<td style="padding:12px 25px">
				<table width="100%" class="doc_border">
					<tr height="33">
						<td width="80" align="center" class="doc_field_name"><b>폴더위치</b></td>
						<td width="320" style="padding: 5px 0 0 10px;">
							<select name="folder_location" style="width:187px;">
								<option value=""> 폴더위치를 선택하세요. </option>
								<option value="1">받은 결재 문서함</option>
								<option value="2">올린 결재 문서함</option>
							</select>
						</td>
						<td width="" rowspan="2"><input type="button" class="doc_submit_btn_style" style="width:70px; height:58px; margin-left:5px;" onClick="create_folder()" value="생성"></td>
					</tr>
					<tr height="33">
						<td width="80" align="center" class="doc_field_name"><b>폴더명</b></td>
						<td style="padding: 5px 0 0 10px; border-right: 0;"><input name="folder_name" type="text" style="width:300px; font-size:12px;"></td>
					</tr>
				</table>				
			</td>
		</tr>
		</form>

		<tr>
			<td align="left" style="padding:19px 0 0 0;">
				<table width="100%">
					<tr>
						<td height="25">
							<table width="100%">
								<tr>
									<td width="90%" style="padding:8px 0 0 18px; letter-spacing:-1px;"><b>받은 결재 문서함 > 폴더관리</b></td>
								</tr>								
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
								<tr height="20">
									<td width="40" class="title bb br"><b>No</b></td>
									<td width="" class="title bb br">폴더명</td>
									<td width="400" class="title bb"></td>
								</tr>

<?								if($receive_folder_count == 0) { ?>
									<tr height="60">
										<td align="center" class="bb" colspan="3" style="padding-top:25px;">
											<b>생성된 폴더가 없습니다</b>
										</td>
									</tr>
<?								} else {?>
<?									while($receive_folder_row = mssql_fetch_array($receive_folder_result)) { ?>
										<tr height="25">
											<td class="docid bb"><?=$receive_folder_row['FolderSeq']; ?></td>
											<td class="content bb"><img src="../css/img/folder.gif"><span style="padding-left:5px; line-height:20px"><?=Br_iconv($receive_folder_row['FolderName']); ?></span></td>
											<td class="content bb" style="line-height:20px;"><a onClick="javacript:delete_folder(<?=$receive_folder_row['FolderSeq']; ?>,1)" style="cursor:pointer;"><font color="red"><b>삭제</b></font></a></td>
										</tr>
<?									} ?>
<?								} ?>
							</table>
						</td>
					</tr>

					<tr>
						<td height="50"></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td align="left" style="padding:19px 0 0 0;">
				<table width="100%">
					<tr>
						<td height="25">
							<table width="100%">
								<tr>
									<td width="90%" style="padding:8px 0 0 18px; letter-spacing:-1px;"><b>올린 결재 문서함 > 폴더관리</b></td>
								</tr>								
							</table>
						</td>
					</tr>

					<tr>
						<td>
							<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
								<tr height="20">
									<td width="40" class="title bb br"><b>No</b></td>
									<td width="" class="title bb br">폴더명</td>
									<td width="400" class="title bb"></td>
								</tr>

<?								if($offer_folder_count == 0) { ?>
									<tr height="60">
										<td align="center" class="bb" colspan="3" style="padding-top:25px;">
											<b>생성된 폴더가 없습니다</b>
										</td>
									</tr>
<?								} else {?>
<?									while($offer_folder_row = mssql_fetch_array($offer_folder_result)) { ?>
										<tr height="25">
											<td class="docid bb"><?=$offer_folder_row['FolderSeq']; ?></td>
											<td class="content bb"><img src="../css/img/folder.gif"><span style="padding-left:5px; line-height:20px"><?=Br_iconv($offer_folder_row['FolderName']); ?></span></td>
											<td class="content bb" style="line-height:20px;"><a onClick="javacript:delete_folder(<?=$offer_folder_row['FolderSeq']; ?>,2)" style="cursor:pointer;"><font color="red"><b>삭제</b></font></a></td>
										</tr>
<?									} ?>
<?								} ?>
							</table>
						</td>
					</tr>

					<form name="form_folder_delete" action="?page=e_doc&menu=personalFolder" method="post">
						<input type="hidden" name="mode" value="folder_delete">
						<input type="hidden" name="folder_location">
						<input type="hidden" name="folder_seq">
					</form>

					<tr>
						<td height="50"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
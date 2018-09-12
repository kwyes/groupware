<?
include_once "includes/general.php";
$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$LUserID = $_SESSION['memberID'];
?>
<script LANGUAGE="JAVASCRIPT">
function doc_submit() {
	var target = document.forms.form_proposal;

	if(target.subject.value == "") {
		alert("문서 제목을 입력하세요.");
		target.subject.focus();
		return;
	} else {
		var answer = confirm("저장 하시겠습니까?");

		if(answer) {
			target.mode.value = "note_submit";
			target.dtype.value = "1";
			target.submit();
		} else {
			target.subject.focus();
			return;
		}
	}
}
function doc_save() {
	var target = document.forms.form_proposal;

	if(target.subject.value != "") {
		var answer = confirm("저장 하시겠습니까?");
		if(answer) {
			target.mode.value = "update_save";
			saveContent();
		}
	}
}
function doc_delete() {
	var target = document.forms.form_proposal;

	var answer = confirm("삭제 하시겠습니까?");
	if(answer) {
		target.mode.value = "note_delete";
		target.submit();
	}
}
function clear_period() {
	var target = document.forms.form_proposal;
	target.period.checked = 0;
}
function clear_date() {
	var target = document.forms.form_proposal;
	target.search_dateStart.value = "";
	target.search_dateEnd.value = "";
}
</script>
<?
	$today = date("Y-m-d");
	$query = "select bdId,a.boardId,CompanyId,UserId,bdTitle,bdDescription as Contents,bdHit,CONVERT(char(20), a.RegDate, 120) AS RegDate, blName, ".
				" CONVERT(char(10), a.StartDate, 23) AS StartDate, CONVERT(char(10), a.EndDate, 23) AS EndDate, period ".
				"from board_data a, board_list b ".
			"where bdId = ".$ID." AND a.boardId = b.boardId ";
	$rst = mssql_query($query);
    $row = mssql_fetch_array($rst);
	$bname = $row['blName'];
?>
<td width="" align="left" valign="top">
	<form name="form_proposal" method="post" action="upload/upload_form.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="ID" value="<?=$ID?>">
	<input type="hidden" name="Type" value="<?=$Type?>">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">커뮤니티 > 직원게시판</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
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
									<td width="50%" align="right" style="padding: 0px 14px;">
										<a href="/"><button class="doc_submit_btn_style" onclick="history.go(-1); return false;">뒤로가기</button></a>
									</td>
									<?if($LUserID == $row['UserId']) {?>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_save(this.form)">저장</button></a></td>
									<td width="15" ></td>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_delete(this.form)">삭제</button></a></td>
									<?}?>
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
						<td colspan="3" align="center" class="doc_title"><?=Br_iconv($bname)?></td>
					</tr>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목</b></td>
						<td colspan="2" class="doc_field_content"><input name="subject" type="text" style="width:630px;" value="<?=Br_iconv($row['bdTitle'])?>"></input></td>
					</tr>
					<tr>
						<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;용</b></td>
						<td align="center" colspan="2" style="border: 1px solid #c9c9c9;">
							<? include_once "editor/editor.html"; ?>
						</td>
					</tr>
<?
$ImgVariable = array();

//if ($Type == 1) {
	$ImgPath = "upload/BoardAttach/";
	$query = "SELECT DocID, FileSeq, NewFilename FROM board_Attach ".
			 "WHERE DocID = $ID ".
			 "ORDER BY FileSeq ASC";
	$result3 = mssql_query($query);
	while($row3 = mssql_fetch_array($result3)) {
		$ImgVariable[$row3['FileSeq']] = $row3['NewFilename'];
	}
//} else if($Type == 2) {
//}
?>
					<tr class="doc_border">
						<td align="center" class="doc_field_name"><b>파일첨부</b></td>
						<td height="100"><? include_once ("loadImage.php"); ?>
			<?			
						// 업로드 할 수 있는 이미지 갯수 3개로 한정 
						$aImageCount = $countImage;
						while($countImage < 3) {
							$countImage++;
			?>
							<br>&nbsp;<input type="file" name="aImage[]">
						<? } ?>
						</td>
						<input type="hidden" name="MAX_FILE_SIZE" value="5242880">
						<!-- 저장되어 있는 이미지 갯수 -->
						<input type="hidden" name="aImageCount" value="<?=$aImageCount; ?>">
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
									<td width="50%" align="right" style="padding: 0px 14px;">
										<a href="/"><button class="doc_submit_btn_style" onclick="history.go(-1); return false;">뒤로가기</button></a>
									</td>
									<?if($LUserID == $row['UserId']) {?>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_save(this.form)">저장</button></a></td>
									<td width="15" ></td>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_delete(this.form)">삭제</button></a></td>
									<?}?>
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
	</form>
</td>

				</tr>
			</table>
		</td>	
	</tr>
</table>
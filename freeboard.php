<?
include_once "includes/general.php";

$menu = ($_GET['menu']) ? $_GET['menu'] : $_POST['menu'];
?>
<script LANGUAGE="JAVASCRIPT">
function doc_submit(str) {
	var target = document.forms.form_proposal;

	if(target.subject.value == "") {
		alert("문서 제목을 입력하세요.");
		target.subject.focus();
		return;
	} else {
		var answer = confirm("저장 하시겠습니까?");

		if(answer) {
			target.mode.value = "note_submit";
			if(str == "free")
				target.dtype.value = "6";
			else if(str == "help")
				target.dtype.value = "7";
			saveContent();
//			target.submit();
		} else {
			target.subject.focus();
			return;
		}
	}
}
</script>
<?
	$today = date("Y-m-d");

	if($menu=="free") {
		$strMenu = "직원게시판";
	} else if($menu=="help") {
		$strMenu = "업무협조";
	} else {
		$strMenu = "";
	}
?>
<td width="" align="left" valign="top">
	<form name="form_proposal" method="post" action="upload/upload_form.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="dtype" value="">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">커뮤니티 > <?=$strMenu?></td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
<?/*?>
		<tr>
			<td align="right" style="padding: 0 12px 0 0;">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="50%" align="left" style="padding: 0px 14px;">
							<a href="?page=community&menu=free"><div class="menu_button2" style="width:70px">뒤로가기</div></a>
						</td>
						<td width="50%" align="right">
							<table>
								<tr>
								<?if($LUserID == $row['UserId']) {?>
									<td width="80" align="right">
										<div class="menu_button2" style="width:70px" onclick="javascript:doc_submit('<?=$menu?>')">저장</div>
									</td>
								<?}?>
									<td width="80" align="right">
										<div class="menu_button2" style="width:70px" onclick="">취소</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
<?*/?>
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<tr>
						<td colspan="2" align="center" class="doc_title"><?=$strMenu?></td>
					</tr>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목</b></td>
						<td class="doc_field_content"><input name="subject" type="text" style="width:630px;"></input></td>
					</tr>
					<tr>
						<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;용</b></td>
						<td align="center" colspan="4" style="border: 1px solid #c9c9c9;">
							<? include_once "editor/editor.html"; ?>
						</td>
					</tr>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일 첨부 1</b></td>
						<td class="doc_field_content">
							첨부 이미지:
							<input name="userfile" type="file" />
						</td>
					</tr>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일 첨부 2</b></td>
						<td class="doc_field_content">
							첨부 이미지:
							<input name="userfile2" type="file" />
						</td>
					</tr>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일 첨부 3</b></td>
						<td class="doc_field_content">
							첨부 이미지:
							<input name="userfile3" type="file" />
						</td>
					</tr>
				</table>
			</td>						
		</tr>
		<tr>
			<td align="right" style="padding: 0 12px 0 0;">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="50%" align="left" style="padding: 0px 14px;">
							<a href="?page=community&menu=free"><div class="menu_button2" style="width:70px">뒤로가기</div></a>
						</td>
						<td width="50%" align="right">
							<table>
								<tr>
								<?if($LUserID == $row['UserId']) {?>
									<td width="80" align="right">
										<div class="menu_button2" style="width:70px" onclick="javascript:doc_submit('<?=$menu?>')">저장</div>
									</td>
								<?}?>
									<td width="80" align="right">
										<div class="menu_button2" style="width:70px" onclick="location.reload(true);">CLEAR</div>
									</td>
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
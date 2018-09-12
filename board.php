<?
include_once "includes/general.php";
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
			target.dtype.value = "4";
			saveContent();
//			target.submit();
		} else {
			target.subject.focus();
			return;
		}
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
	$StartDate = $EndDate = $today;
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
						<td width="360" align="left" class="content_title">커뮤니티 > 공지사항</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>
<?/*?>
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
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_submit(this.form)">저장</button></a></td>
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
						<td colspan="2" align="center" class="doc_title">공지사항 올리기</td>
					</tr>
					<tr class="doc_border">
						<td height="30" width="95" align="center" class="doc_field_name"><b>공지 회사</b></td>
						<td align="left" class="doc_field_content">
						<select name="usecompid">
							<option value='0'<?if($rst['companyID']== 0) echo "selected"; ?>>&raquo; 공통</option>
					<?	
							$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
							$row2 = mssql_query($query);
							while($rst = mssql_fetch_array($row2)) {
					?>
							<option value='<?=$rst['companyID']?>'<?if($rst['companyID']== $CompanyID) echo "selected"; ?>>&raquo; <?=$rst['companyDesc']?></option>
					<?		}	?>
						</select>
						</td>
					</tr>
					<tr class="doc_border">
						<td height="30" width="95" align="center" class="doc_field_name"><b>공지 기간</b></td>
						<td class="doc_field_content">시작일: <input id="search_dateStart" name="search_dateStart" type="text" style="width:85px;" maxlength="10" value="<?=$StartDate?>" onclick="clear_period()">&nbsp;&nbsp;
						종료일: <input id="search_dateEnd" name="search_dateEnd" type="text" style="width:85px;" maxlength="10" value="<?=$EndDate?>" onclick="clear_period()">&nbsp;&nbsp;(
						<? if($row['period']) {?>
							<input name="period" type="checkbox" value="1" checked="checked" onclick="clear_date()">&nbsp;기간없음&nbsp;)
						<?} else {?>
							<input name="period" type="checkbox" value="0" onclick="clear_date()">&nbsp;기간없음&nbsp;)
						<?}?>
						</td>
					</tr>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>문서 제목</b></td>
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
										<div class="menu_button2" style="width:70px" onclick="javascript:doc_submit(this.form)">저장</div>
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
<?/*?>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td width="50%" align="right" style="padding: 0px 14px;">
										<a href="/"><button class="doc_submit_btn_style" onclick="history.go(-1); return false;">뒤로가기</button></a>
									</td>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_submit(this.form)">저장</button></a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
<?*/?>
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
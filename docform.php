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
			target.mode.value = "submit";
			target.submit();
		} else {
			target.subject.focus();
			return;
		}
	}
}
function goBack() {
	window.history.go(-1);
}
</script>
<?
	$today = date("Y-m-d");
?>
<td width="" align="left" valign="top">
	<form name="form_proposal" method="post" action="upload/upload_form.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">문서양식함</td>
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
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_submit(this.form)">저장</button></a></td>
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
						<td colspan="2" align="center" class="doc_title">문서 양식 올리기</td>
					</tr>
					<tr class="doc_border">
						<td width="95" align="center" class="doc_field_name"><b>사용 회사</b></td>
						<td height="30" align="left" class="doc_field_content">
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
						<td height="30" align="center" class="doc_field_name"><b>문서 제목</b></td>
						<td class="doc_field_content"><input name="subject" type="text" style="width:630px;"></input></td>
					</tr>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>문서 설명</b></td>
						<td class="doc_field_content"><input name="desc" type="text" style="width:630px;"></input></td>
					</tr>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일 첨부</b></td>
						<td class="doc_field_content">
							첨부 이미지:
							<input name="userfile" type="file" />
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
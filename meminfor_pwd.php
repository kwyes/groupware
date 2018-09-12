<?
$mode = $_POST['mode'];

if($mode == "changePWD") {
	$UserID = $_SESSION['memberID'];
	$cur_pwd = $_POST['cur_pwd'];
	$chg_pwd = $_POST['chg_pwd'];
	$chg_pwd2 = $_POST['chg_pwd2'];

	if($chg_pwd != $chg_pwd2) {
		echo "<script type='text/javascript'>alert('새 비밀번호와 비밀번호 확인이 일치하지 않습니다.');</script>";
	} else {
		$query = "SELECT memID ".
				 "FROM Member ".
				 "WHERE memID = '$UserID' AND memPW = '$cur_pwd'";
		$query_result = mssql_query($query);
		$row = mssql_fetch_array($query_result);

		if($row['memID']) {
			$query = "UPDATE Member ".
					 "SET memPW = '$chg_pwd' ".
					 "WHERE memID = '$UserID' AND memPW = '$cur_pwd'";
			mssql_query($query);
			echo "<script type='text/javascript'>alert('비밀번호가 변경 되었습니다.');</script>";
		} else {
			echo "<script type='text/javascript'>alert('현재 비밀번호가 일치하지 않습니다.');</script>";
		}
	}

}
?>

<td width="" align="left" valign="top" height="500">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">개인정보 > 비밀번호 변경</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<form name="form_changePWD" action="?page=meminfor&menu=pwd" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="mode" value="changePWD">
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;" class="doc_border">
								<tr>
									<td width="130" height="30" align="center" class="doc_field_name"><b>현재 비밀번호</b></td>
									<td class="doc_field_content"><input name="cur_pwd" type="password" required></input></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>새 비밀번호</b></td>
									<td class="doc_field_content"><input name="chg_pwd" type="password" required></input></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>새 비밀번호(확인)</b></td>
									<td class="doc_field_content"><input name="chg_pwd2" type="password" required></input></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="left" style="padding: 0 0 0 12px;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style">변경하기</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		</form>

	</table>
</td>

<!--
	</table>
	<p>
	<table>
		<tr height="30">
			<td></td><td></td><td></td>
		</tr>
		<tr>
			<td width="70"></td>
			<td width="120" height="40">현재 비밀번호:</td>
			<td width="70"><input name="cur_pwd"></input></td>
		</tr>
		<tr>
			<td></td>
			<td height="40">비밀번호 변경:</td>
			<td><input name="chg_pwd"></input></td>
		</tr>
		<tr>
			<td></td>
			<td height="40">비밀번호 변경(확인):</td>
			<td><input name="chg_pwd2"></input></td>
		</tr>
	</table>
</td>
-->

				</tr>
			</table>
		</td>	
	</tr>
</table>

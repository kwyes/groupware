<?
$mode = $_POST['mode'];

if($mode == "changeInfo") {
	$ID = $_POST['ID'];
	$eMail = $_POST['eMail'];
	$phone = $_POST['phone'];
	$notification = $_POST['notification'];

	$query = "UPDATE Member ".
			 "SET memEmail = '$eMail', memPhone = '$phone', notification = $notification ".
			 "WHERE memID = '$ID'";
	mssql_query($query);

	echo "<script type='text/javascript'>location.href='?page=meminfor&menu=up';</script>";
}

$LoginId = $_SESSION['memberID'];
$query = "SELECT memID, memPW, memName, memEmail, companyID, deptID, memPosition, memPhone, memLevel, memStatus, notification, loginDate ".
		"FROM member WHERE memID = '$LoginId' ";
$query_result = mssql_query($query, $conn);
$row = mssql_fetch_array($query_result);
?>
<td width="" align="left" valign="top" height="500">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">개인정보 > 정보수정</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<form name="form_chagneInfo" action="?page=meminfor&menu=up" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="mode" value="changeInfo">
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;" class="doc_border">
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>아이디</b></td>
									<td class="doc_field_content"><?=$row['memID']?></td>
									<input type="hidden" name="ID" value="<?=$row['memID']?>">
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>성명</b></td>
									<td class="doc_field_content"><?=Br_iconv($row['memName'])?></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>회사명</b></td>
									<td class="doc_field_content"><?=get_company_name($row['companyID'])?></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>부서명</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_Dept($row['deptID']))?></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>직급</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_duty($row['memPosition']))?></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>이메일</b></td>
									<td class="doc_field_content"><input name="eMail" style="width:300px;" value="<?=$row['memEmail']?>"></input></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>전화번호</b></td>
									<td class="doc_field_content"><input name="phone" style="width:300px;" value="<?=$row['memPhone']?>"></input></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>알림설정</b></td>
									<td class="doc_field_content">
										<input type="radio" name="notification" value="1" <?=($row['notification'] == 1 ? 'checked' : '') ?>>E-Mail</input>
										<input type="radio" name="notification" value="2" <?=($row['notification'] == 2 ? 'checked' : '') ?>>Push Notification</input>
									</td>
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
									<td><button class="doc_submit_btn_style" onClick="doc_submit()">저장하기</td>
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
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="90" align="right" class="content_title">아이디:</td>
						<td align="left" style="padding: 14px;"><?=$row['memID']?></td> 
					</tr>
					<tr>
						<td align="right" class="content_title">성&nbsp;&nbsp;&nbsp;&nbsp;명:</td>
						<td align="left" style="padding: 14px;"><?=Br_iconv($row['memName'])?></td> 
					</tr>
					<tr>
						<td align="right" class="content_title">회&nbsp;사&nbsp;명:</td>
						<td align="left" style="padding: 14px;"><?=get_company_name(Br_iconv($row['companyID']))?></td> 
					</tr>
					<tr>
						<td align="right" class="content_title">부&nbsp;서&nbsp;명:</td>
						<td align="left" style="padding: 14px;"><?=get_Dept(Br_iconv($row['deptID']))?></td> 
					</tr>
					<tr>
						<td align="right" class="content_title">직&nbsp;&nbsp;&nbsp;&nbsp;급:</td>
						<td align="left" style="padding: 14px;"><?=get_duty(Br_iconv($row['memPosition']))?></td> 
					</tr>
					<tr>
						<td align="right" class="content_title">이&nbsp;메&nbsp;일:</td>
						<td align="left" style="padding: 14px;"><input name="eMail" value="<?=$row['memEmail']?>"></input></td> 
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>
-->

				</tr>
			</table>
		</td>	
	</tr>
</table>

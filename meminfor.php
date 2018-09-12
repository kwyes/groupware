<?
include_once "includes/general.php";

	$LoginId = $_SESSION['memberID'];
	$query = "SELECT memID, memPW, memName, memEmail, companyID, deptID, memPosition, memPhone, memLevel, memStatus, notification, CONVERT(char(19), loginDate, 120) AS loginDate ".
			"FROM member WHERE memID = '$LoginId' ";
	$query_result = mssql_query($query, $conn);
	$row = mssql_fetch_array($query_result);
?>
<td width="" align="left" valign="top" height="500">
	<table width="100%">
		<!-- meminfo TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">개인정보 > 정보조회</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- meminfo TITLE START -->

		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;" class="doc_border">
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>아이디</b></td>
									<td class="doc_field_content"><?=$row['memID']?></td>
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
									<td class="doc_field_content"><?=$row['memEmail']?></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>전화번호</b></td>
									<td class="doc_field_content"><?=$row['memPhone']?></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>알림설정</b></td>
									<td class="doc_field_content"><?=($row['notification'] == 1 ? "E-Mail" : "Push Notification")?></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>최근접속일시</b></td>
									<td class="doc_field_content"><?=$row['loginDate']?></td>
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
		</td>	
	</tr>
</table>

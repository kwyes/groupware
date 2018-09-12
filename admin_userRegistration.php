<?
include_once("mail/sendmail.php");

$mode = $_POST['mode'];

if($mode == "registration_approve") {
	$memID = $_POST['memID'];
	$memLevel = $_POST['memLevel'];
	$memStatus = 1;
	$regDate = date("Y-m-d H:i:s");

	$query = "UPDATE Member SET memLevel = $memLevel, memStatus = $memStatus, regDate = '$regDate' ".
			 "WHERE memID = '$memID'";
	mssql_query($query);

	$memInfo_query = "SELECT memID, memName, memEmail ".
					 "FROM Member WHERE memID = '$memID' ";
	$memInfo_query_result = mssql_query($memInfo_query);
	$memInfo_row = mssql_fetch_array($memInfo_query_result);

	$fromName = "경영지원실-전산팀";
	$fromEmail = "itdiv@hannamsm.com";
	$toName = Br_iconv($memInfo_row['memName']);
	$toEmail = $memInfo_row['memEmail'];
	$subject = "[GROUPWARE] 신청하신 계정이 승인되었습니다.";
	$content = "<p>신청하신 계정이 승인되었습니다.</p><br>";
	$content .= "<p>아이디 - ".$memInfo_row['memID']."</p>";
	$content .= "<p><a href='http://group.t-brothers.com/' target='_blank'>http://group.t-brothers.com/</a></p>";

	sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $content, $isDebug=0);
}

$query = "SELECT memID, memName, companyID, deptID, memPosition ".
		 "FROM Member ".
		 "WHERE memStatus = 3";

$query_result = mssql_query($query);
$query_row = mssql_num_rows($query_result);
?>

<script>
function change_memLevel(memLevel) {
	document.form_registration_approve.memLevel.value = memLevel;
}

function approve(memID) {
	document.form_registration_approve.memID.value = memID;
	document.form_registration_approve.submit();
}
</script>

<td width="" align="left" valign="top">
	<table width="100%">
		<!-- admin_userRegistration TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">신규멤버 신청서</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- admin_userRegistration TITLE END -->

		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<tr>
			<td height="20"></td>
		</tr>

		<tr>
			<td>
				<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
					<tr height="20">
						<td width="100" class="title bb br">아이디</td>
						<td width="80" class="title bb br">이름</td>
						<td width="200" class="title bb br">소속회사</td>
						<td width="100" class="title bb br">소속부서</td>
						<td width="80" class="title bb br">직급</td>
						<td width="" class="title bb br"></td>
					</tr>

<?					if($query_row == 0) { ?>
						<tr height="60">
							<td align="center" class="bb" colspan="6" style="padding-top:25px;">
								<b>신청서가 없습니다</b>
							</td>
						</tr>
<?					} else {?>
						<form name="form_registration_approve" action="?page=admin&menu=userRegistration" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							<input type="hidden" name="mode" value="registration_approve">
							<input type="hidden" name="memID" value="">
							<input type="hidden" name="memLevel" value="">
						</form>
<?						while($row = mssql_fetch_array($query_result)) { ?>
							<tr height="40">
								<td class="content bb" style="padding-top:15px;"><?=$row['memID']; ?></a></td>
								<td class="content bb" style="padding-top:15px;"><?=Br_iconv($row['memName']); ?></td>
								<td class="content bb" style="padding-top:15px;"><?=get_company_name($row['companyID']); ?></td>
								<td class="content bb" style="padding-top:15px;"><?=Br_iconv(get_dept_name($row['companyID'], $row['deptID'])); ?></td>
								<td class="content bb" style="padding-top:15px;"><?=Br_iconv(get_duty($row['memPosition'])); ?></td>
								<td class="content bb" style="padding-left:30px;">
									<select name="memLevel" style="width:200px; margin-top:0;" onChange="change_memLevel(this.value)">
										<option value=""> 레벨 선택 </option>
										<option value="1"> Level. 1 (회장) </option>
										<option value="2"> Level. 2 (임원) </option>
										<option value="3"> Level. 3 (본부장) </option>
										<option value="4"> Level. 4 (팀장) </option>
										<option value="5"> Level. 5 (사원) </option>
									</select>

<?									$memID = $row['memID']; ?>
									<input type="button" class="doc_submit_btn_style" onClick="approve('<?=$memID; ?>')" value="승인">
								</td>
							</tr>
<?						} ?>
<?					} ?>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
	</table>
</td>
				
				</tr>
			</table>
		</td>	
	</tr>
</table>
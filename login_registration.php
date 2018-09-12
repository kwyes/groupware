<?
$mode = $_POST['mode'];

if($mode == "submit_registration") {
	$userID = $_POST['userID'];
	$userPWD = $_POST['userPWD'];
	$userPWD2 = $_POST['userPWD2'];
	$userName = Br_dconv($_POST['userName']);
	$userEmail = $_POST['userEmail'];
	$userPhone = $_POST['userPhone'];
	$userComp = $_POST['userComp'];
	$userDept = $_POST['userDept'];
	$userPosi = $_POST['userPosi'];
	// 멤버 DB - memStatus 값
	// 1.사용중 2.사용중지 3.사용대기
	$memStatus = 3;

	$query = "INSERT INTO Member (memID, memPW, memName, memEmail, memPhone, companyID, deptID, memPosition, memStatus) ".
			 "VALUES ('$userID', '$userPWD', '$userName', '$userEmail', '$userPhone', $userComp, $userDept, $userPosi, $memStatus)";
	mssql_query($query);
?>
	<script>alert('가입 요청되었습니다.\n관리자가 승인처리한 후 아이디 사용이 가능합니다.'); location.href='?page=login'</script>
<?
}
?>

<script>
function check_duplicationID() {
	var userID = document.form_registration.userID.value;

	if(userID == "") {
		alert('아이디를 입력해주세요.');
	} else {
		var popURL = "login_reg_checkDupID.php?check=" + userID;
		var popOption = "width=370, height=160, resizable=no, scrollbars=no, status=no;";
		window.open(popURL, "", popOption);
	}
}

function check_before_submit() {
	var userID = document.form_registration.userID.value;
	var checkedID = document.form_registration.duplicationID.value;

	if(userID == checkedID) {
		var PWD1 = document.form_registration.userPWD.value;
		var PWD2 = document.form_registration.userPWD2.value;

		if(PWD1 == PWD2) {
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

			if(!filter.test(document.form_registration.userEmail.value)) {
				alert('Invalid Email.\n이메일 오류');
				return false;
			}

			document.form_registration.submit();
		} else {
			alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.');
		}
	} else {
		alert('아이디 중복확인이 필요합니다.');
	}
}

function change_Field(getVal) {
	var objTwo = document.form_registration.userDept;
	var i;

	for (i = document.form_registration.userDept.options.length; i >= 0; i--) {
		document.form_registration.userDept.options[i] = null; 
	}

	switch (getVal) {

	case '1': 
		objTwo.options[0] = new Option ('소속부서를 선택하세요.','');
		objTwo.options[1] = new Option ('영업','1');
		objTwo.options[2] = new Option ('구매','2');
		objTwo.options[3] = new Option ('물류','3');
		objTwo.options[4] = new Option ('하드웨어','4');
		objTwo.options[5] = new Option ('업무지원','5');
		objTwo.options[6] = new Option ('임원진','8');
		return;
	case '2': 
		objTwo.options[0] = new Option ('소속부서를 선택하세요.','');
		objTwo.options[1] = new Option ('영업','1');
		objTwo.options[2] = new Option ('구매','2');
		objTwo.options[3] = new Option ('물류','3');
		objTwo.options[4] = new Option ('업무지원','4');
		objTwo.options[5] = new Option ('본부','9');
		return;
	case '3': 
	case '4': 
		objTwo.options[0] = new Option ('소속부서를 선택하세요.','');
		objTwo.options[1] = new Option ('매장','1');
		objTwo.options[2] = new Option ('야채','2');
		objTwo.options[3] = new Option ('정육','3');
		objTwo.options[4] = new Option ('반찬','4');
		objTwo.options[5] = new Option ('생선','5');
		objTwo.options[6] = new Option ('하우스웨어','6');
		objTwo.options[7] = new Option ('C/S','7');
		objTwo.options[8] = new Option ('본부','9');
		return;
	case '5': 
		objTwo.options[0] = new Option ('소속부서를 선택하세요.','');
		objTwo.options[1] = new Option ('회계','1');
		objTwo.options[2] = new Option ('전산','2');
		objTwo.options[3] = new Option ('총무','3');
		objTwo.options[4] = new Option ('홍보','4');
		objTwo.options[5] = new Option ('경영지원','5');
		return;
	}
}
</script>

<form name="form_registration" action="?page=login_registration" method="post" accept-charset="utf-8" onSubmit="check_before_submit(); return false">
<input type="hidden" name="mode" value="submit_registration">
<input type="hidden" name="duplicationID" value="">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- login_application TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">아이디 등록신청</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- login_application TITLE END -->

		<!-- login_application FORM START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%" class="doc_border">
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>아이디</b></td>
						<td class="doc_field_content">
							<input name="userID" style="width:120px;" required>
							<input type="button" class="doc_submit_btn_style" style="margin:0 0 0 15px;" value="중복확인" onClick="check_duplicationID();">
						</td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>비밀번호</b></td>
						<td class="doc_field_content"><input name="userPWD" type="password" style="width:130px;" required></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>비밀번호 확인</b></td>
						<td class="doc_field_content"><input name="userPWD2" type="password" style="width:130px;" required></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>이름</b></td>
						<td class="doc_field_content"><input name="userName" style="width:80px;" required></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>이메일</b></td>
						<td class="doc_field_content"><input name="userEmail" style="width:200px;" required></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>전화번호</b></td>
						<td class="doc_field_content"><input name="userPhone" style="width:200px;" required></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>소속회사</b></td>
						<td class="doc_field_content">
							<select name="userComp" style="width:200px;" onchange='change_Field(this.value)' required>
<?								$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
								$query_result = mssql_query($query);
?>
								<option value=""> 소속회사를 선택하세요. </option>
<?								while($row = mssql_fetch_array($query_result)) { ?>
									<option value="<?=$row['companyID']; ?>"><?=$row['companyDesc']; ?></option>
<?								} ?>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>소속부서</b></td>
						<td class="doc_field_content">
							<select name="userDept" style="width:200px;" required>
								<option value=""> 소속회사를 먼저 선택하세요. </option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>직급</b></td>
						<td class="doc_field_content">
							<select name="userPosi" style="width:200px;" required>
<?								$query = "SELECT dutyID, dutyName FROM Duty WHERE dutyID < 99 ORDER BY dutyID";
								$query_result = mssql_query($query);
?>
								<option value=""> 직급을 선택하세요. </option>
<?								while($row = mssql_fetch_array($query_result)) { ?>
									<option value="<?=$row['dutyID']?>"><?=Br_iconv($row['dutyName']); ?></option>
<?								} ?>
							</select>
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
									<td><input type="submit" class="doc_submit_btn_style" value="신청하기"></td>
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
		<!-- login_application FORM END -->
	</table>
</td>
</form>

				</tr>
			</table>
		</td>	
	</tr>
</table>
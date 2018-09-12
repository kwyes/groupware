<?
$mode = $_POST['mode'];

if($mode == "modify") {
	$userID = $_POST['userID'];
	$userName = Br_dconv($_POST['userName']);
	$userEmail = $_POST['userEmail'];
	$userPhone = $_POST['userPhone'];
	$userComp = $_POST['userComp'];
	$userDept = $_POST['userDept'];
	$userPosi = $_POST['userPosi'];
	$userStatus = $_POST['userStatus'];
	$userLevel = $_POST['userLevel'];

/*
	echo "userID: ".$userID."<br>";
	echo "userName: ".$userName."<br>";
	echo "userEmail: ".$userEmail."<br>";
	echo "userPhone: ".$userPhone."<br>";
	echo "userComp: ".$userComp."<br>";
	echo "userDept: ".$userDept."<br>";
	echo "userPosi: ".$userPosi."<br>";
*/

	$query = "UPDATE Member SET memName = '$userName', memEmail = '$userEmail', memPhone = '$userPhone', companyID = $userComp, deptID = $userDept, memPosition = $userPosi, memStatus = $userStatus, memLevel = $userLevel ".
			 "WHERE memID = '$userID'";
	mssql_query($query);

	echo "<script type='text/javascript'>location.href='?page=admin&menu=userManagement';</script>";
} else {
	$memID = $_POST['memID'];

	$query = "SELECT memID, memName, memEmail, memPhone, companyID, deptID, memPosition, memLevel, memStatus ".
			 "FROM Member ".
			 "WHERE memID = '$memID'";

	$query_result = mssql_query($query);
	$row = mssql_fetch_array($query_result);
}
?>

<script>
function change_Field(getVal) {
	var objTwo = document.form_management.userDept;
	var i;

	for (i = document.form_management.userDept.options.length; i >= 0; i--) {
		document.form_management.userDept.options[i] = null; 
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

function dept(compID, deptID) {
	change_Field(compID);

	var objTwo = document.form_management.userDept;
	objTwo.options[deptID].selected = "selected";
}
</script>

<body onload="dept('<?=$row['companyID']; ?>', '<?=$row['deptID']; ?>')">
<form name="form_management" action="?page=admin&menu=userManagement_view" enctype="multipart/form-data" method="post" accept-charset="utf-8" onSubmit="check_before_submit(); return false">
<input type="hidden" name="mode" value="modify">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- login_application TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">멤버 상세정보 관리</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- login_application TITLE END -->

		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<!-- login_application FORM START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%" class="doc_border">
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>아이디</b></td>
						<td class="doc_field_content"><?=$row['memID']; ?></td>
						<input type="hidden" name="userID" value="<?=$row['memID']; ?>">
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>이름</b></td>
						<td class="doc_field_content"><input name="userName" style="width:80px;" value="<?=Br_iconv($row['memName']); ?>" required></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>이메일</b></td>
						<td class="doc_field_content"><input name="userEmail" style="width:200px;" value="<?=$row['memEmail']; ?>"></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>전화번호</b></td>
						<td class="doc_field_content"><input name="userPhone" style="width:200px;" value="<?=$row['memPhone']; ?>" required></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>소속회사</b></td>
						<td class="doc_field_content">
							<select name="userComp" style="width:200px;" onChange='change_Field(this.value)' required>
<?								$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
								$query_result = mssql_query($query);
?>
								<option value=""> 소속회사를 선택하세요. </option>
<?								while($row2 = mssql_fetch_array($query_result)) { ?>
									<option value="<?=$row2['companyID']; ?>" <?=($row2['companyID'] == $row['companyID']) ? selected : '' ?> ><?=$row2['companyDesc']; ?></option>
<?								} ?>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>소속부서</b></td>
						<td class="doc_field_content">
							<select name="userDept" style="width:200px;" required>

							</select>
						</td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>직급</b></td>
						<td class="doc_field_content">
							<select name="userPosi" style="width:200px;" required>
<?								$query = "SELECT dutyID, dutyName FROM Duty ORDER BY dutyID";
								$query_result = mssql_query($query);
?>
								<option value=""> 직급을 선택하세요. </option>
<?								while($row2 = mssql_fetch_array($query_result)) { ?>
									<option value="<?=$row2['dutyID']?>" <?=($row2['dutyID'] == $row['memPosition']) ? selected : '' ?> ><?=Br_iconv($row2['dutyName']); ?></option>
<?								} ?>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>계정상태</b></td>
						<td class="doc_field_content">
							<select name="userStatus" style="width:200px;" required>
								<option value=""> 계정상태를 선택하세요. </option>
								<option value="1" <?=($row['memStatus'] == 1) ? selected : '' ?>> 사용중 </option>
								<option value="2" <?=($row['memStatus'] == 2) ? selected : '' ?>> 사용중지 </option>
								<option value="3" <?=($row['memStatus'] == 3) ? selected : '' ?>> 사용대기 </option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>계정등급</b></td>
						<td class="doc_field_content">
							<select name="userLevel" style="width:200px;" required>
								<option value=""> 계정등급을 선택하세요. </option>
								<option value="1" <?=($row['memLevel'] == 1) ? selected : '' ?>> 회장 </option>
								<option value="2" <?=($row['memLevel'] == 2) ? selected : '' ?>> 임원 </option>
								<option value="3" <?=($row['memLevel'] == 3) ? selected : '' ?>> 본부장 </option>
								<option value="4" <?=($row['memLevel'] == 4) ? selected : '' ?>> 팀장 </option>
								<option value="5" <?=($row['memLevel'] == 5) ? selected : '' ?>> 사원 </option>
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
									<td><input type="submit" class="doc_submit_btn_style" value="저장하기"></td>
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
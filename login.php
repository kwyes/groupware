<script>
window.onload = function() {
    document.getElementById("USERID").focus();
};
</script>
<?
session_start();
$LoginId = $_POST['USERID'];
$LoginPw = $_POST['USERPW'];

$LoginId = str_replace("'", "''", $LoginId);
$LoginPw = str_replace("'", "''", $LoginPw);

$url2 = ($_GET['url2']) ? $_GET['url2'] : $_POST['url2'];

if($LoginId && $LoginPw) {
	$LoginId = Br_dconv($LoginId);

	$query = "SELECT memID, memPW, memName, companyID, deptID, memPosition, memLevel, memStatus, useHelp ".
			 "FROM Member WHERE memID = '$LoginId' ";
	$query_result = mssql_query($query, $conn);
	$row = mssql_fetch_array($query_result);

	if($row['memPW'] == $LoginPw && $row['memStatus'] == 1) {
		$_SESSION['memberID'] = $LoginId;
		$_SESSION['memberPW'] = $LoginPw;
		$_SESSION['memberName'] = $row['memName'];
		$_SESSION['memberCID'] = $row['companyID'];
		$_SESSION['memberDID'] = $row['deptID'];
		$_SESSION['memberPosition'] = $row['memPosition'];
		$_SESSION['memberLevel'] = $row['memLevel'];
		$_SESSION['memberStatus'] = $row['memStatus'];
		$_SESSION['useHelp'] = $row['useHelp'];

		$loginTime = date("Y-m-d H:i:s");
		$query = "UPDATE Member SET loginDate = '$loginTime' WHERE memID = '$LoginId' AND memPW = '$LoginPw'";
		mssql_query($query);

		$query = "SELECT * FROM hr_authority WHERE id = '$LoginId'";
		$query_result = mssql_query($query, $conn);
		$row = mssql_fetch_array($query_result);
		
		if($row['hr_level'] && $row['hannam_code']) {
			$_SESSION['hr_level'] = $row['hr_level'];
			$_SSSION['hr_code'] = $row['hannam_code'];
		} else {
			$_SESSION['hr_level'] = 0;
			$_SESSION['hr_code'] = 0;
		}

?>
		<script type="text/javascript">
			<? if($url2 == "/?page=logout") { ?>
				location.href="?page=e_doc";
			<? } else { ?>
				location.href="<?=$url2?>";
			<? } ?>
		</script>

<?	} else { ?>
<?		
		if($row['memStatus'] == 2) {
			$failed_msg = "사용이 중지된 아이디입니다.";
		} else if($row['memStatus'] == 3) {
			$failed_msg = "사용대기중인 아이디입니다.";
		} else {
			$failed_msg = "등록되지 않은 아이디 혹은 비밀번호입니다.";
		}
?>

		<div class="login_wrapper">
			 <form name="login_form" method="post" action="?page=login" class="login" autocomplete="off">
			  <h1>Group&nbsp;&nbsp;TB</h1>
		    <input id = "USERID" type="text" name="USERID" class="login-input" />
		    <input type="password" name="USERPW" class="login-input" />
		    <input type="submit" value="Login" class="login-submit">
		    <p class="login-help"><a href="?page=login_registration"><font color = "white">Create an account</font></a></p>
		    </form>
		</div>
<?		
//		}
	}

} else if($LoginId || $LoginPw) {
?>
	<div class="login_wrapper">
		 <form name="login_form" method="post" action="?page=login" class="login" autocomplete="off">
			  <h1>Group&nbsp;&nbsp;TB</h1>
		    <input id = "USERID" type="text" name="USERID" class="login-input" />
		    <input type="password" name="USERPW" class="login-input" />
		    <input type="submit" value="Login" class="login-submit">
		    <p class="login-help"><a href="?page=login_registration"><font color = "white">Create an account</font></a></p>
		    </form>
	</div>

<? } else { ?>
	<div>
<?		if($_SESSION["memberID"]) { ?>
			<script>location.href="?page=e_doc";</script>
<?		} else { ?>
			
	<div class="login_wrapper">
	<div class="login">
			<form name="login_form" method="post" action="?page=login"  autocomplete="off">
			<h1 style="padding-bottom:16px">Group&nbsp;&nbsp;TB</h1>
		    <input id = "USERID" type="text" name="USERID" class="login-input" />
		    <input type="password" name="USERPW" class="login-input" />
		    <input type="submit" value="Login" class="login-submit">
		    <p class="login-help"><a href="?page=login_registration"><font color = "white">Create an account</font></a></p>
		    </form>
	</div>
	</div>
<?	} ?>
	</div>
<? } ?>
				</tr>
			</table>
		</td>	
	</tr>
</table>
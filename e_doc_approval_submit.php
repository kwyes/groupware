<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
include_once("includes/general.php");
include_once("mail/sendmail.php");

$ID = ($_GET['ID']) ? (int)$_GET['ID'] : (int)$_POST['ID'];
$Seq = ($_GET['Seq']) ? (int)$_GET['Seq'] : (int)$_POST['Seq'];
$Type = ($_GET['Type']) ? (int)$_GET['Type'] : (int)$_POST['Type'];
$UserID = ($_GET['UserID']) ? $_GET['UserID'] : $_POST['UserID'];
$current_url = ($_GET['current_url']) ? $_GET['current_url'] : $_POST['current_url'];

$Subject = ($_GET['doc_subject']) ? $_GET['doc_subject'] : $_POST['doc_subject'];
$Comment = ($_GET['Comment']) ? $_GET['Comment'] : $_POST['Comment'];
$approval = ($_GET['approval']) ? (int)$_GET['approval'] : (int)$_POST['approval'];
$today = date('Y-m-d H:i:s');
/*
echo "ID :".$ID."<br>";
echo "Seq :".$Seq."<br>";
echo "Type :".$Type."<br>";
echo "UserID :".$UserID."<br>";
echo "Comment :".$Comment."<br>";
echo "approval :".$approval."<br>";
*/

$fromName = Br_iconv($_SESSION['memberName']);
//$Subject = Br_iconv($Subject);

$contents="
	<body>
	  <table>
		<tr height ='25'>
		  <td>결재할 문서가 있습니다.</td>
		</tr>
		<tr height ='25'>
		  <td>문서번호: $ID - $Seq</td>
		</tr>
		<tr height ='25'>
		  <td>문서제목: $Subject</td>
		</tr>
		<tr height ='25'>
		  <td>상신자: $fromName</td>
		</tr>
		<tr height ='25'>
		  <td><a href='http://group.t-brothers.com/?page=e_doc&menu=receive&sub=view_wait&ID=$ID&Type=$Type&Seq=$Seq' target='_blank'>결재할 문서 바로가기</a></td>
		</tr>
		<tr height ='50'>
		  <td>From: Groupware Mailing Service ... </td>
		</tr>
	  </table>
	</body>
";

echo "<div style='text-align:center; padding-top:100px; font-size:12px;'><img src='../images/ajax-loader.gif'> 처리중입니다. 잠시만 기다려 주세요..</div>";

//1.결재완료, 2:결재진행, 3:임시저장, 4:회수, 5:반려, 6:보류, 7:전결, 8:의견

if($approval == 4) {
	$query = "UPDATE E_DOC_Header SET Status = $approval ".
			 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
	mssql_query($query);

	if($Type == 1) {
		$query = "UPDATE Doc SET ApprovalStatus = $approval, ApprovalDate = '$today' ".
				 "WHERE DocID = $ID AND DocSeq = $Seq";
		mssql_query($query);
	} else if($Type == 2) {
		$query = "UPDATE Cooperation SET ApprovalStatus = $approval, ApprovalDate = '$today' ".
				 "WHERE DocID = $ID AND DocSeq = $Seq";
		mssql_query($query);
	} else if($Type == 3) {
		$query = "UPDATE Voucher SET ApprovalStatus = $approval, ApprovalDate = '$today' ".
				 "WHERE VoucherID = $ID AND VoucherSeq = $Seq";
		mssql_query($query);
	}
	else if($Type == 4) {
		
		$query = "UPDATE BusinessEm SET ApprovalStatus = $approval, ApprovalDate = '$today' ".
				 "WHERE DocID = $ID AND DocSeq = $Seq";
		mssql_query($query);

		$query = "UPDATE Businesstrip SET ApprovalStatus = $approval, ApprovalDate = '$today' ".
				 "WHERE DocID = $ID AND DocSeq = $Seq";
		mssql_query($query);
		$query = "UPDATE Businesstrip2 SET ApprovalStatus = $approval, ApprovalDate = '$today' ".
				 "WHERE DocID = $ID AND DocSeq = $Seq";
		mssql_query($query);
		$query = "UPDATE Businesstrip3 SET ApprovalStatus = $approval, ApprovalDate = '$today' ".
				 "WHERE DocID = $ID AND DocSeq = $Seq";
		mssql_query($query);
	}

} else {
	$res = save_approval_submit($ID, $Seq, $Type, $UserID, $Comment, $approval, $today);

	if($res) {
		$query = "SELECT ApprovalUserSeq, ApprovalUserID FROM ApprovalList ".
						"WHERE DocID = $ID AND DocType = $Type AND DocSeq = '$Seq'  ".
						"ORDER BY ApprovalUserSeq ";
		$query_result = mssql_query($query);
		while($result = mssql_fetch_array($query_result))
		{
			$LastApprovalSeq = $result['ApprovalUserSeq'];

			if($result['ApprovalUserID'] == $UserID) {
				$MyApprovalSeq = $result['ApprovalUserSeq'];
			}
		}
		
		if($Type == 1) {
			$title = "기안서가 상신 되었습니다.";
			if($LastApprovalSeq != $MyApprovalSeq) {
				go_sendmail($ID, $Type, $Seq, $title, $contents, $MyApprovalSeq+1);
			}
			if($LastApprovalSeq == $MyApprovalSeq || $approval == 5) {
				$res3 = save_approval_proposal($ID, $Seq, $Type, $approval, $today);
			}
		} else if($Type == 2) {
			$title = "협조문이 상신 되었습니다.";
			if($LastApprovalSeq != $MyApprovalSeq) {
				go_sendmail($ID, $Type, $Seq, $title, $contents, $MyApprovalSeq+1);
			}
			if($LastApprovalSeq == $MyApprovalSeq || $approval == 5) {
				$res3 = save_approval_coop($ID, $Seq, $Type, $approval, $today);
			}
		} else if($Type == 3) {
			$title = "지출결의서가 상신 되었습니다.";
			if($LastApprovalSeq != $MyApprovalSeq) {
				go_sendmail($ID, $Type, $Seq, $title, $contents, $MyApprovalSeq+1);
			}
			if($LastApprovalSeq == $MyApprovalSeq || $approval == 5) {
				$res3 = save_approval_voucher($ID, $Seq, $Type, $approval, $today);
			}
		}
		else if($Type == 4) {
			$title = "출장계획서가 상신 되었습니다.";
			if($LastApprovalSeq != $MyApprovalSeq) {
				//go_sendmail($ID, $Type, $Seq, $title, $contents, $MyApprovalSeq+1);
			}
			if($LastApprovalSeq == $MyApprovalSeq || $approval == 5) {
				$res3 = save_approval_businesstrip($ID, $Seq, $Type, $approval, $today);
			}
		}
	}
}
?>
<script>
	location.href = "http://group.t-brothers.com/?page=e_doc";
</script>
<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$userID = $_SESSION['memberID'];
$ID = ($_GET['ID']) ? (int)$_GET['ID'] : (int)$_POST['ID'];
$Type = ($_GET['Type']) ? (int)$_GET['Type'] : (int)$_POST['Type'];
$Seq = ($_GET['Seq']) ? (int)$_GET['Seq'] : (int)$_POST['Seq'];

if($mode == "remove") {
	$fUserID = $_POST['fUserID'];
	$query = "DELETE FROM DocFavoriteApproval WHERE fUserID = '$fUserID' AND UserID = '$userID'";
	mssql_query($query);
}

if($mode == "addLast") {
	$memID = ($_GET['memID']) ? $_GET['memID'] : $_POST['memID'];

	$query = "SELECT max(ApprovalUserSeq)+1 AS ApprovalUserSeq FROM ApprovalList WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	$query_result = mssql_query($query);
	$row = mssql_fetch_array($query_result);
	$ApprovalUserSeq = $row['ApprovalUserSeq'];

	$query = "INSERT INTO ApprovalList (DocID, DocType, DocSeq, ApprovalUserSeq, ApprovalUserID, ApprovalStatus) ".
			 "VALUES ($ID, $Type, $Seq, $ApprovalUserSeq, '$memID', 2)";
	mssql_query($query);
?>
	<script type="text/javascript">
		parent.location.href="<?=ABSOLUTE_PATH?>?page=e_doc&menu=receive&sub=view_wait&ID=<?=$ID; ?>&Type=<?=$Type; ?>&Seq=<?=$Seq; ?>";
	</script>

<?
}


$query = "SELECT fav.fUserID AS fUserID, fav.fUserName AS fUserName, comp.companyDesc AS fUserCompany, dept.deptName AS fUserDepartment, duty.dutyName AS fUserPosition FROM DocFavoriteApproval fav ".
		 "INNER JOIN Company AS comp ON fav.fUserCompany = comp.companyID ".
		 "INNER JOIN Department AS dept ON fav.fUserDepartment = dept.deptID AND fav.fUserCompany = dept.companyID ".
		 "INNER JOIN Duty AS duty ON fav.fUserPosition = duty.dutyID ".
		 "WHERE fav.UserID = '$userID' ".
		 "ORDER BY fav.fUserCompany ASC, fav.fUserPosition ASC";
$query_result = mssql_query($query);
$query_row = mssql_num_rows($query_result);
?>
<script>
	function add_to_doc(memID, memName, userID) {
		if(userID == memID) {
			alert("본인은 결재자에 포함될 수 없습니다.");
		} else {
			for(var i = 1; i < 10; i++) {
				if(parent.document.getElementById("appUser" + i).value == memID) {
					var existed = "ture";
					break;
				} else {
					var existed = "false";
				}
			}
			if(existed == "ture") {
				alert("이미 결재자에 등록되어 있는 직원입니다.");
			} else {
				for(var i = 1; i < 10; i++) {
					if(parent.document.getElementById("app" + i).innerHTML == "") {
						parent.document.getElementById("app" + i).innerHTML = "결재자" + i;
						parent.document.getElementById("appUser" + i).value = memID;
						parent.document.getElementById("appUserName" + i).innerHTML = memName + "<br>";

						var btn = parent.document.createElement("input");
						btn.type = "button";
						btn.value = "취소";
						btn.onclick = function() { parent.delete_from_doc(i); };
						parent.document.getElementById("appUserName" + i).appendChild(btn);

						break;
					}
				}
			}
		}
	}

	function add_to_last(ID, Type, Seq, memID, memName, userID) {
		if(userID == memID) {
			alert("본인은 이미 결재자에 포함되어 있습니다.");
		} else {
			for(var i = 1; i < 10; i++) {
				if(parent.document.getElementById("app" + i).innerHTML == memName) {
					var existed = "ture";
					break;
				} else {
					var existed = "false";
				}
			}
			if(existed == "ture") {
				alert("이미 결재자에 등록되어 있는 직원입니다.");
			} else {
				var answer = confirm(memName + "님을 최종결재자로 추가 하시겠습니까?");
				if(answer) {
					window.location.href = "?page=addLastUser&ID=" + ID + "&Type=" + Type + "&Seq=" + Seq + "&memID=" + memID + "&mode=addLast";
				}
			}
		}
	}
</script>

<link href="../css/style.css" rel="stylesheet" type="text/css" />

<table width="100%">
<?	if(empty($query_row)) { ?>
		<tr>
			<td align="center"><p class="warning"><b>등록된 사원이 없습니다.</b></p></td>
		</tr>
<?	} else {?>
		<tr height="340">
			<td align="center" style="padding:5px 10px 5px 10px; border:1px;" border="1" bordercolor="#c9c9c9">
				<table width="100%">
					<tr height="30" style="border-bottom:1px solid #CCC">
						<td width="20%" align="center" style="padding-top:8px;"><b>이름</b></td>
						<td width="20%" align="center" style="padding-top:8px;"><b>직급</b></td>
						<td width="20%" align="center" style="padding-top:8px;"><b>회사</b></td>
						<td width="20%" align="center" style="padding-top:8px;"><b>부서</b></td>
						<td width="17%" align="center" style="padding-top:8px;"></td>
						<td width="3%" align="center" style="padding-top:8px;"></td>
					</tr>
<?					while($row = mssql_fetch_array($query_result)) { ?>
						<tr height="20" style="border-bottom:1px dotted #CCC">
						<form name="addTo" action="?page=<?=$page; ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
							<input type="hidden" name="mode" value="remove"></input>
							<input type="hidden" name="fUserID" value="<?=$row['fUserID']; ?>"></input>
<?
							$fUserID = $row['fUserID'];
							$fUserName = Br_iconv($row['fUserName']);
?>
							<td align="center" style="padding-top:5px;"><?=Br_iconv($row['fUserName']); ?></td>
							<td align="center" style="padding-top:5px;"><?=Br_iconv($row['fUserPosition']); ?></td>
							<td align="center" style="padding-top:5px;"><?=Br_iconv($row['fUserCompany']); ?></td>
							<td align="center" style="padding-top:5px;"><?=Br_iconv($row['fUserDepartment']); ?></td>
<?							if($page == "fUserSearch") { ?>
								<td align="center"><input type="button" onClick="add_to_doc('<?=$fUserID; ?>', '<?=$fUserName; ?>', '<?=$userID; ?>')" value="지정"></input></td>
								<td align="center"><button type="submit">★</button></td>
<?							} else if($page == "faddLastUser") { ?>
								<td align="center"><input type="button" onClick="add_to_last(<?=$ID; ?>, <?=$Type; ?>, <?=$Seq; ?>, '<?=$fUserID; ?>', '<?=$fUserName; ?>', '<?=$userID; ?>')" value="추가"></input></td>
								<td align="center"><button type="submit">★</button></td>
<?							} ?>
						</form>
						</tr>
<?					} ?>
				</table>
			</td>
		</tr>
<?	} ?>
</table>
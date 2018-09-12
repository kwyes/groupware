<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$userID = $_SESSION['memberID'];
$ID = ($_GET['ID']) ? (int)$_GET['ID'] : (int)$_POST['ID'];
$Type = ($_GET['Type']) ? (int)$_GET['Type'] : (int)$_POST['Type'];
$Seq = ($_GET['Seq']) ? (int)$_GET['Seq'] : (int)$_POST['Seq'];

if($mode == "addToFav") {
	$userID = $_SESSION['memberID'];
	$memID = $_POST['memID'];
	$memName = $_POST['memName'];
	$memCompany = $_POST['memCompany'];
	$memDepartment = $_POST['memDepartment'];
	$memPosition = $_POST['memPosition'];

	$memName = Br_dconv($memName);

	$query = "INSERT INTO DocFavoriteApproval (UserID, fUserID, fUserName, fUserCompany, fUserDepartment, fUserPosition) ".
			 "VALUES ('$userID', '$memID', '$memName', '$memCompany', '$memDepartment', '$memPosition')";
	mssql_query($query);
}

if($mode == "search") {
	$userSearchKey = $_POST['userSearchKey'];
	$userSearchKey = Br_dconv($userSearchKey);

	$query = "SELECT mem.memID AS memID, mem.memName AS memName, comp.companyDesc AS companyID, dept.deptName AS deptID, duty.dutyName AS memPosition, mem.companyID AS NUMcompanyID, mem.deptID AS NUMdeptID, mem.memPosition AS NUMmemPosition ".
			 "FROM Member AS mem ".
			 "INNER JOIN Company AS comp ON mem.companyID = comp.companyID ".
			 "INNER JOIN Department AS dept ON mem.deptID = dept.deptID AND mem.companyID = dept.companyID ".
			 "INNER JOIN Duty AS duty ON mem.memPosition = duty.dutyID ".
			 "WHERE (mem.memName LIKE '%$userSearchKey%' OR comp.companyDesc LIKE '%$userSearchKey%' OR comp.companyName LIKE '%$userSearchKey%' OR dept.deptName LIKE '%$userSearchKey%' OR duty.dutyName LIKE '%$userSearchKey%') AND mem.memStatus = 1 ".
			 "ORDER BY mem.companyID ASC, mem.memName ASC";
	$query_result = mssql_query($query);
	$search_row = mssql_num_rows($query_result);
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
?>


<link href="../css/style.css" rel="stylesheet" type="text/css" />

<script>
	function add_to_fav(query_row, index, memID, userID) {
		if(memID == userID) {
			alert("본인은 즐겨찾기에 등록할 수 없습니다.");
		} else {
			if(query_row == 0) {
				document.forms[index].mode.value = "addToFav";
				document.forms[index].submit();
			} else {
				alert("이미 즐겨찾기에 등록되어 있는 직원입니다.");
			}
		}
	}

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

<table width="100%">
	<tr>
		<td style="padding:5px 20px 5px 20px;">
			<table width="100%">
				<tr>
					<form name="userSearch" action="?page=<?=$page; ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8" AUTOCOMPLETE="off">
<?					if($page == "addLastUser") { ?>
						<input type="hidden" name="ID" value="<?=$ID; ?>">
						<input type="hidden" name="Type" value="<?=$Type; ?>">
						<input type="hidden" name="Seq" value="<?=$Seq; ?>">
<?					} ?>
					<input type="hidden" name="mode" value="search"></input>
					<td><input type="text" name="userSearchKey"></td>
					<td><button type="submit">검색</button></td>
					</form>
				</tr>
			</table>
		</td>
	</tr>

<?	if(isset($search_row)) { ?>
<?		if($search_row == 0) { ?>
			<tr>
				<td align="center"><p class="warning"><b>검색된 사원이 없습니다.</b></p></td>
			</tr>
<?		} else {?>
			<tr height="340">
				<td align="center" style="padding:5px 10px 5px 10px; border:1px;" border="1" bordercolor="#c9c9c9">
					<table width="100%">
						<tr height="30" style="border-bottom:1px solid #CCC">
							<td width="20%" align="center" style="padding-top:8px;"><b>이름</b></td>
							<td width="20%" align="center" style="padding-top:8px;"><b>직급</b></td>
							<td width="20%" align="center" style="padding-top:8px;"><b>회사</b></td>
							<td width="22%" align="center" style="padding-top:8px;"><b>부서</b></td>
							<td width="17%" align="center" style="padding-top:8px;"></td>
							<td width="2%" align="center" style="padding-top:8px;"></td>
						</tr>
<?						$index = 1; ?>
<?						while($row = mssql_fetch_array($query_result)) { ?>
<?							if($row['memID'] != "admin") { ?>
								<tr height="20" style="border-bottom:1px dotted #CCC">
								<form name="addTo" action="?page=<?=$page; ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
									<input type="hidden" name="mode" value=""></input>
									<input type="hidden" name="memID" value="<?=$row['memID']; ?>"></input>
									<input type="hidden" name="memName" value="<?=Br_iconv($row['memName']); ?>"></input>
									<input type="hidden" name="memCompany" value="<?=$row['NUMcompanyID']; ?>"></input>
									<input type="hidden" name="memDepartment" value="<?=$row['NUMdeptID']; ?>"></input>
									<input type="hidden" name="memPosition" value="<?=$row['NUMmemPosition']; ?>"></input>

									<td align="center" style="padding-top:5px;"><?=Br_iconv($row['memName']); ?></td>
									<td align="center" style="padding-top:5px;"><?=Br_iconv($row['memPosition']); ?></td>
									<td align="center" style="padding-top:5px;"><?=Br_iconv($row['companyID']); ?></td>
									<td align="center" style="padding-top:5px;"><?=Br_iconv($row['deptID']); ?></td>
									
<?									// Check weather serached user is existed in DocFavoriteApproval
									$memID = $row['memID'];
									$memName = @iconv('euc-kr', 'utf-8', $row['memName']);
									$query2 = "SELECT TOP 1 fUserID FROM DocFavoriteApproval WHERE fUserID = '$memID' AND UserID = '$userID'";
									$query_result2 = mssql_query($query2);
									$query_row = mssql_num_rows($query_result2);
?>
<?									if($page == "userSearch") { ?>
										<td align="center"><input type="button" onClick="add_to_doc('<?=$memID; ?>', '<?=$memName; ?>', '<?=$userID; ?>')" value="지정"></input></td>
										<td align="center"><input type="button" onClick="add_to_fav('<?=$query_row; ?>', '<?=$index; ?>', '<?=$memID; ?>', '<?=$userID; ?>')" value="★"></input></td>
<?									} else if($page == "addLastUser") { ?>
										<td align="center"><input type="button" onClick="add_to_last(<?=$ID; ?>, <?=$Type; ?>, <?=$Seq; ?>, '<?=$memID; ?>', '<?=$memName; ?>', '<?=$userID; ?>')" value="추가"></input></td>
										<td align="center"><input type="button" onClick="add_to_fav('<?=$query_row; ?>', '<?=$index; ?>', '<?=$memID; ?>', '<?=$userID; ?>')" value="★"></input></td>
<?									} ?>
								</form>
								</tr>
<?								$index++; ?>
<?							} ?>
<?						} ?>
					</table>
				</td>
			</tr>
<?		}?>
<?	} ?>
</table>
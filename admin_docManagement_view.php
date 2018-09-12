<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];

if($mode == "modify") {
	$user_no = $_POST['user_no'];
	$user_status = $_POST['user_status'];
	$user_original_status = $_POST['user_original_status'];
	/*
	echo "ID = ".$ID."<br>";
	echo "Type = ".$Type."<br>";
	echo "Seq = ".$Seq."<br>";
	echo "user_no = ".$user_no."<br>";
	echo "user_status = ".$user_status."<br>";
	echo "user_original_status = ".$user_original_status."<br>";
	*/

	$query = "UPDATE E_DOC_Header SET ".
			 "Status = $user_status ".
			 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq ";
	mssql_query($query);

	if($Type == 1) {
		$query = "UPDATE Doc SET ".
				 "ApprovalStatus = $user_status, ".
				 "ApprovalDate = NULL ".
				 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ";
		mssql_query($query);
	} else if($Type == 2) {
		$query = "UPDATE Cooperation SET ".
				 "ApprovalStatus = $user_status, ".
				 "ApprovalDate = NULL ".
				 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ";
		mssql_query($query);
	} else if($Type == 3) {
		$query = "UPDATE Voucher SET ".
				 "ApprovalStatus = $user_status, ".
				 "ApprovalDate = NULL ".
				 "WHERE VoucherID = $ID AND VoucherType = $Type AND VoucherSeq = $Seq ";
		mssql_query($query);
	} else if($Type == 9) {
		$query = "UPDATE itemSpotCheck SET ".
				 "approvalStatus = $user_status, ".
				 "approvalDate = NULL ".
				 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND item_seq = 0 ";
		mssql_query($query);
	}

	$query = "UPDATE ApprovalList SET ".
			 "ApprovalStatus = $user_status, ".
			 "ApprovalDate = NULL ".
			 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq AND ApprovalUserSeq = $user_no AND ProcessSeq = 0 ";
	mssql_query($query);
}

$query = "SELECT ID, Type, Seq, UserID, Subject, Status ".
		 "FROM E_DOC_Header ".
		 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);
?>

<script>

function toggle_userStatus(mode, user_no, user_original_status) {
	if(mode == 'enable') {
		document.getElementById('userStatus_' + user_no).disabled = false;
		document.form_management.user_original_status.value = user_original_status;
		document.getElementById('changeStatus_' + user_no).style.display = "none";
		document.getElementById('cancelStatus_' + user_no).style.display = "";
	} else if(mode == 'disable') {
		document.getElementById('userStatus_' + user_no).value = user_original_status;
		document.form_management.user_original_status.value = "";
		document.getElementById('userStatus_' + user_no).disabled = true;
		document.getElementById('cancelStatus_' + user_no).style.display = "none";
		document.getElementById('changeStatus_' + user_no).style.display = "";
	}
}

function change_submit(reject_user_no) {
	if(document.form_management.user_original_status.value == "" || document.getElementById('userStatus_' + reject_user_no).value == 5) {
		alert('변경 사항이 없습니다.');
	} else {
		if(document.getElementById('userStatus_' + reject_user_no).value == 1) {
			alert('결재 완료로는 바꿀수 없습니다.');
		} else {
			var answer = confirm("변경 하시겠습니까?");
			if(answer) {
				document.form_management.user_no.value = reject_user_no;
				document.form_management.user_status.value = document.getElementById('userStatus_' + reject_user_no).value;
				document.forms.form_management.submit();
			}
		}
	}
}

/*
function check(total_num_user, doc_status) {
	var do_submit = false;
	for(var i = 1 ; i <= total_num_user ; i++) {
		if(document.getElementById('userStatus_' + i).disabled == false) {
			document.form_management.user_no.value = i;
			document.form_management.user_status.value = document.getElementById('userStatus_' + i).value;
			do_submit = true;
		}
	}

	if(do_submit == true) {
		var answer = confirm("변경 하시겠습니까?");
		if(answer) {
			document.form_management.doc_status.value = doc_status;
			document.forms.form_management.submit();
		}
	} else {
		alert("변경사항이 없습니다.");
	}
}

function change_docStatus(doc_original_status) {
	document.getElementById('docStatus').disabled = false;
}

function cancel_change_docStatus(doc_original_status) {
}


function change_userStatus(user_no, total_num_user, user_original_status, doc_status) {
	document.getElementById('userStatus_' + user_no).disabled = false;
	document.form_management.user_original_status.value = user_original_status;
	document.getElementById('cancelStatus_' + user_no).style.display = "";
}

function cancel_change_userStatus(user_no, total_num_user, user_original_status) {
	document.getElementById('userStatus_' + user_no).value = user_original_status;
	document.getElementById('userStatus_' + user_no).disabled = true;
	document.getElementById('cancelStatus_' + user_no).style.display = "none";
	document.form_management.user_original_status.value = "";
}
*/
</script>

<form name="form_management" action="?page=admin&menu=docManagement_view" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="ID" value="<?=$ID; ?>">
<input type="hidden" name="Type" value="<?=$Type; ?>">
<input type="hidden" name="Seq" value="<?=$Seq; ?>">
<input type="hidden" name="user_no" value="">
<input type="hidden" name="user_status" value="">
<input type="hidden" name="user_original_status" value="">
</form>
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- login_application TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">문서 상세정보 관리</td>
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
						<td width="150" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
						<td class="doc_field_content"><b><?=create_DocID($row['ID'], $row['Seq']); ?></b></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>문서종류</b></td>
						<td class="doc_field_content"><?=get_docName($row['Type']); ?></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>기안자</b></td>
						<td class="doc_field_content"><?=get_user_name($row['UserID']); ?></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>문서제목</b></td>
						<td class="doc_field_content"><?=Br_iconv($row['Subject']); ?></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
						<td class="doc_field_content"><?=get_doc_approval($row['Status']); ?></td>
					</tr>
					<tr>
						<td width="150" height="30" align="center" class="doc_field_name" colspan="2"><b>결재자 상태</b></td>
					</tr>
<?
					if($row['Type'] == 9) {
						$query3 = "SELECT ApprovalUserID, ApprovalStatus FROM ApprovalList WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq AND ApprovalUserSeq >= 2 AND ProcessSeq = 0 ORDER BY ApprovalUserSeq";
						$query_result3 = mssql_query($query3);
						$i = 2;
					} else {
						$query3 = "SELECT ApprovalUserID, ApprovalStatus FROM ApprovalList WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq AND ApprovalUserSeq >= 1 AND ProcessSeq = 0 ORDER BY ApprovalUserSeq";
						$query_result3 = mssql_query($query3);
						$i = 1;
					}
					$query2 = "SELECT StatusID, StatusDesc FROM ApprovalStatus WHERE StatusID NOT IN (3, 4, 6, 7, 8) ORDER BY StatusID";
					$query_result2 = mssql_query($query2);
?>
<?					while($row3 = mssql_fetch_array($query_result3)) { ?>
						<tr>
							<td width="150" height="30" align="center" class="doc_field_name"><b>결재자 <?=$i; ?> - <?=get_user_name($row3['ApprovalUserID']); ?></b></td>
							<td class="doc_field_content">
								<select name="<?='userStatus_'.$i; ?>" id="<?='userStatus_'.$i; ?>" style="width:200px;" disabled>
<?									$query_result2 = mssql_query($query2); ?>
<?									while($row2 = mssql_fetch_array($query_result2)) { ?>
										<option value="<?=$row2['StatusID']; ?>" <?=($row2['StatusID'] == $row3['ApprovalStatus']) ? selected : '' ?> ><?=Br_iconv($row2['StatusDesc']); ?></option>
<?									} ?>
								</select>
<?								if($row3['ApprovalStatus'] == 5) { ?>
<?									$reject_user_no = $i; ?>
									<input type="button" id="<?='changeStatus_'.$i; ?>" onClick="toggle_userStatus('enable', <?=$i; ?>, <?=$row3['ApprovalStatus']; ?>)" value="수정">
									<input type="button" id="<?='cancelStatus_'.$i; ?>" onClick="toggle_userStatus('disable', <?=$i; ?>, <?=$row3['ApprovalStatus']; ?>)" style="display:none; background-color:yellow;" value="취소">
<?								} ?>
							</td>
						</tr>
<?						$i++; ?>
<?					} ?>
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
									<td><input type="button" class="doc_submit_btn_style" onClick="change_submit(<?=$reject_user_no; ?>)" value="저장하기"></td>
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
				</tr>
			</table>
		</td>	
	</tr>
</table>

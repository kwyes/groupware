<?
$mode = $_POST['mode'];

if($mode == "search") {
	$userID = $_POST['search_userID'];
	$userName = Br_dconv($_POST['search_userName']);
	$userComp = $_POST['search_userComp'];

	$query = "SELECT memID, memName, companyID, deptID, memPosition, memLevel, memStatus ".
			 "FROM Member ".
			 "WHERE memID LIKE '%$userID%' AND memName LIKE '%$userName%' AND companyID LIKE '%$userComp%'";

} else {
	$query = "SELECT memID, memName, companyID, deptID, memPosition, memLevel, memStatus ".
			 "FROM Member ";
			 //"WHERE memStatus = 1 OR memStatus = 3";
}

$query_result = mssql_query($query);
$query_row = mssql_num_rows($query_result);
?>

<script>
function post_to_url(path, params, method) {
    method = method || "post";

	var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }
    document.body.appendChild(form);
    form.submit();
}
</script>

<td width="" align="left" valign="top">
	<table width="100%">
		<!-- admin_userRegistration TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">멤버관리</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- admin_userRegistration TITLE END -->

		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<!-- admin_userRegistration SEARCH BOX START -->
		<form name="form_search" action="?page=admin&menu=userManagement" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="mode" value="search">
		<tr>
			<td style="padding:12px 25px">
				<table width="100%" class="doc_border">
					<tr height="30">
						<td width="80" align="center" class="doc_field_name"><b>아이디</b></td>
						<td width="200" style="padding: 3px 0 0 10px;"><input name="search_userID" type="text" style="width:184px; font-size:12px;""></input></td>
						<td width="80" align="center" class="doc_field_name"><b>이름</b></td>
						<td width="200" style="padding: 3px 0 0 10px; border-right: 0;"><input name="search_userName" type="text" style="width:184px; font-size:12px;""></input></td>
						<td style="border-left: 0;"></td>
					</tr>
					<tr height="30">
						<td width="80" align="center" class="doc_field_name"><b>소속회사</b></td>
						<td width="200" style="padding: 3px 0 0 10px;">
							<select name="search_userComp" style="width:187px;">
<?								$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
								$row2 = mssql_query($query);
?>
								<option value=""> -- 전체 -- </option>
<?								while($rst = mssql_fetch_array($row2)) { ?>
									<option value='<?=$rst['companyID']; ?>'><?=$rst['companyDesc']; ?></option>
<?								} ?>
							</select>
						</td>
						<td colspan="2" align="right" style="border-right: 0; padding:3px 10px 0 0;"><button>검색</button></td>
						<td style="border-left: 0;"></td>
					</tr>
				</table>				
			</td>
		</tr>
		</form>
		<!-- admin_userRegistration SEARCH BOX END -->

		<tr>
			<td>
				<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
					<tr height="20">
						<td width="120" class="title bb br">아이디</td>
						<td width="100" class="title bb br">이름</td>
						<td width="240" class="title bb br">소속회사</td>
						<td width="100" class="title bb br">소속부서</td>
						<td width="80" class="title bb br">직급</td>
						<td width="80" class="title bb br">계정등급</td>
						<td width="" class="title bb br">계정상태</td>
					</tr>

<?					if($query_row == 0) { ?>
						<tr height="60">
							<td align="center" class="bb" colspan="7" style="padding-top:25px;">
								<b>신청서가 없습니다</b>
							</td>
						</tr>
<?					} else {?>
<?						while($row = mssql_fetch_array($query_result)) { ?>
							<tr height="25">
								<td class="docid bb"><a href="javascript:post_to_url('?page=admin&menu=userManagement_view', {'memID':'<?=$row['memID']?>'});"><?=$row['memID']; ?></a></td>
								<td class="content bb"><?=Br_iconv($row['memName']); ?></td>
								<td class="content bb"><?=get_company_name($row['companyID']); ?></td>
								<td class="content bb"><?=Br_iconv(get_dept_name($row['companyID'], $row['deptID'])); ?></td>
								<td class="content bb"><?=Br_iconv(get_duty($row['memPosition'])); ?></td>
								<td class="content bb"><?=$row['memLevel']; ?></td>
								<td class="content bb"><?=$row['memStatus']; ?></td>
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
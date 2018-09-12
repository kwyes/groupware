<?
$UserID = $_SESSION['memberID'];
$today = date("Y-m-d");
$Status = 2;

/*
$query = "SELECT TOP 7 ID, Type, Seq, Status, CompanyID, UserID, CONVERT(char(10), SubmitDate, 126) AS SubmitDate, Subject, RegDate ".
		 "FROM E_DOC_Header ".
		 "WHERE convert(char(10), SubmitDate, 126) <= '$today' AND Status = $Status ".
		 "ORDER BY RegDate DESC";
*/
$query = "SELECT TOP 7 head.ID AS ID, head.Type AS Type, head.Seq AS Seq, head.Status AS Status, head.CompanyID AS CompanyID, head.UserID AS UserID, CONVERT(char(10), head.SubmitDate, 126) AS SubmitDate, head.Subject AS Subject, head.RegDate AS RegDate ".
			 "FROM E_DOC_Header AS head ".
			 "INNER JOIN ApprovalList AS app ON app.DocID = head.ID AND app.DocType = head.Type AND app.DocSeq = head.Seq AND (app.ApprovalStatus = $Status) AND app.ApprovalUserID = '$UserID' ".
			 "WHERE convert(char(10), head.SubmitDate, 126) <= '$today' AND head.Status = $Status ".
			 "ORDER BY head.RegDate DESC";
$query_result = mssql_query($query);
$query_row = mssql_num_rows($query_result);
?>

<script>
	function post_to_url(path, params) {
		method = "POST";

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

<tr>
	<td>
		<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
			<tr height="20">
				<td width="100" align="left" class="title bb br">문서번호</td>
				<td width="10%" align="left" class="title bb br">문서종류</td>
				<td align="left" class="title bb br">제목</td>
				<td width="10%" align="left" class="title bb br">문서상태</td>
				<td width="20%" align="left" class="title bb br">실행회사</td>
				<td width="10%" align="left" class="title bb br">작성자</td>
				<td width="10%" align="left" class="title bb br">작성일자</td>
			</tr>
<?			$is_appUserExisted = FALSE; ?>
<?			while($row = mssql_fetch_array($query_result)) { ?>
<?				$ID = create_DocID($row['ID'], $row['Seq']); ?>
<?				$AppUserCheck = check_ApprovalUser_wait($row['Type'], $row['ID'], $row['Seq'], $UserID); ?>
<?				$is_read = check_is_read($row['Type'], $row['ID'], $row['Seq'], $UserID); ?>

<?				if($AppUserCheck == $_SESSION['memberID']) { ?>
<?					$is_appUserExisted = TRUE; ?>
					<tr height="25" style="<?=($is_read == 0) ? "background-color:yellow" : ""; ?>">
						<td class="docid bb"><a href="javascript:post_to_url('?page=e_doc&menu=receive&sub=view_wait', {'ID':<?=$row['ID']?>, 'Seq':<?=$row['Seq']?>, 'Type':<?=$row['Type']?>});"><?=$ID; ?></a></td>
						<td class="content bb"><?=get_docName($row['Type']); ?></td>
						<td align="left" class="content bb"><a href="javascript:post_to_url('?page=e_doc&menu=receive&sub=view_wait', {'ID':<?=$row['ID']?>, 'Seq':<?=$row['Seq']?>, 'Type':<?=$row['Type']?>});"><?=Br_iconv($row['Subject']); ?></a></td>
						<td class="content bb" style="color:#088A08;"><?=get_doc_approval($row['Status']); ?></td>
						<td class="content bb"><?=get_company_name($row['CompanyID']); ?></td>
						<td class="content bb"><?=get_user_name($row['UserID']); ?></td>
						<td class="date bb"><?=$row['SubmitDate']; ?></td>
					</tr>
<?				} ?>
<?			} ?>
<?			if($is_appUserExisted == FALSE) { ?>
				<tr height="60">
					<td align="center" class="bb" colspan="7" style="padding-top:25px;">
						<b>조회된 문서가 없습니다</b>
					</td>
				</tr>
<?			} ?>
		</table>
	</td>
</tr>

<div id="div_boardlist_loading" style="display:none; padding-top:10px; text-align:center;">
	<img src="<?=SYSTEM_PATH?>/images_site/ajax-loader.gif"><span style="font-family:verdana;">데이터 가져오는 중..</span>
</div>
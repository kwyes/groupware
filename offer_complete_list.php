<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$list = ($_GET['list']) ? $_GET['list'] : $_POST['list'];
$folderNum = ($_GET['folderNum']) ? $_GET['folderNum'] : $_POST['folderNum'];
$UserID = $_SESSION['memberID'];
$today = date("Y-m-d");
$Status = 1;	// 결재완료

if($mode == "search") {
	$docType = $_POST['search_docType'];
	$dateStart = $_POST['search_dateStart'];
	$dateEnd = (empty($_POST['search_dateEnd']) ? $today : $_POST['search_dateEnd']);
	$submitUser = Br_dconv($_POST['search_submitUser']);
	$docSubject = Br_dconv($_POST['search_docSubject']);
	$docCompany = $_POST['search_docCompany'];

	if($folderNum) {
		$folderLocation = 2;
		$query = "SELECT head.ID AS ID, head.Type AS Type, head.Seq AS Seq, head.Status AS Status, head.CompanyID AS CompanyID, mem.memID AS UserID, ".
				 "CONVERT(char(10), head.SubmitDate, 126) AS SubmitDate, head.Subject AS Subject, head.RegDate AS RegDate ".
				 "FROM E_DOC_Header AS head ".
				 "INNER JOIN Member AS mem ON mem.memName LIKE '%$submitUser%' AND mem.memID = '$UserID' AND head.UserID = mem.memID ".
				 "INNER JOIN PersonalFolder AS pf ON pf.UserID = '$UserID' AND pf.FolderLocation = $folderLocation AND pf.FolderSeq = $folderNum AND pf.DocID = head.ID AND pf.DocType = head.Type AND pf.DocSeq = head.Seq ".
				 "WHERE (CONVERT(char(10), head.SubmitDate, 126) BETWEEN '$dateStart' AND '$dateEnd') AND (head.Status = $Status) AND (head.Type LIKE '%$docType%') ".
				 "AND (head.Subject LIKE '%$docSubject%') AND (head.CompanyID LIKE '%$docCompany%') ".
				 "ORDER BY pf.RegDate DESC";

	} else {
		$query = "SELECT head.ID AS ID, head.Type AS Type, head.Seq AS Seq, head.Status AS Status, head.CompanyID AS CompanyID, mem.memID AS UserID, ".
				 "CONVERT(char(10), head.SubmitDate, 126) AS SubmitDate, head.Subject AS Subject, head.RegDate AS RegDate ".
				 "FROM E_DOC_Header AS head ".
				 "INNER JOIN Member AS mem ON mem.memName LIKE '%$submitUser%' AND mem.memID = '$UserID' AND head.UserID = mem.memID ".
				 "WHERE (CONVERT(char(10), head.SubmitDate, 126) BETWEEN '$dateStart' AND '$dateEnd') AND (head.Status = $Status) AND (head.Type LIKE '%$docType%') ".
				 "AND (head.Subject LIKE '%$docSubject%') AND (head.CompanyID LIKE '%$docCompany%') ".
				 "ORDER BY head.RegDate DESC";
	}

} else {
	/*
	$query = "SELECT head.ID AS ID, head.Type AS Type, head.Seq AS Seq, head.Status AS Status, head.CompanyID AS CompanyID, head.UserID AS UserID, CONVERT(char(10), head.SubmitDate, 126) AS SubmitDate, head.Subject AS Subject, head.RegDate AS RegDate ".
			 "FROM E_DOC_Header AS head ".
			 "INNER JOIN Doc AS doc ON head.ID = doc.DocID AND head.Seq = doc.DocSeq ".
			 "WHERE head.UserID = '$UserID' AND convert(char(10), head.SubmitDate, 126) <= '$today' AND (head.Status = $Status) ".
			 "ORDER BY doc.ApprovalDate DESC";
	*/
	if($folderNum) {
		$folderLocation = 2;
		$query = "SELECT head.ID AS ID, head.Type AS Type, head.Seq AS Seq, head.Status AS Status, head.CompanyID AS CompanyID, head.UserID AS UserID, CONVERT(char(10), head.SubmitDate, 126) AS SubmitDate, head.Subject AS Subject, head.RegDate AS RegDate ".
				 "FROM E_DOC_Header AS head ".
				 "INNER JOIN PersonalFolder AS pf ON pf.UserID = '$UserID' AND pf.FolderLocation = $folderLocation AND pf.FolderSeq = $folderNum AND pf.DocID = head.ID AND pf.DocType = head.Type AND pf.DocSeq = head.Seq ".
				 "WHERE head.UserID = '$UserID' AND convert(char(10), head.SubmitDate, 126) <= '$today' AND (head.Status = $Status) ".
				 "ORDER BY pf.RegDate DESC";
	} else {
		$query = "SELECT ID, Type, Seq, Status, CompanyID, UserID, CONVERT(char(10), SubmitDate, 126) AS SubmitDate, Subject, RegDate ".
				 "FROM E_DOC_Header ".
				 "WHERE UserID = '$UserID' AND convert(char(10), SubmitDate, 126) <= '$today' AND (Status = $Status) ".
				 "ORDER BY RegDate DESC";
	}
}
$query_result = mssql_query($query);
$query_row = mssql_num_rows($query_result);

// page navigation
if(!isset($list)) {
	$list = 1;
}
$total_row = $query_row;
$page_per_list = 15;
$page_total = ceil($total_row/$page_per_list);
if($total_row > 0) {
	mssql_data_seek($query_result, ($list-1)*$page_per_list);
}
?>

<script>
function post_to_url(path, params) {
    method = "post";

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

function page_navigation(mode, list) {
	if(mode == "search") {
		document.forms.form_search.list.value = list;
	} else {
		document.forms.form_search.list.value = list;
		document.forms.form_search.mode.value = "";
	}
	document.forms.form_search.submit();
}
</script>

<!-- offer_complete_list START -->
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- offer_complete_list TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">올린 결재 문서함 > 상신문서</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- offer_complete_list TITLE END -->

		<!-- offer_complete_list MAIN START -->
		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<!-- offer_complete_list SEARCH BOX START -->
		<form name="form_search" action="<?=($folderNum) ? '?page=e_doc&menu=offer&sub=offer_folder' : '?page=e_doc&menu=offer&sub=complete' ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="mode" value="search">
		<input type="hidden" name="list" value=1>
<?		if($folderNum) { ?>
			<input type="hidden" name="showOffer" value=<?=$showOffer; ?>>
			<input type="hidden" name="folderNum" value=<?=$folderNum; ?>>
<?		} ?>
		<tr>
			<td style="padding:12px 25px">
				<table width="100%" class="doc_border">
					<tr height="30">
						<td width="80" align="center" class="doc_field_name"><b>문서종류</b></td>
						<td width="200" style="padding: 3px 0 0 10px;">
							<select name="search_docType" style="width:187px;">
<?								$query = "SELECT docID, docName FROM DocKind ORDER BY docID";
								$row2 = mssql_query($query);
?>
								<option value=""> -- 전체 -- </option>
<?								while($rst = mssql_fetch_array($row2)) { ?>
									<option value='<?=$rst['docID']; ?>' <?if($rst['docID'] == $docType) echo "selected"; ?>><?=Br_iconv($rst['docName']); ?></option>
<?								} ?>
							</select>
						</td>
						<td width="80" align="center" class="doc_field_name"><b>기안일</b></td>
						<td width="300" style="padding: 3px 0 0 10px; border-right: 0;">
							<input name="search_dateStart" id="search_dateStart" type="text" style="font-size:12px;" value="<?=$dateStart; ?>"></input>
							<span style="padding-top: 10px;">&nbsp;~&nbsp;<span>
							<input name="search_dateEnd" id="search_dateEnd" type="text" style="font-size:12px;" value="<?=(empty($_POST['search_dateEnd']) ? "" : $_POST['search_dateEnd']); ?>"></input>
						</td>
						<td style="border-left: 0;"></td>
					</tr>
					<tr height="30">
						<td width="80" align="center" class="doc_field_name"><b>실행회사</b></td>
						<td width="200" style="padding: 3px 0 0 10px;">
							<select name="search_docCompany" style="width:187px;">
<?								$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
								$row2 = mssql_query($query);
?>
								<option value=""> -- 전체 -- </option>
<?								while($rst = mssql_fetch_array($row2)) { ?>
									<option value='<?=$rst['companyID']; ?>' <?if($rst['companyID'] == $docCompany) echo "selected"; ?>><?=$rst['companyDesc']; ?></option>
<?								} ?>
							</select>
						</td>
						<td width="80" align="center" class="doc_field_name"><b>제목</b></td>
						<td style="padding: 3px 0 0 11px; border-right: 0;"><input name="search_docSubject" type="text" style="width:300px; font-size:12px;" value="<?=$_POST['search_docSubject']; ?>"></input></td>
						<td style="border-left: 0;"></td>
					</tr>
					<tr height="30">
						<td width="80" align="center" class="doc_field_name"><b>기안자</b></td>
						<td width="200" style="padding: 3px 0 0 10px;"><input name="search_submitUser" type="text" style="width:184px; font-size:12px;" value="<?=$_POST['search_submitUser']; ?>"></input></td>
						<td colspan="2" align="right" style="border-right: 0; padding-top: 3px;"><button>검색</button></td>
						<td style="border-left: 0;"></td>
					</tr>
				</table>				
			</td>
		</tr>
		</form>
		<!-- offer_complete_list SEARCH BOX END -->

		<!-- offer_complete_list LIST START -->
		<tr>
			<td>
				<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
					<tr height="20">
						<td width="100" class="title bb br">문서번호</td>
						<td width="120" class="title bb br">문서종류</td>
						<td width="" class="title bb br">제목</td>
						<td width="100" class="title bb br">문서상태</td>
						<td width="200" class="title bb br">실행회사</td>
						<td width="80" class="title bb br">기안자</td>
						<td width="80" class="title bb">기안일</td>
					</tr>
<?					if($query_row == 0) { ?>
						<tr height="60">
							<td align="center" class="bb" colspan="7" style="padding-top:25px;">
								<b>조회된 문서가 없습니다</b>
							</td>
						</tr>
<?					} else { ?>
<?						$i = 1; ?>
<?						while($row = mssql_fetch_array($query_result)) { ?>

							<tr height="25">
								<td class="docid bb">
<?									if($folderNum) { ?>
										<a href="javascript:post_to_url('?page=e_doc&menu=offer&sub=view_folder&showOffer=<?=$showOffer; ?>&folderNum=<?=$folderNum; ?>', {'ID':<?=$row['ID']?>, 'Seq':<?=$row['Seq']?>, 'Type':<?=$row['Type']?>});"><?=create_DocID($row['ID'], $row['Seq']); ?></a>
<?									} else { ?>
										<a href="javascript:post_to_url('?page=e_doc&menu=offer&sub=view_complete', {'ID':'<?=$row['ID']?>', 'Seq':'<?=$row['Seq']?>','Type':'<?=$row['Type']?>'});"><?=create_DocID($row['ID'], $row['Seq']); ?></a>
<?									} ?>				
								</td>
								<td class="content bb"><?=get_docName($row['Type']); ?></td>
								<td class="content bb">
<?									if($folderNum) { ?>
										<a href="javascript:post_to_url('?page=e_doc&menu=offer&sub=view_folder&showOffer=<?=$showOffer; ?>&folderNum=<?=$folderNum; ?>', {'ID':<?=$row['ID']?>, 'Seq':<?=$row['Seq']?>, 'Type':<?=$row['Type']?>});"><?=Br_iconv($row['Subject']); ?></a>
<?									} else { ?>
										<a href="javascript:post_to_url('?page=e_doc&menu=offer&sub=view_complete', {'ID':'<?=$row['ID']?>', 'Seq':'<?=$row['Seq']?>','Type':'<?=$row['Type']?>'});"><?=Br_iconv($row['Subject']); ?></a>
<?									} ?>
								</td>
								<td class="content bb" style="color:#0000FF;"><?=get_doc_approval($row['Status']); ?></td>
								<td class="content bb"><?=get_company_name($row['CompanyID']); ?></td>
								<td class="content bb"><?=get_user_name($row['UserID']); ?></td>
								<td class="date bb"><?=$row['SubmitDate']; ?></td>
							</tr>
<?							if($i++ == $page_per_list) {
								break;
							} ?>
<?						} ?>
<?					} ?>
				</table>
			</td>
		</tr>
		
		<tr>
			<td height="30"></td>
		</tr>

		<!-- offer_complete_list PAGE NAVIGATION START -->
		<tr>
			<td align="center">
<?
				for($i = 1+(floor(($list-1)/10)*10) ; $i <= $page_total ; $i++) {
					if(($i-1)%10 == '0' && $i != '1') {
						$temp_page = floor(($list-1)/10)*10;
						if($mode == "search") {
							echo "&nbsp<a href='javascript:page_navigation(\"$mode\", $temp_page)'>◀</a> \n";
						} else {
							echo "&nbsp<a href='javascript:page_navigation(\"$mode\", $temp_page)'>◀</a> \n";
						}
					}
					if($i == 1+(floor(($list-1)/10)*10)) {
						echo "&nbsp<font color='#A4A4A4'> | </font>&nbsp";
					}
					if($i == $list) {
						if($mode == "search") {
							echo "<b><a style='color:red; text-decoration:underline;' href='javascript:page_navigation(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						} else {
							echo "<b><a style='color:red; text-decoration:underline;' href='javascript:page_navigation(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						}
					} else {
						if($mode == "search") {
							echo "<b><a href='javascript:page_navigation(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						} else {
							echo "<b><a href='javascript:page_navigation(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						}
					}
					if($i%10 == '0') {
						$i++;
						if($mode == "search") {
							echo "<a href='javascript:page_navigation(\"$mode\", $i)'>▶</a> \n";
						} else {
							echo "<a href='javascript:page_navigation(\"$mode\", $i)'>▶</a> \n";
						}
						break;
					}
				}	
?>
			</td>
		</tr>
		<!-- offer_complete_list PAGE NAVIGATION END -->

		<tr>
			<td height="30"></td>
		</tr>
		<!-- offer_complete_list LIST END -->
		<!-- offer_complete_list MAIN END -->
	</table>
</td>
<!-- offer_complete_list END -->
				</tr>
			</table>
		</td>	
	</tr>
</table>
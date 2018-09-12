<?
include_once "includes/general.php";

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$userID = $_SESSION['memberID'];

if($mode == "search") {
	$docSearchKey = Br_dconv($_POST['docSearchKey']);
	$key = explode('-', $docSearchKey);
	$key1 = $key[0];
	if($key[1] < 10) {
		$key2 = $key[1][1];
	} else {
		$key2 = $key[1];
	}

	if(is_numeric($key[0]) && is_numeric($key[1]) && strlen($key[0]) == 8 && strlen($key[1]) == 2) {
		$query = "SELECT head.ID AS ID, head.Type AS Type, head.Seq AS Seq, doc.Subject AS Subject ".
				 "FROM E_DOC_Header AS head ".
				 "INNER JOIN Doc As doc ON doc.DocID = head.ID AND doc.DocSeq = head.Seq AND doc.DocType = head.Type ".
				 "WHERE head.Status = 1 AND head.ID = $key1 AND head.seq = $key2 ".
				 "ORDER BY head.RegDate DESC";
	} else {
		$query = "SELECT head.ID AS ID, head.Type AS Type, head.Seq AS Seq, doc.Subject AS Subject ".
				 "FROM E_DOC_Header AS head ".
				 "INNER JOIN Doc As doc ON doc.DocID = head.ID AND doc.DocSeq = head.Seq AND doc.DocType = head.Type ".
				 "WHERE head.UserID = '$userID' AND head.Status = 1 AND (head.ID LIKE '%$docSearchKey%' OR doc.Subject LIKE '%$docSearchKey%') ".
				 "ORDER BY head.RegDate DESC";
	}
} else {
	$query = "SELECT head.ID AS ID, head.Type AS Type, head.Seq AS Seq, doc.Subject AS Subject ".
			 "FROM E_DOC_Header AS head ".
			 "INNER JOIN Doc As doc ON doc.DocID = head.ID AND doc.DocSeq = head.Seq AND doc.DocType = head.Type ".
			 "WHERE head.UserID = '$userID' AND head.Status = 1 ".
			 "ORDER BY head.RegDate DESC";
}
$query_result = mssql_query($query);
$search_row = mssql_num_rows($query_result);
?>

<link href="css/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" charset="utf-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript">
function addLink_to_doc(ID, Seq, Type) {
	var id = ID + "_" + Seq;
	if(Seq < 10) {
		var content = ID + "-0" + Seq;
	} else {
		var content = ID + "-" + Seq;
	}

	if(parent.document.getElementById(id) == undefined) {
		var answer = true;
	} else {
		alert("이미 등록된 참조문서입니다.");
		var answer = false;
	}

	if(answer) {
		var link_doc = parent.document.createElement("a");
		link_doc.innerHTML = content;
		link_doc.style.color = "#2E9AFE";
		link_doc.style.fontSize = "15";
		link_doc.style.fontWeight = "bold";
		link_doc.href = "javascript:preview_doc1(" + ID + "," + Seq + "," + Type + ");";
		
		var image = parent.document.createElement("img");
		image.style.marginTop = "3";
		image.src = "../css/img/bt_del.gif";

		var link_img = parent.document.createElement("a");
		link_img.href = "javascript:delete_linkedDoc(" + ID + "," + Seq + ");";
		link_img.appendChild(image);

		var link_div = parent.document.createElement("div");
		link_div.id = id;
		link_div.style.paddingTop = "5";
		link_div.style.paddingLeft = "30";
		link_div.style.display = "inline-block";
		link_div.appendChild(link_doc);
		link_div.appendChild(link_img);
		parent.document.getElementById("doc_link").appendChild(link_div);

		var hidden = parent.document.createElement("input");
		hidden.id = "linkList_" + id;
		hidden.name = "doc_linkList[]";
		hidden.type = "hidden";
		hidden.value = ID + "_" + Seq + "_" + Type;
		parent.document.forms.form_proposal.appendChild(hidden);

		/*
		var hidden = parent.document.createElement("input");
		hidden.id = "link_doc_id";
		hidden.name = "link_doc_id[]";
		hidden.type = "hidden";
		hidden.value = ID;
		parent.document.forms.form_proposal.appendChild(hidden);

		var hidden = parent.document.createElement("input");
		hidden.id = "link_doc_seq";
		hidden.name = "link_doc_seq[]";
		hidden.type = "hidden";
		hidden.value = Seq;
		parent.document.forms.form_proposal.appendChild(hidden);

		var hidden = parent.document.createElement("input");
		hidden.id = "link_doc_type";
		hidden.name = "link_doc_type[]";
		hidden.type = "hidden";
		hidden.value = Type;
		parent.document.forms.form_proposal.appendChild(hidden);
		*/
	}
}

function preview_doc(ID, Seq, Type, mode) {
	var popUrl = "print_preview_proposal.php?ID="+ID+"&Type="+Type+"&Seq="+Seq+"&mode="+mode;
	var popOption = "width=800, height=600, toolbar=0, location=0, directories=0, resizable=1, menubar=0, scrollbars=yes, status=no";

	window.open(popUrl,"",popOption);
}
</script>


<table width="100%">
	<tr>
		<td style="padding:5px 20px 5px 20px;">
			<table width="100%">
				<tr>
					<form name="docSearch" action="iframe_docLink.php" method="post" accept-charset="utf-8" AUTOCOMPLETE="off">
						<input type="hidden" name="mode" value="search"></input>
						<td><input type="text" name="docSearchKey" style="width:95%;"></td>
						<td><button type="submit">검색</button></td>
					</form>
				</tr>
			</table>
		</td>
	</tr>

<?	if(isset($search_row)) { ?>
<?		if($search_row == 0) { ?>
			<tr>
				<td align="center"><p class="warning"><b>검색된 문서가 없습니다.</b></p></td>
			</tr>
<?		} else {?>
			<tr height="340">
				<td align="center" style="padding:5px 10px 5px 10px; border:1px;" border="1" bordercolor="#c9c9c9">
					<table width="100%" class="doc_main_table">
						<tr height="30" style="border-bottom:1px solid #CCC">
							<td width="20%" align="center" style="padding-top:8px;"><b>문서번호</b></td>
							<td width="" align="center" style="padding-top:8px;"><b>제목</b></td>
							<td width="20%" align="center" style="padding-top:8px;"><b></td>
						</tr>
<?							while($row = mssql_fetch_array($query_result)) { ?>
								<tr height="20" style="border-bottom:1px dotted #CCC">
								<form name="addTo" action="iframe_docLink.php" method="post" accept-charset="utf-8">
									<td align="center" style="padding-top:5px;" class="docid"><a href="javascript:preview_doc(<?=$row['ID']?>, <?=$row['Seq']?>, <?=$row['Type']?>, 'preview')"><?=create_DocID($row['ID'], $row['Seq']); ?></a></td>
									<td align="center" style="padding-top:5px;" class="content"><?=Br_iconv($row['Subject']); ?></td>
									<td align="center" style="padding-top:5px;" class="content"><button onClick="addLink_to_doc(<?=$row['ID']?>, <?=$row['Seq']?>, <?=$row['Type']?>)">선택</button></td>
								</form>
								</tr>
<?							} ?>
					</table>
				</td>
			</tr>
<?		}?>
<?	} ?>
</table>
<?
include_once "includes/general.php";

$query = "SELECT companyID, deptName, deptID FROM Department WHERE NOT (DeptID = 9) ORDER BY companyID ASC";		// 각 회사 본부는 제외되 있음
$query_result = mssql_query($query);
while($row = mssql_fetch_array($query_result)) {
	if($row['companyID'] == 1) {
		$TB_dept[] = Br_iconv($row['deptName']);
		$TB_deptID[] = $row['deptID'];
	} else if($row['companyID'] == 2) {
		$MN_dept[] = Br_iconv($row['deptName']);
		$MN_deptID[] = $row['deptID'];
	} else if($row['companyID'] == 3) {
		$BBY_dept[] = Br_iconv($row['deptName']);
		$BBY_deptID[] = $row['deptID'];
	} else if($row['companyID'] == 4) {
		$SRY_dept[] = Br_iconv($row['deptName']);
		$SRY_deptID[] = $row['deptID'];
	} else if($row['companyID'] == 5) {
		$WVI_dept[] = Br_iconv($row['deptName']);
		$WVI_deptID[] = $row['deptID'];
	}
}

$TB_dept_str = implode(",", $TB_dept);
$MN_dept_str = implode(",", $MN_dept);
$BBY_dept_str = implode(",", $BBY_dept);
$SRY_dept_str = implode(",", $SRY_dept);
$WVI_dept_str = implode(",", $WVI_dept);
?>
<script type="text/javascript"  charset="utf-8" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script>
function open_dept(i) {
	$("#dept" + i).toggle();
	if($("#dept"+i+"_icon1").attr("src") == "../css/img/tree_plus.gif") {
		$("#dept"+i+"_icon1").attr("src", "../css/img/tree_minus.gif");
		$("#dept"+i+"_icon2").attr("src", "../css/img/tree_folder_open.gif");
	} else if($("#dept"+i+"_icon1").attr("src") == "../css/img/tree_minus.gif") {
		$("#dept"+i+"_icon1").attr("src", "../css/img/tree_plus.gif");
		$("#dept"+i+"_icon2").attr("src", "../css/img/tree_folder_close.gif");
	}
}

function add_to_doc(companyID, departmentID, department) {
	if(companyID == 1) {
		var company = "TB";
	} else if(companyID == 2) {
		var company = "MN";
	} else if(companyID == 3) {
		var company = "BBY";
	} else if(companyID == 4) {
		var company = "SRY";
	} else if(companyID == 5) {
		var company = "WVI";
	}

	department = department.split(",");
	count_company = department.length;

	if(departmentID == 0) {
		for(var i = 1; i < count_company; i++) {
			var id = companyID + "_" + i;

			if((typeof(parent.document.getElementById(id)) != 'undefined' && parent.document.getElementById(id) != null)) {
			} else {
				var image = parent.document.createElement("img");
				if(i == 1)	image.onclick = function() { parent.delete_coopList(companyID, 1); };
				else if(i == 2)	image.onclick = function() { parent.delete_coopList(companyID, 2); };
				else if(i == 3)	image.onclick = function() { parent.delete_coopList(companyID, 3); };
				else if(i == 4)	image.onclick = function() { parent.delete_coopList(companyID, 4); };
				else if(i == 5)	image.onclick = function() { parent.delete_coopList(companyID, 5); };
				else if(i == 6)	image.onclick = function() { parent.delete_coopList(companyID, 6); };
				else if(i == 7)	image.onclick = function() { parent.delete_coopList(companyID, 7); };
				image.src = "../css/img/bt_del.gif";
				image.style.cursor = "pointer";

				var element = parent.document.createElement("div");
				element.id = id;
				element.innerHTML = company + " - " + department[i];
				element.style.paddingLeft = "5";
				element.style.marginTop = "3";
				element.appendChild(image);
				parent.document.getElementById("coopAdded").appendChild(element);

				var hidden = parent.document.createElement("input");
				hidden.id = "coopList_" + id;
				hidden.type = "hidden";
				hidden.value = id;
				hidden.name = "doc_coopList[]";
				var coopForm = parent.document.forms.form_proposal;
				coopForm.appendChild(hidden);
			}
		}
	} else {
		var id = companyID + "_" + departmentID;

		if((typeof(parent.document.getElementById(id)) != 'undefined' && parent.document.getElementById(id) != null)) {
			alert("이미 추가된 부서입니다.");
		} else {
			var image = parent.document.createElement("img");
			image.onclick = function() { parent.delete_coopList(companyID, departmentID); };
			image.src = "../css/img/bt_del.gif";
			image.style.cursor = "pointer";

			var element = parent.document.createElement("div");
			element.id = id;
			element.innerHTML = company + " - " + department[departmentID];
			element.style.paddingLeft = "5";
			element.style.marginTop = "3";
			element.appendChild(image);
			parent.document.getElementById("coopAdded").appendChild(element);

			var hidden = parent.document.createElement("input");
			hidden.id = "coopList_" + id;
			hidden.type = "hidden";
			hidden.value = id;
			hidden.name = "doc_coopList[]";
			var coopForm = parent.document.forms.form_proposal;
			coopForm.appendChild(hidden);
		}
	}
}
</script>

<table width="100%">
	<tr height="380">
		<td valign="top">
			<table width="100%" style="line-height:0.5">
				<tr>
					<td>
						<img onClick="open_dept(1)" id="dept1_icon1" src="../css/img/tree_plus.gif" style="position:relative; top:3px; cursor:pointer;">
						<img onClick="open_dept(1)" id="dept1_icon2" src="../css/img/tree_folder_close.gif" style="position:relative; top:4px; cursor:pointer;">
						<span onClick="open_dept(1)" style="cursor:pointer;">T-Brothers</span>
					</td>
				</tr>
				<tr id="dept1" style="display:none;">
					<td>
<?						for($i = 0; $i < sizeof($TB_dept); $i++ ) { ?>
							<div>
								<img src="../css/img/tree_extend.gif" style="position:relative; top:3px;">
								<img <?=($i == sizeof($TB_dept)-1 ? 'src="../css/img/tree_inside_end.gif"' : 'src="../css/img/tree_inside_extend.gif"') ?> style="position:relative; top:4px;">
								<img src="../css/img/tree_icon.gif" style="position:relative; top:4px;">
								<span style="cursor:pointer;" onClick="add_to_doc(1, <?=$TB_deptID[$i]; ?>, '<?=$TB_dept_str; ?>')"><?=$TB_dept[$i]; ?></span>
							</div>
<?						} ?>
					</td>
				</tr>


				<tr>
					<td>
						<img onClick="open_dept(2)" id="dept2_icon1" src="../css/img/tree_plus.gif" style="position:relative; top:3px; cursor:pointer;">
						<img onClick="open_dept(2)" id="dept2_icon2" src="../css/img/tree_folder_close.gif" style="position:relative; top:4px; cursor:pointer;">
						<span onClick="open_dept(2)" style="cursor:pointer;">Manna</span>
					</td>
				</tr>
				<tr id="dept2" style="display:none;">
					<td>
<?						for($i = 0; $i < sizeof($MN_dept); $i++ ) { ?>
							<div>
								<img src="../css/img/tree_extend.gif" style="position:relative; top:3px;">
								<img <?=($i == sizeof($TB_dept)-1 ? 'src="../css/img/tree_inside_end.gif"' : 'src="../css/img/tree_inside_extend.gif"') ?> style="position:relative; top:4px;">
								<img src="../css/img/tree_icon.gif" style="position:relative; top:4px;">
								<span style="cursor:pointer;" onClick="add_to_doc(2, <?=$MN_deptID[$i]; ?>, '<?=$MN_dept_str; ?>')"><?=$MN_dept[$i]; ?></span>
							</div>
<?						} ?>
					</td>
				</tr>

				<tr>
					<td>
						<img onClick="open_dept(3)" id="dept3_icon1" src="../css/img/tree_plus.gif" style="position:relative; top:3px; cursor:pointer;">
						<img onClick="open_dept(3)" id="dept3_icon2" src="../css/img/tree_folder_close.gif" style="position:relative; top:4px; cursor:pointer;">
						<span onClick="open_dept(3)" style="cursor:pointer;">Burnaby</span>
					</td>
				</tr>
				<tr id="dept3" style="display:none;">
					<td>
<?						for($i = 0; $i < sizeof($BBY_dept); $i++ ) { ?>
							<div>
								<img src="../css/img/tree_extend.gif" style="position:relative; top:3px;">
								<img <?=($i == sizeof($TB_dept)-1 ? 'src="../css/img/tree_inside_end.gif"' : 'src="../css/img/tree_inside_extend.gif"') ?> style="position:relative; top:4px;">
								<img src="../css/img/tree_icon.gif" style="position:relative; top:4px;">
								<span style="cursor:pointer;" onClick="add_to_doc(3, <?=$BBY_deptID[$i]; ?>, '<?=$BBY_dept_str; ?>')"><?=$BBY_dept[$i]; ?></span>
							</div>
<?						} ?>
					</td>
				</tr>

				<tr>
					<td>
						<img onClick="open_dept(4)" id="dept4_icon1" src="../css/img/tree_plus.gif" style="position:relative; top:3px; cursor:pointer;">
						<img onClick="open_dept(4)" id="dept4_icon2" src="../css/img/tree_folder_close.gif" style="position:relative; top:4px; cursor:pointer;">
						<span onClick="open_dept(4)" style="cursor:pointer;">Surrey</span>
					</td>
				</tr>
				<tr id="dept4" style="display:none;">
					<td>
<?						for($i = 0; $i < sizeof($SRY_dept); $i++ ) { ?>
							<div>
								<img src="../css/img/tree_extend.gif" style="position:relative; top:3px;">
								<img <?=($i == sizeof($TB_dept)-1 ? 'src="../css/img/tree_inside_end.gif"' : 'src="../css/img/tree_inside_extend.gif"') ?> style="position:relative; top:4px;">
								<img src="../css/img/tree_icon.gif" style="position:relative; top:4px;">
								<span style="cursor:pointer;" onClick="add_to_doc(4, <?=$SRY_deptID[$i]; ?>, '<?=$SRY_dept_str; ?>')"><?=$SRY_dept[$i]; ?></span>
							</div>
<?						} ?>
					</td>
				</tr>

				<tr>
					<td>
						<img onClick="open_dept(5)" id="dept5_icon1" src="../css/img/tree_plus.gif" style="position:relative; top:3px; cursor:pointer;">
						<img onClick="open_dept(5)" id="dept5_icon2" src="../css/img/tree_folder_close.gif" style="position:relative; top:4px; cursor:pointer;">
						<span onClick="open_dept(5)" style="cursor:pointer;">Westview</span>
					</td>
				</tr>
				<tr id="dept5" style="display:none;">
					<td>
<?						for($i = 0; $i < sizeof($WVI_dept); $i++ ) { ?>
							<div>
								<img src="../css/img/tree_extend.gif" style="position:relative; top:3px;">
								<img <?=($i == sizeof($TB_dept)-1 ? 'src="../css/img/tree_inside_end.gif"' : 'src="../css/img/tree_inside_extend.gif"') ?> style="position:relative; top:4px;">
								<img src="../css/img/tree_icon.gif" style="position:relative; top:4px;">
								<span style="cursor:pointer;" onClick="add_to_doc(5, <?=$WVI_deptID[$i]; ?>, '<?=$WVI_dept_str; ?>')"><?=$WVI_dept[$i]; ?></span>
							</div>
<?						} ?>
					</td>
				</tr>
			</table>
		</td>
	</tr>	
</table>
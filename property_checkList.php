
<?
include_once "includes/db_configms.php";
include_once "includes/common_class.php";

$area = ($_GET['area']) ? $_GET['area'] : $_POST['area'];
$areaNum = preg_replace('/\D/', '', $area);

$checkList_query = "SELECT checkList_ID, checkList_description ".
				   "FROM property_checkList ".
				   "WHERE area_ID = $areaNum AND status = 1 ".
				   "ORDER BY checkList_ID";
$checkList_query_result = mssql_query($checkList_query);			
?>
<style>
body {
	margin: 0;
}

.checklist_table {
	width: 100%;
	border-collapse: collapse;
}
.checklist_table td {
	text-align: center;
	border: 1px solid gray;
	padding: 3px 5px;
	font-size: 12px;
}
</style>

<script>
function closeChecklist(area_id) {
	var completed_area_list = parent.document.forms.property_inspection_form.completed_area.value;

	if(completed_area_list.indexOf(area_id + "_") > -1) {
		parent.$('#TB_map_image').mapster('set', false, area_id);
		parent.$('#TB_map_image').mapster('set', true, area_id, {
			strokeColor: '0000FF'
		});
	} else {
		parent.$('#TB_map_image').mapster('set', false, area_id);
	}

	parent.$("#mask").fadeOut("slow");
	parent.$('#tb_checkList_popup').hide();
}

function resetChecklist(area_id) {
	var answer = confirm("초기화 하시겠습니까?");
	if(answer) {
		var numberOfcl = document.getElementById("checkList_table").rows.length;
		var completed_area_list = parent.document.forms.property_inspection_form.completed_area.value;

		if(completed_area_list.indexOf(area_id + "_") > -1) {
			parent.document.forms.property_inspection_form.completed_area.value = completed_area_list.replace(area_id + "_", "");
			deleteResultCheckList(area_id, numberOfcl);
		}

		parent.$('#TB_map_image').mapster('set', false, area_id);
		parent.$("#mask").fadeOut("slow");
		parent.$('#tb_checkList_popup').hide();
	}
}

function updateChecklist(area_id) {
	if(verifyCheckList(area_id)) {
		parent.$('#TB_map_image').mapster('set', false, area_id);
		parent.$('#TB_map_image').mapster('set', true, area_id, {
			strokeColor: '0000FF'
		});

		parent.$("#mask").fadeOut("slow");
		parent.$('#tb_checkList_popup').hide();
	} else {
		alert("체크리스트 작성을 마무리해 주세요.");
	}
}

function verifyCheckList(area_id) {
	var cl_table = document.getElementById("checkList_table");
	var numberOfcl = cl_table.rows.length;

	for(var i = 2; i < numberOfcl; i++) {		// i = 2, skip the table header
		if(cl_table.rows[i].cells[1].childNodes[0].checked == false && cl_table.rows[i].cells[2].childNodes[0].checked == false) {
			return false;
		}
	}

	buildResultCheckList(area_id, cl_table, numberOfcl);
	return true;
}

function buildResultCheckList(area_id, cl_table, numberOfcl) {
	var completed_area_list = parent.document.forms.property_inspection_form.completed_area.value;

	if(completed_area_list.indexOf(area_id + "_") < 0) {
		parent.document.forms.property_inspection_form.completed_area.value += area_id + "_";
	} else {
		deleteResultCheckList(area_id, numberOfcl);
	}

	var result_table = parent.document.getElementById("result_table");
	var result_list = result_table.children[0].children;

	for(var i = 0; i < result_list.length; i++) {
		if(result_list[i].id.indexOf(area_id + "_0") > -1) {
			var list_row = result_list[i];

			var list_size = document.createElement("input");
			list_size.type = "hidden";
			list_size.name = "result_" + area_id + "_size";
			list_size.value = numberOfcl - 2;
			list_row.appendChild(list_size);

			for(var j = 2; j < numberOfcl; j++) {
				list_row = list_row.nextElementSibling;

				if(cl_table.rows[j].cells[1].childNodes[0].checked == false && cl_table.rows[j].cells[2].childNodes[0].checked == true) {
					list_row.cells[1].style.color = "red";
					list_row.cells[1].innerHTML = "Bad";

					var list_value = document.createElement("input");
					list_value.type = "hidden";
					list_value.name = "result_" + area_id + "_condition" + (j-1);
					list_value.value = "0";
					list_row.cells[0].appendChild(list_value);
				} else {
					list_row.cells[1].style.color = "blue";
					list_row.cells[1].innerHTML = "Good";

					var list_value = document.createElement("input");
					list_value.type = "hidden";
					list_value.name = "result_" + area_id + "_condition" + (j-1);
					list_value.value = "1";
					list_row.cells[0].appendChild(list_value);
				}
				list_row.cells[2].innerHTML = cl_table.rows[j].cells[3].childNodes[0].value;
				
				var list_value = document.createElement("input");
				list_value.type = "hidden";
				list_value.name = "result_" + area_id + "_comment" + (j-1);
				list_value.value = cl_table.rows[j].cells[3].childNodes[0].value;
				list_row.cells[0].appendChild(list_value);

				list_row.style.backgroundColor = "#CECEF6";
			}
			break;
		}
	}
}

function deleteResultCheckList(area_id, numberOfcl) {
	var result_table = parent.document.getElementById("result_table");
	var result_list = result_table.children[0].children;

	for(var i = 0; i < result_list.length; i++) {
		if(result_list[i].id.indexOf(area_id + "_0") > -1) {
			var list_row = result_list[i];

			if(list_row.lastElementChild.tagName == "INPUT") {
				list_row.removeChild(list_row.lastElementChild);
			}

			for(var j = 2; j < numberOfcl; j++) {
				list_row = list_row.nextElementSibling;

				if(list_row.cells[0].lastElementChild.tagName == "INPUT") {
					list_row.cells[0].removeChild(list_row.cells[0].lastElementChild);
					list_row.cells[0].removeChild(list_row.cells[0].lastElementChild);
				}

				list_row.cells[1].innerHTML = "";
				list_row.cells[2].innerHTML = "";
				list_row.style.backgroundColor = "#FFFFFF";
			}
			break;
		}
	}
}
</script>

<table width="100%" style="margin:0;" cellspacing=0 cellpadding=0>
	<tr height="50">
		<td style="padding-left:20px; background-color:#F6CECE;">
			<table width="100%">
				<tr>
					<td><font size="4"><b>Check List - <?=$area; ?></b></font></td>
					<td width="40" align="left"><img id="closeBtn" src="css/img/bt_closelayer.gif" style="width:25px; cursor:pointer;" onClick="closeChecklist('<?=$area; ?>')"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr height="772">
		<td style="background-color:#CECEF6; vertical-align:top; padding:20px 5px;">
			<table id="checkList_table" class="checklist_table" cellspacing=0 cellpadding=0>
				<tr style="font-weight:bold;">
					<td width="180px" rowspan=2>List</td>
					<td colspan=2>Condition</td>
					<td rowspan=2>Comment</td>
				</tr>
				<tr style="font-weight:bold;">
					<td width="50px">Good</td>
					<td width="50px">Bad</td>
				</tr>
				<? while($checkList_row = mssql_fetch_array($checkList_query_result)) { ?>
					<tr>
						<td width="180px"><?=Br_iconv($checkList_row['checkList_description']); ?></td>
						<td><input type="radio" name="cl_<?=$checkList_row['checkList_ID']; ?>_condition" style="width:20px; height:20px;" value="y" /></td>
						<td><input type="radio" name="cl_<?=$checkList_row['checkList_ID']; ?>_condition" style="width:20px; height:20px;" value="n" /></td>
						<td><textarea name="cl_<?=$checkList_row['checkList_ID']; ?>_comment" style="width:100%; height:100%;"></textarea></td>
					</tr>
				<? } ?>
			</table>
		</td>
	</tr>
	<tr height="50">
		<td style="padding-left:20px; background-color:#F6CECE;">
			<table width="100%">
				<tr>
					<td align="center"><button id="resetBtn" onClick="resetChecklist('<?=$area; ?>')">초기화</button>&nbsp;&nbsp;<button id="updateBtn" onClick="updateChecklist('<?=$area; ?>')">확인</button></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
<script type="text/javascript" src="js/jquery.imagemapster.js"></script>
<script type="text/javascript" src="js/PropertyMapping.js"></script>

<?
$mode = $_POST['mode'];

if($mode == "save") {
	$numberOfarea = 17;

	// Delete the old check list
	$delCL_query = "DELETE FROM property_checkList";
	mssql_query($delCL_query);

	// Add the new check list
	$new_areaList = array();
	for($i = 1; $i <= $numberOfarea; $i++) {
		for($j = 1; $j <= sizeof($_POST['cl_area'.$i.'_list']); $j++) {
			$area_list = $_POST['cl_area'.$i.'_list'][$j-1];
			$area_list = str_replace("'", "''", $area_list);
			array_push($new_areaList, "(".$i.", ".$j.", '".Br_dconv($_POST['cl_area'.$i.'_list'][$j-1])."', ".$_POST['cl_area'.$i.'_status'][$j-1].")");
		}
	}

	// Insert the new check list
	$newCL_query = "INSERT INTO property_checkList (area_ID, checkList_ID, checkList_description, status) ".
				   "VALUES ".join(", ", $new_areaList);
	mssql_query($newCL_query);
}

$checkList_query = "SELECT * FROM property_checkList ORDER BY area_ID, checkList_ID";
$checkList_query_result = mssql_query($checkList_query);
$checkList_num = mssql_num_rows($checkList_query_result);
?>

<style>
#cl_table {
	width: 100%;
	border-collapse: collapse;
}
#cl_table td {
	text-align: center;
	border: 1px solid gray;
	vertical-align: middle;
	padding: 3px 5px;
}
</style>

<script>
function add_cl(target, area_id) {
	var cl_table = document.getElementById("cl_table");
	var last_element = target.parentElement.parentElement;
	var row = cl_table.insertRow(last_element.rowIndex);
	var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
	var cell3 = row.insertCell(2);

	var newList_input = document.createElement("input");
	newList_input.name = "cl_area" + area_id + "_list[]";
	newList_input.style.width = "100%";
	cell1.appendChild(newList_input);

	var newList_select = document.createElement("select");
	newList_select.name = "cl_area" +  area_id + "_status[]";
	var newList_option1 = document.createElement("option");
	newList_option1.value = 1;
	newList_option1.text = "Active";
	newList_select.add(newList_option1);
	var newList_option2 = document.createElement("option");
	newList_option2.value = 0;
	newList_option2.text = "Inactive";
	newList_select.add(newList_option2);
	cell2.appendChild(newList_select);

	var newList_span = document.createElement("span");
	newList_span.style.fontWeight = "bold";
	newList_span.style.color = "red";
	newList_span.style.cursor = "pointer";
	newList_span.innerHTML = "X";
	newList_span.onclick = function() {remove_cl(this)};
	cell3.appendChild(newList_span);
}

function remove_cl(target) {
	var cl_table = document.getElementById("cl_table");
	var selected_element = target.parentElement.parentElement.rowIndex;

	cl_table.deleteRow(selected_element);
}

function save_cl() {
	var cl_table = document.getElementById("cl_table");
	var cl_rows = cl_table.rows;

	for(var i = 0; i < cl_rows.length; i++) {

		var cl_cells = cl_rows[i].cells;
		if(cl_cells.length > 1) {

			var cl_input = cl_cells[0].children[0];
			if(!cl_input.value || cl_input.value == "") {
				alert("오류: 미완성된 체크리스트가 있습니다.");
				return;
			}
		}
	}

	var answer = confirm("체크리스트를 저장 하시겠습니까?");
	if(answer) {
		var form = document.forms.checkList_form;
		form.mode.value = "save";
		form.submit();
	}
}
</script>

<td width="" align="left" valign="top">
	<table width="100%">
		<tr>
			<td height="40" colspan=2>
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title" style="letter-spacing:0;">체크리스트 수정</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30" class="doc_submit_area" colspan=2>
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;"><button class="doc_submit_btn_style" onClick="save_cl()">저장</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td>
				<table>
					<tr>
						<td width="700px" style="border:1px solid red;">
							<img id="TB_map_image" src="images/property/TB_map.jpg" usemap="#TB_map_coords_name" style="width:700px;">
						</td>
						<td width="720px" style="border:1px solid red;">
							<div style="font-size:18px; font-weight:bold; text-align:center; padding:10px 0; background-color:#D8D8D8; border-bottom:1px solid black;">체크리스트</div>
							<form name="checkList_form" action="?page=property&menu=modifyCL" method="post">
							<div style="height:829px; overflow-x:hidden;">
								<table width="100%" id="cl_table" style="padding:10px 0;">
									<input type="hidden" name="mode" />
									<? $i = 0; ?>
									<? while($checkList_row = mssql_fetch_array($checkList_query_result)) { ?>
										<? $i++; ?>
										<? if($checkList_row['checkList_ID'] == 1) { ?>
											<? if($checkList_row['area_ID'] > 1) { ?>
												<tr>
													<td colspan=3><input type="button" value="추가" style="width:100%; background-color:#CECEF6" onClick="add_cl(this, <?=$checkList_row['area_ID']-1; ?>)"></td>
												</tr>
											<? } ?>
											<tr id="cl_area<?=$checkList_row['area_ID']; ?>_0" style="background-color:#F6CECE">
												<td colspan=3 style="font-weight:bold">Area <?=$checkList_row['area_ID']; ?></td>
											</tr>
										<? } ?>
										<tr>
											<td>
												<input name="cl_area<?=$checkList_row['area_ID']; ?>_list[]" value="<?=Br_iconv($checkList_row['checkList_description']); ?>" style="width:100%"></input>
											</td>
											<td width="80px">
												<select name="cl_area<?=$checkList_row['area_ID']; ?>_status[]">
													<option value=1 <?=(($checkList_row['status'] == 1) ? "selected" : "" )?>>Active</option>
													<option value=0 <?=(($checkList_row['status'] == 1) ? "" : "selected" )?>>Inactive</option>
												</select>
											</td>
											<td width="20px">
												<span style="font-weight:bold; color:red; cursor:pointer" onClick="remove_cl(this)">X</span>
											</td>
										</tr>
										<? if($i == $checkList_num) { ?>
											<tr>
												<td colspan=3><input type="button" value="추가" style="width:100%; background-color:#CECEF6" onClick="add_cl(this, <?=$checkList_row['area_ID']; ?>)"></td>
											</tr>
										<? } ?>
									<? } ?>
								</table>
							</div>
							</form>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30" class="doc_submit_area" colspan=2>
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;"><button class="doc_submit_btn_style" onClick="save_cl()">저장</td>
					</tr>
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

<map id="TB_map_coords" name="TB_map_coords_name">
	<area area_shortname="area1" area-fullname="AREA1" href="#" shape="poly" coords="190,180, 206,180, 206,186, 190,186" />
	<area area_shortname="area1" area-fullname="AREA1" href="#" shape="poly" coords="196,168, 206,180, 190,180, 190,173, 193,170" />
	<area area_shortname="area1" area-fullname="AREA1" href="#" shape="poly" coords="232,180, 277,180, 277,196, 232,196" />
	<area area_shortname="area1" area-fullname="AREA1" href="#" shape="poly" coords="309,180, 355,180, 355,196, 309,196" />
	<area area_shortname="area1" area-fullname="AREA1" href="#" shape="circle" coords="36, 30, 24" />

	<area area_shortname="area2" area-fullname="AREA2" href="#" shape="poly" coords="459,240, 483,217, 497,232, 473,254" />
	<area area_shortname="area2" area-fullname="AREA2" href="#" shape="poly" coords="519,240, 543,217, 557,233, 533,255" />
	<area area_shortname="area2" area-fullname="AREA2" href="#" shape="poly" coords="579,240, 602,217, 617,233, 593,255" />
	<area area_shortname="area2" area-fullname="AREA2" href="#" shape="poly" coords="640,238, 663,215, 678,229, 655,253" />
	<area area_shortname="area2" area-fullname="AREA2" href="#" shape="poly" coords="683,211, 700,211, 700,217, 683,217" />
	<area area_shortname="area2" area-fullname="AREA2" href="#" shape="poly" coords="690,197, 700,208, 700,211, 683,211, 683,204, 686,200" />
	<area area_shortname="area2" area-fullname="AREA2" href="#" shape="circle" coords="94, 30, 24" />

	<area area_shortname="area3" area-fullname="AREA3" href="#" shape="poly" coords="746,206, 768,183, 784,198, 760,221" />
	<area area_shortname="area3" area-fullname="AREA3" href="#" shape="poly" coords="800,180, 817,180, 817,187, 800,187" />
	<area area_shortname="area3" area-fullname="AREA3" href="#" shape="poly" coords="808,168, 818,180, 800,180, 800,174, 803,170" />
	<area area_shortname="area3" area-fullname="AREA3" href="#" shape="poly" coords="851,180, 888,180, 888,194, 851,194" />
	<area area_shortname="area3" area-fullname="AREA3" href="#" shape="circle" coords="152, 30, 24" />

	<area area_shortname="area4" area-fullname="AREA4" href="#" shape="poly" coords="177,180, 397,180, 456,243, 486,214, 516,243, 546,214, 576,243, 606,214, 636,243, 666,211, 743,211, 771,180, 911,180, 942,209, 942,214, 912,214, 912,340, 942,340, 942,357, 909,357, 909,384, 881,384, 881,356, 743,356, 743,288, 713,288, 713,328, 620,328, 620,356, 177,356" />
	<area area_shortname="area4" area-fullname="AREA4" href="#" shape="circle" coords="210, 30, 24" />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="190,180, 206,180, 206,186, 190,186" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="232,180, 277,180, 277,196, 232,196" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="309,180, 355,180, 355,196, 309,196" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="459,240, 483,217, 497,232, 473,254" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="519,240, 543,217, 557,233, 533,255" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="579,240, 602,217, 617,233, 593,255" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="640,238, 663,215, 678,229, 655,253" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="683,211, 700,211, 700,217, 683,217" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="746,206, 768,183, 784,198, 760,221" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="800,180, 817,180, 817,187, 800,187" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="851,180, 888,180, 888,194, 851,194" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="915,354, 930,354, 930,357, 915,357" nohref />
	<area area_shortname="area4" area-fullname="AREA4" shape="poly" coords="884,381, 899,381, 899,384, 884,384" nohref />

	<area area_shortname="area5" area-fullname="AREA5" href="#" shape="poly" coords="177,356, 620,356, 620,1057, 502,1057, 502,872, 61,872, 61,824, 177,824" />
	<area area_shortname="area5" area-fullname="AREA5" href="#" shape="poly" coords="743,356, 881,356, 881,869, 743,869" />
	<area area_shortname="area5" area-fullname="AREA5" href="#" shape="poly" coords="912,214, 942,214, 942,340, 912,340" />
	<area area_shortname="area5" area-fullname="AREA5" href="#" shape="poly" coords="620,328, 713,328, 713,288, 743,288, 743,356, 620,356" />
	<area area_shortname="area5" area-fullname="AREA5" href="#" shape="circle" coords="268, 30, 24" />
	<area area_shortname="area5" area-fullname="AREA5" shape="poly" coords="617,448, 620,448, 620,478, 617,478" nohref />
	<area area_shortname="area5" area-fullname="AREA5" shape="poly" coords="617,710, 620,710, 620,740, 617,740" nohref />
	<area area_shortname="area5" area-fullname="AREA5" shape="poly" coords="617,931, 620,931, 620,961, 617,961" nohref />
	<area area_shortname="area5" area-fullname="AREA5" shape="poly" coords="610,499, 615,496, 620,495, 620,508" nohref />
	<area area_shortname="area5" area-fullname="AREA5" shape="poly" coords="610,584, 615,581, 620,581, 620,594" nohref />
	<area area_shortname="area5" area-fullname="AREA5" shape="poly" coords="610,854, 615,850, 620,850, 620,864" nohref />
	<area area_shortname="area5" area-fullname="AREA5" shape="poly" coords="610,1014, 615,1011, 620,1010, 620,1024" nohref />
	<area area_shortname="area5" area-fullname="AREA5" shape="poly" coords="880,852, 875,850, 871,848, 880,840" nohref />

	<area area_shortname="area6" area-fullname="AREA6" href="#" shape="poly" coords="62,212, 88,180, 177,180, 177,446, 62,446" />
	<area area_shortname="area6" area-fullname="AREA6" href="#" shape="circle" coords="326, 30, 24" />
	<area area_shortname="area7" area-fullname="AREA7" href="#" shape="poly" coords="62,447, 177,447, 177,705, 62,705" />
	<area area_shortname="area7" area-fullname="AREA7" href="#" shape="circle" coords="384, 30, 24" />
	<area area_shortname="area8" area-fullname="AREA8" href="#" shape="poly" coords="62,705, 177,705, 177,824, 62,824" />
	<area area_shortname="area8" area-fullname="AREA8" href="#" shape="circle" coords="442, 30, 24" />

	<area area_shortname="area9" area-fullname="AREA9" href="#" shape="poly" coords="620,356, 743,356, 743,548, 620,548" />
	<area area_shortname="area9" area-fullname="AREA9" href="#" shape="poly" coords="617,448, 620,448, 620,478, 617,478" />
	<area area_shortname="area9" area-fullname="AREA9" href="#" shape="poly" coords="610,499, 615,496, 620,495, 620,508" />
	<area area_shortname="area9" area-fullname="AREA9" href="#" shape="circle" coords="500, 30, 24" />

	<area area_shortname="area10" area-fullname="AREA10" href="#" shape="poly" coords="620,547, 743,547, 743,882, 620,882" />
	<area area_shortname="area10" area-fullname="AREA10" href="#" shape="poly" coords="617,710, 620,710, 620,740, 617,740" />
	<area area_shortname="area10" area-fullname="AREA10" href="#" shape="poly" coords="610,584, 615,581, 620,581, 620,594" />
	<area area_shortname="area10" area-fullname="AREA10" href="#" shape="poly" coords="610,854, 615,850, 620,850, 620,864" />
	<area area_shortname="area10" area-fullname="AREA10" href="#" shape="circle" coords="558, 30, 24" />

	<area area_shortname="area11" area-fullname="AREA11" href="#" shape="poly" coords="620,882, 743,882, 743,1057, 620,1057" />
	<area area_shortname="area11" area-fullname="AREA11" href="#" shape="poly" coords="617,931, 620,931, 620,961, 617,961" />
	<area area_shortname="area11" area-fullname="AREA11" href="#" shape="poly" coords="610,1014, 615,1011, 620,1010, 620,1024" />
	<area area_shortname="area11" area-fullname="AREA11" href="#" shape="circle" coords="616, 30, 24" />

	<area area_shortname="area12" area-fullname="AREA12" href="#" shape="poly" coords="909,357, 942,357, 942,869, 881,869, 881,384, 909,384" />
	<area area_shortname="area12" area-fullname="AREA12" href="#" shape="poly" coords="915,354, 930,354, 930,357, 915,357" />
	<area area_shortname="area12" area-fullname="AREA12" href="#" shape="poly" coords="884,381, 899,381, 899,384, 884,384" />
	<area area_shortname="area12" area-fullname="AREA12" href="#" shape="poly" coords="880,852, 875,850, 871,848, 880,840" />
	<area area_shortname="area12" area-fullname="AREA12" href="#" shape="circle" coords="674, 30, 24" />

	<area area_shortname="area13" area-fullname="AREA13" href="#" shape="poly" coords="61,1057, 943,1057, 943,1196, 61,1196" />
	<area area_shortname="area13" area-fullname="AREA13" href="#" shape="circle" coords="732, 30, 24" />

	<area area_shortname="area14" area-fullname="AREA14" href="#" shape="poly" coords="33,100, 970,100, 970,180, 771,180, 742,211, 668,211, 636,243, 606,214, 576,243, 546,214, 516,243, 486,214, 456,243, 398,180, 33,180" />
	<area area_shortname="area14" area-fullname="AREA14" href="#" shape="circle" coords="790, 30, 24" />
	<area area_shortname="area14" area-fullname="AREA14" shape="poly" coords="196,168, 206,180, 190,180, 190,173, 193,170" nohref />
	<area area_shortname="area14" area-fullname="AREA14" shape="poly" coords="690,197, 700,208, 700,211, 683,211, 683,204, 686,200" nohref />
	<area area_shortname="area14" area-fullname="AREA14" shape="poly" coords="808,168, 818,180, 800,180, 800,174, 803,170" nohref />

	<area area_shortname="area15" area-fullname="AREA15" href="#" shape="poly" coords="33,180, 88,180, 61,212, 61,1196, 33,1196" />
	<area area_shortname="area15" area-fullname="AREA15" href="#" shape="circle" coords="848, 30, 24" />

	<area area_shortname="area16" area-fullname="AREA16" href="#" shape="poly" coords="911,180, 970,180, 970,1196, 943,1196, 943,209" />
	<area area_shortname="area16" area-fullname="AREA16" href="#" shape="circle" coords="910, 30, 24" />

	<area area_shortname="area17" area-fullname="AREA17" href="#" shape="poly" coords="10,63, 992,63, 992,1196, 970,1196, 970,100, 33,100, 33,1196, 10,1196" />
	<area area_shortname="area17" area-fullname="AREA17" href="#" shape="circle" coords="968, 30, 24" />
</map>
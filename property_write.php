<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
<script type="text/javascript" src="js/jquery.imagemapster.js"></script>
<script type="text/javascript" src="js/PropertyMapping.js"></script>

<?
include_once "includes/db_configms.php";

$userId = $_SESSION['memberID'];
$mode = $_POST['mode'];

if($mode == "submit" || $mode == "save") {
	$area_completed = $_POST['completed_area'];
	$area_completed = explode("_", $area_completed);
	array_pop($area_completed);
	sort($area_completed);

	//Save the header
	if($mode == "submit") {
		//Get header id
		$getHeaderId_query = "SELECT TOP 1 id FROM property_header ORDER BY id DESC";
		$getHeaderId_query_result = mssql_query($getHeaderId_query);
		$getHeaderId_row = mssql_fetch_array($getHeaderId_query_result);
		if(!$getHeaderId_row) {
			$header_id = 1;
		} else {
			$header_id = $getHeaderId_row['id'] + 1;
		}

		//Insert the header
		$insertHeader_query = "INSERT INTO property_header (id, inspector) VALUES ($header_id, '$userId') ";
		mssql_query($insertHeader_query);
	}

	$submitted_form = array();

	for($i = 0; $i <= sizeof($area_completed); $i++) {
		if($_POST['result_'.$area_completed[$i].'_size']) {
			$areaNum = (int)filter_var($area_completed[$i], FILTER_SANITIZE_NUMBER_INT);

			for($j = 1; $j <= $_POST['result_'.$area_completed[$i].'_size']; $j++) {
				$comment_dconv = $_POST['result_'.$area_completed[$i].'_comment'.$j];
				$comment_dconv = str_replace("'", "''", $comment_dconv);

				if($mode == "save") {
					array_push($submitted_form, "('".$userId."', ".$areaNum.", ".$j.", ".$_POST['result_'.$area_completed[$i].'_condition'.$j].", '".Br_dconv($comment_dconv)."')");
				}
				if($mode == "submit") {
					array_push($submitted_form, "(".$header_id.", ".$areaNum.", ".$j.", ".$_POST['result_'.$area_completed[$i].'_condition'.$j].", '".Br_dconv($comment_dconv)."')");
				}
			}
		}
	}
	//Delete the previous temporary saved data
	$deleteSavedTemp = "DELETE FROM property_savedTemp WHERE user_id = '$userId'";
	mssql_query($deleteSavedTemp);

	if($mode == "save") {
		//Insert new temporary saved data
		if(!empty($submitted_form)) {
			$insertSavedTemp = "INSERT INTO property_savedTemp (user_id, area_id, checkList_id, condition, comment) ".
							   "VALUES ".join(", ", $submitted_form);
			mssql_query($insertSavedTemp);
		}
	}

	if($mode == "submit") {
		$insertContent_query = "INSERT INTO property_content (header_id, area_id, checkList_id, condition, comment) ".
							   "VALUES ".join(", ", $submitted_form);
		mssql_query($insertContent_query);

		//update checkList_description
		$updateCLD_query = "UPDATE PC SET PC.checkList_description = PCL.checkList_description FROM property_content PC ".
						   "INNER JOIN property_checkList PCL ON PC.area_id = PCL.area_ID AND PC.checkList_id = PCL.checkList_ID ".
						   "WHERE PC.header_id = $header_id ";
		mssql_query($updateCLD_query);

		echo '<script>location.href = "?page=property&menu=history";</script>';
	}
}

if($mode == "reset") {
	$reset_query = "DELETE FROM property_savedTemp WHERE user_id = '$userId'";
	mssql_query($reset_query);
}

// getting total number of area
$getAreaLen_query = "SELECT area_ID FROM property_checkList GROUP BY area_ID";
$getAreaLen_query_result = mssql_query($getAreaLen_query);
$getAreaLen = mssql_num_rows($getAreaLen_query_result);

// getting checklist list
$checkList_query = "SELECT area_ID, checkList_ID, checkList_description ".
				   "FROM property_checkList ".
				   "WHERE status = 1 ".
				   "ORDER BY area_ID, checkList_ID";
$checkList_query_result = mssql_query($checkList_query);

// getting temporary saved data
$savedTemp_query = "SELECT area_id, checkList_id, condition, comment ".
				  "FROM property_savedTemp ".
				  "WHERE user_id = '$userId' ".
				  "ORDER BY area_id, checkList_id";
$savedTemp_query_result = mssql_query($savedTemp_query);
$savedTemp_num_row = mssql_num_rows($savedTemp_query_result);

$completed_area = "";
$savedTemp = array();
if($savedTemp_num_row) {
	while($savedTemp_row = mssql_fetch_array($savedTemp_query_result)) {
		if(!array_key_exists($savedTemp_row['area_id'], $savedTemp)) {
			$savedTemp += array(
				$savedTemp_row['area_id'] => array(
					$savedTemp_row['checkList_id'] => array(
						"condition" => $savedTemp_row['condition'],
						"comment" => $savedTemp_row['comment']
					)
				)
			);
		} else {
			$savedTemp[$savedTemp_row['area_id']] += array(
				$savedTemp_row['checkList_id'] => array(
					"condition" => $savedTemp_row['condition'],
					"comment" => $savedTemp_row['comment']
				)
			);
		}

		if(strpos($completed_area, "area".$savedTemp_row['area_id']."_") === false) {
			$completed_area .= "area".$savedTemp_row['area_id']."_";
		}
	}
}
?>

<style>
#result_table {
	width: 100%;
	border-collapse: collapse;
}
#result_table td {
	text-align: center;
	border: 1px solid gray;
	vertical-align: middle;
	padding: 3px 5px;
}
</style>

<script>
function result_reset() {
	var answer = confirm("점검지 작성을 초기화 하시겠습니까?");
	if(answer) {
		var form = document.forms.property_inspection_form;
		form.mode.value = "reset";
		form.submit();
	}
}

function result_save() {
	var answer = confirm("작성된 점검지를 임시저장 하시겠습니까?");
	if(answer) {
		var form = document.forms.property_inspection_form;
		form.mode.value = "save";
		form.submit();
	}
}

function result_submit(areaLen) {
	var form = document.forms.property_inspection_form;
	var completed_area_list = form.completed_area.value;
	var area_list = completed_area_list.split("_");
	area_list.pop();									// pop(), remove the last element

	if(area_list.length < areaLen) {
		alert("점검지 작성이 완료되지 않았습니다.");
	} else {
		var answer = confirm("점검지 작성을 완료 하시겠습니까?");
		if(answer) {
			form.mode.value = "submit";
			form.submit();
		}
	}
}
</script>

<body onbeforeunload="return '저장 하셨습니까?\n이 페이지를 나가면 작업중이던 내용은 사라집니다.'">
<div id="mask" style="position:absolute; z-index:900; background-color:#000; display:none; left:0; top:0;"></div>
<div id="tb_checkList_popup" style="width:720px; height:872px; position:absolute; z-index:1000; left:888px; top:146px; border:2px solid black; background-color:#FFF; display:none;">
	<iframe id="tb_checkList_iframe" width="100%" height="100%" frameborder="0"></iframe>
</div>

<td width="" align="left" valign="top">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title" style="letter-spacing:0;">점검지 - Write</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="left">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="result_reset()">초기화</td>
								</tr>
							</table>
						</td>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="result_save()">저장</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="result_submit(<?=$getAreaLen; ?>)">완료</td>
								</tr>
							</table>
						</td>
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
							<form name="property_inspection_form" action="?page=property" method="post">
							<input type="hidden" name="mode" />
							<div style="font-size:18px; font-weight:bold; text-align:center; padding:10px 0; background-color:#D8D8D8">점검지</div>
							<table width="100%" style="padding:10px 0; background-color:#D8D8D8; border-bottom:1px solid black;">
								<tr>
									<td width="33.3%" style="padding:2px 5px; text-align:left">번호:</td>
									<td width="33.3%" style="padding:2px 5px; text-align:center">Date: <?=date('Y-m-d'); ?></td>
									<td width="33.3%" style="padding:2px 5px; text-align:right">점검자: <?=get_user_name($_SESSION['memberID']); ?></td>
								</tr>
							</table>
							<div style="height:809px; overflow-x:hidden;">
								<table width="100%" id="result_table" style="padding:10px 0;">
									<? while($checkList_row = mssql_fetch_array($checkList_query_result)) { ?>
										<? if($checkList_row['checkList_ID'] == 1) { ?>
											<tr id="result_area<?=$checkList_row['area_ID']; ?>_0">
												<td colspan=3 style="background-color:#F6CECE; font-weight:bold">Area <?=$checkList_row['area_ID']; ?></td>
												<? if(array_key_exists($checkList_row['area_ID'], $savedTemp)) { ?>
													<input type="hidden" name="result_area<?=$checkList_row['area_ID']; ?>_size" value="<?=sizeof($savedTemp[$checkList_row['area_ID']]); ?>">
												<? } ?>
											</tr>
										<? } ?>

										<? if(array_key_exists($checkList_row['area_ID'], $savedTemp) && array_key_exists($checkList_row['checkList_ID'], $savedTemp[$checkList_row['area_ID']])) { ?>
											<?
											$comment_iconv = $savedTemp[$checkList_row['area_ID']][$checkList_row['checkList_ID']]['comment'];
											$comment_iconv =  str_replace("\'", "'", $comment_iconv);
											$comment_iconv =  str_replace('\"', '"', $comment_iconv);
											?>
											<tr style="background-color:#CECEF6;">
												<td width="180">
													<?=Br_iconv($checkList_row['checkList_description']); ?>
													<input type="hidden" name="result_area<?=$checkList_row['area_ID']; ?>_condition<?=$checkList_row['checkList_ID']; ?>" value="<?=$savedTemp[$checkList_row['area_ID']][$checkList_row['checkList_ID']]['condition']; ?>">
													<input type="hidden" name="result_area<?=$checkList_row['area_ID']; ?>_comment<?=$checkList_row['checkList_ID']; ?>" value="<?=Br_iconv($comment_iconv); ?>">
												</td>
												<td width="50" style="font-weight:bold; <?=(($savedTemp[$checkList_row['area_ID']][$checkList_row['checkList_ID']]['condition'] == '1') ? 'color:blue' : 'color:red' ); ?>"><?=(($savedTemp[$checkList_row['area_ID']][$checkList_row['checkList_ID']]['condition'] == 1) ? 'Good' : 'Bad' ); ?></td>
												<td style="text-align:left;"><?=Br_iconv($comment_iconv); ?></td>
											</tr>
										<? } else { ?>
											<tr>
												<td width="180"><?=Br_iconv($checkList_row['checkList_description']); ?></td>
												<td width="50" style="font-weight:bold"></td>
												<td style="text-align:left;"></td>
											</tr>
										<? } ?>
									<? } ?>
								</table>
							</div>
							<input type="hidden" name="completed_area" value="<?=$completed_area; ?>" />
							</form>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="left">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="result_reset()">초기화</td>
								</tr>
							</table>
						</td>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="result_save()">저장</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="result_submit(<?=$getAreaLen; ?>)">완료</td>
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
	</table>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
</body>

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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.js"></script>
<script type="text/javascript" src="js/jquery.imagemapster.js"></script>
<script type="text/javascript" src="js/PropertyMapping.js"></script>

<?
include_once "includes/db_configms.php";

$id = ($_GET['id']) ? $_GET['id'] : $_POST['id'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

if($mode == "confirm") {
	$confirm_query = "UPDATE property_header SET ".
					 "confirmor1_check = CASE WHEN confirmor1 = '".$_SESSION['memberID']."' AND confirmor1_check = 0 THEN 1 ELSE confirmor1_check END, ".
					 "confirmor2_check = CASE WHEN confirmor2 = '".$_SESSION['memberID']."' AND confirmor2_check = 0 THEN 1 ELSE confirmor2_check END, ".
					 "confirmor3_check = CASE WHEN confirmor3 = '".$_SESSION['memberID']."' AND confirmor3_check = 0 THEN 1 ELSE confirmor3_check END ".
					 "WHERE id = $id";
	mssql_query($confirm_query);					
}

$header_query = "SELECT *, CONVERT(char(10), date, 120) AS date FROM property_header WHERE id = $id";
$header_query_result = mssql_query($header_query);
$header_row = mssql_fetch_array($header_query_result);

$history_query = "SELECT area_id, checkList_id, checkList_description, condition, comment ".
				 "FROM property_content ".
				 "WHERE header_id = $id ".
				 "ORDER BY area_id, checkList_id";
$history_query_result = mssql_query($history_query);
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
function confirmor_confirm(header_id) {
	var answer = confirm("점검지 확인을 완료 하시겠습니까?");
	if(answer) {
		var form = document.createElement("form");
		form.setAttribute('method', "post");
		form.setAttribute('action', "?page=property&menu=view&id=" + header_id);

		var mode = document.createElement("input");
		mode.setAttribute('type', "hidden");
		mode.setAttribute('name', "mode");
		mode.setAttribute('value', "confirm");

		form.appendChild(mode);
		form.submit();

		document.getElementsByTagName('body')[0].appendChild(form);
	}
}

function print(id) {
	var popUrl = "property_print.php?id=" + id;
	var popOption = "width=750, height=600, toolbar=0, location=0, directories=0, resizable=1, menubar=0, scrollbars=yes, status=no";

	window.open(popUrl, "", popOption);
}

function maintenance(header_id, area_id, checkList_id) {
	var maskHeight = $(document).height();  
	var maskWidth = $(document).width();
	var windowHeight = $(window).height();
	var windowWidth = $(window).width();

	$('#mask').css({'width':maskWidth, 'height':maskHeight});
	$('#mask').fadeTo("slow", 0.6);
	$('#maintenance_iframe').attr("src", "property_maintenance.php?header=" + header_id + "&area=" + area_id + "&checkList=" + checkList_id);
	$('#maintenance_result_popup').css({'left':(windowWidth/2)-200, 'top':(windowHeight/2)-250});
	$('#maintenance_result_popup').fadeTo("slow", 1);
}

$(window).resize(function () {
	var maskHeight = $(document).height();  
	var maskWidth = $(document).width();
	var windowHeight = $(window).height();
	var windowWidth = $(window).width();

	$('#mask').css({'width':maskWidth, 'height':maskHeight});
	$('#maintenance_result_popup').css({'left':(windowWidth/2)-200, 'top':(windowHeight/2)-250});
});
 
</script>

<div id="mask" style="position:absolute; z-index:900; background-color:#000; display:none; left:0; top:0;"></div>
<div id="maintenance_result_popup" style="width:400px; height:500px; position:fixed; z-index:1000; border:2px solid black; background-color:#FFF; display:none;">
	<iframe id="maintenance_iframe" width="100%" height="100%" frameborder="0"></iframe>
</div>

<td width="" align="left" valign="top">
	<table width="100%">
		<tr>
			<td height="40" colspan=2>
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title" style="letter-spacing:0;">점검지 - View</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30" class="doc_submit_area" colspan=2>
				<table width="100%">
					<tr>
						<td align="left">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="print(<?=$id; ?>)">인쇄</td>
								</tr>
							</table>
						</td>
						<? if(($header_row['confirmor1'] == $_SESSION['memberID'] && $header_row['confirmor1_check'] == 0) || ($header_row['confirmor2'] == $_SESSION['memberID'] && $header_row['confirmor2_check'] == 0) || ($header_row['confirmor3'] == $_SESSION['memberID'] && $header_row['confirmor3_check'] == 0)) { ?>
							<td align="right" style="padding: 0 12px 0 0;"><button class="doc_submit_btn_style" onClick="confirmor_confirm(<?=$header_row['id']; ?>)">확인</td>
						<? } ?>
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
							<div style="font-size:18px; font-weight:bold; text-align:center; padding:10px 0; background-color:#D8D8D8">점검지</div>
							<table width="100%" style="padding:10px 0; background-color:#D8D8D8; border-bottom:1px solid black;">
								<tr><td>
								<div style="padding:2px 5px; float:left;">
									<div>번호:&nbsp&nbsp&nbsp&nbsp&nbsp <?=$header_row['id']; ?></div>
									<div>날짜:&nbsp&nbsp&nbsp&nbsp&nbsp <?=$header_row['date']; ?></div>
									<div>점검자:&nbsp&nbsp <?=get_user_name($header_row['inspector']); ?></div>
								</div>
								<div style="padding:2px 5px; float:right;">
									<div>
										확인자 1:&nbsp&nbsp <?=get_user_name($header_row['confirmor1']); ?>&nbsp&nbsp<?=(($header_row['confirmor1_check'] == 1) ? "<font color='blue'><b>O</b></font>" : "<font color='red'><b>X</b></font>" ); ?>
									</div>
									<div>
										확인자 2:&nbsp&nbsp <?=get_user_name($header_row['confirmor2']); ?>&nbsp&nbsp<?=(($header_row['confirmor2_check'] == 1) ? "<font color='blue'><b>O</b></font>" : "<font color='red'><b>X</b></font>" ); ?>
									</div>
									<div>
										확인자 3:&nbsp&nbsp <?=get_user_name($header_row['confirmor3']); ?>&nbsp&nbsp<?=(($header_row['confirmor3_check'] == 1) ? "<font color='blue'><b>O</b></font>" : "<font color='red'><b>X</b></font>" ); ?>
									</div>
								</div>
								</tr></td>
							</table>
							<div style="height:777px; overflow-x:hidden; clear:both;">
								<table width="100%" id="result_table" style="padding:10px 0;">
									<? while($history_row = mssql_fetch_array($history_query_result)) { ?>
										<? if($history_row['checkList_id'] == 1) { ?>
											<tr id="result_area<?=$history_row['area_id']; ?>_0">
												<td colspan=3 style="background-color:#F6CECE; font-weight:bold">Area <?=$history_row['area_id']; ?></td>
											</tr>
										<? } ?>
										<?
										$comment_iconv = $history_row['comment'];
										$comment_iconv =  str_replace("\'", "'", $comment_iconv);
										$comment_iconv =  str_replace('\"', '"', $comment_iconv);

										$maintenance_query = "SELECT status, comment ".
															 "FROM property_maintenance ".
															 "WHERE header_id = $id AND area_id = ".$history_row['area_id']." AND checkList_id = ".$history_row['checkList_id'];
										$maintenance_query_result = mssql_query($maintenance_query);
										$maintenance_row = mssql_fetch_array($maintenance_query_result);

										if($maintenance_row['status'] == 1)			$status = "수리중";
										elseif($maintenance_row['status'] == 2)		$status = "수리완료";						
										$log_iconv = $maintenance_row['comment'];
										$log_iconv =  str_replace("\'", "'", $log_iconv);
										$log_iconv =  Br_iconv(str_replace('\"', '"', $log_iconv));
										?>
										<tr style="background-color:#CECEF6">
											<? if($history_row['condition'] == 1) { ?>
												<td width="180"><?=Br_iconv($history_row['checkList_description']); ?></td>
												<td width="60" style="font-weight:bold; color:blue;">Good</td>
												<td style="text-align:left;"><?=Br_iconv($comment_iconv); ?></td>
											<? } else { ?>
												<? if($maintenance_row['status'] == 0) { ?>
													<td width="180"><?=Br_iconv($history_row['checkList_description']); ?></td>
													<td width="60" style="font-weight:bold; color:red; cursor:pointer;" onClick="maintenance(<?=$id; ?>, <?=$history_row['area_id']; ?>, <?=$history_row['checkList_id']; ?>)">Bad</td>
													<td style="text-align:left;"><?=Br_iconv($comment_iconv); ?></td>
												<? } else { ?>
														<td width="180" rowspan=2><?=Br_iconv($history_row['checkList_description']); ?></td>
														<td width="60" rowspan=2 style="font-weight:bold; color:red; cursor:pointer;" onClick="maintenance(<?=$id; ?>, <?=$history_row['area_id']; ?>, <?=$history_row['checkList_id']; ?>)">Bad</td>
														<td style="text-align:left;"><?=Br_iconv($comment_iconv); ?></td>
													</tr>
													<tr style="background-color:#CECEF6">
														<td style="text-align:left;">
															<font color="<?=(($maintenance_row['status'] == 1) ? "red" : "blue"); ?>" style="text-decoration:underline"><b><?=$status; ?></b></font><br><br>
															<pre><?=$log_iconv; ?></pre>
														</td>
												<? } ?>
											<? } ?>
										</tr>
									<? } ?>
								</table>
							</div>
							<input type="hidden" name="completed_area" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30" class="doc_submit_area" colspan=2>
				<table width="100%">
					<tr>
						<td align="left">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="print(<?=$id; ?>)">인쇄</td>
								</tr>
							</table>
						</td>
						<? if(($header_row['confirmor1'] == $_SESSION['memberID'] && $header_row['confirmor1_check'] == 0) || ($header_row['confirmor2'] == $_SESSION['memberID'] && $header_row['confirmor2_check'] == 0) || ($header_row['confirmor3'] == $_SESSION['memberID'] && $header_row['confirmor3_check'] == 0)) { ?>
							<td align="right" style="padding: 0 12px 0 0;"><button class="doc_submit_btn_style" onClick="confirmor_confirm(<?=$header_row['id']; ?>)">확인</td>
						<? } ?>
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
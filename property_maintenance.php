<?
include_once "includes/db_configms.php";
include_once "includes/common_class.php";

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$header = ($_GET['header']) ? $_GET['header'] : $_POST['header'];
$area = ($_GET['area']) ? $_GET['area'] : $_POST['area'];
$checkList = ($_GET['checkList']) ? $_GET['checkList'] : $_POST['checkList'];

if($mode == "updateReport") {
	$maintenance_status = $_POST['maintenance_status'];
	$maintenance_log = $_POST['maintenance_log'];
	$maintenance_log_dconv = str_replace("'", "''", $maintenance_log);
	$maintenance_log_dconv = Br_dconv($maintenance_log_dconv);

	// check maintenance db first whether log existed 
	$checkMaintenance_query = "SELECT status ".
							  "FROM property_maintenance ".
							  "WHERE header_id = $header AND area_id = $area AND checkList_id = $checkList";
	$checkMaintenance_query_result = mssql_query($checkMaintenance_query);
	$checkMaintenance_row = mssql_fetch_array($checkMaintenance_query_result);

	if(!$checkMaintenance_row) {
		// not exist
		$updateMaintenance_query = "INSERT INTO property_maintenance (header_id, area_id, checkList_id, status, comment) ".
								   "VALUES ($header, $area, $checkList, $maintenance_status, '$maintenance_log_dconv')";
	} else {
		// already exist
		$updateMaintenance_query = "UPDATE property_maintenance SET ".
								   "status = $maintenance_status, ".
								   "comment = '$maintenance_log_dconv', ".
								   "mod_date = getdate() ".
								   "WHERE header_id = $header AND area_id = $area AND checkList_id = $checkList";
	}
	mssql_query($updateMaintenance_query);
}

$history_query = "SELECT area_id, checkList_id, checkList_description, condition, comment ".
				 "FROM property_content ".
				 "WHERE header_id = $header AND area_id = $area AND checkList_id = $checkList ";
$history_query_result = mssql_query($history_query);
$history_row = mssql_fetch_array($history_query_result);

$maintenance_query = "SELECT status, comment ".
					 "FROM property_maintenance ".
					 "WHERE header_id = $header AND area_id = $area AND checkList_id = $checkList ";
$maintenance_query_result = mssql_query($maintenance_query);
$maintenance_row = mssql_fetch_array($maintenance_query_result);

if($maintenance_row) {
	$status = $maintenance_row['status'];
	$log = $maintenance_row['comment'];
	$log =  str_replace("\'", "'", $log);
	$log =  str_replace('\"', '"', $log);
} else {
	$status = 0;
	$log = "";
}
?>
<style>
body {
	margin: 0;
}

.maintenance_table {
	width: 100%;
	border-collapse: collapse;
}
.maintenance_table td {
	text-align: center;
	padding: 3px 5px;
	font-size: 15px;
	text-align: left;
}
</style>

<script>
function closeMaintenanceResult() {
	parent.$("#mask").fadeOut("slow");
	parent.$("#maintenance_result_popup").hide();
}

function updateReport() {
	var answer = confirm("작성을 완료 하시겠습니까?");
	if(answer) {
		document.forms.maintenance_report.mode.value = "updateReport";
		document.forms.maintenance_report.submit();
		parent.$("#mask").fadeOut("slow");
		parent.$("#maintenance_result_popup").hide();
		parent.location.reload();
	}
}
</script>

<table width="100%" style="margin:0;" cellspacing=0 cellpadding=0>
	<tr height="30">
		<td style="padding-left:10px; background-color:#F6CECE;">
			<table width="100%">
				<tr>
					<td><b>Maintenance Report</b></td>
					<td width="25" align="left"><img id="closeBtn" src="css/img/bt_closelayer.gif" style="width:20px; cursor:pointer;" onClick="closeMaintenanceResult()"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr height="440px">
		<td style="background-color:#CECEF6; vertical-align:top; padding:10px;">
			<table class="maintenance_table" cellspacing=0 cellpadding=0>
				<tr>
					<td width="60px"><b>Area</b></td>
					<td width="5px">:</td>
					<td><?=$history_row['area_id']; ?></td>
				</tr>
				<tr>
					<td width="60px"><b>List</b></td>
					<td width="5px">:</td>
					<td><?=Br_iconv($history_row['checkList_description']); ?></td>
				</tr>
				<tr>
					<td width="60px"><b>Condition</b></td>
					<td width="5px">:</td>
					<td style="font-weight:bold; color:<?=(($history_row['condition'] == 1) ? "blue;" : "red;" ); ?>"><?=(($history_row['condition'] == 1) ? "Good" : "Bad" ); ?></td>
				</tr>
				
				<form name="maintenance_report" action="property_maintenance.php" method="post">
				<input type="hidden" name="mode">
				<input type="hidden" name="header" value=<?=$header; ?>>
				<input type="hidden" name="area" value=<?=$area; ?>>
				<input type="hidden" name="checkList" value=<?=$checkList; ?>>
				<tr>
					<?
					$comment_iconv = $history_row['comment'];
					$comment_iconv =  str_replace("\'", "'", $comment_iconv);
					$comment_iconv =  str_replace('\"', '"', $comment_iconv);
					?>
					<td width="60px"><b>Comment</b></td>
					<td width="5px">:</td>
					<td style="padding-top:10px;"><div style="max-height:120px; overflow-x:hidden; clear:both; vertical-align:middle;"><?=Br_iconv($comment_iconv); ?></div></td>
				</tr>
				<tr height="10px"><td colspan=3 style="border-bottom:1px solid grey"></td></tr>
				<tr height="10px"><td colspan=3></td></tr>
				<tr>
					<td width="60px"><b>Status</b></td>
					<td width="5px">:</td>
					<td style="padding-top:7px;">
						<select name="maintenance_status" style="width:100px;">
							<option value="0" <?=(($status == 0) ? "selected" : ""); ?>>수리전</option>
							<option value="1" <?=(($status == 1) ? "selected" : ""); ?>>수리중</option>
							<option value="2" <?=(($status == 2) ? "selected" : ""); ?>>수리완료</option>
						</select>
					</td>
				</tr>
				<tr>
					<td width="60px"><b>Log</b></td>
					<td width="5px">:</td>
					<td><textarea name="maintenance_log" style="width:100%; resize:none;" rows=10;><?=Br_iconv($log); ?></textarea></td>
				</tr>
				</form>
			</table>
		</td>
	</tr>
	<tr height="30">
		<td style="padding-left:20px; background-color:#F6CECE;">
			<table width="100%">
				<tr>
					<td align="center"><button onClick="updateReport()">저장</button>&nbsp;&nbsp;<button onClick="closeMaintenanceResult()">취소</button></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
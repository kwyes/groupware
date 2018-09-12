<?
include_once "includes/db_configms_HN.php";

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$company = ($_GET['comp']) ? $_GET['comp'] : $_POST['comp'];

if($mode != "employee_sample_code" && $mode != "employee_code" && $mode != "employee_name") {
	echo '<link href="../css/style.css" rel="stylesheet" type="text/css" />';
}

if($mode == "employee_name") {
	$name = ($_GET['name']) ? $_GET['name'] : $_POST['name'];
	$name = iconv('utf-8', 'euc-kr', $name);
	$company_table = array("dt_stf_tb", "dt_stf_manna", "dt_stf_bby", "dt_stf_sry", "dt_stf_wv");

	$name_query = "SELECT hnm FROM ".$company_table[$company-1]." WHERE hnm = '".$name."'";
	$name_query_result = mssql_query($name_query) or die ('Database connetion failed');
	$name_row = mssql_fetch_array($name_query_result);

	if($name_row['hnm']) {
		echo 0;
	} else {
		echo 1;
	}
}

if($mode == "department_list") {
	if($company != 0) {
		$depart_list = array();
		$company_table = array("ft_buseo_tb", "ft_buseo_manna", "ft_buseo_bby", "ft_buseo_sry", "ft_buseo_wv");

		$depart_query = "SELECT cd, nm FROM ".$company_table[$company-1]." ORDER BY cd";
		$depart_query_result = mssql_query($depart_query) or die ('Database connetion failed');
		while($depart_row = mssql_fetch_array($depart_query_result)) {
			$temp = array($depart_row['cd'], iconv('euc-kr', 'utf-8', $depart_row['nm']));
			array_push($depart_list, $temp);
		}
	} else {
		$depart_list = NULL;
	}

	$depart = ($_GET['depart']) ? $_GET['depart'] : $_POST['depart'];
?>
	<div style="height:30; margin-top:5px;">
		<select id="hr_department" name="hr_department" style="width:90%; max-width:250px; width:250px;">
			<option value=""> --- 부서 선택 (회사선택 필수) --- </option>
			<? for($i = 0; $i < sizeof($depart_list); $i++) { ?>
				<option value="<?=$depart_list[$i][0]; ?>" <?=(($depart_list[$i][1] == $depart) ? "selected" : ""); ?>><?=$depart_list[$i][1]; ?></option>
			<? } ?>
		</select>
	</div>
<?
}


if($mode == "employee_code") {
	$code = ($_GET['code']) ? $_GET['code'] : $_POST['code'];
	$company_table = array("dt_stf_tb", "dt_stf_manna", "dt_stf_bby", "dt_stf_sry", "dt_stf_wv");

	$code_query = "SELECT id FROM ".$company_table[$company-1]." WHERE id = ".$code;
	$code_query_result = mssql_query($code_query) or die ('Database connetion failed');
	$code_row = mssql_fetch_array($code_query_result);

	if($code_row['id']) {
		echo 0;
	} else {
		echo 1;
	}
}

if($mode == "employee_list") {
	$company_table = array("dt_stf_tb", "dt_stf_manna", "dt_stf_bby", "dt_stf_sry", "dt_stf_wv");
	if($company == 1) {
		$rangeS = 101;
		$rangeE = 1000;
	} else if($company == 2 || $company == 5) {
		$rangeS = 100;
		$rangeE = 1000;
	} else if($company == 3) {
		$rangeS = 1010;
		$rangeE = 10000;
	} else {
		$rangeS = 1207;
		$rangeE = 10000;
	}

	$employeeList_query = "SELECT id, hnm, seq ".
						  "FROM ".$company_table[$company-1]." ".
						  "WHERE id BETWEEN ".$rangeS." AND ".$rangeE." ".
						  "ORDER BY id";
	$employeeList_query_result = mssql_query($employeeList_query) or die ('Database connetion failed');
?>
	<tr height="378px">
		<td align="center" style="padding:5px 10px 5px 10px; border:1px;" border="1" bordercolor="#c9c9c9">
			<table width="100%">
				<tr height="30" style="border-bottom:1px solid #CCC">
					<td width="30%" align="center" style="padding-top:8px;"><b>직원코드</b></td>
					<td width="40%" align="center" style="padding-top:8px;"><b>이름</b></td>
					<td width="30%" align="center" style="padding-top:8px;"><b>Seq</b></td>
				</tr>

				<? while($employeeList_row = mssql_fetch_array($employeeList_query_result)) { ?>
					<tr height="20" style="border-bottom:1px dotted #CCC">
						<td align="center" style="padding-top:5px;"><?=($employeeList_row['id']); ?></td>
						<td align="center" style="padding-top:5px;"><?=iconv('euc-kr', 'utf-8', $employeeList_row['hnm']); ?></td>
						<td align="center" style="padding-top:5px;"><?=iconv('euc-kr', 'utf-8', $employeeList_row['seq']); ?></td>
					</tr>
				<? } ?>

			</table>
		</td>
	</tr>
<?
}
?>
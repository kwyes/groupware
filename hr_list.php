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

function page_navigation(list) {
	document.forms.item_list_page.list.value = list;
	document.forms.item_list_page.submit();
}

function page_navigation_search(mode, list) {
	document.forms.form_search.list.value = list;
	document.forms.form_search.submit();
}

function add_employee() {
	location.replace("?page=hr&menu=new");
}

</script>

<?
include_once "includes/db_configms_HN.php";
$loginid = $_SESSION['memberID'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$list = ($_GET['list']) ? $_GET['list'] : $_POST['list'];
$company = ($_GET['search_hrCompany']) ? $_GET['search_hrCompany'] : $_POST['search_hrCompany'];

if(!isset($company)) {
	$company = 1;
}

switch ($company) {
	default: 
		$tablename = "dt_stf_tb";
		$tablename2 = "dt_trans_buseo_tb";
		$tablename3 = "ft_buseo_tb";
		$dt_trans_position = "dt_trans_position_tb";
		$company_name = "T-Brothers Food & Trading Ltd.";
		break;
	case "1" : 
		$tablename = "dt_stf_tb";
		$tablename2 = "dt_trans_buseo_tb";
		$tablename3 = "ft_buseo_tb";
		$dt_trans_position = "dt_trans_position_tb";
		$company_name = "T-Brothers Food & Trading Ltd.";
		break;
	case "2" : 
		$tablename = "dt_stf_manna";
		$tablename2 = "dt_trans_buseo_manna";
		$tablename3 = "ft_buseo_manna";
		$dt_trans_position = "dt_trans_position_manna";
		$company_name = "Manna International Ltd.";
		break;
	case "3" : 
		$tablename = "dt_stf_bby";
		$tablename2 = "dt_trans_buseo_bby";
		$tablename3 = "ft_buseo_bby";
		$dt_trans_position = "dt_trans_position_bby";
		$company_name = "Hannam Supermaket Burnaby";
		break;
	case "4" :
		$tablename = "dt_stf_sry";
		$tablename2 = "dt_trans_buseo_sry";
		$tablename3 = "ft_buseo_sry";
		$dt_trans_position = "dt_trans_position_sry";
		$company_name = "Hannam Supermaket Surrey";
		break;
	case "5" : 
		$tablename = "dt_stf_wv";
		$tablename2 = "dt_trans_buseo_wv";
		$tablename3 = "ft_buseo_wv";
		$dt_trans_position = "dt_trans_position_wv";
		$company_name = "Westview Investment Inc";
		break;
}
$ft_position_com = "ft_position_com";


if(!isset($list)) {
	$list = 1;
}

$per_page = 20;
//$last_page = ($list * $per_page) - $per_page;
$last_page = (($list - 1) * $per_page);

if($mode == "search") {
	$search_hrStatus = ($_GET['search_hrStatus']) ? $_GET['search_hrStatus'] : $_POST['search_hrStatus'];
	$hrStatus = ($search_hrStatus == 1) ? " AND term_dt IS NULL " : "";
	$hrName = ($_GET['search_hrName']) ? Br_dconv($_GET['search_hrName']) : Br_dconv($_POST['search_hrName']);
	$hrId = ($_GET['search_hrId']) ? $_GET['search_hrId'] : $_POST['search_hrId'];
	$search_bday = ($_GET['search_bday']) ? $_GET['search_bday'] : $_POST['search_bday'];


	$page_total_query = "SELECT * ".
						"FROM $tablename ".
						"WHERE id LIKE '%$hrId%' AND hnm LIKE '%$hrName%' ".$hrStatus.
						"ORDER BY seq ASC";
	$page_total_result = mssql_query($page_total_query);
	$total = mssql_num_rows($page_total_result);
	$page_total = ceil($total / $per_page);

	$query = "SELECT TOP ".$per_page." hnm, id, term_dt, imageYN ".
			 "FROM $tablename ".
			 "WHERE id LIKE '%$hrId%' AND hnm LIKE '%$hrName%' ".$hrStatus." AND id NOT IN (SELECT TOP ".$last_page." id FROM $tablename WHERE id LIKE '%$hrId%' AND hnm LIKE '%$hrName%' ".$hrStatus." ORDER BY seq ASC) ".
			 "ORDER BY seq ASC";
	$query_result = mssql_query($query);
	$query_num = mssql_num_rows($query_result);

} else {
	$page_total_query = "SELECT * FROM $tablename WHERE term_dt IS NULL ORDER BY seq ASC";
	$page_total_result = mssql_query($page_total_query);
	$total = mssql_num_rows($page_total_result);
	$page_total = ceil($total / $per_page);

	$query = "SELECT TOP ".$per_page." hnm, id, term_dt , imageYN ".
			 "FROM $tablename ".
			 "WHERE term_dt IS NULL AND id NOT IN (SELECT TOP ".$last_page." id FROM $tablename WHERE term_dt IS NULL ORDER BY seq ASC) ".
			 "ORDER BY seq ASC";
	$query_result = mssql_query($query);
	$query_num = mssql_num_rows($query_result);
}
?>


<!-- e-doc Main START -->
<td height="500" align="left" valign="top">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">인사관리 > 사원리스트</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>

		<!--tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr-->

		<form name="form_search" action="?page=hr" enctype="multipart/form-data" method="post" accept-charset="utf-8">
		<input type="hidden" name="mode" value="search">
		<input type="hidden" name="list" value=1>
		<tr style = "border-collapse: collapse; border-top: 1px #c9c9c9 solid;border-right: 1px #c9c9c9 solid;">
			<td style="padding:12px 25px">
				<table class="doc_border">
					<tr height="30">
						<td width="80" align="center" class="doc_field_name"><b>회사</b></td>
						<td width="200" style="padding: 4px 0 0 10px;">
							<select name="search_hrCompany" style="width:187px;">
<?								include_once "includes/db_configms.php"; ?>
<?								$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
								$row2 = mssql_query($query, $conn);
?>								
								<option value=""> -- 회사선택 -- </option>
								

<?								while($rst = mssql_fetch_array($row2)) { ?>
									<option value='<?=$rst['companyID']; ?>' <?if($rst['companyID'] == $company) echo "selected"; ?>><?=$rst['companyDesc']; ?></option>
<?								} ?>


							</select>
						</td>
						<td width="80" align="center" class="doc_field_name"><b>계정상태</b></td>
						<td width="150" style="padding: 4px 0 0 10px; border-right: 1;">
							<input name="search_hrStatus" type="radio" value="0" <?=($search_hrStatus == 0) ? "checked" : ""; ?>>All</input>
							<input name="search_hrStatus" type="radio" value="1" <?=($search_hrStatus == 1 || $search_hrStatus == "") ? "checked" : ""; ?>>Active Only</input>
						</td>
					
						
						
							
						

					</tr>
					<tr height="30">
						<td width="80" align="center" class="doc_field_name"><b>이름</b></td>
						<td width="200" style="padding: 4px 0 0 10px;"><input name="search_hrName" type="text" style="width:184px; font-size:12px;" value="<?=$_POST['search_hrName']; ?>"></input></td>
						<td width="80" align="center" class="doc_field_name"><b>직원코드</b></td>
						<td width="300" style="padding: 4px 0 0 10px; border-right: 1;"><input name="search_hrId" type="text" style="width:184px; font-size:12px;" value="<?=$_POST['search_hrId']; ?>"></input></td>
								
						</td>
					</tr>
					<tr height="30">
						<td colspan=5 style="padding:4px 0 0 20px"><button>검색</button></td>
					</tr>
				</table>				
			</td>
		</tr>
		</form>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td style="padding: 0 12px 0 0;">
							<table>
								<tr>
<?								include_once "includes/db_configms_HN.php"; ?>
<?								$queryforbday = "SELECT * from ".$tablename." where convert(varchar(5),[birth_dt],110) = convert(varchar(5),getdate(),110) AND term_dt is null";
								$rowforbday = mssql_query($queryforbday, $conn_HN);
?>
								<td style = "font-size:16px;padding-top:3px;FONT-FAMILY: 굴림,돋움,verdana,arial,helvetica;">
								<? if(mssql_num_rows($rowforbday) > 0) 
									echo "<span style=color:#2E9AFE;>생일이신 직원분 : </span>";
								?>
								
<?								while($rstforbday = mssql_fetch_array($rowforbday)) { ?>
									<? echo @iconv("euc-kr","utf-8",$rstforbday['hnm']); ?>
<?								} ?>
								<? if(mssql_num_rows($rowforbday) > 0) 
									echo "<span style=color:#B50D0D;>  생일 축하드립니다</span>";
								?>
								</td>	
									
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr height="30">
			<td align="right" style = "padding:0 12px 0 0;"><button class="doc_submit_btn_style" onClick="add_employee()">신규등록</td>
		</tr>

		<tr style="margin-top:20px;">
			<td>
				<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
					<tr height="20">
						<td width="100" align="left" class="title bb br">아이디</td>
						<td width="100" align="left" class="title bb br">이름</td>
						<td width="200" align="left" class="title bb br">회사</td>
						<td width="100" align="left" class="title bb br">부서</td>
						<td width="60" align="left" class="title bb br">직급</td>
						<td width="60" align="left" class="title bb br">직책</td>
						<td width="60" align="left" class="title bb br">사진상태</td>
						<td align="left" class="title bb br">근무상태</td>
					</tr>

<?					if($query_num == 0) { ?>
						<tr height="60">
							<td align="center" class="bb" colspan="8" style="padding-top:25px;">
								<b>검색된 사원이 없습니다.</b>
							</td>
						</tr>
<?					} else {

						while($row = mssql_fetch_array($query_result)) { 

							if($row['imageYN'] != 'Y') {
								$pic_path = "http://184.69.79.114:8000/photo2/".$row['hnm'].".jpg";
								$pic_headers = @get_headers($pic_path);

								if($pic_headers[0] == "HTTP/1.1 404 Not Found") {
										$imageYN = "N";
								} else {
										$imageYN = "Y";
								}

								$Query = "UPDATE $tablename SET ".
												"imageYN='$imageYN' ".
												"WHERE id =  '".$row['id']."' ";
								mssql_query($Query);
							} else {
								$imageYN = "Y";
							}

							$depart_query="select nm from $tablename3 where cd IN (select top 1 buseo from $tablename2 where id = ".$row['id']." order by dt desc, ipdt desc)";
							$depart_query_result=mssql_query($depart_query);
							$depart_row=mssql_fetch_array($depart_query_result);

							$position_query = "SELECT nm FROM hr_position WHERE cd = (SELECT TOP 1 hr_position FROM hr_stf_position WHERE company_cd = $company AND id = ".$row['id']." ORDER BY dt DESC)";
							$position_query_result = mssql_query($position_query);
							$position_row = mssql_fetch_array($position_query_result);
							$title_query = "SELECT nm FROM hr_title WHERE cd = (SELECT TOP 1 hr_title FROM hr_stf_position WHERE company_cd = $company AND id = ".$row['id']." ORDER BY dt DESC)";
							$title_query_result = mssql_query($title_query);
							$title_row = mssql_fetch_array($title_query_result);
?>
							<tr height="25">
								<td class="docid bb"><a href="javascript:post_to_url('?page=hr&menu=view', {'id':<?=$row['id']; ?>, 'company':<?=$company; ?>});"><?=$row['id']; ?></a></td>
								<td class="content bb"><?=Br_iconv($row['hnm']); ?></td>
								<td class="content bb"><?=$company_name; ?></td>
								<td class="content bb"><?=Br_iconv($depart_row['nm']); ?></td>
								<td class="content bb"><?=Br_iconv($position_row['nm']); ?></td>
								<td class="content bb"><?=Br_iconv($title_row['nm']); ?></td>
								<td class="content bb"><font color="red" style="font-weight:bold;"><?=($imageYN != "Y") ? "X" : "O"; ?></font></td>
								<td class="content bb"><font color="red" style="font-weight:bold;"><?=($row['term_dt'] == NULL) ? "O" : "X"; ?></font></td>
							</tr>
<?						} ?>
<?					} ?>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30"></td>
		</tr>

		<tr>
			<td align="center">
<?
				for($i = 1+(floor(($list-1)/10)*10) ; $i <= $page_total ; $i++) {
					if(($i-1)%10 == '0' && $i != '1') {
						$temp_page = floor(($list-1)/10)*10;
						if($mode == "search") {
							echo "&nbsp<a href='javascript:page_navigation_search(\"$mode\", $temp_page)'>◀</a> \n";
						} else {
							echo "&nbsp<a href='javascript:page_navigation($temp_page)'>◀</a> \n";
						}
					}
					if($i == 1+(floor(($list-1)/10)*10)) {
						echo "&nbsp<font color='#A4A4A4'> | </font>&nbsp";
					}
					if($i == $list) {
						if($mode == "search") {
							echo "<b><a style='color:red; text-decoration:underline;' href='javascript:page_navigation_search(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						} else {
							echo "<b><a style='color:red; text-decoration:underline;' href='javascript:page_navigation($i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						}
					} else {
						if($mode == "search") {
							echo "<b><a href='javascript:page_navigation_search(\"$mode\", $i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						} else {
							echo "<b><a href='javascript:page_navigation($i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						}
					}
					if($i%10 == '0') {
						$i++;
						if($mode == "search") {
							echo "<a href='javascript:page_navigation_search(\"$mode\", $i)'>▶</a> \n";
						} else {
							echo "<a href='javascript:page_navigation($i)'>▶</a> \n";
						}
						break;
					}
				}	
?>
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

<form name="item_list_page" action="?page=hr"  method="POST" accept-charset="utf-8">
	<input type="hidden" name="list" value="<?=$list; ?>">
</form>
<!-- e-doc Main END -->
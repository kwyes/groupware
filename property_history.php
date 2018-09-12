<?
$list = ($_GET['list']) ? $_GET['list'] : $_POST['list'];

$per_page = 20;
if(!isset($list))	$list = 1;
$last_page = (($list - 1) * $per_page);

$totalPage_query = "SELECT id FROM property_header";
$totalPage_query_result = mssql_query($totalPage_query);
$totalPage_num_row = mssql_num_rows($totalPage_query_result);

$history_query = "SELECT TOP ".$per_page." *, CONVERT(char(10), date, 120) AS date FROM property_header ".
				 "WHERE id NOT IN (SELECT TOP ".$last_page." id FROM property_header ORDER BY id DESC) ".
				 "ORDER BY id DESC";
$history_query_result = mssql_query($history_query);
$history_num_row = mssql_num_rows($history_query_result);

$page_total = ceil($totalPage_num_row / $per_page);
?>

<script>
function page_navigation(list) {
	location.href = "?page=property&menu=history&list=" + list;
}
</script>

<td width="" align="left" valign="top">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">점검 History - List</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>

		<tr>
			<td>
				<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
					<tr height="20">
						<td width="60" class="title bb br">번호</td>
						<td width="160" class="title bb br">점검일</td>
						<td width="100" class="title bb br">점검자</td>
						<td width="60" class="title bb">확인자 1</td>
						<td width="30" class="title bb br"></td>
						<td width="60" class="title bb">확인자 2</td>
						<td width="30" class="title bb br"></td>
						<td width="60" class="title bb">확인자 3</td>
						<td width="30" class="title bb br"></td>
						<td width="60" class="title bb br">수리전</td>
						<td width="60" class="title bb br">수리중</td>
						<td width="60" class="title bb br">수리완료</td>
						<td width="" class="title bb"></td>
					</tr>
			

					<? if($history_num_row == 0) { ?>
						<tr height="60px">
							<td align="center" class="bb" colspan="10" style="padding-top:25px;"><b>데이터가 없습니다.</b></td>
						</tr>
					<? } else { ?>
						<? while($history_row = mssql_fetch_array($history_query_result)) { ?>
							<?
							// condition = bad 갯수
							$countBad_query = "SELECT count(checkList_id) AS badNum ".
											  "FROM property_content ".
											  "WHERE header_id = ".$history_row['id']." AND condition = 0";
							$countBad_query_result = mssql_query($countBad_query);
							$countBad_row = mssql_fetch_array($countBad_query_result);

							// 수리중 갯수
							$fixIng_query = "SELECT count(checkList_id) AS fixIng ".
											"FROM property_maintenance ".
											"WHERE header_id = ".$history_row['id']." AND status = 1";
							$fixIng_query_result = mssql_query($fixIng_query);
							$fixIng_row = mssql_fetch_array($fixIng_query_result);

							// 수리완료 갯수
							$fixDone_query = "SELECT count(checkList_id) AS fixDone ".
											 "FROM property_maintenance ".
											 "WHERE header_id = ".$history_row['id']." AND status = 2";
							$fixDone_query_result = mssql_query($fixDone_query);
							$fixDone_row = mssql_fetch_array($fixDone_query_result);
							?>
							<tr height="25">
								<td class="docid bb"><a href="?page=property&menu=view&id=<?=$history_row['id']; ?>"><?=$history_row['id']; ?></a></td>
								<td class="content bb"><a href="?page=property&menu=view&id=<?=$history_row['id']; ?>"><?=$history_row['date']; ?></a></td>
								<td class="content bb"><?=get_user_name($history_row['inspector']); ?></td>
								<td class="content bb"><?=get_user_name($history_row['confirmor1']); ?></td>
								<td class="content bb"><?=(($history_row['confirmor1_check'] == 1) ? "<font color='blue'><b>O</b></font>" : "<font color='red'><b>X</b></font>" ); ?></td>
								<td class="content bb"><?=get_user_name($history_row['confirmor2']); ?></td>
								<td class="content bb"><?=(($history_row['confirmor2_check'] == 1) ? "<font color='blue'><b>O</b></font>" : "<font color='red'><b>X</b></font>" ); ?></td>
								<td class="content bb"><?=get_user_name($history_row['confirmor3']); ?></td>
								<td class="content bb"><?=(($history_row['confirmor3_check'] == 1) ? "<font color='blue'><b>O</b></font>" : "<font color='red'><b>X</b></font>" ); ?></td>
								<td class="content bb" style="text-align:center;"><b><?=$countBad_row['badNum']-$fixIng_row['fixIng']-$fixDone_row['fixDone']; ?></b></td>
								<td class="content bb" style="text-align:center;"><b><?=$fixIng_row['fixIng']; ?></b></td>
								<td class="content bb" style="text-align:center;"><b><?=$fixDone_row['fixDone']; ?></b></td>
								<td class="content bb"></td>
							</tr>
					
						<? } ?>
					<? } ?>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30"></td>
		</tr>
		<tr>
			<td align="center">
				<?
				if($history_num_row) {
					$per_page_navi = 10;

					if(($list % $per_page_navi) == 0)	$prev_page = $list - $per_page_navi;
					else								$prev_page = ($list - ($list % $per_page_navi));
					//if($prev_page < 1)					$prev_page = 1;
					if(($list % $per_page_navi) == 0)	$next_page = $list + 1;
					else								$next_page = ($list + $per_page_navi) - ($list % $per_page_navi) + 1;
					if($next_page > $page_total)		$next_page = $page_total;

					$start_navi = 1 + (floor(($list-1)/$per_page_navi) * $per_page_navi);
					$end_navi = $start_navi + $per_page_navi - 1;
					if($end_navi > $page_total)		$end_navi = $page_total;

					for($i = $start_navi; $i <= $end_navi; $i++) {
						if($i == $start_navi) {
							if($prev_page >= 1) {
								echo "&nbsp<a href='javascript:page_navigation($prev_page)'>◀</a> \n";
							}
							echo "&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						}
						if($i == $list) {
							echo "<b><a style='color:red; text-decoration:underline;' href='javascript:page_navigation($i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						} else {
							echo "<b><a href='javascript:page_navigation($i)'>$i</a></b>"."&nbsp<font color='#A4A4A4'> | </font>&nbsp";
						}
						if($i == $end_navi && $i < $next_page) {
							echo "<a href='javascript:page_navigation($next_page)'>▶</a> \n";
						}
					}
				}
				?>
			</td>
		</tr>
	</table>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
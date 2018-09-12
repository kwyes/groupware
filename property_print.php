<link href="../css/style.css" rel="stylesheet" type="text/css" />

<?
include_once "includes/setup.php";

$id = ($_GET['id']) ? $_GET['id'] : $_POST['id'];

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

<!DOCTYPE html>
<html>
<body onload="window.focus();window.print();" style="margin:auto; padding:5px;">
<table width="100%" style="min-width:720px;">
	<tr>
		<td>
			<table>
				<tr>
					<td width="720px">
						<div style="font-size:18px; font-weight:bold; text-align:center; padding:10px 0;">점검지</div>
						<table width="100%" style="padding:10px 0; border-bottom:1px solid black;">
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
						<div>
							<table width="100%" id="result_table" style="padding:10px 0;">
								<? $i = 0; ?>
								<? while($history_row = mssql_fetch_array($history_query_result)) { ?>
									<? if($history_row['checkList_id'] == 1) { ?>
										<? $i++; ?>
										<tr id="result_area<?=$history_row['area_id']; ?>_0">
											<td colspan=3 style="font-weight:bold">Area <?=$history_row['area_id']; ?></td>
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
									<tr>
										<? if($history_row['condition'] == 1) { ?>
											<td width="180"><?=Br_iconv($history_row['checkList_description']); ?></td>
											<td width="60" style="font-weight:bold; color:blue;">Good</td>
											<td style="text-align:left;"><?=Br_iconv($comment_iconv); ?></td>
										<? } else { ?>
											<? if($maintenance_row['status'] == 0) { ?>
												<td width="180"><?=Br_iconv($history_row['checkList_description']); ?></td>
												<td width="60" style="font-weight:bold; color:red; cursor:pointer;">Bad</td>
												<td style="text-align:left;"><?=Br_iconv($comment_iconv); ?></td>
											<? } else { ?>
													<td width="180" rowspan=2><?=Br_iconv($history_row['checkList_description']); ?></td>
													<td width="60" rowspan=2 style="font-weight:bold; color:red; cursor:pointer;" >Bad</td>
													<td style="text-align:left;"><?=Br_iconv($comment_iconv); ?></td>
												</tr>
												<tr>
													<td style="text-align:left;">
														<font color="red" style="text-decoration:underline"><b><?=$status; ?></b></font><br><br>
														<pre><?=$log_iconv; ?></pre>
													</td>
											<? } ?>
										<? } ?>
										<!--
										<td width="100"><?=Br_iconv($history_row['checkList_description']); ?></td>
										<td width="60" style="font-weight:bold; color:<?=(($history_row['condition'] == 1) ? "blue" : "red" ); ?>"><?=(($history_row['condition'] == 1) ? "Good" : "Bad" ); ?></td>
										<td style="text-align:left;"><?=Br_iconv($comment_iconv); ?></td>
										-->
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
</table>
</body>
</html>
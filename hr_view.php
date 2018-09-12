<script>
function post_to_url(params) {
	var tabchked = document.querySelector('input[name="tab"]:checked').value;

	url = "?page=hr&menu=modify&tab="+tabchked;
	method = "POST";
	

	
	
	

	var form = document.createElement("form");
	form.setAttribute("method", method);
	form.setAttribute("action", url);

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
</script>


<?
include_once "includes/db_configms_HN.php";

$id = ($_GET['id']) ? $_GET['id'] : $_POST['id'];
$company = ($_GET['company']) ? $_GET['company'] : $_POST['company'];

switch ($company) {
	default: 
		$tablename = "dt_stf_tb";
		$tablename2 = "dt_trans_buseo_tb";
		$tablename3 = "ft_buseo_tb";
		$dt_trans_position = "dt_trans_position_tb";
		$company_name = "T-Brothers Food & Trading Ltd.";
		$company_Sname = "TB";
		$wage_table = "dt_trans_wage_tb";
		$deposit_table = "dt_deposit_tb";
		break;
	case "1" : 
		$tablename = "dt_stf_tb";
		$tablename2 = "dt_trans_buseo_tb";
		$tablename3 = "ft_buseo_tb";
		$dt_trans_position = "dt_trans_position_tb";
		$company_name = "T-Brothers Food & Trading Ltd.";
		$company_Sname = "TB";
		$wage_table = "dt_trans_wage_tb";
		$deposit_table = "dt_deposit_tb";
		break;
	case "2" : 
		$tablename = "dt_stf_manna";
		$tablename2 = "dt_trans_buseo_manna";
		$tablename3 = "ft_buseo_manna";
		$dt_trans_position = "dt_trans_position_manna";
		$company_name = "Manna International Ltd.";
		$company_Sname = "MANNA";
		$wage_table = "dt_trans_wage_manna";
		$deposit_table = "dt_deposit_manna";
		break;
	case "3" : 
		$tablename = "dt_stf_bby";
		$tablename2 = "dt_trans_buseo_bby";
		$tablename3 = "ft_buseo_bby";
		$dt_trans_position = "dt_trans_position_bby";
		$company_name = "Hannam Supermaket Burnaby";
		$company_Sname = "BBY";
		$wage_table = "dt_trans_wage_bby";
		$deposit_table = "dt_deposit_bby";
		break;
	case "4" :
		$tablename = "dt_stf_sry";
		$tablename2 = "dt_trans_buseo_sry";
		$tablename3 = "ft_buseo_sry";
		$dt_trans_position = "dt_trans_position_sry";
		$company_name = "Hannam Supermaket Surrey";
		$company_Sname = "SRY";
		$wage_table = "dt_trans_wage_sry";
		$deposit_table = "dt_deposit_sry";
		break;
	case "5" : 
		$tablename = "dt_stf_wv";
		$tablename2 = "dt_trans_buseo_wv";
		$tablename3 = "ft_buseo_wv";
		$dt_trans_position = "dt_trans_position_wv";
		$company_name = "Westview Investment Inc";
		$company_Sname = "WV";
		$wage_table = "dt_trans_wage_wv";
		$deposit_table = "dt_deposit_wv";
		break;
}
$tablename4 = "ft_city_com";
$tablename5 = "ft_province_com";
$ft_position_com = "ft_position_com";

/***** TAB 1: START *****/
$query = "SELECT *, CONVERT(char(10), birth_dt, 126) AS birth_dt, CONVERT(char(10), visa_dt, 126) AS visa_dt, CONVERT(char(10), ipsa_sdt, 126) AS ipsa_sdt, CONVERT(char(10), ipsa_dt, 126) AS ipsa_dt, CONVERT(char(10), term_dt, 126) AS term_dt, CONVERT(char(10), employeecard_dt, 126) AS employeecard_dt ".
		 "FROM $tablename ".
		 "WHERE id = $id ";
$query_result = mssql_query($query) or die ('Database connetion failed');
$row = mssql_fetch_array($query_result);

$depart_query = "select nm from $tablename3 where cd IN (select top 1 buseo from $tablename2 where id = ".$row['id']." order by dt desc, ipdt desc)";
$depart_query_result = mssql_query($depart_query) or die ('Database connetion failed');
$depart_row = mssql_fetch_array($depart_query_result);

$position_query = "SELECT nm FROM hr_position WHERE cd = (SELECT TOP 1 hr_position FROM hr_stf_position WHERE company_cd = $company AND id = ".$row['id']." ORDER BY dt DESC)";
$position_query_result = mssql_query($position_query);
$position_row = mssql_fetch_array($position_query_result);

$title_query = "SELECT nm FROM hr_title WHERE cd = (SELECT TOP 1 hr_title FROM hr_stf_position WHERE company_cd = $company AND id = ".$row['id']." ORDER BY dt DESC)";
$title_query_result = mssql_query($title_query);
$title_row = mssql_fetch_array($title_query_result);

$city_query = "select nm from $tablename4 where cd = ".$row['city'];
$city_query_result = mssql_query($city_query) or die ('Database connetion failed');
$city_row = mssql_fetch_array($city_query_result);

$province_query = "select long_nm from $tablename5 where country_cd = 0 AND province_cd = ".$row['province'];
$province_query_result = mssql_query($province_query) or die ('Database connetion failed');
$province_row = mssql_fetch_array($province_query_result);

$photo_path = "E:\d_backup\03.Timecard\Staff_Photo\00.모든부서";
/***** TAB 1: END *****/
?>

<td height="500" align="left" valign="top">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">인사관리 > 사원 상세정보</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="post_to_url({'id':<?=$row['id']; ?>, 'company':<?=$company; ?>});">수정모드</td>
									
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="10"></td>
		</tr>

		<tr><td>
		
		
		<div id="css_tabs">
			
			<input id="tab1" type="radio" name="tab" value="hr1" checked="checked">
			<input id="tab2" type="radio" name="tab" value="hr2">
			<input id="tab3" type="radio" name="tab" value="hr3">
			<input id="tab4" type="radio" name="tab" value="hr4">

			<label for="tab1">신상정보</label>
			<label for="tab2">인사정보</label>
			<label for="tab3">근태정보</label>
			<label for="tab4">업로드파일</label>
			
			<div class="tab1_content">
				<table>
				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table align="left" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="150" style="font-size:18px; border:0;"><b>인사정보</b></td>
											<td style="border:0;"></td>
											<td width="200" style="border:0;"></td>
											
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>소속회사</b></td>
											<td class="doc_field_content" style="width:406px;"><?=$company_name; ?></td>
											<td rowspan=8><img style="width:198px; height:208px; padding:1px;" src="http://184.69.79.114:8000/photo2/<?=Br_iconv($row['hnm']); ?>.jpg" onError="this.src='images/noImage.jpg'"></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>근무상태</b></td>
											<td class="doc_field_content" style="width:787px;"><font color="red" style="font-weight:bold;"><?=($row['term_dt'] == NULL) ? "O" : "X"; ?></font></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직원코드</b></td>
											<td class="doc_field_content"><?=$row['id']; ?></td>
											<td style="padding-left:10px;width:101px;" class="doc_field_name"><b>사원증 지급일자</b></td>
											<td class="doc_field_content"><font color="red" style="font-weight:bold;"><?=($row['employeecard_dt'] == NULL) ? "X" : "O"; ?></font></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>한글성명</b></td>
											<td class="doc_field_content"><?=Br_iconv($row['hnm']); ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>최초입사일</b></td>
											<td class="doc_field_content"><?=$row['ipsa_sdt']; ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>First Name</b></td>
											<td class="doc_field_content"><?=$row['first_nm']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>입사일</b></td>
											<td class="doc_field_content"><?=$row['ipsa_dt']; ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>Last Name</b></td>
											<td class="doc_field_content"><?=$row['last_nm']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>퇴사일</b></td>
											<td class="doc_field_content"><?=$row['term_dt']; ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>소속부서</b></td>
											<td class="doc_field_content"><?=Br_iconv($depart_row['nm']); ?></td>
											<td style="padding-left:10px;" class="doc_field_name" rowspan=3><b>메모</b></td>
											<td class="doc_field_content" rowspan=3><?=Br_iconv($row['bigo']); ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직급</b></td>
											<td class="doc_field_content"><?=Br_iconv($position_row['nm']); ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직책</b></td>
											<td class="doc_field_content"><?=Br_iconv($title_row['nm']); ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>개인정보</b></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>생년월일</b></td>
											<td class="doc_field_content" colspan=5><?=$row['birth_dt']; ?> <? echo (($row['birth_gubun'] == 0) ? "(양력)" : "(음력)"); ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>연락처 (Home)</b></td>
											<td class="doc_field_content"><?=$row['tel11']."-".$row['tel12']."-".$row['tel13']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>연락처 (Cell)</b></td>
											<td class="doc_field_content"><?=$row['tel21']."-".$row['tel22']."-".$row['tel23']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>E-mail</b></td>
											<td class="doc_field_content"><?=$row['email']; ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;"" class="doc_field_name"><b>Address</b></td>
											<td class="doc_field_content" colspan=5><?=$row['street']; ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>City</b></td>
											<td class="doc_field_content"><?=$city_row['nm']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>Province</b></td>
											<td class="doc_field_content"><?=$province_row['long_nm']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>Postal Code</b></td>
											<td class="doc_field_content"><?=$row['postal_cd']; ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>비자상태</b></td>
											<td class="doc_field_content">
												<? 
													if($row['p_status'] == 0) {
														echo "시민";
													} else if($row['p_status'] == 1) {
														echo "영주";
													} else {
														echo "비자";
													}
												?>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>비자만료일</b></td>
											<td class="doc_field_content"><?=$row['visa_dt']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>SIN</b></td>
											<td class="doc_field_content"><?=$row['sin1']." ".$row['sin2']." ".$row['sin3']; ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!--tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>추가정보</b></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>근무상태</b></td>
											<td class="doc_field_content"><font color="red" style="font-weight:bold;"><?=($row['term_dt'] == NULL) ? "O" : "X"; ?></font></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>사원증여부</b></td>
											<td class="doc_field_content" colspan=3><font color="red" style="font-weight:bold;"><?=($row['term_dt'] == NULL) ? "O" : "X"; ?></font></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>최초입사일</b></td>
											<td class="doc_field_content"><?=$row['ipsa_sdt']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>입사일</b></td>
											<td class="doc_field_content"><?=$row['ipsa_dt']; ?></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>퇴사일</b></td>
											<td class="doc_field_content"><?=$row['term_dt']; ?></td>
										</tr>
										<tr class="doc_border" height="80">
											<td style="padding-left:10px;" class="doc_field_name"><b>메모</b></td>
											<td class="doc_field_content" colspan=5><?=Br_iconv($row['bigo']); ?></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr-->
				</table>
			</div>
			
			<div class="tab2_content">
				<table>
				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>부서</b></td>
											<td style="border:0;" colspan=5></td>
										</tr>
										<?
										$buseo_query = "SELECT buseo, CONVERT(char(10), dt, 126) AS dt FROM $tablename2 WHERE id = ".$row['id']." ORDER BY ipdt";
										$buseo_query_result = mssql_query($buseo_query);
										$buseo_query_num = mssql_num_rows($buseo_query_result);
										?>
										<tr>
											<td colspan="6">
												<table width="300px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td class="doc_field_name" align="center"><b>부서</b></td>
													</tr>
													<? if($buseo_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=2><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? while($buseo_row = mssql_fetch_array($buseo_query_result)) { ?>
															<?
															$buseo_name_query = "SELECT nm FROM $tablename3 WHERE cd = ".$buseo_row['buseo'];
															$buseo_name_query_result = mssql_query($buseo_name_query);
															$buseo_name_row = mssql_fetch_array($buseo_name_query_result);
															?>
															<tr class="doc_border" height="20">
																<td align="center"><?=$buseo_row['dt']; ?></td>
																<td align="center"><?=Br_iconv($buseo_name_row['nm']); ?></td>
															</tr>
														<? } ?>
													<? } ?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" class="doc_wrapper">
						<table>
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>직급/직책</b></td>
											<td style="border:0;" colspan=5></td>
										</tr>
										<?
										$posiTitle_query = "SELECT CONVERT(char(10), dt, 126) AS dt, hr_position, hr_title FROM hr_stf_position WHERE company_cd = $company AND id = ".$row['id']." ORDER BY seq";
										$posiTitle_query_result = mssql_query($posiTitle_query);
										$posiTitle_query_num = mssql_num_rows($posiTitle_query_result);
										?>
										<tr>
											<td colspan="6">
												<table width="300px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td width="89" class="doc_field_name" align="center"><b>직급</b></td>
														<td width="89" class="doc_field_name" align="center"><b>직책</b></td>
													</tr>
													<? if($posiTitle_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=3><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? while($posiTitle_row = mssql_fetch_array($posiTitle_query_result)) { ?>
															<?
															$posi_name_query = "SELECT nm FROM hr_position WHERE cd = ".$posiTitle_row['hr_position'];
															$posi_name_query_result = mssql_query($posi_name_query);
															$posi_name_row = mssql_fetch_array($posi_name_query_result);

															$title_name_query = "SELECT nm FROM hr_title WHERE cd = ".$posiTitle_row['hr_title'];
															$title_name_query_result = mssql_query($title_name_query);
															$title_name_row = mssql_fetch_array($title_name_query_result);
															?>
															<tr class="doc_border" height="20">
																<td align="center"><?=$posiTitle_row['dt']; ?></td>
																<td align="center"><?=Br_iconv($posi_name_row['nm']); ?></td>
																<td align="center"><?=Br_iconv($title_name_row['nm']); ?></td>
															</tr>
														<? } ?>
													<? } ?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" class="doc_wrapper">
						<table>
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>급여</b></td>
											<td style="border:0;" colspan=5></td>
										</tr>
										<?
										$wage_query = "SELECT wage, pay_gubun, bigo, CONVERT(char(10), dt, 126) AS dt FROM $wage_table WHERE id = ".$row['id']." ORDER BY dt";
										$wage_query_result = mssql_query($wage_query);
										$wage_query_num = mssql_num_rows($wage_query_result);
										?>
										<tr>
											<td colspan="6">
												<table width="560px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td width="60" class="doc_field_name" align="center"><b>Type</b></td>
														<td width="100" class="doc_field_name" align="center"><b>Wage</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
													</tr>
													<? if($wage_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=4><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? while($wage_row = mssql_fetch_array($wage_query_result)) { ?>
															<tr class="doc_border" height="20">
																<td align="center"><?=$wage_row['dt']; ?></td>
																<td align="center"><?=(($wage_row['pay_gubun'] == 0) ? "시급" : "월급" ); ?></td>
																<td align="center"><?=$wage_row['wage']; ?></td>
																<td align="left" style="padding-left:3px;"><?=Br_iconv($wage_row['bigo']); ?></td>
															</tr>
														<? } ?>
													<? } ?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" class="doc_wrapper">
						<table>
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>상/벌점</b></td>
											<td style="border:0;" colspan=5></td>
										</tr>
										<?
										$point_query = "SELECT rDesc, reward, bigo, CONVERT(char(10), rDate, 126) AS dt FROM dt_stf_reward WHERE company = '$company_Sname' AND id = ".$row['id']." ORDER BY rDate";
										$point_query_result = mssql_query($point_query);
										$point_query_num = mssql_num_rows($point_query_result);
										?>
										<tr>
											<td colspan="6">
												<table width="560px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td width="80" class="doc_field_name" align="center"><b>내용</b></td>
														<td width="80" class="doc_field_name" align="center"><b>포인트</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
													</tr>
													<? if($point_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=4><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? while($point_row = mssql_fetch_array($point_query_result)) { ?>
															<tr class="doc_border" height="20">
																<td align="center"><?=$point_row['dt']; ?></td>
																<td align="center"><?=Br_iconv($point_row['rDesc']); ?></td>
																<td align="center"><?=$point_row['reward']; ?></td>
																<td align="left" style="padding-left:3px;"><?=Br_iconv($point_row['bigo']); ?></td>
															</tr>
														<? } ?>
													<? } ?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" class="doc_wrapper">
						<table>
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>직원디파짓</b></td>
											<td style="border:0;" colspan=5></td>
										</tr>
										<?
										$deposit_query = "SELECT kind, serial_num, unit, amt, received_nm, bigo, ip_id, CONVERT(char(10), ip_dt, 126) AS dt FROM $deposit_table WHERE stf_id = ".$row['id']." ORDER BY dt";
										$deposit_query_result = mssql_query($deposit_query);
										$deposit_query_num = mssql_num_rows($deposit_query_result);
										?>
										<tr>
											<td colspan="6">
												<table width="1000" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="50" class="doc_field_name" align="center"><b>Kind</b></td>
														<td width="200" class="doc_field_name" align="center"><b>디파짓 물품명</b></td>
														<td width="80" class="doc_field_name" align="center"><b>Serial #</b></td>
														<td width="50" class="doc_field_name" align="center"><b>Size</b></td>
														<td width="100" class="doc_field_name" align="center"><b>Amount</b></td>
														<td width="100" class="doc_field_name" align="center"><b>받은사람</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
													</tr>
													<? if($deposit_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=7><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? while($deposit_row = mssql_fetch_array($deposit_query_result)) { ?>
															<?
															$deposit_name_query = "SELECT nm FROM ft_deposit_kind_com WHERE cd = ".$deposit_row['kind'];
															$deposit_name_query_result = mssql_query($deposit_name_query);
															$deposit_name_row = mssql_fetch_array($deposit_name_query_result);
															?>
															<tr class="doc_border" height="20">
																<td align="center"><?=$deposit_row['kind']; ?></td>
																<td align="center"><?=Br_iconv($deposit_name_row['nm']); ?></td>
																<td align="center"><?=$deposit_row['serial_num']; ?></td>
																<td align="center"><?=$deposit_row['unit']; ?></td>
																<td align="center"><?=$deposit_row['amt']; ?></td>
																<td align="center"><?=Br_iconv($deposit_row['received_nm']); ?></td>
																<td align="left" style="padding-left:3px;"><?=Br_iconv($deposit_row['bigo']); ?></td>
															</tr>
														<? } ?>
													<? } ?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</table>
			</div>

			<div class="tab3_content">
			<?
				$schedule_query = "SELECT CONVERT(char(10), start_date, 126) AS start_date, CONVERT(char(10), end_date, 126) AS end_date, type, bigo FROM hr_stf_schedule WHERE company_cd = $company AND id = ".$row['id']." ORDER BY start_date";
				$schedule_query_result = mssql_query($schedule_query);
				$schedule_query_result2 = mssql_query($schedule_query);
				$schedule_query_num = mssql_num_rows($schedule_query_result);
				//$schedule_type_list = array("지각", "조퇴", "반차", "결근", "무급휴가", "유급휴가");
				$schedule_type_list = array("유급휴가","무급휴가","반차","조퇴","지각","결근");
			?>
				<table>
				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>근태정보</b></td>
											<!--td style="border:0;" colspan=5></td-->
											<td style="border:0; width:515px;></td>
											<!--td style="border:1;padding-left: -5px;text-align: right;padding-top: 3px;font-size: 16px;"-->
											
										
											
											<!--/td-->
										</tr>
<? 
											while($schedulerow = mssql_fetch_array($schedule_query_result2)) {
															
											
												$vday = ceil((strtotime($schedulerow['end_date']) - strtotime($schedulerow['start_date'])) / (60*60*24));
												if($schedule_type_list[$schedulerow['type']-1] !== '지각'){
													$vday = $vday + 1;
												}
												if($schedule_type_list[$schedulerow['type']-1] == '반차' || $schedule_type_list[$schedulerow['type']-1] == '조퇴'){
													$vday = 0.5;
												}
												if($schedule_type_list[$schedulerow['type']-1] == '무급휴가'){
												
													$vday = 0;
												}
												
																	

                                                 
												$sum += $vday;


												//$chkyear = date("Y",strtotime($schedulerow['end_date']));
											}
											$ipy = substr($row['ipsa_dt'],0,4);
											$ny = date('Y');
											$compareyear = $ny - $ipy;
											$ny2 = date('Y-m-d');
											$datecompare = strtotime($ny2) - strtotime($row['ipsa_dt']);
											$datecompare = floor($datecompare/3600/24+1);
											if($datecompare < 365){
												$vdayperyear = "4%";
												/*if($ipy !== $ny){
													$avday = ($compareyear - 2) * 10;
													
												}
												else
													$avday  = ($compareyear - 1) * 10;*/
													$avday = 0;
											}
											else if($datecompare > 1460) {
												$v = $compareyear -1;
												$v2 = $v - 5;
												$v = $v - $v2;												
												$avday = ($v * 10) + ($v2 * 15);																							
												$vdayperyear = "6%";	
											}
											else{
												$avday =  ($compareyear - 1) * 10 ;
												$vdayperyear = "4%";
											}

											$fullday  = $avday - $sum;
											
											if($avday < 0){
												$fullday = $avday - $sum;
											}
											else
												$fullday  = $avday - $sum;
											//echo "<tr class='doc_border' height='30'>";
									
											

											
?>											


										<tr>
											<td colspan="6">
											<table width="1000" style="table-layout:fixed; padding:0;">
											<tr height="30" style = "border-collapse: separate;border: 1px solid #999;">
														<td width="196" align="center" style = "border-collapse:separate;border: 1px solid #999;background:#eee;">
														<span style = "font-variant: small-caps;font-size: 1.2em; font-weight: 700; color: #777;line-height:1.8;">Vacation Fee</span>
														</td>
														<td width="49" align="center" style = "border-collapse:separate;border: 1px solid #999;">
														<span style="font-variant: small-caps;font-size: 1.3em; font-weight: 700;line-height:1.8;"><b><? echo $vdayperyear; ?></b></span>
														</td>
														<td width="196" align="center" style = "border-collapse:separate;border: 1px solid #999;background:#eee;">
														<span style="font-variant: small-caps;font-size: 1.2em; font-weight: 700; color:#777;line-height:1.8;">Total Vacation</span>
														</td>
														<td width="49" align="center" style = "border-collapse:separate;border: 1px solid #999;">
														<span style="font-variant: small-caps;font-size: 1.3em; font-weight: 700;line-height:1.8;"><b><? echo $avday; ?></b></span>
														</td>	
														<td width="196" align="center" style = "border-collapse:separate;border: 1px solid #999;background:#eee;">
														<span style = "font-variant: small-caps;font-size: 1.2em; font-weight: 700; color: #777;line-height:1.8;">Used Vacation</span>
														</td>
														<td width="49" align="center" style = "border-collapse:separate;border: 1px solid #999;">
														<span style="font-variant: small-caps;font-size: 1.3em; font-weight: 700;color:red;line-height:1.8;"><b><? echo $sum; ?></b></span>
														</td>
														<td width="196" align="center" style = "border-collapse:separate;border: 1px solid #999;background:#eee;">
														<span style="font-variant: small-caps;font-size: 1.2em; font-weight: 700; color:#777;line-height:1.8;">Available Vacation</span>
														</td>
														<td width="49" align="center" style = "border-collapse:separate;border: 1px solid #999;">
														<span style="font-variant: small-caps;font-size: 1.3em; font-weight: 700;color:green;line-height:1.8;"><b><? echo $fullday; ?></b></span>
														</td>
																										
											</tr>
											<tr height="15" style = "border:0;">
											</tr>

												
													
											</table>
												<table width="1000" style="table-layout:fixed; padding:0;">
													
													
												
													
													
													<tr class="doc_border" height="30">
														<td width="100" class="doc_field_name" align="center"><b>Type</b></td>
														<td width="120" class="doc_field_name" align="center"><b>Start Date</b></td>
														<td width="120" class="doc_field_name" align="center"><b>End Date</b></td>
														<td width="50" class="doc_field_name" align="center"><b>Day</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
													</tr>
													<? if($schedule_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=5><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? while($schedule_row = mssql_fetch_array($schedule_query_result)) { ?>
															<tr class="doc_border" height="20">
																<td align="center"><?=$schedule_type_list[$schedule_row['type']-1]; ?></td>
																<td align="center"><?=$schedule_row['start_date']; ?></td>
																<td align="center"><?=$schedule_row['end_date']; ?></td>
																<td align="center">
																
																<? 
																	$vday = ceil((strtotime($schedule_row['end_date']) - strtotime($schedule_row['start_date'])) / (60*60*24));
																	if($schedule_type_list[$schedule_row['type']-1] !== '지각'){
																		$vday = $vday + 1;
																	}
																	if($schedule_type_list[$schedule_row['type']-1] == '반차' || $schedule_type_list[$schedule_row['type']-1] == '조퇴'){
																		$vday = 0.5;
																	}
																	
																	echo $vday;
																	
																?>
																
																
																</td>
																<td align="left" style="padding-left:3px;"><?=Br_iconv($schedule_row['bigo']); ?></td>
															</tr>
														<? } ?>
													<? } ?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</table>
			</div>



			<div class="tab4_content">
				<table>
				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>업로드 파일</b></td>
											<td style="border:0;" colspan=5></td>
										</tr>
										<?
										$file_query = "SELECT subject, bigo, file_name FROM hr_files WHERE company_cd = $company AND id = ".$row['id']." ORDER BY dt";
										$file_query_result = mssql_query($file_query);
										$file_query_result2 = mssql_query($file_query);
										$file_query_num = mssql_num_rows($file_query_result);

										$filepath = "upload/hr/";
										?>

										<?
										$subarr = array();
										while($file_row2 = mssql_fetch_array($file_query_result2)) {
										
											array_push($subarr,$file_row2['subject']);
										
										}
										
										  ?>



										<tr>
											
												<tr class="doc_border" height="30">
														<td align="center" width="158.8" style="padding-top: 5px;"><b>고용계약서</b><input type="checkbox" name="employeeagreement" value="1"
														<? for($i=0;$i<sizeof($subarr);$i++){
															if(@iconv("euc-kr","utf-8",$subarr[$i]) == '고용계약서') 	
																echo "checked : ''";
														}
															 ?>></td>	
														<td align="center" width="158.8" style="padding-top: 5px;"><b>비밀협정서</b><input type="checkbox" name="employeeagreement" value="1" <?for($i=0;$i<sizeof($subarr);$i++){
															if(@iconv("euc-kr","utf-8",$subarr[$i]) == '비밀협정서') 	
																echo "checked : ''";
														} ?>></td>	
														<td align="center" width="158.8" style="padding-top: 5px;"><b>경업금지협약서</b><input type="checkbox" name="employeeagreement" value="1" <? for($i=0;$i<sizeof($subarr);$i++){
															if(@iconv("euc-kr","utf-8",$subarr[$i]) == '경업금지협약서') 	
																echo "checked : ''";
														} ?>></td>
														<td align="center" width="158.8" style="padding-top: 5px;"><b>Void Check</b><input type="checkbox" name="employeeagreement" value="1" <? for($i=0;$i<sizeof($subarr);$i++){
															if(@iconv("euc-kr","utf-8",$subarr[$i]) == 'Void Check') 	
																echo "checked : ''";
														} ?>></td>
														<td align="center" width="158.8" style="padding-top: 5px;"><b>ID</b><input type="checkbox" name="employeeagreement" value="1" <?for($i=0;$i<sizeof($subarr);$i++){
															if(@iconv("euc-kr","utf-8",$subarr[$i]) == 'ID') 	
																echo "checked : ''";
														} ?>></td>
												</tr>
												
												<tr height="15">
												</tr>		



										
									
											<td colspan="6">
												<table width="800" style="table-layout:fixed; padding:0;">
													
													
													
													
													<tr class="doc_border" height="30">
														<td width="250" class="doc_field_name" align="center"><b>Subject</b></td>
														<td width="200" class="doc_field_name" align="center"><b>Upload</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
													</tr>
													<? if($file_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=3><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? while($file_row = mssql_fetch_array($file_query_result)) { ?>
															<? $fullpath = $filepath.$company_Sname."/".$file_row['file_name']; ?>
															<? $temp = explode("__", $file_row['file_name']); ?>
															<? $filename = $temp[2]; ?>
															<tr class="doc_border" height="20">
																<td align="center"><?=Br_iconv($file_row['subject']); ?></td>
																<td align="left" style="padding-left:3px;"><a href="<?=$fullpath; ?>" target="_blank"><?=Br_iconv($filename); ?></a></td>
																<td align="left" style="padding-left:3px;"><?=Br_iconv($file_row['bigo']); ?></td>
															</tr>
														<? } ?>
													<? } ?>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</table>
			</div>
		</div>
		</td></tr>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="post_to_url({'id':<?=$row['id']; ?>, 'company':<?=$company; ?>});">수정모드</td>
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
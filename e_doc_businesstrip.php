<!-- Submit Button Javascript START -->
<script>

	function doc_submit() {
		var target = document.forms.form_proposal;
		target.doc_date.required = "required";
		target.doc_subject.required = "required";
		target.runcompid.required = "required";

		for(var i = 1; i < 10; i++) {
			if(document.getElementById("appUser" + i).value != "") {
				var is_app_set = "true";
				break;
			} else {
				var is_app_set = "false";
			}
		}

		if(is_app_set == "false") {
			alert("결재자를 선택하십시요.");
		} else {

			if(target.doc_date.value != "" && target.doc_subject.value != "" && target.runcompid.value != "") {
				var answer = confirm("상신 하시겠습니까?");
				if(answer) {
					target.mode.value = "submit";
					saveContent();
				}
			}
		}
	}

	
	function doc_submit2() {
		var target = document.forms.form_proposal;
		target.mode.value = "submit";
		saveContent();
		
	}


	function doc_save() {
		var target = document.forms.form_proposal;
		target.doc_date.required = "required";
		target.doc_subject.required = "required";
		if(target.doc_date.value != "" && target.doc_subject.value != "") {
			var answer = confirm("저장 하시겠습니까?");
			if(answer) {
				target.mode.value = "save";
				saveContent();
			}
		}
	}


	function purposeadd() {
		var table = document.getElementById("business");
		var row_num = table.getElementsByTagName("tr").length;
		var row = table.insertRow(row_num);
		row.className = "doc_border";
		row.style.height = "24px";

		var cell0 = row.insertCell(0);
		var cell1 = row.insertCell(1);
		var cell2 = row.insertCell(2);
		var cell3 = row.insertCell(3);

		
		cell0.style.textAlign = "center";

		cell0.innerHTML = "<input name='businesspurpose[]' type='text' style='width:100%; text-align:left;'>";
		cell0.colSpan = 2;
		cell0.style.width = '445px';


		cell1.style.textAlign = "center";
		cell1.innerHTML = "<input name='acheive[]' type='text' readonly style='width:100%; text-align:left;'>";
		cell1.style.width = '78px';


		cell2.style.textAlign = "center";
		cell2.innerHTML = "<input name='resultandproblem[]' type='text' readonly style='width:100%; text-align:left;'>";
		cell2.style.width = '325px';


		cell3.style.textAlign = "center";
		cell3.innerHTML = "<span style='padding-left:1px;color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\"business\", " + row_num + ");'>X</span>";
		cell3.style.width = '10px';


		


	}

		function purposeadd2() {
		var table = document.getElementById("businesspath");
		var row_num = table.getElementsByTagName("tr").length;
		var row = table.insertRow(row_num);
		row.className = "doc_border";
		row.style.height = "24px";

		var cell0 = row.insertCell(0);
		var cell1 = row.insertCell(1);
		var cell2 = row.insertCell(2);
		var cell3 = row.insertCell(3);

		
		cell0.style.textAlign = "center";

		cell0.innerHTML = "<input name='businessdate[]' type='text' style='width:100%;' maxlength='10'>";

		cell0.style.width = '115px';


		cell1.style.textAlign = "center";
		cell1.innerHTML = "<input name='companyvisit[]' type='text' style='width:100%; text-align:left;'>";
		cell1.style.width = '650px';


		cell2.style.textAlign = "center";
		cell2.innerHTML = "<input name='resultforvisit[]' type='text' readonly style='width:100%; text-align:left;'>";
		cell2.style.width = '605px';

		cell3.style.textAlign = "center";
		cell3.innerHTML = "<span style='padding-left:1px;color:red; font-weight:bold; cursor:pointer;' onClick='del_row2(\"businesspath\", " + row_num + ");'>X</span>";
		cell3.style.width = '45px';



	}



	function del_row(table_name, seq) {
		var table = document.getElementById(table_name);
		var total_row = table.getElementsByTagName("tr").length;
		var col_num = table.rows[2].cells.length;
		table.deleteRow(seq);

		if(seq < (total_row - 1)) {
			for(var i = seq; i < (total_row - 1); i++) {
				table.rows[i].cells[col_num - 1].innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\""+ table_name + "\", " + i + ");'>X</span>";
			}
		}

	
	}

	function del_row2(table_name, seq) {
		var table = document.getElementById(table_name);
		var total_row = table.getElementsByTagName("tr").length;
		var col_num = table.rows[0].cells.length;
		table.deleteRow(seq);

		if(seq < (total_row - 1)) {
			for(var i = seq; i < (total_row - 1); i++) {
				table.rows[i].cells[col_num - 1].innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row2(\""+ table_name + "\", " + i + ");'>X</span>";
			}
		}

	
	}





</script>
<!-- Submit Button Javascript END -->

<!-- e-doc Proposal START -->


<td width="" align="left" valign="top">
<form name="form_proposal" action="upload/upload_businesstrip.php" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">출장 계획서/보고서</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc TITLE END -->

		<!-- e-doc Proposal MAIN START -->
		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr align="right" style="padding: 0 12px 0 0;">
									<td><button class="doc_submit_btn_style" onClick="doc_submit()">상신하기</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="doc_save()">임시저장</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Submit/Save BTN END -->

		<!-- Proposal FORM START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<!-- Proposal FORM TITLE START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td width="140"></td>
									<td align="center" class="doc_title">출장 계획서/보고서</td>
									<td width="100" align="right" style="padding-top:10px;"><button id="fApproval">결재자 검색</button></td>
									<td width="40" align="right" style="padding-top:10px;"><button id="doc_approval_btn">★</button></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Proposal FORM TITLE END -->

					<!-- Proposal FORM CONTENT START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;">
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content" style="border-collapse: separate;border-right: 0px solid #FFFFFF;width: 551px;"></td>
									<td style="border-collapse: separate;border-left: 0px solid #FFFFFF;"></td>

									<td width="365" rowspan="6" align="center" valign="top">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="5%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;">기안자</td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1"></td><input type="hidden" id="appUser1" name="appUser1" >
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"></td><input type="hidden" id="appUser2" name="appUser2" >
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"></td><input type="hidden" id="appUser3" name="appUser3" >
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"></td><input type="hidden" id="appUser4" name="appUser4" >
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;"><?=Br_iconv($_SESSION['memberName'])?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName1"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName2"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName3"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName4"></td>
											</tr>
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app5"></td><input type="hidden" id="appUser5" name="appUser5" >
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app6"></td><input type="hidden" id="appUser6" name="appUser6" >
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app7"></td><input type="hidden" id="appUser7" name="appUser7" >
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app8"></td><input type="hidden" id="appUser8" name="appUser8" >
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app9"></td><input type="hidden" id="appUser9" name="appUser9" >											
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName5"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName6"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName7"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName8"></td>
												<td style="border-bottom:1px #eaeaea solid; padding:30px 0 0 0;" id="appUserName9"></td>
											</tr>
										</table>
									</td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서종류</b></td>
									<td colspan="2" class="doc_field_content">출장 계획서/보고서</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td colspan="2" class="doc_field_content">작성전 문서입니다.</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안자</b></td>
									<td colspan="2" class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>실행회사</b></td>
									<td colspan="2" align="left" class="doc_field_content">
										<select name="runcompid"  style="width:100px;">
<?										$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
										$row2 = mssql_query($query);
?>
<?										while($rst = mssql_fetch_array($row2)) { ?>
											<option value='<?=$rst['companyID']?>'<?if($rst['companyID'] == $_SESSION['memberCID']) echo "selected"; ?>><?=$rst['companyDesc']?></option>
<?										}	?>
										</select>
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>계획서 작성일</b></td>
									<td colspan="2" class="doc_field_content"><input id="doc_calendar" name="doc_date" type="text" style="width:85px;" maxlength="10" value="<?=date("Y-m-d");?>"></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td colspan="3" class="doc_field_content" colspan="2"><input name="doc_subject" type="text" style="width:630px;"></td>
								</tr>

								<!-- Editor START -->
								<tr>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>출장대상자<br /><br />경비내역</b></td>
								
									<td style="border: 1px solid #c9c9c9;border-right:0px;">

										<table style = "border-collapse: separate;border: 1px solid #c9c9c9;margin: 19px 25px;">
											
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">이름</th>
													<td style = "width:293px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bname1" type="text" style="width:100%; text-align:left;"></td>
													
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">직급</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bpos1" type="text" style="width:100%; text-align:left;"></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">부서</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bdep1" type="text" style="width:100%; text-align:left;"></td>
												</tr>
												
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3C3C3;">이름</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bname2" type="text" style="width:100%; text-align:left;"></td>
													
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3C3C3;">직급</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bpos2" type="text" style="width:100%; text-align:left;"></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3C3C3;">부서</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bdep2" type="text" style="width:100%; text-align:left;"></td>
												</tr>
												
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#FF6666;">이름</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bname3" type="text" style="width:100%; text-align:left;"></td>
													
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#FF6666;">직급</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bpos3" type="text" style="width:100%; text-align:left;"></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#FF6666;">부서</th>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="bdep3" type="text" style="width:100%; text-align:left;"></td>
												</tr>	

												
											</tbody>
										</table>		
										
									</td>


									

									
								
									<td colspan="2" style="border: 1px solid #c9c9c9;border-left:0px;">

										<table style = "border-collapse: separate;border: 1px solid #c9c9c9;margin: 19px 0px;width:64.5%;">
											
												<tr style = "height: 24px;">
													<th style = "width:14%;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">항목</th>
													<th style = "width:43%;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">계획</th>
													<th style = "width:43%;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">실비</th>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">숙박비</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="hotelpurpose" type="text" style="width:100%; text-align:left;"></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="hotelpay" type="text" readonly style="width:100%; text-align:left;"></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">항공비</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="airpurpose" type="text" style="width:100%; text-align:left;"></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="airpay" type="text" readonly style="width:100%; text-align:left;"></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">교통비</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="transpurpose" type="text" style="width:100%; text-align:left;"></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="transpay" type="text" readonly style="width:100%; text-align:left;"></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">식비</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="mealpurpose" type="text" style="width:100%; text-align:left;"></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="mealpay" type="text" readonly style="width:100%; text-align:left;"></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">그 외</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="etcpurpose" type="text" style="width:100%; text-align:left;"></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="etcpay" type="text" readonly style="width:100%; text-align:left;"></td>
												</tr>
												<tr style="height: 24px;text-align:center;">
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;background-color:#F3E9E9;">Total</td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="totalpurpose" type="text" style="width:100%; text-align:left;"></td>
													<td style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;">
													<input name="totalpay" type="text" readonly style="width:100%; text-align:left;"></td>
												</tr>
											</tbody>
										</table>		
										
									</td>
									
								</tr>
								
							
								
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>출장목적</td>
									<td colspan="3" style="border: 1px solid #c9c9c9;">
										<table id="business" style = "border-collapse: separate;border: 1px solid #c9c9c9;margin: 19px 25px;width:75.5%;">
											<tbody>
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">목적 :</th>
													<th colspan = "4" style = "width:280px;border-collapse: separate;border: 1px solid #c9c9c9;font-size: 15px;vertical-align: middle;"><input name="mainbusinesspurpose" type="text" style="width:100%;height:120%; text-align:left;"></th>

												</tr>
												<tr style = "height: 24px;">
													<th style = "width:96px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">기간 :</th>
													<th colspan = "4" style = "width:280px;border-collapse: separate;border: 1px solid #c9c9c9;font-size: 15px;vertical-align: middle;"><input name="businessduration" type="text" style="width:100%;height:120%;text-align:left;"></th>

												</tr>
												<tr style="height: 24px;">
													<th colspan ="2" style = "padding-left:28px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;text-align:left;background-color:#F3E9E9;">출장목표(중요도 순으로 기재)</th>
													<th style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;width:76px;text-align:center;background-color:#F3E9E9;">달성율</th>
													<th style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;width:323px;text-align:center;background-color:#F3E9E9;">결과(성과 또는 문제점)</th>
													<th style = "border-collapse: separate;border: 1px solid #c9c9c9;vertical-align:middle;width:43px;">
													<input type="button" value="추가" onClick="purposeadd()"> 
													</th>
												</tr>											

										
										</table>
									</td>
								</tr>


								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>출장지역경로<br /><br />업무</b></td>
									<td colspan = "3" style="border: 1px solid #c9c9c9;">
								
										<table id="businesspath" style = "border-collapse: separate;border: 1px solid #c9c9c9;margin: 19px 25px;width:75.5%;">
											<tbody>
												<tr style = "height: 24px;">
													<th style = "width:115px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">일자</th>
													<th style = "width:650px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">출장일정 및 방문업체</th>
													<th style = "width:605px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;background-color:#F3E9E9;">결과</th>
													<th style = "width:45px;border-collapse: separate;border: 1px solid #c9c9c9;vertical-align: middle;">
													<input style="height: 24px;" type="button" value="추가" onClick="purposeadd2()">
													</th>
												</tr>
												
											
												
											
											
											</tbody>
										</table>		
										
									</td>
								</tr>


								<tr style="display:none;">
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내용</b></td>
									<td align="center" colspan="2" style="border: 1px solid #c9c9c9;">
										<? include_once "editor/editor.html"; ?>
									</td>
								</tr>
										
								
								
								
								
								
								
								
								
								
								<!-- Editor END -->
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content" colspan="3">
										<input name="userfile1" type="file" />
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content" colspan="3">
										<input name="userfile2" type="file" />
									</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content" colspan="3">
										<input name="userfile3" type="file" />
									</td>
								</tr>
							</table>
						</td>						
					</tr>
					<!-- Proposal FORM CONTENT END -->
				</table>
			</td>
		</tr>
		<!-- e-doc Proposal MAIN END -->

		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><button class="doc_submit_btn_style" onClick="doc_submit()">상신하기</td>
									<td width="5"></td>
									<td><button class="doc_submit_btn_style" onClick="doc_save()">임시저장</td>
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
		<!-- Submit/Save BTN END -->
		<!-- e-doc Proposal MAIN END -->
	</table>
</td>
</form>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Proposal END -->

<!-- Favorite Approval User Add START -->
<script>
	$(document).ready(function(){
		$("#fApproval").click(function(){
			var pos = $(this).position();
			var _left = pos.left;
			var _top = pos.top + 10;
			var _width = $("#AllUserList").width() - $(this).width();
			$("#AllUserList").css("left", _left - _width - 310);
			$("#AllUserList").css("top", _top+$(this).height());
			$("#UserSearch").attr("src", "?page=userSearch");
			$("#AllUserList").show();
			$("#doc_approval_user").hide();
		});
	});
</script>

<div id="AllUserList" style="border:2px #666666 solid; background-color:#ffffff; position:absolute; z-index:10; display:none; width:300px; left:0px; top:0px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="middle" style="padding:14px 0 0 20px; background-color:#F6CECE;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr height="25">
						<td style="letter-spacing:-1px;"><b>결재자 검색하기</b></td>
						<td width="22" align="left"><a href="javascript:"><img src="css/img/bt_closelayer.gif" onClick="jQuery('#AllUserList').hide()"></a></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td><iframe id="UserSearch" src="?page=userSearch" height="400"></iframe></td>
		</tr>
	</table>
</div>
<!-- Favorite Approval User Add END -->

<!-- Approval Person Select jQuery & HTML START -->
<script>
	$(document).ready(function(){
		$("#doc_approval_btn").click(function(){
			var pos = $(this).position();
			var _left = pos.left;
			var _top = pos.top + 10;
			var _width = $("#doc_approval_user").width() - $(this).width();
			$("#doc_approval_user").css("left", _left - _width - 350);
			$("#doc_approval_user").css("top", _top+$(this).height());
			$("#fUserSearch").attr("src", "?page=fUserSearch");
			$("#doc_approval_user").show();
			$("#AllUserList").hide();
		});
	});
</script>

<div id="doc_approval_user" style="border:2px #666666 solid; background-color:#ffffff; position:absolute; z-index:10; display:none; width:300px; left:0px; top:0px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="middle" style="padding:14px 0 0 20px; background-color:#CECEF6;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr height="25">
						<td style="letter-spacing:-1px;"><b>결재자 즐겨찾기</b></td>
						<td width="22" align="left"><a href="javascript:"><img src="css/img/bt_closelayer.gif" onClick="jQuery('#doc_approval_user').hide()"></a></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td><iframe id="fUserSearch" src="?page=fUserSearch" height="400"></iframe></td>
		</tr>
	</table>
</div>
<!-- Approval Person Select jQuery & HTML END -->

<!-- Delete Approval User START -->
<script>
	function delete_from_doc(i) {
		document.getElementById("app" + i).innerHTML = "";
		document.getElementById("appUser" + i).value = "";
		document.getElementById("appUserName" + i).innerHTML = "";
	}
</script>
<!-- Delete Approval User END -->
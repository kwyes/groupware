<script>
function Mask(){
	var maskHeight = $(document).height();  
	var maskWidth = $(document).width(); 

	$('#mask').css({'width':maskWidth,'height':maskHeight});
	$('#mask').fadeTo("slow", 0.6);
	$('.window').css({'left':maskWidth/2-(300/2)});
	$('.window').show();
}

$(document).ready(function() {
	$('.openMask').click(function(e){
		e.preventDefault();
		Mask();
	});

	$('.window .close').click(function (e) {  
		e.preventDefault();
		$("#mask").fadeOut("slow");
		$('.window').hide();  
	});

	$( window ).resize( function() {
		if (!$("#mask").is(':hidden')) {
			Mask();
		}
	});
});

function popupOpen(Id,Type,Seq,UserID,Subject,url) {

	var popUrl = "e_doc_notice.php?ID="+Id+"&Type="+Type+"&Seq="+Seq+"&UserID="+UserID+"&Subject="+Subject+"&url="+url;
	var popOption = "width=370, height=360, resizable=no, scrollbars=no, status=no";

	window.open(popUrl,"",popOption);
}

function printPage(Id, Type, Seq) {
	var popUrl = "print_preview_salesJournal.php?ID="+Id+"&Type="+Type+"&Seq="+Seq;
	var popOption = "width=800, height=600, toolbar=0, location=0, directories=0, resizable=1, menubar=0, scrollbars=yes, status=no";

	window.open(popUrl,"",popOption);
}
</script>

<?
$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$url = urlencode($url);

$query = "SELECT TOP 1 CompanyID, UserID, CONVERT(char(10), SubmitDate, 120) AS SubmitDate, Status, Subject, CONVERT(char(20), RegDate, 120) AS RegDate ".
		 "FROM E_DOC_Header ".
		 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq ";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);

$company_cd = $row['CompanyID'];
$submitDate = $row['SubmitDate'];
$submitUserID = $row['UserID'];
$submitTime = $row['RegDate'];
$status = $row['Status'];
$Subject = Br_iconv($row['Subject']);

if($status == 1) {
	$font_color = "#0000FF";
	$doc_status = "결제완료";
} else if($status == 2){
	$font_color = "#088A08";
	$doc_status = "결재진행중";
} else if($status == 4) {
	$font_color = "#DF0101";
	$doc_status = "회수";
} else if($status == 5) {
	$font_color = "#DF0101";
	$doc_status = "반려";
}

$UserID = $_SESSION['memberID'];
$query = "UPDATE ApprovalList SET is_read = 1 ".
		 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq AND ApprovalUserID = '$UserID' AND is_read = 0 ";
mssql_query($query);

$query = "SELECT ApprovalUserID, ApprovalUserSeq, ApprovalStatus, ApprovalComment, CONVERT(char(20), ApprovalDate, 120) AS ApprovalDate, is_read, CONVERT(char(20), RegDate, 120) AS RegDate ".
		 "FROM ApprovalList ".
		 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
		 "ORDER BY RegDate ASC, ApprovalUserSeq ASC";
$result2 = mssql_query($query);

$iCount = 0;
$rejected = FALSE;
$i = 1;
while($row2 = mssql_fetch_array($result2)) {
	$LastApproval[$row2['ApprovalUserSeq']] = $row2['ApprovalUserID'];
	$ListVariable[$row2['ApprovalUserSeq']] = get_user_name($row2['ApprovalUserID']);
	$DateVariable[$row2['ApprovalUserSeq']] = ($rejected == TRUE) ? 0 : $row2['ApprovalStatus'] ;
	$is_read[$row2['ApprovalUserSeq']] = $row2['is_read'];

	$ListVariable2[$iCount] = get_user_name($row2['ApprovalUserID']);
	$StatusVariable2[$iCount] = Br_iconv(get_ApprovalStatus($row2['ApprovalStatus']));
	$UserSeq[$iCount] = $row2['ApprovalUserSeq'];
	if($row2['is_read'] == 1) {
		$comments[$iCount] = Br_iconv($row2['ApprovalComment']);
		//echo $comments[$iCount]."<br>";
	}
	if($row2['ApprovalDate']) {
		$logTime[$iCount] = $row2['ApprovalDate'];
	} else {
		if($row2['is_read'])	$logTime[$iCount] = $row2['RegDate'];
	}
	$iCount++;

	if($DateVariable[$row2['ApprovalUserSeq']] == 1) {
		$color[$row2['ApprovalUserSeq']] = "#0000FF";
	} else if($DateVariable[$row2['ApprovalUserSeq']] == 2) {
		if($is_read[$row2['ApprovalUserSeq']] == 0) {
			$color[$row2['ApprovalUserSeq']] = "#FF8000";
		} else {
			$color[$row2['ApprovalUserSeq']] = "#088A08";
		}
	} else if($DateVariable[$row2['ApprovalUserSeq']] == 5) {
		$color[$row2['ApprovalUserSeq']] = "#DF0101";
		$rejected = TRUE;
	}
}
?>

<!-- e-doc Sales Activities Journal START -->
<form name="salesJournal" action="upload/upload_salesJournal.php" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="">
<input type="hidden" name="ID" value="<?=$ID; ?>">
<input type="hidden" name="Type" value="<?=$Type; ?>">
<input type="hidden" name="Seq" value="<?=$Seq; ?>">
<input type="hidden" name="Comment" value="">
<input type="hidden" name="approval" value="">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">Sales Activities Journal</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc TITLE END -->

		<!-- e-doc Sales Activities Journal MAIN START -->
		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
<?						if($sub == "view_done" || $sub == "view_complete" || $sub == "view_folder" || $sub == "view_submit" ||($menu == "view_all" && $status == 1)) { ?>
							<td align="left"><input type="button" class="doc_submit_btn_style" onClick="printPage('<?=$ID?>','<?=$Type?>','<?=$Seq?>')" value="인쇄"></td>
<?						} ?>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
<?									if($sub == "view_wait") { ?>
										<td><button class="doc_submit_btn_style openMask">결재하기</button></td>
<?									} ?>
<?									if($sub == "view_submit" && $DateVariable[1] == 2) { ?>
										<td><input type="button" class="doc_submit_btn_style" onClick="approve(4)" value="회수하기"></td>
<?									} ?>
<?									if($sub == "view_recovery") { ?>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(1)" value="상신하기"></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(2)" value="수정하기"></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(3)" value="삭제하기"></td>
<?									} ?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Submit/Save BTN END -->

		<!-- Sales Activities Journal FORM START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<!-- Sales Activities Journal FORM TITLE START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td width="140"></td>
									<td align="center" class="doc_title">Sales Activities Journal</td>
<?									if($LastApproval[sizeof($LastApproval)] == $UserID && $status == 2) { ?>
										<td width="100" align="right" style="padding-top:10px;"><input type="button" id="fApproval" value="결재자 검색"></td>
										<td width="40" align="right" style="padding-top:10px;"><input type="button" id="doc_approval_btn" value="★"></td>
<?									} else { ?>
										<td width="140"></td>
<?									} ?>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Sales Activities Journal FORM TITLE END -->

					<? $strR="<img width='54' height='54' style='padding-top: 9px;' src='/images/09_img.png'>"; ?>

					<!-- Sales Activities Journal FORM CONTENT START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;">
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content"><b><?=create_DocID($ID, $Seq); ?></b></td>
									<td width="365" rowspan="6" align="center" valign="top" style="padding:0;border-bottom:1px #afafaf solid;">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="7%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;"><?=get_user_name($submitUserID); ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1"><?=$ListVariable[1]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"><?=$ListVariable[2]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"><?=$ListVariable[3]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"><?=$ListVariable[4]; ?></td>
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid;"><img width="54" height="54" style="padding-top: 9px;" src="/images/00_img.png"></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName1"><?=(($DateVariable[1] == 2 && $is_read[1] == 0) ? $strR : get_docimg_approval($DateVariable[1])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName2"><?=(($DateVariable[2] == 2 && $is_read[2] == 0) ? $strR : get_docimg_approval($DateVariable[2])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName3"><?=(($DateVariable[3] == 2 && $is_read[3] == 0) ? $strR : get_docimg_approval($DateVariable[3])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName4"><?=(($DateVariable[4] == 2 && $is_read[4] == 0) ? $strR : get_docimg_approval($DateVariable[4])); ?></td>
											</tr>
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app5"><?=$ListVariable[5]; ?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app6"><?=$ListVariable[6]; ?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app7"><?=$ListVariable[7]; ?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app8"><?=$ListVariable[8]; ?></td>
												<td style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app9"><?=$ListVariable[9]; ?></td>										
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName5"><?=(($DateVariable[5] == 2 && $is_read[5] == 0) ? $strR : get_docimg_approval($DateVariable[5])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName6"><?=(($DateVariable[6] == 2 && $is_read[6] == 0) ? $strR : get_docimg_approval($DateVariable[6])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName7"><?=(($DateVariable[7] == 2 && $is_read[7] == 0) ? $strR : get_docimg_approval($DateVariable[7])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName8"><?=(($DateVariable[8] == 2 && $is_read[8] == 0) ? $strR : get_docimg_approval($DateVariable[8])); ?></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName9"><?=(($DateVariable[9] == 2 && $is_read[9] == 0) ? $strR : get_docimg_approval($DateVariable[9])); ?></td>
											</tr>
										</table>
									</td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서종류</b></td>
									<td class="doc_field_content">Sales Activities Journal</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content" style="color:<?=$font_color; ?>"><b><?=$doc_status; ?></b></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
									<td class="doc_field_content"><?=get_user_name($submitUserID); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>상신일</b></td>
									<td class="doc_field_content"><?=$submitDate; ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>실행회사</b></td>
									<td class="doc_field_content"><?=get_company_name($company_cd); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name" colspan="3"><b>작업일지</b></td>
								</tr>

								<tr>
									<td colspan="3">
										<table width="100%" cellspacing="0">
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td width="6%" align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;">시각(Time)</td>
												<td width="47%" align="center" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">업체명(Customer)</td>
												<td width="47%" align="center" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">특이사항(Remark)</td>
		
											</tr>
										
											<?	for($i = 1; $i <= 11; $i++) { ?>
												<?	
													$content_query = "SELECT customer, remark FROM salesJournal WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND sub_seq = $i AND sub_seq_30min = 0";
													$content_query_result = mssql_query($content_query);
												?>
													<tr class="doc_border" height="60px"  style="font-size:13px;">
														<td align="center" style="vertical-align:middle;"><?=($i+7).":00"; ?></td>
													<?	if(!mssql_num_rows($content_query_result)) { ?>
															<td></td>
															<td></td>
													<?	} else { ?>
														<?	$content_row = mssql_fetch_array($content_query_result); ?>
															<td style="padding:5px;"><pre><?=Br_iconv($content_row['customer']); ?></pre></td>
															<td style="padding:5px;"><pre><?=Br_iconv($content_row['remark']); ?></pre></td>
													<?	} ?>
													</tr>

												<? if($i < 11) { ?>
													<?	
														$content_30_query = "SELECT customer, remark FROM salesJournal WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND sub_seq = $i AND sub_seq_30min = 1";
														$content_30_query_result = mssql_query($content_30_query);
													?>
														<tr class="doc_border" height="60px"  style="font-size:13px;">
															<td align="center" style="vertical-align:middle;"><?=($i+7).":30"; ?></td>
														<?	if(!mssql_num_rows($content_30_query_result)) { ?>
																<td></td>
																<td></td>
														<?	} else { ?>
															<?	$content_30_row = mssql_fetch_array($content_30_query_result); ?>
																<td style="padding:5px;"><pre><?=Br_iconv($content_30_row['customer']); ?></pre></td>
																<td style="padding:5px;"><pre><?=Br_iconv($content_30_row['remark']); ?></pre></td>
														<?	} ?>
														</tr>
												<? } ?>
											<?	} ?>

											<?	
												$content_query = "SELECT customer, remark FROM salesJournal WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND sub_seq = 12";
												$content_query_result = mssql_query($content_query);
												$content_row = mssql_fetch_array($content_query_result);
											?>
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td width="100%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;" colspan="3">시장동향 (Market Tendency)</td>
											</tr>
											<tr class="doc_border" height="120px"  style="font-size:13px;">
												<?	if(!empty($$content_row['customer'])) { ?>
														<td colspan="3" style="padding:5px;"></td>
												<?	} else { ?>
														<td colspan="3" style="padding:5px;"><pre><?=Br_iconv($content_row['customer']); ?></pre></td>
												<?	} ?>
											</tr>
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td width="100%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;" colspan="3">특이사항 (Remark)</td>
											</tr>
											<tr class="doc_border" height="120px"  style="font-size:13px;">
												<?	if(!empty($$content_row['remark'])) { ?>
														<td colspan="3" style="padding:5px;"></td>
												<?	} else { ?>
														<td colspan="3" style="padding:5px;"><pre><?=Br_iconv($content_row['remark']); ?></pre></td>
												<?	} ?>
											</tr>
										</table>
									</td>
								</tr>

								<?	
									$content_query = "SELECT approvalDate FROM salesJournal WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND sub_seq = 0";
									$content_query_result = mssql_query($content_query);
									$content_row = mssql_fetch_array($content_query_result);
								?>
								<!-- 											파일첨부 보이는란 -->
<?
$ImgVariable = array();

if ($Type == 8) {
	$ImgPath = "upload/saleAttach/";
	$query = "SELECT ID, Seq, FileSeq, NewFilename FROM salesJournalAttach ".
			 "WHERE ID = $ID AND Seq = $Seq ".
			 "ORDER BY FileSeq ASC";
	$result3 = mssql_query($query);
	while($row3 = mssql_fetch_array($result3)) {
		$ImgVariable[$row3['FileSeq']] = $row3['NewFilename'];
	}
}
?>

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[1])));
	if($ImgVariable[1] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls" || $ext=="docx" || $ext=="doc")) { ?>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content" colspan="2" style="border: 1px solid #c9c9c9;">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[1])?>" target='pdf'><?=Br_iconv($ImgVariable[1])?></A>
									</td>
								</tr>
<?		} else if($ImgVariable[1]) { ?>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content" colspan="2">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[1])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[1])?>" style="max-width: 100%; height: auto;"></A>
									</td>
								</tr>
<?		} ?>

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[2])));
	if($ImgVariable[2] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls" || $ext=="docx" || $ext=="doc")) { ?>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content" colspan="2" style="border: 1px solid #c9c9c9;">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[2])?>" target='pdf'><?=Br_iconv($ImgVariable[2])?></A>
									</td>
								</tr>
<?		}  else if($ImgVariable[2]) { ?>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content" colspan="2" style="border: 1px solid #c9c9c9;">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[2])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[2])?>" style="max-width: 100%; height: auto;"></A>
									</td>
								</tr>
<?		} ?>

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[3])));
	if($ImgVariable[3] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls" || $ext=="docx" || $ext=="doc")) { ?>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content" colspan="2" style="border: 1px solid #c9c9c9;">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" target='pdf'><?=Br_iconv($ImgVariable[3])?></A>
									</td>
								</tr>
<?		}  else if($ImgVariable[3]) { ?>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content" colspan="2" style="border: 1px solid #c9c9c9;">
										<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" style="max-width: 100%; height: auto;"></A>
									</td>
								</tr>
<?		} ?>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>결재자 의견</b></td>
									<td class="doc_field_content" style="padding-top:5px; padding-bottom:5px;" colspan="<?=($content_row['approvalDate'] ? 2 : '' ); ?>">
										<?="<span style='line-height:20px;'>".$submitTime." - ".get_user_name($submitUserID)." 상신"."</span>"; ?><br>
									<!-- Log START -->
<?									$j = 0;
									for($i = 0; $i < 30; $i++) {
										if($logTime[$i]) {
											$j++;
											if($comments[$i])	$display = " &lt;&lt; <font color=green><b>".$comments[$i]." </b></font>&gt;&gt;<br>";
											else				$display = "<br>";

											if ($j%2 == 0)	$setColor = "<font color='black'>";
											else			$setColor = "<font color='blue'>";

											echo "<span style='line-height:20px;'>".$setColor.$logTime[$i]." - ".$ListVariable2[$i]." ".$StatusVariable2[$i]."</font>".$display."</span>";
										}
									} ?>
									</td>
								<?if(!$content_row['approvalDate'])	{?>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><input type="BUTTON" value="의견 기록" onClick="javascript:popupOpen('<?=$ID?>','<?=$Type?>','<?=$Seq?>','<?=$UserID?>','<?=$Subject?>','<?=$url?>')"></td>
								<?}?>
								</tr>
							</table>
						</td>						
					</tr>
					<!-- Sales Activities Journal FORM CONTENT END -->
				</table>
			</td>
		</tr>
		<!-- e-doc Sales Activities Journal MAIN END -->

		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
<?									if($sub == "view_wait") { ?>
										<td><button class="doc_submit_btn_style openMask">결재하기</button></td>
<?									} ?>
<?									if($sub == "view_submit" && $DateVariable[1] == 2) { ?>
										<td><input type="button" class="doc_submit_btn_style" onClick="approve(4)" value="회수하기"></td>
<?									} ?>
<?									if($sub == "view_recovery") { ?>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(1)" value="상신하기"></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(2)" value="수정하기"></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(3)" value="삭제하기"></td>
<?									} ?>
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
		<!-- e-doc Sales Activities Journal MAIN END -->
	</table>
</td>
</form>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Sales Activities Journal END -->

<!-- 결재하기 버튼 클릭시 START -->
<div id="mask"></div> 
<div id="window" class="window" onmousedown="startDrag(event, this)">
	<table width="300" height="380" style="background-color:#FFFFFF;">
		<tr>
			<td height="25" valign="middle" style="padding:14px 0 0 20px; background-color:#ececec;">
				<table width="100%">
					<tr>
						<td style="letter-spacing:-1px;"><font size="4"><b>결재</b></font></td>
						<td width="22" align="left"><img src="css/img/bt_closelayer.gif" class="close"></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30"></td>
		</tr>

		<tr height="50">
			<td>
				<table width="100%">
					<tr>
						<td style="padding-left:10px;"><font size="3">문서번호 :</font></td>
						<td><font size="3" color="red"><?=create_DocID($ID, $Seq); ?></font></td>
					</tr>
					<tr><td height="5"></td></tr>
					<tr>
						<td style="padding-left:10px;"><font size="3">문서종류 :</font></td>
						<td><font size="3" color="red"><?=get_docName($Type)?></font></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30"></td>
		</tr>
		<tr>
			<td style="padding-left:10px;"><font size="3">결재자 의견</font></td>
		</tr>
		<tr>
			<td style="padding-left:5px;">
				<textarea onClick="focus()" name="comment" id="comment" rows="7" cols="33"></textarea>
			</td>
		</tr>

		<tr>
			<td>
				<table width="100%">
					<tr>
						<td align="right"><input type="button" class="login_btn" onClick="approve(1)" value="결재"></td>
						<td width="5"></td>
						<td align="center"><input type="button" class="login_btn" onClick="approve(5)" value="반려"></td>
						<td width="5"></td>
						<td align="left"><input type="button" class="login_btn close" value="취소"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div> 
<!-- 결재하기 버튼 클릭시 END -->

<!-- 결재창 버튼 이벤트 START -->
<script>
function approve(approvalStatus) {
	var comment = document.getElementById("comment").value;
	document.forms.salesJournal.Comment.value = comment;
	document.forms.salesJournal.approval.value = approvalStatus;

	if(approvalStatus == 1) {
		document.forms.salesJournal.mode.value = "approve";
		var answer = confirm("결재 하시겠습니까?");
	} else if(approvalStatus == 4) {
		document.forms.salesJournal.mode.value = "withdraw";
		var answer = confirm("회수 하시겠습니까?");
	} else if(approvalStatus == 5) {
		document.forms.salesJournal.mode.value = "deny";
		var answer = confirm("반려 하시겠습니까?");
	}

	if(answer) {
		document.forms.salesJournal.submit();
	}
}
</script>
<!-- 결재창 버튼 이벤트 END -->

<!-- 임시저장, 회수문서 결재창 버튼 이벤트 START -->
<script>
function re_approve(event) {
	// event: 1.상신, 2.수정, 3.삭제
	if(event == 1) {
		document.forms.salesJournal.approval.value = 2;
		document.forms.salesJournal.mode.value = "re_submit";
		var answer = confirm("재상신 하시겠습니까?");
	} else if(event == 2) {
		document.forms.salesJournal.approval.value = 4;
		document.forms.salesJournal.action = "?page=e_doc&menu=offer&sub=edit_recovery";
	} else if(event == 3) {
		document.forms.salesJournal.mode.value = "delete";
		var answer = confirm("삭제 하시겠습니까?");
	}

	if(answer || event == 2) {
		document.forms.salesJournal.submit();
	}
}
</script>
<!-- 임시저장, 회수문서 결재창 버튼 이벤트 END -->

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
		$("#fUserSearch").attr("src", "?page=addLastUser&ID=<?=$ID; ?>&Type=<?=$Type; ?>&Seq=<?=$Seq; ?>");
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
			<td><iframe id="UserSearch" src="?page=addLastUser&ID=<?=$ID; ?>&Type=<?=$Type; ?>&Seq=<?=$Seq; ?>" height="400"></iframe></td>
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
			$("#fUserSearch").attr("src", "?page=faddLastUser&ID=<?=$ID; ?>&Type=<?=$Type; ?>&Seq=<?=$Seq; ?>");
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
			<td><iframe id="fUserSearch" src="?page=faddLastUser&ID=<?=$ID; ?>&Type=<?=$Type; ?>&Seq=<?=$Seq; ?>" height="400"></iframe></td>
		</tr>
	</table>
</div>
<!-- Approval Person Select jQuery & HTML END -->

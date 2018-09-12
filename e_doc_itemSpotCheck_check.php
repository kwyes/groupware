<script>
function calculate_difference(actual_qty, index, acctEdge_qty, avg_cost, total_item_num) {
	if(actual_qty || actual_qty.length > 0) {
		actual_qty = actual_qty * 1;
		acctEdge_qty = acctEdge_qty * 1;
		avg_cost = avg_cost * 1;
		var difference = actual_qty - acctEdge_qty;
		var diff_amount = difference * avg_cost;

		document.getElementById('qty_diff_'+index).innerHTML = difference;
		document.getElementById('cost_diff_'+index).innerHTML = diff_amount.toFixed(2);
	} else {
		document.getElementById('qty_diff_'+index).innerHTML = "";
		document.getElementById('cost_diff_'+index).innerHTML = "";
	}

	var total_diff = 0;
	for(var i = 1; i <= total_item_num; i++) {
		var diff_amount = document.getElementById('cost_diff_'+i).innerHTML * 1;
		total_diff = total_diff + diff_amount;
	}
	document.getElementById('total_diff_amount').innerHTML = total_diff.toFixed(2);
}

function doc_submit(total_item_num) {
	var field_required = false;
	for(var i = 1; i <= total_item_num; i++) {
		if(document.getElementById('actual_qty_'+i).value.length == 0) {
			field_required = false;
			break;
		} else {
			field_required = true;
		}
	}

	if(field_required) {
		var target = document.forms.itemSpotCheck;
		var answer = confirm("상신 하시겠습니까?\n상신후에는 수정, 회수가 불가능합니다.");
		if(answer) {
			target.mode.value = "checked";
			target.submit();
		}
	} else {
		alert("모든 실재고를 입력해 주십시요.");
	}
}

function doc_delete() {
	var target = document.forms.itemSpotCheck;
	var answer = confirm("삭제 하시겠습니까?");
	if(answer) {
		target.mode.value = "delete";
		target.submit();
	}
}

function popupOpen(Id,Type,Seq,UserID,Subject,url) {

	var popUrl = "e_doc_notice.php?ID="+Id+"&Type="+Type+"&Seq="+Seq+"&UserID="+UserID+"&Subject="+Subject+"&url="+url;
	var popOption = "width=370, height=360, resizable=no, scrollbars=no, status=no";

	window.open(popUrl,"",popOption);
}
</script>

<?
$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$today = date("Y-m-d H:i:s");
$UserID = $_SESSION['memberID'];
$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$url = urlencode($url);

$query = "SELECT top 1 item_category, CONVERT(char(19), submitDate, 120) AS submitDate, approvalStatus, submitUserID, approvalDate ".
		 "FROM itemSpotCheck ".
		 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND item_seq = 0";
//echo $query;
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);
$item_category = Br_iconv($row['item_category']);
$submitDate = $row['submitDate'];
$approvalStatus = $row['approvalStatus'];
$submitUserID = $row['submitUserID'];
$approvalDate = $row['approvalDate'];
$Subject = date("Y-m-d")." Item Spot Check List";

if($row['approvalStatus'] == 1) {
	$font_color = "#0000FF";
	$doc_status = "결제완료";
} else if($row['approvalStatus'] == 2){
	$doc_status = "결재진행중";
} else if($row['approvalStatus'] == 6) {
	$font_color = "#DF0101";
	$doc_status = "검사대기";
}

/*
echo "item_category - ".$item_category."<br>";
echo "submitDate - ".$submitDate."<br>";
echo "approvalStatus - ".$approvalStatus."<br>";
echo "submitUserID - ".$submitUserID."<br>";
*/

$query = "SELECT ApprovalUserID, ApprovalUserSeq, ApprovalStatus, ApprovalComment, CONVERT(char(20), ApprovalDate, 120) AS ApprovalDate, is_read,CONVERT(char(20), RegDate, 120) AS RegDate ".
		 "FROM ApprovalList ".
		 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
		 "ORDER BY RegDate ASC";
$result2 = mssql_query($query);

$iCount = 0;
while($row2 = mssql_fetch_array($result2)) {
	$LastApproval[$row2['ApprovalUserSeq']] = $row2['ApprovalUserID'];
	$ListVariable[$row2['ApprovalUserSeq']] = get_user_name($row2['ApprovalUserID']);
	$DateVariable[$row2['ApprovalUserSeq']] = $row2['ApprovalStatus'];
	$is_read[$row2['ApprovalUserSeq']] = $row2['is_read'];

	$ListVariable2[$iCount] = get_user_name($row2['ApprovalUserID']);
	$StatusVariable2[$iCount] = Br_iconv(get_ApprovalStatus($row2['ApprovalStatus']));
	if($row2['is_read'] == 1) {
		$comments[$iCount] = Br_iconv($row2['ApprovalComment']);
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
	}
}


$item_query = "SELECT item_seq, item_category, item_code, item_description, acctEdge_qty, avg_cost, submitDate, submitUserID ".
			  "FROM itemSpotCheck ".
			  "WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND item_seq != 0 ".
			  "ORDER BY item_seq ASC";
$item_query_result = mssql_query($item_query);
$item_query_num = mssql_num_rows($item_query_result);

?>


<!-- e-doc Item_Spot_Check START -->
<form name="itemSpotCheck" action="upload/upload_itemSpotCheck.php" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="">
<input type="hidden" name="ID" value="<?=$ID; ?>">
<input type="hidden" name="Type" value="<?=$Type; ?>">
<input type="hidden" name="Seq" value="<?=$Seq; ?>">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">Item Spot Check 작성</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc TITLE END -->

		<!-- e-doc Item_Spot_Check MAIN START -->
		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<? if($_SESSION['memberID'] == $submitUserID) { ?>
										<td><input type="button" class="doc_submit_btn_style" onClick="doc_delete()" value="삭제하기"></td>
										<td width="5"></td>
									<? } ?>
									<td><input type="button" class="doc_submit_btn_style" onClick="doc_submit(<?=$item_query_num; ?>)" value="상신하기"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Submit/Save BTN END -->

		<!-- Item_Spot_Check FORM START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<!-- Item_Spot_Check FORM TITLE START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td align="center" class="doc_title">Item Spot Check</td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Item_Spot_Check FORM TITLE END -->
					<? $strR="<img width='54' height='54' style='padding-top: 9px;' src='/images/09_img.png'>"; ?>
					<!-- Item_Spot_Check FORM CONTENT START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;">
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content" style="border-right: 0;"><b><?=create_DocID($ID, $Seq); ?></b></td>
									<td width="95" class="doc_field_content" style="border-left: 0; border-right: 0;"></td>
									<td class="doc_field_content" style="border-left: 0;"></td>
									<td width="365" rowspan="6" align="center" valign="top" style="padding:0;border-bottom:1px #afafaf solid;">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="5%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;"><?=get_user_name($submitUserID); ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1">검사자</td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"><?=$ListVariable[2]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"><?=$ListVariable[3]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"><?=$ListVariable[4]; ?></td>
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid;"><img width="54" height="54" style="padding-top: 9px;" src="/images/00_img.png"></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName1"><?=get_docimg_approval($DateVariable[1]); ?></td>
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
									<td class="doc_field_content" colspan="3">Item Spot Check</td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content" colspan="3" style="color:<?=$font_color; ?>"><?=$doc_status; ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>작성자</b></td>
									<td class="doc_field_content"><?=get_user_name($submitUserID); ?></td>
									<td height="30" align="center" class="doc_field_name"><b>검사자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>상신시간</b></td>
									<td class="doc_field_content"><?=$submitDate; ?></td>
									<td height="30" align="center" class="doc_field_name"><b>검사시간</b></td>
									<td class="doc_field_content"><?=$today; ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>카테고리</b></td>
									<td class="doc_field_content" colspan="3"><?=$item_category; ?></td>
								</tr>

								<tr>
									<td colspan="5" height="100%;">
										<table width="100%" style="margin-top:10px;" cellspacing="0">
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td width="5%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;">No</td>
												<td width="15%" align="left" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">Item Code</td>
												<td width="30%" align="left" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">Item Description</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#084B8A; color:#FFFFFF;">Avg. Cost ($)</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#084B8A; color:#FFFFFF;">AcctEdge 재고</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#DBA901; border-right:1px solid #FFFFFF;">실재고</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#C9C799; border-right:1px solid #FFFFFF;">Difference</td>
												<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#C9C799;">Diff. Amount ($)</td>
											</tr>
										<?	$index = 1; ?>
										<?	while($item_query_row = mssql_fetch_array($item_query_result)) { ?>
												<tr class="doc_border" height="20px"  style="font-size:13px;">
													<td align="center" style="padding-top:3px;"><?=$index; ?></td>
													<td align="left" style="padding:3px 0 0 5px;"><?=$item_query_row['item_code']; ?></td>
													<td align="left" style="padding:3px 0 0 5px;"><?=Br_iconv($item_query_row['item_description']); ?></td>
													<td align="right" style="padding:3px 5px 0 0;" id="<?="cost_".$index ?>"><?=round($item_query_row['avg_cost'], 2); ?></td>
													<td align="right" style="padding:3px 5px 0 0;" id="<?="qty_".$index ?>"><?=$item_query_row['acctEdge_qty']; ?></td>
													<td align="right"><input type="text" name="actual_qty[]" id="<?="actual_qty_".$index ?>" style="text-align:right;" onkeypress='return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46)' onblur="calculate_difference(this.value, <?=$index; ?>, <?=$item_query_row['acctEdge_qty']; ?>, <?=$item_query_row['avg_cost']; ?>, <?=$item_query_num; ?>)" required="required"></input></td>
													<td align="right" style="padding:3px 5px 0 0;" id="<?="qty_diff_".$index ?>"></td>
													<td align="right" style="padding:3px 5px 0 0;" id="<?="cost_diff_".$index ?>"></td>
													<input type="hidden" name="avg_cost[]" value="<?=$item_query_row['avg_cost']; ?>" />
													<input type="hidden" name="acctEdge_qty[]" value="<?=$item_query_row['acctEdge_qty']; ?>" />
												</tr>
										<?		$index++; ?>
										<?	} ?>
											<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
												<td style="border:0;"></td>
												<td style="border:0;"></td>
												<td style="border:0;"></td>
												<td style="border:0;"></td>
												<td style="border:0;"></td>
												<td style="border:0;"></td>
												<td align="right" style="padding:5px 5px 0 0; background-color:#C9C799; border:2px solid #000000;">Total Diff. Amount ($)</td>
												<td align="right" style="padding:5px 5px 0 0; background-color:#C9C799; border:2px solid #000000;" id="total_diff_amount"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="30" colspan="5" style="border-right: 1px solid #c9c9c9;"></td>
								</tr>

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>결재자 의견</b></td>
									<td class="doc_field_content" style="padding-top:5px; padding-bottom:5px;" colspan="3">
										<?="<span style='line-height:20px;'>".$submitDate." - ".get_user_name($submitUserID)." 상신"."</span>"; ?><br>
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
								<?if(!$row['ApprovalDate'])	{?>
									<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><input type="BUTTON" value="의견 기록" onClick="javascript:popupOpen('<?=$ID?>','<?=$Type?>','<?=$Seq?>','<?=$UserID?>','<?=$Subject?>','<?=$url?>')"></td>
								<?}?>
								</tr>
							</table>
						</td>						
					</tr>
					<!-- Item_Spot_Check FORM CONTENT END -->
				</table>
			</td>
		</tr>
		<!-- e-doc Item_Spot_Check MAIN END -->

		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<? if($_SESSION['memberID'] == $submitUserID) { ?>
										<td><input type="button" class="doc_submit_btn_style" onClick="doc_delete()" value="삭제하기"></td>
										<td width="5"></td>
									<? } ?>
									<td><input type="button" class="doc_submit_btn_style" onClick="doc_submit(<?=$item_query_num; ?>)" value="상신하기"></td>
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
		<!-- e-doc Item_Spot_Check MAIN END -->
	</table>
</td>
</form>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Item_Spot_Check END -->
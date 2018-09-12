<?/* 맨처음 검사자가 status가 8이아니면 e_doc_itemspotcheck_check를 인클루드해서 해서 실재고입력가능하게하게함 */
$queryforchk = "SELECT TOP 1 ApprovalUserID, ApprovalStatus FROM  ApprovalList WHERE docID = $ID AND doctype = $Type AND docseq = $Seq";
$query_resultforchk = mssql_query($queryforchk);
$rowforchk = mssql_fetch_array($query_resultforchk);
$approvalforchk = $rowforchk['ApprovalStatus'];
$apidforchk = $rowforchk['ApprovalUserID'];

if($approvalforchk != '8' && $_SESSION['memberID'] == $apidforchk){
	include_once "e_doc_itemSpotCheck_check.php";
} 

else{
?>
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
</script>

<?
$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Seq = ($_GET['Seq']) ? $_GET['Seq'] : $_POST['Seq'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$UserID = $_SESSION['memberID'];
$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$url = urlencode($url);

$query = "SELECT top 1 item_category, CONVERT(char(19), submitDate, 120) AS submitDate, approvalStatus, approvalDate, submitUserID, total_diff_amount, CONVERT(char(19), checkDate, 120) AS checkDate, CheckUserID ".
		 "FROM itemSpotCheck ".
		 "WHERE ID = $ID AND Type = $Type AND Seq = $Seq AND item_seq = 0";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);
$item_category = Br_iconv($row['item_category']);
$submitDate = $row['submitDate'];
$approvalStatus = $row['approvalStatus'];
$approvalDate = $row['approvalDate'];
$submitUserID = $row['submitUserID'];
$total_diff_amount = $row['total_diff_amount'];
$checkDate = $row['checkDate'];
$CheckUserID = $row['CheckUserID'];

if($row['approvalStatus'] == 1) {
	$font_color = "#0000FF";
	$doc_status = "결제완료";
} else if($row['approvalStatus'] == 2){
	$font_color = "#088A08";
	$doc_status = "결재진행중";
} else if($row['approvalStatus'] == 6) {
	$font_color = "#DF0101";
	$doc_status = "검사대기";
} else if($row['approvalStatus'] == 5) {
	$font_color = "#DF0101";
	$doc_status = "반려";
}

$query = "UPDATE ApprovalList SET is_read = 1, RegDate = GETDATE() ".
		 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq AND ApprovalUserID = '$UserID' AND is_read = 0 ";
mssql_query($query);

$query = "SELECT ApprovalUserID, ApprovalUserSeq, ApprovalStatus, ApprovalComment, CONVERT(char(20), ApprovalDate, 120) AS ApprovalDate, is_read, CONVERT(char(20), RegDate, 120) AS RegDate ".
		 "FROM ApprovalList ".
		 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq ".
		 "ORDER BY RegDate ASC";
$result2 = mssql_query($query);

$iCount = 0;
$rejected = FALSE;
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


$item_query = "SELECT item_seq, item_category, item_code, item_description, acctEdge_qty, avg_cost, submitDate, submitUserID, actual_qty, difference, diff_amount ".
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
<input type="hidden" name="Comment" value="">
<input type="hidden" name="approval" value="">
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
									<? if($sub == "view_wait") { ?>
										<td><button class="doc_submit_btn_style openMask">결재하기</button></td>
									<? } ?>
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
									<td width="140"></td>
									<td align="center" class="doc_title">Item Spot Check</td>
<?									if($LastApproval[sizeof($LastApproval)] == $UserID && $approvalStatus == 2) { ?>
										<td width="100" align="right" style="padding-top:10px;"><input type="button" id="fApproval" value="결재자 검색"></td>
										<td width="40" align="right" style="padding-top:10px;"><input type="button" id="doc_approval_btn" value="★"></td>
<?									} else { ?>
										<td width="140"></td>
<?									} ?>
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
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1"><?=$ListVariable[1]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"><?=$ListVariable[2]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"><?=$ListVariable[3]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"><?=$ListVariable[4]; ?></td>
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid;"><img width="54" height="54" style="padding-top: 9px;" src="/images/00_img.png"></td>
												<!-- <td style="border-bottom:1px #eaeaea solid;" id="appUserName1"><?=get_docimg_approval(1); ?></td> -->
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
									<td class="doc_field_content"><?=get_user_name($CheckUserID); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>상신시간</b></td>
									<td class="doc_field_content"><?=$submitDate; ?></td>
									<td height="30" align="center" class="doc_field_name"><b>검사시간</b></td>
									<td class="doc_field_content"><?=$checkDate; ?></td>
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
													<td align="right" style="padding:3px 5px 0 0;"><?=round($item_query_row['avg_cost'], 2); ?></td>
													<td align="right" style="padding:3px 5px 0 0;"><?=$item_query_row['acctEdge_qty']; ?></td>
													<td align="right" style="padding:3px 5px 0 0;"><?=$item_query_row['actual_qty']; ?></td>
													<td align="right" style="padding:3px 5px 0 0;"><?=$item_query_row['difference']; ?></td>
													<td align="right" style="padding:3px 5px 0 0;"><?=number_format(round($item_query_row['diff_amount'], 2), 2); ?></td>
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
												<td align="right" style="padding:5px 5px 0 0; background-color:#C9C799; border:2px solid #000000;"><?=number_format(round($total_diff_amount, 2), 2); ?></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="30" colspan="5" style="border-right: 1px solid #c9c9c9;"></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>결재자 의견</b></td>
									<td class="doc_field_content" style="padding-top:5px; padding-bottom:5px;" colspan="<?=($approvalDate ? '4' : '3' ); ?>">
										<?="<span style='line-height:20px;'>".$submitDate." - ".get_user_name($submitUserID)." 상신"."</span>"; ?><br>
									<!-- Log START -->
<?									$j = 0;
									for($i = 0; $i < 30; $i++) {
										if($logTime[$i]) {
											if($UserSeq[$i] == 1) {
												$j++;
												if ($j%2 == 0)	$setColor = "<font color='black'>";
												else			$setColor = "<font color='blue'>";

												echo "<span style='line-height:20px;'>".$setColor.$logTime[$i]." - ".$ListVariable2[$i]." 검사완료"."</font></span>"."<br>";
											} else {
												$j++;
												if($comments[$i])	$display = " &lt;&lt; <font color=green><b>".$comments[$i]." </b></font>&gt;&gt;<br>";
												else				$display = "<br>";

												if ($j%2 == 0)	$setColor = "<font color='black'>";
												else			$setColor = "<font color='blue'>";

												echo "<span style='line-height:20px;'>".$setColor.$logTime[$i]." - ".$ListVariable2[$i]." ".$StatusVariable2[$i]."</font>".$display."</span>";
											}
										}
									} ?>
									</td>
								<?if(!$approvalDate)	{?>
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
									<? if($sub == "view_wait") { ?>
										<td><button class="doc_submit_btn_style openMask">결재하기</button></td>
									<? } ?>
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

<!-- 결재창 마우스로 이동 START -->
<script type='text/javascript'>
var img_L = 0;
var img_T = 0;
var targetObj;

function getLeft(o) {
	return parseInt(o.style.left.replace('px', ''));
}
function getTop(o){
	return parseInt(o.style.top.replace('px', ''));
}

function moveDrag(e) {
	var e_obj = window.event? window.event : e;
	var dmvx = parseInt(e_obj.clientX + img_L);
	var dmvy = parseInt(e_obj.clientY + img_T);
	targetObj.style.left = dmvx +"px";
	targetObj.style.top = dmvy +"px";
	return false;
}

function startDrag(e, obj) {
	targetObj = obj;
	var e_obj = window.event? window.event : e;
	img_L = getLeft(obj) - e_obj.clientX;
	img_T = getTop(obj) - e_obj.clientY;

	document.onmousemove = moveDrag;
	document.onmouseup = stopDrag;
	if(e_obj.preventDefault)e_obj.preventDefault(); 
}

function stopDrag(){
	document.onmousemove = null;
	document.onmouseup = null;
}
</script>
<!-- 결재창 마우스로 이동 END -->

<!-- 결재창 버튼 이벤트 START -->
<script>
function approve(approvalStatus) {
	var comment = document.getElementById("comment").value;

	document.forms.itemSpotCheck.Comment.value = comment;
	document.forms.itemSpotCheck.approval.value = approvalStatus;

	if(approvalStatus == 1) {
		var answer = confirm("결재 하시겠습니까?");
		if(answer) {
			document.forms.itemSpotCheck.mode.value = "approved";
			document.forms.itemSpotCheck.submit();
		}
	} else if(approvalStatus = 5) {
		var answer = confirm("반려 하시겠습니까?");
		if(answer) {
			document.forms.itemSpotCheck.mode.value = "denied";
			document.forms.itemSpotCheck.submit();
		}
	}
}
</script>
<!-- 결재창 버튼 이벤트 END -->

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
<? } ?>
<!-- Approval Person Select jQuery & HTML END -->
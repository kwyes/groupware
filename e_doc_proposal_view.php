<script src="http://code.jquery.com/jquery-latest.js"></script>
<link href="/gw/resources/component/editor/daum/css/content_view.css" rel="stylesheet"  type="text/css"/>

<?
$UserID = $_SESSION['memberID'];
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$url = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
$url = urlencode($url);

if($mode == "move_doc") {
	$folderNum = ($_GET['folderNum']) ? $_GET['folderNum'] : $_POST['folderNum'];
	$folderSeq = ($_GET['folderSeq']) ? $_GET['folderSeq'] : $_POST['folderSeq'];
	$folderLocation = ($_GET['folderLocation']) ? $_GET['folderLocation'] : $_POST['folderLocation'];

	$query = "SELECT max(ContentSeq)+1 AS ContentSeq FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND FolderSeq = $folderSeq ".
			 "AND ($ID NOT IN (SELECT DocID FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND FolderSeq = $folderSeq) ".
			 "OR $Type NOT IN (SELECT DocType FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND FolderSeq = $folderSeq) ".
			 "OR $Seq NOT IN (SELECT DocSeq FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND FolderSeq = $folderSeq))";
	$query_result = mssql_query($query);
	$row = mssql_fetch_array($query_result);
	$contentSeq = $row['ContentSeq'];

/*
	echo "folderSeq: ".$folderSeq."<br>";
	echo "folderLocation: ".$folderLocation."<br>";
	echo "contentSeq: ".$contentSeq."<br>";
	echo "ID: ".$ID."<br>";
	echo "Type: ".$Type."<br>";
	echo "Seq: ".$Seq."<br>";
*/

	if($contentSeq) {
		$query = "INSERT INTO PersonalFolder (UserID, FolderLocation, FolderSeq, ContentSeq, DocID, DocType, DocSeq) ".
				 "VALUES ('$UserID', $folderLocation, $folderSeq, $contentSeq, $ID, $Type, $Seq)";
		mssql_query($query);

		if($folderNum) {
			$query = "DELETE FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND FolderSeq = $folderNum AND DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
			mssql_query($query);
		}

		if($folderLocation == 1) {
?>
			<script type="text/javascript">
				location.href="?page=e_doc&menu=receive&sub=receive_folder&showReceive=1&folderNum=<?=$folderSeq; ?>";
			</script>
<?		} else { ?>
			<script type="text/javascript">
				location.href="?page=e_doc&menu=offer&sub=offer_folder&showOffer=1&folderNum=<?=$folderSeq; ?>";
			</script>
<?
		}
	} else {
		if($folderNum) {
			$query = "UPDATE PersonalFolder SET RegDate = GETDATE() WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND FolderSeq = $folderSeq AND DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
			mssql_query($query);

			$query = "DELETE FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND FolderSeq = $folderNum AND DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
			mssql_query($query);

			if($folderLocation == 1) {
?>
				<script type="text/javascript">
					location.href="?page=e_doc&menu=receive&sub=receive_folder&showReceive=1&folderNum=<?=$folderSeq; ?>";
				</script>

<?			} else { ?>
				<script type="text/javascript">
					location.href="?page=e_doc&menu=offer&sub=offer_folder&showOffer=1&folderNum=<?=$folderSeq; ?>";
				</script>
<?			}
		} else {
?>
			<script type="text/javascript">
				alert("지정한 폴더에 이미 보관중인 문서입니다.");
			</script>
<?
		}
	}
} else if($mode == "delete_doc") {
	$folderNum = ($_GET['folderNum']) ? $_GET['folderNum'] : $_POST['folderNum'];
	$folderLocation = ($_GET['folderLocation']) ? $_GET['folderLocation'] : $_POST['folderLocation'];

	$query = "DELETE FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND FolderSeq = $folderNum AND DocID = $ID AND DocType = $Type AND DocSeq = $Seq";
	mssql_query($query);

	if($folderLocation == 1) {
?>
		<script type="text/javascript">
			location.href="?page=e_doc&menu=receive&sub=receive_folder&showReceive=1&folderNum=<?=$folderNum; ?>";
		</script>

<?	} else { ?>
		<script type="text/javascript">
			location.href="?page=e_doc&menu=offer&sub=offer_folder&showOffer=1&folderNum=<?=$folderNum; ?>";
		</script>
<?	}

}


// 상태 체크
// 0.읽지않음 1.읽음
$query = "UPDATE ApprovalList SET is_read = 1, RegDate = GETDATE() ".
		 "WHERE DocID = $ID AND DocType = $Type AND DocSeq = $Seq AND ApprovalUserID = '$UserID' AND is_read = 0 ";
mssql_query($query);

$ListVariable = array();
$DateVariable = array();
$comments = array();
$logTime = array();
$ListVariable2 = array();
$StatusVariable2 = array();

for($i=0; $i<30; $i++) {
	$DateVariable[$i] = "";
}

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
//	$comments[$row2['ApprovalUserSeq']] = $row2['ApprovalComment'];
//	$logTime[$row2['ApprovalUserSeq']] = $row2['ApprovalDate'];
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

$query = "SELECT DocCompanyID, CONVERT(char(10), DocSubmitDate, 126) AS DocSubmitDate, UserID, Subject, Contents, ApprovalStatus, CONVERT(char(20), RegDate, 120) AS SubmitTime, CONVERT(char(19), ApprovalDate, 120) AS ApprovalDate ".
		 "FROM Doc ".
		 "WHERE DocID = $ID AND DocSeq = $Seq ".
		 "ORDER BY DocSeq ASC";
$query_result = mssql_query($query);
$row = mssql_fetch_array($query_result);
$Subject = Br_iconv($row['Subject']);
?>

<script type="text/javascript">
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

function preview_mail(form) {
    var message = form.message.value;
    var preview = window.open('','preview');
    preview.document.write(message);
    preview.document.close();
    return true;
}
</script>

<!-- e-doc Proposal START -->
<form name="form_proposal" action="e_doc_approval_submit.php" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="ID" value="<?=$ID;?>">
<input type="hidden" name="Type" value="<?=$Type;?>">
<input type="hidden" name="Seq" value="<?=$Seq;?>">
<input type="hidden" name="doc_subject" value='<?=Br_iconv($row['Subject']); ?>'>
<input type="hidden" name="UserID" value="<?=$_SESSION['memberID'];?>">
<input type="hidden" name="Comment" value="">
<input type="hidden" name="approval" value="">
<input type="hidden" name="re_approval" value="">
<input type="hidden" name="current_url" value="">
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">받은 결재 문서함 > <?=get_doc_approval($row['ApprovalStatus']); ?></td>
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
<?						if($sub == "view_done" || $sub == "view_complete" || $sub == "view_folder" || $sub == "view_submit" ||($menu == "view_all" && $row['ApprovalStatus'] == 1)) { ?>
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
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(1, <?=$row['ApprovalStatus']; ?>)" value="상신하기"></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(2, <?=$row['ApprovalStatus']; ?>)" value="수정하기"></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(3, <?=$row['ApprovalStatus']; ?>)" value="삭제하기"></td>
<?									} ?>
<?									if($sub == "view_done" || $sub == "view_complete" || $sub == "view_folder") { ?>
										<td style="padding-top:5px;">
											<select id="move_folder" name="move_folder"  style="width:150px;">
<?	
												if($row['UserID'] == $UserID) {
													$folderLocation = 2;
												} else {
													$folderLocation = 1;
												}
												$folder_query = "SELECT FolderSeq, FolderName FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND ContentSeq = 0 ORDER BY FolderSeq";
												$folder_query_result = mssql_query($folder_query);
?>
												<option value="">폴더를 선택하세요.</option>
<?												while($folder_query_row = mssql_fetch_array($folder_query_result)) { ?>
<?													if($folder_query_row['FolderSeq'] !=  $folderNum) { ?>
														<option value='<?=$folder_query_row['FolderSeq']; ?>'><?=Br_iconv($folder_query_row['FolderName']); ?></option>
<?													} ?>
<?												} ?>
											</select>
										</td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="move_doc()" value="이동"></td>
<?										if($sub == "view_folder") { ?>
											<td width="5"></td>
											<td><input type="button" class="doc_submit_btn_style" onClick="delete_doc()" value="삭제"></td>
<?										} ?>
<?									} ?>
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
									<td align="center" class="doc_title">기안서</td>
<?									if($LastApproval[sizeof($LastApproval)] == $UserID && $row['ApprovalStatus'] == 2) { ?>
										<td width="100" align="right" style="padding-top:10px;"><input type="button" id="fApproval" value="결재자 검색"></td>
										<td width="40" align="right" style="padding-top:10px;"><input type="button" id="doc_approval_btn" value="★"></td>
<?									} else { ?>
										<td width="140"></td>
<?									} ?>
								</tr>
							</table>
						</td>
					</tr>
					<!-- Proposal FORM TITLE END -->
<?
	$strR="<img width='54' height='54' style='padding-top: 9px;' src='/images/09_img.png'>";
?>
					<!-- Proposal FORM CONTENT START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" style="border: 1px solid #c9c9c9; table-layout:fixed;">
								<tr class="doc_border">
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content"><b><?=create_DocID($ID, $Seq); ?></b></td>
									<td width="365" rowspan="6" align="center" valign="top">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="7%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;"><?=get_user_name($row['UserID']); ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app1"><?=$ListVariable[1]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app2"><?=$ListVariable[2]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app3"><?=$ListVariable[3]; ?></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;" id="app4"><?=$ListVariable[4]; ?></td>
											</tr>
											<tr height="70" align="center">
												<td style="border-bottom:1px #eaeaea solid;"><img width="54" height="54" style="padding-top: 9px;" src="/images/00_img.png"></td>
												<td style="border-bottom:1px #eaeaea solid;" id="appUserName1"><?=(($DateVariable[1] == 2 && $is_read[1] == 0) ?  $strR : get_docimg_approval($DateVariable[1])); ?></td>
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
									<td class="doc_field_content"><?=get_docName($Type); ?></td>
								</tr>
								<tr class="doc_border">
									<?	if($row['ApprovalStatus'] == 1) {
											$font_color = "#0000FF";
										} else if($row['ApprovalStatus'] == 2){
											$font_color = "#088A08";
										} else if($row['ApprovalStatus'] == 5) {
											$font_color = "#DF0101";
										} ?>
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content" style="color:<?=$font_color; ?>"><b><?=get_doc_approval($row['ApprovalStatus']); ?></b></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안자</b></td>
									<td class="doc_field_content"><?=get_user_name($row['UserID']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>실행회사</b></td>
									<td  align="left" class="doc_field_content"><?=get_company_name($row['DocCompanyID']); ?></td>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>기안일</b></td>
<?									if($row['ApprovalDate']) { ?>
										<td class="doc_field_content"><?=$row['DocSubmitDate']." (".$row['ApprovalDate'].")"; ?></td>
<?									} else { ?>
										<td class="doc_field_content"><?=$row['DocSubmitDate']; ?></td>
<?									} ?>
								</tr>
								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>제목</b></td>
									<td class="doc_field_content" colspan="2"><?=Br_iconv($row['Subject']); ?></td>
								</tr>

								<!-- Editor START -->
								<tr class="doc_border">
									<td align="center" class="doc_field_name"><b>내용</b></td>
									<td colspan="2" style="min-height:200; padding: 10px 12px; line-height:1.5;"><?=str_replace('-ms-word-break:','word-break:' , str_replace('\"', '"', Br_iconv($row['Contents']))); ?></td>
								</tr>
								<!-- Editor END -->

								<tr class="doc_border">
									<td height="30" align="center" class="doc_field_name"><b>결재자 의견</b></td>
									<td class="doc_field_content" style="padding-top:5px; padding-bottom:5px;" colspan="<?=($row['ApprovalDate'] ? 2 : '' ); ?>">
										<?="<span style='line-height:20px;'>".$row['SubmitTime']." - ".get_user_name($row['UserID'])." 상신"."</span>"; ?><br>
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

<?
$ImgVariable = array();

if ($Type == 1) {
	$ImgPath = "upload/DocAttach/";
	$query = "SELECT DocID, DocSeq, FileSeq, NewFilename FROM DocAttach ".
			 "WHERE DocID = $ID AND DocSeq = $Seq ".
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
<?									if($sub == "view_wait") { ?>
										<td><button class="doc_submit_btn_style openMask">결재하기</button></td>
<?									} ?>
<?									if($sub == "view_submit" && $DateVariable[1] == 2) { ?>
										<td><input type="button" class="doc_submit_btn_style" onClick="approve(4)" value="회수하기"></td>
<?									} ?>
<?									if($sub == "view_recovery") { ?>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(1, <?=$row['ApprovalStatus']; ?>)" value="상신하기"></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(2, <?=$row['ApprovalStatus']; ?>)" value="수정하기"></td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="re_approve(3, <?=$row['ApprovalStatus']; ?>)" value="삭제하기"></td>
<?									} ?>
<?									if($sub == "view_done" || $sub == "view_complete" || $sub == "view_folder") { ?>
										<td style="padding-top:5px;">
											<select id="move_folder" name="move_folder"  style="width:150px;">
<?	
												if($row['UserID'] == $UserID) {
													$folderLocation = 2;
												} else {
													$folderLocation = 1;
												}
												$folder_query = "SELECT FolderSeq, FolderName FROM PersonalFolder WHERE UserID = '$UserID' AND FolderLocation = $folderLocation AND ContentSeq = 0 ORDER BY FolderSeq";
												$folder_query_result = mssql_query($folder_query);
?>
												<option value="">폴더를 선택하세요.</option>
<?												while($folder_query_row = mssql_fetch_array($folder_query_result)) { ?>
<?													if($folder_query_row['FolderSeq'] !=  $folderNum) { ?>
														<option value='<?=$folder_query_row['FolderSeq']; ?>'><?=Br_iconv($folder_query_row['FolderName']); ?></option>
<?													} ?>
<?												} ?>
											</select>
										</td>
										<td width="5"></td>
										<td><input type="button" class="doc_submit_btn_style" onClick="move_doc()" value="이동"></td>
<?										if($sub == "view_folder") { ?>
											<td width="5"></td>
											<td><input type="button" class="doc_submit_btn_style" onClick="delete_doc()" value="삭제"></td>
<?										} ?>
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
		<!-- e-doc Proposal MAIN END -->
	</table>
</td>
</form>


<?
if($folderLocation == 1) {
	if($folderNum) {
		$move_doc_action = "?page=e_doc&menu=receive&sub=view_folder";
	} else {
		$move_doc_action = "?page=e_doc&menu=receive&sub=view_done";
	}
} else {
	if($folderNum) {
		$move_doc_action = "?page=e_doc&menu=offer&sub=view_folder";
	} else {
		$move_doc_action = "?page=e_doc&menu=offer&sub=view_complete";
	}
}
?>
<form name="form_move_doc" action="<?=$move_doc_action; ?>" method="post" accept-charset="utf-8">
	<input type="hidden" name="mode" value="move_doc">
	<input type="hidden" name="ID" value="<?=$ID;?>">
	<input type="hidden" name="Type" value="<?=$Type;?>">
	<input type="hidden" name="Seq" value="<?=$Seq;?>">
	<input type="hidden" name="folderLocation" value="<?=$folderLocation;?>">
	<input type="hidden" name="folderSeq">
	<? if($folderNum) { ?>
		<input type="hidden" name="<?=($showReceive) ? 'showReceive' : 'showOffer'?>" value="1">
		<input type="hidden" name="folderNum" value="<?=$folderNum;?>">
	<? } ?>
</form>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Proposal END -->

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

<!-- 결재창 마우스로 이동 START 
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
결재창 마우스로 이동 END -->

<!-- 결재창 버튼 이벤트 START -->
<script>
function approve(approvalStatus) {
//	var comment = document.getElementById("comment").innerHTML;
	var comment = document.getElementById("comment").value;

	document.forms.form_proposal.Comment.value = comment;
	document.forms.form_proposal.approval.value = approvalStatus;

	if(approvalStatus == 1) {
		var answer = confirm("결재 하시겠습니까?");
	} else if(approvalStatus == 4) {
		var answer = confirm("회수 하시겠습니까?");
	} else if(approvalStatus == 5) {
		var answer = confirm("반려 하시겠습니까?");
	}

	if(answer) {
		document.forms.form_proposal.submit();
	}
}
</script>
<!-- 결재창 버튼 이벤트 END -->

<!-- 임시저장, 회수문서 결재창 버튼 이벤트 START -->
<script>
function re_approve(event, approvalStatus) {
	// event: 1.상신, 2.수정, 3.삭제
	// approvalStatus: 3.임시저장, 4.회수
	document.forms.form_proposal.re_approval.value = event;
	document.forms.form_proposal.approval.value = approvalStatus;
	if(approvalStatus == 3) {
		document.forms.form_proposal.action = "?page=e_doc&menu=offer&sub=edit_save";
	} else if(approvalStatus == 4) {
		document.forms.form_proposal.action = "?page=e_doc&menu=offer&sub=edit_recovery";
	}

	if(event == 1) {
		var answer = confirm("상신 하시겠습니까?");
	} else if(event == 3) {
		var answer = confirm("삭제 하시겠습니까?");
	}

	if(answer || event == 2) {
		document.forms.form_proposal.submit();
	}
}
</script>
<!-- 임시저장, 회수문서 결재창 버튼 이벤트 END -->

<script>
function move_doc() {
	var target = document.forms.form_move_doc;
	var select = document.getElementById("move_folder")
	var folderSeq = select.options[select.selectedIndex].value;
	target.folderSeq.value = folderSeq;

	if(folderSeq != "") {
		var answer = confirm("지정한 폴더에 문서를 보관 하시겠습니까?");
		if(answer) {
			target.submit();
		}
	} else {
		alert("보관할 폴더를 선택하십시요.");
	}
}

function delete_doc() {
	var target = document.forms.form_move_doc;
	var answer = confirm("지정한 폴더에 문서를 삭제 하시겠습니까? \n(원본은 삭제 되지 않습니다)");
	if(answer) {
		target.mode.value = "delete_doc";
		target.submit();
	}
}

function printPage(Id, Type, Seq) {
	var popUrl = "print_preview_proposal.php?ID="+Id+"&Type="+Type+"&Seq="+Seq;
	var popOption = "width=800, height=600, toolbar=0, location=0, directories=0, resizable=1, menubar=0, scrollbars=yes, status=no";

	window.open(popUrl,"",popOption);
}
</script>


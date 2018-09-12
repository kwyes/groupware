<?
include_once "includes/general.php";
$ID = ($_GET['ID']) ? $_GET['ID'] : $_POST['ID'];
$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
$LUserID = $_SESSION['memberID'];
$cpage1 = ($_GET['cpage1']) ? $_GET['cpage1'] : $_POST['cpage1'];
?>
<script LANGUAGE="JAVASCRIPT">
function doc_submit() {
	var target = document.forms.form_proposal;

	if(target.subject.value == "") {
		alert("문서 제목을 입력하세요.");
		target.subject.focus();
		return;
	} else {
		var answer = confirm("저장 하시겠습니까?");

		if(answer) {
			target.mode.value = "note_submit";
			target.submit();
		} else {
			target.subject.focus();
			return;
		}
	}
}
function doc_update() {
	document.forms.form_proposal.action = "?page=community&menu=note&sub=edit";
	document.forms.form_proposal.submit();
}
function doc_delete() {
	var target = document.forms.form_proposal;

	var answer = confirm("삭제 하시겠습니까?");
	if(answer) {
		target.mode.value = "note_delete";
		target.submit();
	}
}

</script>
<?
	if($Type == 4) $str = "공지사항";
	else if($Type == 5)  $str = "앨범게시판";
	else if($Type == 6)  $str = "직원게시판";
	else if($Type == 7)  $str = "업무협조";
	else $str = "";

	$today = date("Y-m-d");
	$query = "select bdId,a.boardId,CompanyId,UserId,bdTitle,bdDescription,bdHit,CONVERT(char(20), a.RegDate, 120) AS RegDate, blName, ".
					" CONVERT(char(10), a.StartDate, 23) AS StartDate, CONVERT(char(10), a.EndDate, 23) AS EndDate, period,mainId ".
					"from board_data a, board_list b ".
					"where bdId = ".$ID." AND a.boardId = b.boardId ";
	$rst = mssql_query($query);
    $row = mssql_fetch_array($rst);
	$bname = $row['blName'];

	$query = "SELECT companyID, companyDesc FROM Company WHERE companyID = ".$row['CompanyId'];
	$row2 = mssql_query($query);
	$rst = mssql_fetch_array($row2);
	if($rst['companyID'] == 0) $company = "공통";
	else $company = $rst['companyDesc'];

	$HitCount = $row[bdHit] + 1;
	$query = "UPDATE board_data SET bdHit = $HitCount WHERE bdId = $ID ";
	$result = mssql_query($query);
?>
<td width="" align="left" valign="top">
	<form name="form_proposal" method="post" action="upload/upload_form.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="ID" value="<?=$ID?>">
	<input type="hidden" name="Type" value="<?=$Type?>">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">커뮤니티 > <?=$str?></td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>
<?/*?>
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td width="50%" align="right" style="padding: 0px 14px;">
										<a href="/"><button class="doc_submit_btn_style" onclick="history.go(-1); return false;">뒤로가기</button></a>
									</td>
									<?if($LUserID == $row['UserId']) {?>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_update(this.form)">수정</button></a></td>
									<td width="15" ></td>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_delete(this.form)">삭제</button></a></td>
									<?}?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
<?*/?>
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<tr>
						<td class="doc_field_content"><font color="#0099cc"><b><?=$row['UserId']?></b></font> | 번호: <?=$row['mainId']?> | 등록일: <?=$row['RegDate']?> </td>
						<td class="doc_field_content" align="right">조회수: <b><?=$HitCount?></b>&nbsp;&nbsp;</td>
					</tr>
				</table>
				<table>
					<tr>
						<td height="10" ></td>
					</tr>
				</table>
				<table width="100%">
					<tr>
						<td colspan="2" align="center" class="doc_title"><?=Br_iconv($bname)?></td>
					</tr>
					<tr class="doc_border">
						<td height="30" width="95" align="center" class="doc_field_name"><b>공지 회사</b></td>
						<td align="left" class="doc_field_content"><?=$company?></td>
					</tr>
					<? if($row['period'] == 0 && $row['EndDate'] >= $today ) { ?>
					<tr class="doc_border">
						<td height="30" width="95" align="center" class="doc_field_name"><b>공지 기간</b></td>
						<td align="left" class="doc_field_content"><?=$row['StartDate']?> ~ <?=$row['EndDate']?></td>
					</tr>
					<? } ?>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>문서 제목</b></td>
						<td class="doc_field_content"><?=Br_iconv($row['bdTitle'])?></td>
					</tr>
					<tr>
						<td align="center" class="doc_field_name" style="border: 1px solid #c9c9c9;"><b>내&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;용</b></td>
						<td height="350" style="border: 1px solid #c9c9c9; padding: 10px 12px;"><?=str_replace('\"', '"', Br_iconv($row['bdDescription'])); ?></td>
					</tr>
<?
$ImgVariable = array();

//if ($Type == 1) {
	$ImgPath = "upload/BoardAttach/";
	$query = "SELECT DocID, FileSeq, NewFilename FROM board_Attach ".
			 "WHERE DocID = $ID ".
			 "ORDER BY FileSeq ASC";
	$result3 = mssql_query($query);
	while($row3 = mssql_fetch_array($result3)) {
		$ImgVariable[$row3['FileSeq']] = $row3['NewFilename'];
	}
//} else if($Type == 2) {
//}
?>

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[1])));
	if($ImgVariable[1] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls")) { ?>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
						<td colspan="2" style="border: 1px solid #c9c9c9; padding: 10px 12px;">
							<A href="<?=Br_iconv($ImgPath.$ImgVariable[1])?>" target='pdf'><?=Br_iconv($ImgVariable[1])?></A>
						</td>
					</tr>
<?		} else if($ImgVariable[1]) { ?>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
						<td colspan="2" style="border: 1px solid #c9c9c9; padding: 10px 12px;">
							<A href="<?=Br_iconv($ImgPath.$ImgVariable[1])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[1])?>" width="600" height="900"></A>
						</td>
					</tr>
<?		} ?>

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[2])));
	if($ImgVariable[2] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls")) { ?>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
						<td colspan="2" style="border: 1px solid #c9c9c9; padding: 10px 12px;">
							<A href="<?=Br_iconv($ImgPath.$ImgVariable[2])?>" target='pdf'><?=Br_iconv($ImgVariable[2])?></A>
						</td>
					</tr>
<?	}  else if($ImgVariable[2]) { ?>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
						<td colspan="2" style="border: 1px solid #c9c9c9; padding: 10px 12px;">
							<A href="<?=Br_iconv($ImgPath.$ImgVariable[2])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[2])?>" width="600" height="900"></A>
						</td>
					</tr>
<?	} ?>

<?
	$ext = array_pop(explode(".", strtolower($ImgVariable[3])));
	if($ImgVariable[3] && ($ext=="pdf" || $ext=="xlsx" || $ext=="xls")) { ?>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
						<td colspan="2" style="border: 1px solid #c9c9c9; padding: 10px 12px;">
							<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" target='pdf'><?=Br_iconv($ImgVariable[3])?></A>
						</td>
					</tr>
<?	}  else if($ImgVariable[3]) { ?>
					<tr class="doc_border">
						<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
						<td colspan="2" style="border: 1px solid #c9c9c9; padding: 10px 12px;">
							<A href="<?=Br_iconv($ImgPath.$ImgVariable[3])?>"><img src="<?=Br_iconv($ImgPath.$ImgVariable[3])?>" width="600" height="900"></A>
						</td>
					</tr>
<?	} ?>	
				</table>
			</td>						
		</tr>
		<tr>
			<td align="right" style="padding: 0 12px 0 0;">
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td width="50%" align="left" style="padding: 0px 14px;">
							<a href="?page=community&menu=note&cpage1=<?=$cpage1?>"><div class="menu_button2" style="width:70px">뒤로가기</div></a>
						</td>
						<td width="50%" align="right">
							<table>
								<tr>
								<?if($LUserID == $row['UserId']) {?>
									<td width="80" align="right">
										<div class="menu_button2" style="width:70px" onclick="javascript:doc_update(this.form)">수정</div>
									</td>
									<td width="80" align="right">
										<div class="menu_button2" style="width:70px" onclick="javascript:doc_delete(this.form)">삭제</div>
									</td>
								<?}?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
<?/*?>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td width="50%" align="right" style="padding: 0px 14px;">
										<a href="/"><button class="doc_submit_btn_style" onclick="history.go(-1); return false;">뒤로가기</button></a>
									</td>
									<?if($LUserID == $row['UserId']) {?>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_update(this.form)">수정</button></a></td>
									<td width="15" ></td>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="javascript:doc_delete(this.form)">삭제</button></a></td>
									<?}?>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
<?*/?>
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
	</table>
	</form>
</td>

				</tr>
			</table>
		</td>	
	</tr>
</table>
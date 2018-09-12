<?
$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$UserID = $_SESSION['memberID'];
$today = date("Y-m-d");
$Status = 5;	// 반려

$search_mode =  ($_GET['search_mode']) ? $_GET['search_mode'] : $_POST['search_mode'];
$search_keyword =  ($_GET['search_keyword']) ? $_GET['search_keyword'] : $_POST['search_keyword'];

$search_keyword = Br_dconv($search_keyword);
if($where_keyword) {
	$where_keyword = " WHERE DocSubject like '%$where_keyword%'";
	$where_keyword2 = " AND DocSubject like '%$where_keyword%'";
}

$scale1 = 15; //page
($_GET['cpage1']) ? $cpage1 = $_GET['cpage1'] : $cpage1 = '1';
$start_q1 = Page_View1($cpage1, $scale1);  
$Get_next = "page=doc&cpage1=$cpage1&search_mode=$search_mode&search_keyword=$search_keyword";

$IT_where = $where_keyword;

$row_que = "select count(DocId) as row from Docform $IT_where";
$row_sel = mssql_query($row_que);
$row_fat = mssql_fetch_array($row_sel); 
$row_cnt = $row_fat['row'];

//페이지 인덱스 구하기
$cpage_que = $cpage1 * $scale1;
if($cpage_que == $scale1) {
	$cpage_que = "0";
} else {
	$cpage_que = $cpage_que - $scale1;
}

//마지막 장 갯수 구하기
$IT_top = $row_cnt - $cpage_que;
if($IT_top > $scale1)
{
	$IT_top = $scale1;
}
else
{
	$IT_top = $IT_top;
}

$query = "SELECT TOP $IT_top DocId, DocSubject, DocFilename, DocCompany, UserID, CONVERT(char(10), ".
			"RegDate, 126) as RegDate, DownCount ".
			"FROM Docform WHERE DocId NOT IN ".
				"(SELECT TOP $cpage_que DocId FROM Docform $IT_where ORDER BY RegDate DESC) ".
			"$where_keyword2 ORDER BY RegDate DESC";
$result = mssql_query($query);
$row = mssql_num_rows($result);
?>
<script>
function post_to_url(path, params, method) {
    method = method || "post";

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

function search() {
	var target = document.forms.form_search;

	if(target.search_keyword.value == "") {
		alert("검색할 제목을 입력하세요.");
		target.search_keyword.focus();
		return;
	}
	document.forms.form_search.submit();
}

function popupOpen(UserID){

	var popUrl = "docform.php?UserID="+UserID;
	var popOption = "width=370, height=360, resizable=no, scrollbars=no, status=no";

	window.open(popUrl,"",popOption);
}

function go(UserID){

	 location.href="?page=doc&menu=upload&UserID="+UserID;
}

function down(filename, id, down){

	 location.href="docform_download.php?filename="+filename+"&id="+id+"&down="+down;
}

function del(filename, id){

	var answer = confirm("삭제 하시겠습니까?");

	if(answer) {
		location.href="upload/upload_form.php?mode=delete&cpage1=$cpage1&filename="+filename+"&id="+id;
	} else {
		return;
	}
}
</script>

<!-- offer_submit_list START -->
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- offer_submit_list TITLE START -->
		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>

		<!-- offer_submit_list SEARCH BOX START -->
		<form name="form_search" action="<?php echo $PHP_SELF?>" method="post">
		<input type="hidden" name="mode" value="search">
		<input type="hidden" name="list" value=1>
		<tr>
			<td style="padding:12px 25px">
				<table width="100%" class="doc_border">
					<tr height="30">
						<td width="80" align="center" class="doc_field_name"><b>제목</b></td>
						<td style="padding: 3px 0 0 11px; border-right: 0;"><input name="search_keyword" type="text" style="width:300px; font-size:12px;" value="<?=$_POST['search_keyword']; ?>"></input>&nbsp;&nbsp;
						<input type="button" value="검색" onClick="javascript:search()">&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" value="문서 올리기" onClick="javascript:go('<?=$UserID?>')"></td>
						<td style="border-left: 0;"></td>
					</tr>
				</table>				
			</td>
		</tr>
		</form>
		<!-- offer_submit_list SEARCH BOX END -->

		<!-- offer_submit_list LIST START -->
		<tr>
			<td>
				<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
					<tr height="20">
						<td align="center" width="70" class="title bb br">번호</td>
						<td class="title bb br">문서제목</td>
						<td align="center" width="80" class="title bb br">작성자</td>
						<td align="center" width="60" class="title bb br">사용회사</td>
						<td align="center" width="80" class="title bb br">작성일자</td>
						<td align="center" width="80" class="title bb br">다운로드</td>
						<td align="center" width="80" class="title bb br">삭제</td>
					</tr>
<?					if($row == 0) { ?>
						<tr height="60">
							<td align="center" class="bb" colspan="7" style="padding-top:25px;">
								<b>조회된 문서가 없습니다</b>
							</td>
						</tr>
<?					} else {?>
<?						$i = 1;
						while($row = mssql_fetch_array($result)) {

							$filename = $row['DocFilename'];
							$filename = Br_iconv($filename);
							$filenameall = "upload/FormAttach/".$row['DocFilename']; 
							$filenameall = Br_iconv($filenameall);

							$ext = explode(".", $filename); 
							$ext = strtolower(trim($ext[count($ext)-1])); 

							switch( $ext ) 
							{ 
								case "jpg" :
								case "jpeg" :
									$ext_img_str = "images/jpg.png"; 
									break; 
								case "gif" : 
									$ext_img_str = "images/gif.png"; 
									break; 
								case "zip" : 
									$ext_img_str = "images/zip.png"; 
									break; 
								case "rar" : 
									$ext_img_str = "images/rar.png"; 
									break; 
								case "exe" : 
									$ext_img_str = "images/exe.png"; 
									break; 
								case "xls" : 
								case "xlsx" : 
									$ext_img_str = "images/xml.png"; 
									break; 
								case "hwp" : 
									$ext_img_str = "images/hwp.png"; 
									break; 
								case "doc" : 
								case "docx" : 
									$ext_img_str = "images/doc.png"; 
									break; 
								case "ppt" : 
								case "pptx" : 
									$ext_img_str = "images/ppt.png"; 
									break; 
								default : 
									$ext_img_str = ""; 
									break; 
							}
							if($row['DownCount'])	$down=$row['DownCount'];
							else $down = 0;
?>
							<tr height="25">
								<td align="center" class="docid bb"><a href="javascript:down('<?=$filenameall?>','<?=$row['DocId']?>','<?=$down?>')"><?=$row['DocId']; ?></a></td>
								<td class="content bb"><a href="javascript:down('<?=$filenameall?>','<?=$row['DocId']?>','<?=$down?>')"><?=Br_iconv($row['DocSubject'])?>&nbsp;&nbsp;<?if($ext_img_str) {?><img src="<?=$ext_img_str?>" width=15px, height=15px> <?}?></a></td>
								<td align="center" class="content bb" style="color:#009900"><?=get_user_name($row['UserID']); ?></td>
								<td align="center" class="content bb" style="color:#009900"><? if($row['DocCompany']==0) { echo "공통"; } else {  echo get_company_sname($row['DocCompany']);} ?></td>
								<td align="center" class="content bb"><?=$row['RegDate'];?></td>
								<td align="center" class="content bb"><?=$down?></td>
								<td align="center" class="content bb">
<?								if($UserID == $row['UserID']) {?>
								<a href="javascript:del('<?=$filename?>','<?=$row['DocId']?>')"><font color="red">삭제</font></a>
<?								}?>
								</td>
							</tr>
<?							if($i++ == $page_per_list) {
								break;
							} ?>
<?						} ?>
<?					} ?>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30"></td>
		</tr>

		<!-- PAGE NAVIGATION START -->
		<tr>
			<td align="center">
				<div style="padding-top:10px;">
					<table cellpadding="0" cellspacing="0" width="100%">
						<tr>
							<td align="center">
								<?= Page_View3($scale1, $cpage1, $row_cnt, 10, $Get_next);?>
							</td>
							<? /* ?>
							<td width="55%" align="right">
								<?
									$thispage_start = $cpage_que + 1;
									$thispage_end = $cpage_que + $IT_top;
									$thispage_result = $thispage_end - $cpage_que;
								?>
								전체 <b><?=$row_cnt?></b>개 중
								<span style="color:#7a7a7a;">[현재 페이지: <?=$thispage_start?>번 부터 <?=$thispage_end?>번 까지 <?=$thispage_result?>개의 결과]
								</span>
							</td>
							<td width="45%" align="right">
								<?= Page_View2($scale1, $cpage1, $row_cnt, 10, $Get_next);?>
							</td>
							<? */ ?>
						</tr>
					</table>
				</div>
			</td>
		</tr>

		<!-- PAGE NAVIGATION END -->

		<tr>
			<td height="30"></td>
		</tr>
		<!-- offer_submit_list LIST END -->
		<!-- offer_submit_list MAIN END -->
	</table>
</td>
<!-- offer_submit_list END -->
				</tr>
			</table>
		</td>	
	</tr>
</table>
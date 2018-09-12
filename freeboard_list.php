<?
session_start();
include_once("includes/general.php"); 

$menu = ($_GET['menu']) ? $_GET['menu'] : $_POST['menu'];

if($_SESSION['useHelp'] != 1 && $menu == "help") {
	echo	"<script>
		        window.alert('귀하는 이 페이지의 권한이 없습니다. 관리자에게 문의 하세요.');
		        history.go(-1);
			</script>";
}

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$UserID = $_SESSION['memberID'];
$today = date("Y-m-d");

$search_mode =  ($_GET['search_mode']) ? $_GET['search_mode'] : $_POST['search_mode'];
$search_keyword =  ($_GET['search_keyword']) ? $_GET['search_keyword'] : $_POST['search_keyword'];

$search_keyword = Br_dconv($search_keyword);
if($search_keyword) {
	$where_keyword = " and bdTitle like '%$search_keyword%'";
}

if($menu == "free")			$bType= "6";
else if($menu == "help")		$bType= "7";

$scale1 = 15; //page
($_GET['cpage1']) ? $cpage1 = $_GET['cpage1'] : $cpage1 = '1';
$start_q1 = Page_View1($cpage1, $scale1);  
$Get_next = "page=community&menu=".$menu."&cpage1=$cpage1&search_mode=$search_mode&search_keyword=$search_keyword";

if($menu=='free')
	$IT_where = " boardId = 6".$where_keyword;
else if($menu=='help')
	$IT_where = " boardId = 7".$where_keyword;

$row_que = "select count(boardId) as row from board_data where $IT_where";
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

$query = "SELECT TOP $IT_top bdId,boardId,CompanyId,UserId,bdEmail,dbPhone,bdTitle,bdDescription,bdHit,helpId,freeId,".
			"CONVERT(char(10),RegDate,126) as RegDate, ".
			"CONVERT(char(10),UpdateDate,126) as UpdateDate ".
			"FROM board_data ".
			"WHERE bdId NOT IN (SELECT TOP $cpage_que bdId FROM board_data where $IT_where) AND $IT_where ".
			"ORDER BY RegDate DESC ";
$result = mssql_query($query);
$row = mssql_num_rows($result);
?>
<script>
function post_to_url(path, params) {
    method = "post";

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

function go(str){

	 location.href="?page=community&menu="+str+"&sub=write";
}

function down(filename, id, down){

	 location.href="docform_download.php?filename="+filename+"&id="+id+"&down="+down;
}

function del(id){

	var answer = confirm("삭제 하시겠습니까?");

	if(answer) {
		location.href="upload/upload_form.php?mode=note_delete&ID="+id;
	} else {
		return;
	}
}
</script>

<!-- offer_submit_list START -->
<td width="" align="left" valign="top">
	<table width="100%">
		<form name="form_search" action="<?php echo $PHP_SELF?>" method="post">
		<tr>
			<td height="1" valign="top" style="border-top: 1px #c9c9c9 solid"></td>
		</tr>
		<tr>
			<td style="padding:12px 25px">
				<table width="100%" class="doc_border">
					<tr height="33">
						<td width="80" align="center" class="doc_field_name"><b>제목</b></td>
						<td style="padding: 3px 0 0 11px; border-right: 0;"><input name="search_keyword" type="text" style="width:300px; font-size:12px;" value="<?=$_POST['search_keyword']; ?>"></input>&nbsp;&nbsp;
						<input type="button" value="검색" onClick="javascript:search()">&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" value="문서 올리기" onClick="javascript:go('<?=$menu?>')"></td>
						<td style="border-left: 0;"></td>
					</tr>
				</table>				
			</td>
		</tr>
		</form>
		<tr>
			<td>
				<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
					<tr height="20">
						<td align="center" width="70" class="title bb br">번호</td>
						<td class="title bb br">문서제목</td>
						<td align="center" width="80" class="title bb br">작성자</td>
						<td align="center" width="60" class="title bb br">공지회사</td>
						<td align="center" width="80" class="title bb br">작성일자</td>
						<td align="center" width="80" class="title bb br">조회</td>
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
						$j = 0;
						while($row = mssql_fetch_array($result)) {
							$j++;
							if($row['bdHit'])	$hit=$row['bdHit'];
							else $hit = 0;
							$bdid = $row['bdId'];

							$replyquery = "select brId,brBdId from board_reply where brId = $bdid and brBdId = $bType";
							$resultforreply = mssql_query($replyquery);
							$replynum = mssql_num_rows($resultforreply);
							$replydes = "<span style='color:#468AEA;text-shadow: 4px 4px 2px rgba(150, 150, 150, 1);'>답변</span>";
							$replynum2 = "<span style='color:red;'>".$replynum."</span>";

							if($replynum != 0){
								$replytotal = $replydes."+".$replynum2;
							}
							else 
							{
								$replytotal = "";
							}
							if($bType == '7'){
								$seqid = $row['helpId'];
							}
							else
								$seqid = $row['freeId'];
			

?>
							<tr height="25">
								<td align="center" class="docid bb"><a href="javascript:post_to_url('?page=community&menu=<?=$menu?>&sub=view', {'ID':<?=$row['bdId']?>, 'Type':<?=$bType?>,'cpage1':<?=$cpage1?>})"><?=$seqid?></a></td>
								<td class="content bb"><a href="javascript:post_to_url('?page=community&menu=<?=$menu?>&sub=view', {'ID':<?=$row['bdId']?>, 'Type':<?=$bType?>,'cpage1':<?=$cpage1?>})"><?=str_replace('\"', '"', Br_iconv($row['bdTitle']))."   ".$replytotal?></a></td>
								<td align="center" class="content bb" style="color:#009900"><?=get_user_name($row['UserId']); ?></td>
								<td align="center" class="content bb" style="color:#009900"><? if($row['CompanyId']==0) { echo "공통"; } else {  echo get_company_sname($row['CompanyId']);} ?></td>
								<td align="center" class="content bb"><?=$row['RegDate'];?></td>
								<td align="center" class="content bb"><?=$hit?></td>
								<td align="center" class="content bb">
<?								if($UserID == $row['UserId']) {?>
								<a href="javascript:del('<?=$row['bdId']?>')"><font color="red">삭제</font></a>
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
	</table>
</td>
<!-- offer_submit_list END -->
				</tr>
			</table>
		</td>	
	</tr>
</table>
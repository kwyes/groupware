<?
    include_once "includes/general.php";
	
	$UserID = $_SESSION['memberID'];

	$scale1 = 7;
    ($_GET['cpage1']) ? $cpage1 = $_GET['cpage1'] : $cpage1 = '1';
    $start_q1 = Page_View1($cpage1, $scale1);  

	$s_where = " UserID = '$UserID' AND (Status = 1 OR Status = 5) ";
	$s_sort = " Order by RegDate DESC ";
  	$s_field = "ID, Type, Seq, Status, CompanyID, UserID, SubmitDate, Subject, convert(char(10), SubmitDate, 126) AS SubmitDate, RegDate";
	$row_que = "select count(UserID) as row from E_DOC_Header where $s_where";
	$row_sel = mssql_query($row_que);
    $row_fat = mssql_fetch_array($row_sel); 

	$row = $row_fat['row'];
    $cpage_que = $cpage1 * $scale1;
    if($cpage_que == $scale1)
    {
        $cpage_que = "0";
    }
    else
    {
        $cpage_que = $cpage_que - $scale1;
    }
	
    $IT_top = $row - $cpage_que;
    if($IT_top > $scale1)
    {
        $IT_top = $scale1;
    }
    else
    {
        $IT_top = $IT_top;
    }
    
//    $Get_next = "main=admin&sub=manageproducts&cpage1=$cpage1&search_prcategory=$search_prcategory&search_pname=".Br_iconv($search_pname);
    
    $page_section = $start_q1.",".$scale1;    
    
    if($search_prcategory)
    {
        $where_prcategory = "and prCategory = '$search_prcategory'";
    }
    else
    {
        $where_prcategory = "";
    }
    
    if($search_pname)
    {
        $where_pname = " prodKname like '%$search_pname%' ";
    }
    else
    {
        $where_pname = "";
    }

    //listing
    $list_where = "select top $scale1 $s_field from E_DOC_Header where UserID not in (select top $cpage_que UserID from E_DOC_Header WHERE $s_where $s_sort) and  $s_where $s_sort ";
	$ListSel = mssql_query( $list_where);
?>
<script>

function update_form()
{
	document.edit_form.submit();   
}

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

// 삭제하기
function check_del(c_code,idx,find,search,position)
{
	if (confirm("정말 삭제하시겠습니까?")) {
		document.location.href='customer_reg.php?mode=delete&u_code='+c_code+'&idx='+idx+'&find='+find+'&search='+search+'&position='+position;
	}
}

function go_edit()
{
    if(!document.edit_form.prodId.value)
    {
        alert('PLU is required.');
		return false;
    }
    document.getElementById("btn_edit").disabled = "disabled";
    document.edit_form.submit();  
}

function go_delete(id,seq)
{  
    var flag = confirm('Do you want to delete?');
    if(flag)
    {
        document.delete_form.prodId.value = id;
        document.delete_form.submit();   
    }
}

function go_search()
{
    document.search_form.submit(); 
}                 

function go_rank()
{
    document.rank_form.submit(); 
}         
</script>
	<tr>
		<td>
			<table width="100%" class="doc_main_table" style="border-top:#c9c9c9 1px solid;">
				<tr height="20">
					<td width="100" align="left" class="title bb br">문서번호</td>
					<td width="10%" align="left" class="title bb br">문서종류</td>
					<td align="left" class="title bb br">제목</td>
					<td width="10%" align="left" class="title bb br">문서상태</td>
					<td width="20%" align="left" class="title bb br">실행회사</td>
					<td width="10%" align="left" class="title bb br">작성자</td>
					<td width="10%" align="left" class="title bb br">작성일자</td>
				</tr>
				<? if($row == 0) { ?>
				<tr height="60">
					<td align="center" class="bb" colspan="7" style="padding-top:25px;">
						<b>조회된 문서가 없습니다</b>
					</td>
				</tr>
				<? } else { ?>
					<? while($ListRow = mssql_fetch_array($ListSel)) { ?>
						<tr height="25">
							<td width="10%" class="docid bb"><a href="javascript:post_to_url('index.php?page=e_doc&menu=offer&sub=view_complete', {'ID':'<?=$ListRow['ID']?>', 'Seq':'<?=$ListRow['Seq']?>','Type':'<?=$ListRow['Type']?>'});"><?=create_DocID($ListRow['ID'], $ListRow['Seq'])?></a></td>
							<td width="10%" class="content bb"><?=get_docName($ListRow['Type'])?></td>
						<?	if($ListRow['Status'] == 1) {
								$font_color = "#0000FF";
							} else if($ListRow['Status'] == 2){
								$font_color = "#088A08";
							} else if($ListRow['Status'] == 5) {
								$font_color = "#DF0101";
							} ?>
							<td align="left" class="content bb"><a href="javascript:post_to_url('index.php?page=e_doc&menu=offer&sub=view_complete', {'ID':'<?=$ListRow['ID']?>', 'Seq':'<?=$ListRow['Seq']?>','Type':'<?=$ListRow['Type']?>'});"><?=Br_iconv($ListRow['Subject'])?></a></td>
							<td width="10%" class="content bb" style="color:<?=$font_color; ?>"><?=get_doc_approval($ListRow['Status'])?></td>
							<td width="20%" class="content bb"><?=get_company_name($ListRow['CompanyID'])?></td>
							<td width="10%" class="content bb"><?=get_user_name($ListRow['UserID'])?></td>
							<td width="10%" class="date bb"><?=$ListRow['SubmitDate']?></td>
						</tr>
					<? } ?>
				<? } ?>
			</table>
		</td>
	</tr>
    <div id="div_boardlist_loading" style="display:none; padding-top:10px; text-align:center;">
        <img src="<?=SYSTEM_PATH?>/images_site/ajax-loader.gif"><span style="font-family:verdana;">데이터 가져오는 중..</span>
    </div>

<?/*?>
    <div style="padding-top:10px;">
        <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td align="center">
                    <?= Page_View3($scale1, $cpage1, $row, 10, $Get_next);?>
                </td>
            </tr>
        </table>
    </div>
<?*/?>
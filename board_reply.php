<?
    include_once("includes/general.php");
	$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
	$Type = ($_GET['Type']) ? $_GET['Type'] : $_POST['Type'];
    $bdId = ($_GET['bdId']) ? $_GET['bdId'] : $_POST['bdId'];
    $boardId = ($_GET['boardId']) ? $_GET['boardId'] : $_POST['boardId'];

	if(!$bdId)
    {
        exit;
    }
    
    //refresh
    $refresh = ($_GET['refresh']) ? $_GET['refresh'] : $_POST['refresh'];
    $txtarea_size1 = ($_GET['txtarea_size1']) ? $_GET['txtarea_size1'] : $_POST['txtarea_size1'];

    $search_sort = $_GET['search_sort'];
    if($search_sort == "sort_post")
    {
        $BD_sort = " order by brPostDate asc";
    }
    else if($search_sort== "sort_update")
    {
        $BD_sort = " order by brPostDate desc";
    }
    else
    {
        $BD_sort = " order by brPostDate asc";
    }

    $query = "select brId,brBoardId,brBdId,brMemberId,brDescription,CONVERT(char(20),brPostDate,126) AS brPostDate,CONVERT(char(20),brUpdateDate,126) AS brUpdateDate,brActive ".
			"from board_reply where brId = '$bdId' AND brBdId = $Type";
    $rst = mssql_query($query);
    $ReCount = mssql_num_rows($rst);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<script type="text/javascript" src="<?=SYSTEM_PATH?>/includes/common.js"></script>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<link rel="stylesheet" href="css/style_program.css" type="text/css" />
</head>
<script>
function go_search()
{
    document.search_form.submit();   
}

function go_reply()
{  
    if(!document.reply_form.brDescription.value)
    {
        alert('내용을 입력해주세요.');
        reply_form.brDescription.focus();
        return false;
    }
    
    document.getElementById("btn_reply").disabled = "disabled";
    document.reply_form.submit();
}

function go_delete_reply(brId)
{  
    var flag = confirm('삭제 하시겠습니까?');
    if(flag)
    {
        if(!document.getElementById( "brPassword_delete"+brId ).value)
        {
            alert('Password is required.');
            return false;
        }
        var br_password = document.getElementById( "brPassword_delete"+brId ).value;
        document.reply_delete_form.brId.value = brId;
        document.reply_delete_form.brPassword.value = br_password;
        
        document.reply_delete_form.submit();   
    }
}

function go_delete_reply2(brId, BoardId)
{  
    var flag = confirm('삭제 하시겠습니까?');
    if(flag)
    {
        document.reply_delete_form.bdId.value = brId;
        document.reply_delete_form.boardId.value = BoardId;
		document.reply_delete_form.submit();   
    }
}

//수정모드
function go_edit_reply(brId)
{  
    if(!document.getElementById( "brPassword_edit"+brId ).value)
    {
        alert('Password is required.');
        return false;
    }
    var br_password = document.getElementById( "brPassword_edit"+brId ).value;
    document.reply_edit_form.brId.value = brId;
    document.reply_edit_form.brPassword.value = br_password;
    
    document.reply_edit_form.submit();   
}

function go_edit_reply2(brId, boardId)
{  
    document.reply_edit_form.brId.value = brId;
    document.reply_edit_form.boardId.value = boardId;
    document.reply_edit_form.submit();   
}

function toggle_password_reply(mode, div_no) 
{   
    //삭제모드
    if(mode == "reply_delete")
    {
        if(document.getElementById( "div_reply_delete"+div_no ).style.display == "")
        {
            document.getElementById( "div_reply_delete"+div_no ).style.display = "none";
        }
        else
        {
            document.getElementById( "div_reply_delete"+div_no ).style.display = "";
        }
    }
    //수정모드
    else if(mode == "reply_edit")
    {
        if(document.getElementById( "div_reply_edit"+div_no ).style.display == "")
        {
            document.getElementById( "div_reply_edit"+div_no ).style.display = "none";
        }
        else
        {
            document.getElementById( "div_reply_edit"+div_no ).style.display = "";
        }
    }
}

function go_reply_vote(br_Id, board_Id) 
{   
    var flag = confirm('Do you like this comment?');
    
    if(flag)
    {
        document.reply_vote_form.brv_brId.value = br_Id;
        document.reply_vote_form.brv_boardId.value = board_Id;
           
        document.reply_vote_form.submit();   
    }
}

function go_refresh()
{
    document.refresh_form.submit();
}
</script>
<body>
<div style="border:1px #eeeeee solid;">
    <div id="container" style="background-color:#FFFFFF; color:#222222; border:1px #cccccc solid; padding:20px;">
    <table cellpadding="0" cellspacing="0" width="100%" border="0">
    <form name="refresh_form" method="get" action="<?php echo $PHP_SELF?>">
    <input type="hidden" name="main" value="<?=$main?>">
    <input type="hidden" name="bdId" value="<?=$bdId?>">
    <input type="hidden" name="Type" value="<?=$Type?>">
    <input type="hidden" name="boardId" value="<?=$boardId?>">
    <input type="hidden" name="refresh" value="1">
    </form>

    <form name="search_form" method="get" action="<?php echo $PHP_SELF?>">
    <input type="hidden" name="mode" value="search">
    <input type="hidden" name="main" value="<?=$main?>">
    <input type="hidden" name="bdId" value="<?=$bdId?>">
    <input type="hidden" name="Type" value="<?=$Type?>">
    <input type="hidden" name="boardId" value="<?=$boardId?>">
    <input type="hidden" name="refresh" value="1">
        
    <tr>
        <td align="left">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="50%" align="left">
                        <span style="color:#D15D00;">댓글: <b><?=$ReCount?></b></span>
                    </td>
                </tr>
            </table>
       </td>
    </tr>
    </form>
    <tr><td height="5px;"></td></tr>
    <tr><td height="1px;"><div style="border-top:1px #eeeeee solid;"></div></td></tr>
    <tr><td height="1px;"></td></tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" width="100%">
            <form name="reply_delete_form" method="post" action="upload/upload_board.php">
            <input type="hidden" name="mode" value="delete_reply">             
            <input type="hidden" name="bdId" value="">
			<input type="hidden" name="boardId" value="">
            <input type="hidden" name="refresh" value="1">
            </form>
            
            <form name="reply_edit_form" method="post" action="<?php echo $PHP_SELF?>">
            <input type="hidden" name="mode" value="edit_reply">             
            <input type="hidden" name="brId" value="">
            <input type="hidden" name="boardId" value="">
			<input type="hidden" name="Type" value="<?=$Type?>">
            <input type="hidden" name="txtarea_size1" value='<?=$txtarea_size1?>'>
            <input type="hidden" name="refresh" value="1">
            </form>
            
            <? if($mode != "edit_reply") { ?>
            <? if($ReCount > 0) { ?>    
                <? 
                $i = 1;
                while($row = mssql_fetch_array($rst)) 
                {
                    $s = $i++;
                    $reply_time = Br_datetime_korean_forConverted($row['brPostDate']);
                    $reply_update_time = Br_datetime_korean_forConverted($row['brUpdateDate']);
					
                    $ip_address = $row['brIpAddress'];
                    
					$ExpertDesign = "no";
					$writer_reply = $row['brMemberId'];   
                ?>
                <? if($s != 1) { ?>
                <tr><td height="1px;"><div style="border-top:1px #dddddd dashed;"></div></td></tr>
                <? } ?>
                <tr><td>
                <table cellpadding="0" cellspacing="0" width="98%" border="0">        
                    <tr><td height="10px"></td></tr>    
                    <tr>
                        <td width="85" align="center" valign="top">
                        <? if($ExpertDesign == "normal") { ?>
                            <div style="line-height:130%; padding-left:5px;"><span style="cursor:pointer; color:#A86A20;"><?=get_user_name($writer_reply);?></span></div>
                        <? } else { ?>
                            <div style="line-height:130%; padding-left:5px;"><?=get_user_name($writer_reply);?></div>
                         <? } ?>
                        </td>
                        <td width="580" align="left" valign="top" style="background-color:#FFFFFF;">
                            <div style="line-height:130%; padding-left:7px;">
                                <div style="color:#7a7a7a;">
                                <table cellpadding="0" cellspacing="0" width="100%">
                                    <tr>
                                        <!-- 수정 -->
                                        <span><?=Br_datetime_korean_forConverted($row['brPostDate'])?> 
                                        <? if(($row['brMemberId'] && $row['brMemberId'] == $_SESSION['memberID']) || $_SESSION['memberID'] == "admin") { ?>
                                        <span style="color:#cccccc;">|</span> 
                                        <a href="#" onclick="go_edit_reply2(<?=$row['brId']?>, <?=$row['brBoardId']?>);" title="Edit"><font color="red">수정</font></a>
                                        <? } ?>
                                        <?=($reply_time != $reply_update_time) ? "<span title='Updated: ".$reply_update_time."' style='cursor:pointer;'>Edited</span>" : "";?></span>
                                        </td>
                                    </tr>
                                    <tr><td height="5px;"></td></tr>
                                </table>
                                </div>
                                <div style="padding-top:5px; <?=($BestDesign == "yes") ? "font-weight:bold; color:#8B0E5A;" : "";?>">
                                <span style="line-height:150%;"><?=($row['brActive'] == "2") ? "<font color='red'>Blind by Admin</font>" : nl2br(Br_iconv($row["brDescription"]));?></span>
                                </div>
                            </div>
                        </td>
                        <td align="right" valign="top">
	                        <div style="padding-bottom:10px">
    	                    <!-- 삭제 -->
			                <? if($row['brMemberId'] == $_SESSION['memberID'] || $_SESSION['memberID'] == "admin") { ?>
								<img src='images/br_button_x.gif' onclick="go_delete_reply2('<?=$row['brId']?>', '<?=$row['brBoardId']?>');" style="cursor:pointer" title="Delete">
					        <? } ?>
		                    </div>
                        </td>
                    </tr>
                    <tr><td height="10px;"></td></tr>
                    
                </table></td></tr>
                    <? } ?>
                <tr><td height="1px;"><div style="border-top:1px #eeeeee solid;"></div></td></tr>
                <tr><td height="5px;"></td></tr>
                <? } ?>
            <? } ?>

            </table>
        </td>
    </tr>
<? 
        //수정모드 체크
if($mode == "edit_reply") {

		$br_id = ($_GET['bdId']) ? $_GET['bdId'] : $_POST['bdId'];
		$board_Id = ($_GET['boardId']) ? $_GET['boardId'] : $_POST['boardId'];

        //댓글 내용 불러오기
        $BRE_where = "select * from board_reply where brId = $br_id AND brBoardId = $board_Id ";
        $BRESel = mssql_query($BRE_where);
        $BREFat = mssql_fetch_array($BRESel);
/*   
        if($mode == "edit_reply")
        {
            //댓글 수정 비번이나 권한 체크하기.
            if($userId == "admin")
            {
            }
            else if($BREFat['brMemberId'] == $_SESSION['memberID'])
            {
            }
            else
            {
                //권한없음.
                echo "<script>alert('Incorrect Password.');history.go(-1);</script>";
                exit;
            }
        }
*/
} ?>
    <tr><td height="10"></td></tr>
    <tr>
        <td colspan="4">
            <table cellpadding="0" cellspacing="0" width="100%" border="0">
            <form name="reply_form" method="post" action="upload/upload_board.php">
            <input type="hidden" name="bdId" value='<?=$bdId?>'>
            <input type="hidden" name="boardId" value='<?=$boardId?>'>
            <input type="hidden" name="Type" value='<?=$Type?>'>
            <input type="hidden" name="brIpAddress" value='<?=$_SERVER ['REMOTE_ADDR']?>'>
            <input type="hidden" name="refresh" value="1">
        <? if($mode == "edit_reply") { ?>
            <input type="hidden" name="mode" value="edit_reply">             
            <input type="hidden" name="txtarea_size1" value='<?=$txtarea_size1?>'>
        <? } else { ?>
            <input type="hidden" name="mode" value="write_reply">             
            <input type="hidden" name="brActive" value="1">             
        <? } ?>
                <tr>
                    <td align="center" valign="middle" width="85">
                    <? if($mode == "edit_reply") { ?>
                        <div style="line-height:150%; padding-top:45px;">댓글 수정</div>
                    <? } else { ?>
                        <div style="line-height:150%; padding-top:45px;">댓글 등록</div>
                    <? } ?>
                    </td>
                    <td align="left" width="580">
                        <? if($mode == "edit_reply") { ?>
                            <div>작성자: <font color="#A86A20"><?=get_user_name($_SESSION['memberID'])?></font>
							<? if($_SESSION['memberID'] == "admin") { ?>
								/ <font color="red">Status:</font> 
								<select name="brActive">
								<option value="1" <?=(!$BREFat['brActive'] || $BREFat['brActive'] == "1") ? "selected=selected" : ""; ?>>Active</option>
								<option value="2" <?=($BREFat['brActive'] == "2") ? "selected=selected" : ""; ?>>Hide</option>
								</select>
							<? } ?>
                            </div>
                        <? } else { ?>
                            <? if($_SESSION['memberID']) { ?>
	                            <div>작성자: <span style="color:#A86A20;"><?=get_user_name($_SESSION['memberID'])?></span></div>
                            <? } ?>
                        <? } ?>
                        <div style="padding-top:5px">
	                    <? if($mode == "edit_reply") { ?>
                            <textarea id="brDescription" name="brDescription" cols="80" rows="5" class="simpleform"></textarea>
							<script>
								document.getElementById('brDescription').value="<?=Br_iconv($BREFat['brDescription'])?>"; 
							</script>
		                <? } else { ?>
                            <textarea id="brDescription" name="brDescription" cols="80" rows="5" class="simpleform"></textarea>
				        <? } ?>
                        </div>
                    </td>
                    <td align="left" style="padding-top:40px; padding-left:10px;">
                    <? if($mode == "edit_reply") { ?>
                        <div id="btn_reply" class="menu_button2" style="width:55px;" onclick="go_reply()">Save</div>
                    <? } else { ?>
                        <div id="btn_reply" class="menu_button2" style="width:55px;" onclick="go_reply()">등록</div>
                    <? } ?>
                    </td>
                </tr>
            </form>
            </table>
        </td>
    </tr>

            
    </table>
    </div>
</div>
</body>
</html>
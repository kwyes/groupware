<?
include_once "includes/general.php";
$CompanyID =  $_SESSION['memberCID'];
?>
<script type="text/javascript" src="./se2/js/HuskyEZCreator.js" charset="utf-8">
function go_edit()
{
//    if(!document.edit_form.prodId.value)
//    {
//        alert('PLU is required.');
//		return false;
//    }
//    document.getElementById("btn_edit").disabled = "disabled";
    document.edit_form.submit();  
}

function go_deleteimage(no)
{  
    document.delete_image_form.prodImage.value = no;
    document.delete_image_form.submit();   
}
function go_delete()
{  
    var flag = confirm('Do you want to delete?');
    if(flag)
    {
        document.delete_form.submit();   
    }
}
function popup_imagecenter(bdId, module) 
{    
    window.open("<?=SYSTEM_PATH?>/imagecenter/frame_imagecenter.php?sub=imagelist&bdId="+bdId+"&module="+module,"image_center",'width=700, height=500, left=10, top=20');    
}

var winObject = null;

function popupWindow(){
	var settings = 'toolbar=0,directories=0,status=no,menubar=0,scrollbars=auto,resizable=no,height=200,width=200,left=0,top=0';
	winObject = window.open("test2.htm", "test2", settings);
}

function submitToWindow(){
	winObject.doc-ument.all.text11.value = doc-ument.all.text1.value;
	winObject.doc-ument.all.text22.value = doc-ument.all.text2.value;
	winObject.doc-ument.all.text33.value = doc-ument.all.text3.value;
}
</script>
<?
	$today = date("Y-m-d");
?>
<!-- e-doc right 내용 START -->
<link rel="stylesheet" href="includes/editor/css/editor.css" type="text/css" charset="utf-8"/>
<script src="includes/editor/js/editor_loader.js" type="text/javascript" charset="utf-8"></script>

<td width="" align="left" valign="top">
	<form name="edit_form" method="post" action="upload/upload_Voucher.php" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="insert_expend">
	<input type="hidden" name="today" value="<?=$today?>">
	<input type="hidden" name="ir1" value="<?=$ir1?>">
	<table width="100%">
		<!-- e-doc right TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">결재문서 작성</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc right TITLE END -->

		<!-- e-doc right CONTENT START -->
		<!-- Submit/Save BTN START -->
		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><a href="/"><button class="doc_submit_btn_style" onclick="submitContents(this)">상신하기</button></a></td>
									<td width="5"></td>
									<td><a href="/"><button class="doc_submit_btn_style">임시저장</button></a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<!-- Submit/Save BTN END -->

		<!-- doc main START -->
		<tr>
			<td align="center" class="doc_wrapper">
				<table width="100%">
					<!-- doc title START -->
					<tr>
						<td>
							<table width="100%">
								<tr>
									<td width="140"></td>
									<td align="center" class="doc_title">지출 결의서</td>
									<td width="100" align="right" style="padding-top:10px;"><input type="button" id="fApproval" value="결재자 검색"></input></td>
									<td width="40" align="right" style="padding-top:10px;"><input type="button" id="doc_approval_btn" value="★"></input></td>
								</tr>
							</table>
						</td>
					</tr>
					<!-- doc title END -->

					<!-- doc content START -->
					<tr>
						<td align="center" valign="top">
							<table width="100%" class="doc_border" style="table-layout:fixed;">
								<tr>
									<td width="95" height="30" align="center" class="doc_field_name"><b>문서번호</b></td>
									<td class="doc_field_content" style="border-right: 0;"></td>
									<td width="95" class="doc_field_content" style="border-left: 0; border-right: 0;"></td>
									<td class="doc_field_content" style="border-left: 0;"></td>
									<td width="365" rowspan="6" align="center" valign="top" style="padding:0;border-bottom:1px #afafaf solid;">
										<table width="100%" class="doc_border">
											<tr height="22" align="center" style="background-color:#f6f6f6;">
												<td width="7%" rowspan="4" style="padding:60px 0 0 0;"><b>결<br></br><br></br>재</b></td>
												<td width="19%" style="border-bottom:1px #eaeaea solid; padding:5px 0 0 0;">결의자</td>
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
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>문서종류</b></td>
									<td class="doc_field_content">지출결의서</td>
									<td  width="95" align="center" class="doc_field_name"><b>실행 회사</b></td>
									<td  align="left" class="doc_field_content">
									<select name="runcompid">
								<?	
										$query = "SELECT companyID, companyDesc FROM Company ORDER BY companyID";
										$row2 = mssql_query($query);
										while($rst = mssql_fetch_array($row2)) {
								?>
										<option value='<?=$rst['companyID']?>'<?if($rst['companyID']== $CompanyID) echo "selected"; ?>>&raquo; <?=$rst['companyDesc']?></option>
								<?		}	?>
									</select>
									</td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>문서상태</b></td>
									<td class="doc_field_content">작성전 문서입니다.</td>
									<td  width="95" align="center" class="doc_field_name"><b>Pay To</b></td>
									<td class="doc_field_content"><input name="payto" type="text""></input></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>부&nbsp;서&nbsp;명</b></td>
									<td class="doc_field_content"><?=Br_iconv(get_Dept($_SESSION['memberDID']))?></td>
									<td  width="95" align="center" class="doc_field_name"><b>Amount</b></td>
									<td class="doc_field_content"><input name="amount" type="text""></input></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>작&nbsp;성&nbsp;자</b></td>
									<td class="doc_field_content"><?=Br_iconv($_SESSION['memberName'])?></td>
									<td  width="95" align="center" class="doc_field_name"><b>Payment Method</b></td>
									<td class="doc_field_content">
										<input type="radio" name="PaymentMethod" value="Cash">CASH
										<input type="radio" name="PaymentMethod" value="Cheque" checked="checked">CHEQUE
									</td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>작성일자</b></td>
									<td class="doc_field_content"><input name="doc_date" type="text" style="width:85px;" maxlength="10" value="<?=$today?>"></td>
									<td  width="95" align="center" class="doc_field_name"><b>Currency Type</b></td>
									<td class="doc_field_content">
										<input type="radio" name="Currency" value="CAD$" checked="checked">CAD$
										<input type="radio" name="Currency" value="USD$">USD$
									</td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>제&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목</b></td>
									<td class="doc_field_content" colspan="4"><input name="doc_subject" type="text" style="width:630px;"></input></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>내&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;용</b></td>
									<td colspan="4" style="padding-left:11px;"><textarea name="ir1" id="ir1" style="width:100%; height:300px; display:none;"></textarea>
									<script type="text/javascript">
										var oEditors = [];
										nhn.husky.EZCreator.createInIFrame({
											oAppRef: oEditors,
											elPlaceHolder: "ir1",
											sSkinURI: "./se2/SmartEditor2Skin.html",
											fCreator: "createSEditor2"
										});

										function submitContents(elClickedObj) {
											oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
											// 에디터의 내용에 대한 값 검증은 이곳에서
											// document.getElementById("ir1").value를 이용해서 처리한다.
											try {
											   elClickedObj.edit_form.submit();
											} catch(e) {}
										}
									</script>
									</td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 1</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
										<input name="userfile" type="file" />
										<font color="red">* Current image : </font><a href="javascript:go_deleteimage()">Delete</a>
										<input name="userfile_name" type="text""></input></td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 2</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
										<input name="userfile2" type="file" />
										<font color="red">* Current image : </font><a href="javascript:go_deleteimage()">Delete</a>
										<input name="userfile_name2" type="text""></input></td>
									</td>
								</tr>
								<tr>
									<td height="30" align="center" class="doc_field_name"><b>파일첨부 3</b></td>
									<td class="doc_field_content" colspan="4">
                                        첨부 이미지:
										<!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
										<input name="userfile3" type="file" />
										<font color="red">* Current image : </font><a href="javascript:go_deleteimage()">Delete</a>
										<input name="userfile_name3" type="text""></input></td>
									</td>
								</tr>
							</table>
						</td>						
					</tr>
					<!-- doc content END -->
				</table>
			</td>
		</tr>
		<!-- doc main END -->
		<!-- e-doc right CONTENT END -->
	</table>
	</form>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc right 내용 END -->
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
			<td height="25" valign="middle" style="padding:14px 0 0 20px; background-color:#F6CECE;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
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
			<td height="25" valign="middle" style="padding:14px 0 0 20px; background-color:#CECEF6;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
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
		parent.document.getElementById("app" + i).innerHTML = "";
		parent.document.getElementById("appUser" + i).value = "";
		parent.document.getElementById("appUserName" + i).innerHTML = "";
	}
</script>
<!-- Delete Approval User END -->
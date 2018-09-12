<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="ko" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HTM Groupware</title>
<link rel="shortcut icon" href="favicon.ico" />
<!-- Load CSS -->
<link href="../css/style.css" rel="stylesheet" type="text/css" />

<!-- calendar -->
<link type="text/css" href="../css/ui-lightness/jquery-ui-1.8.24.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../js/jquery-1.8.2.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui-1.8.24.custom.min.js"></script>
<script language="JavaScript" src="js/date_picker.js"></script>
<SCRIPT language="JavaScript">
function moveFocus(next) {
	document.getElementById(next).focus();
}




</SCRIPT>
<script type="text/javascript">
	
	$(function() {
		$( "#doc_calendar" ).datepicker({
			onSelect: function( selectedDate ) {
				$( "#doc_calendar" ).datepicker( 'option', {dateFormat: 'yy-mm-dd'} );
			}
		});
	});
	
	$(function() {
		$( "#search_dateStart" ).datepicker({
			onSelect: function( selectedDate ) {
				$( "#search_dateStart" ).datepicker( 'option', {dateFormat: 'yy-mm-dd'} );
			}
		});
	});
	
	$(function() {
		$( "#search_dateEnd" ).datepicker({
			onSelect: function( selectedDate ) {
				$( "#search_dateEnd" ).datepicker( 'option', {dateFormat: 'yy-mm-dd'} );
			}
		});
	});




</script>


</head>
<body style="padding: 5px 5px 0 5px;">
<!-- 전체 Layout -->
<? if($page == 'login' || $page == 'logout' || $page == '') { ?>
	<? echo "";?>
	<? } else { ?>
		<div class="userinfoarea">
			
		

			<table align="right">
				<tr>
					<td class="frame_userinfo"><?if($_SESSION['memberCID']) { echo get_company_name($_SESSION['memberCID']); }?></td>
					<td class="frame_userinfo" style="color: red;"><?if(isset($_SESSION['memberName'])) {?>&nbsp;&nbsp;|&nbsp;&nbsp;<?}?></td>
					<td class="frame_userinfo">
					<?if(isset($_SESSION['memberName'])){ 
						if($_SESSION['memberLevel'] == 1) {
							echo '<a href = ?page=admin>';
							echo @iconv('euc-kr', 'utf-8',   $_SESSION['memberName']); 
							echo '</a>';
						}
						else
							echo @iconv('euc-kr', 'utf-8',   $_SESSION['memberName']); 
					}?>
					</td>
					<td class="frame_userinfo" style="color: red;"><?if(isset($_SESSION['memberName'])) {?>&nbsp;&nbsp;|&nbsp;&nbsp;<?}?></td>
					<td><? if(isset($_SESSION['memberID'])) { ?> <div class="menu_item"><a href="?page=meminfor" type="button" class="top_btn_style">정보수정</a><?}?></td>
					<td width="5"></td>
					<td>
					<? if(isset($_SESSION['memberID'])) { ?> <div class="menu_item"><a href="?page=logout" type="button" class="top_btn_style">로그아웃</a></div><? } else { ?> <div class="menu_item"><a href="?page=login"><button class="top_btn_style">로그인</button></a></div>
					<?	} ?>
					</td>
				</tr>			
			</table>
			
			<!-- 탑메뉴 -->
			<table align="left" class="tbltopmenu">
				<tr>
					
					<!-- 전자결재및 커뮤니티 문서함열엇을때 -->
					<? if($page == 'e_doc' || $page == 'community' || $page == 'doc') { ?>
					
					
				
					<td class="<?=($page == 'hr') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=hr'" class = 'button black'>인사관리</button></b><td>
					

				
					<td class="<?=($page == 'property') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=property'" class = 'button black'>건물관리</button></b><td>
					


					<td><b style="color: white;"><button onclick="window.location.href ='/00.asset'" class = 'button black'>재산관리</button></b><td>
					
					
					
					
					<? } ?>
					<!-- 전자결재및 커뮤니티 문서함열엇을때 끝 -->
					
					<!-- 인사관리 페이지 열엇을때 -->
					<? if($page == 'hr') { ?>
						
						<td class="<?=($page == 'e_doc') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=e_doc'" class = 'button black'>전자결재</button></b><td>
					
						<td class="<?=($page == 'property') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=property'" class = 'button black'>건물관리</button></b><td>
						
					
						<td><b style="color: white;"><button onclick="window.location.href ='/00.asset'" class = 'button black'>재산관리</button></b><td>
				
					
					<? } ?>
					<!-- 인사관리 페이지 열엇을때 끝-->
					
					<!-- 건물관리 페이지 열엇을때 -->
					<? if($page == 'property') { ?>
							<td class="<?=($page == 'e_doc') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=e_doc'" class = 'button black'>전자결재</button></b><td>
							
							
								<td class="<?=($page == 'hr') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=hr'" class = 'button black'>인사관리</button></b><td>
						

							<td><b style="color: white;"><button onclick="window.location.href ='/00.asset'" class = 'button black'>재산관리</button></b><td>
		
											
					
					<? } ?>
					<!-- 건물관리 페이지 열엇을때 끝-->
					
					<!-- 관리자 페이지 열엇을때 -->
					<? if($page == 'admin') { ?>
							<td class="<?=($page == 'e_doc') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=e_doc'" class = 'button black'>전자결재</button></b><td>
							
							
								<td class="<?=($page == 'hr') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=hr'" class = 'button black'>인사관리</button></b><td>
							

							
								<td class="<?=($page == 'property') ? "active" : "";?>"><b style="color: white;"><button onclick="window.location.href ='?page=property'" class = 'button black'>건물관리</button></b><td>
						
							<td><b style="color: white;"><button onclick="window.location.href ='/00.asset'" class = 'button black'>재산관리</button></b><td>
		
											
					
					<? } ?>
					<!-- 관리자 페이지 열엇을때 끝-->






				</tr>			
			</table>
		
		<!-- 탑메뉴 끝 -->

	</div>
	<? } ?>
	<!-- Top 유저 정보 END -->

	<!-- Top 메뉴 START -->
	<? if($page == 'login' || $page == 'logout' || $page == '') { ?>
	<? echo "";?>
	<? } else { ?>
	<tr class="top_menu_wrapper">
		<td>
			<table width="100%">
				<tr>
					<td class="top_menu_logo" width="190" height="45" align="center">
						<table width="100%" style="table-layout: fixed;">
							<tr height="45">
								<td align="middle"><img width="100" height="35" style="padding-top: 5px;" src="/images/tb-logo_img.png"></td>
							</tr>
						</table>
					</td>

					<td class="top_menu">
						<table>
							<tr>
								
								<? if($page == 'e_doc' || $page == 'community' || $page == 'doc') { ?>

										<td class="<?=($page == 'e_doc') ? "active" : "";?>"><b style="color: white;"><a href="?page=e_doc">전자결재</a></b><td>
										<td class="<?=($page == 'community') ? "active" : "";?>"><b style="color: white;"><a href="?page=community&menu=note">커뮤니티</a></b><td>
										<td class="<?=($page == 'docmanage') ? "active" : "";?>"><b style="color: white;"><a href="?page=doc">문서함관리</a></b><td>

								<? } ?>

								<? if($page == 'hr') {?>
									
									
										<td class="<?=($page == 'hr') ? "active" : "";?>"><b style="color: white;"><a href="?page=hr">인사관리</a></b><td>
									

								<? } ?>

								<? if($page == 'property') {?>
									
									
									<td class="<?=($page == 'property') ? "active" : "";?>"><b style="color: white;"><a href="?page=property">건물관리</a></b><td>
									

								<? } ?>

								<? if($page == 'admin') {?>
									
									<? if($_SESSION['memberLevel'] == 1) {?>								
									<td class="<?=($page == 'admin') ? "active" : "";?>"><b style="color: white;"><a href="?page=admin">관리자 페이지</a></b><td>
									<? } ?>


								<? } ?>


							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<!-- Top 메뉴 END -->
	<? } ?>

	

	<!-- Content START -->
	
	<tr class="content_wrapper">
		<td>
		<? if($page == 'login' || $page == 'logout' || $page == '') { ?>
			<? echo "";?>
		<? } else { ?>
		  <table width="100%" height="100%" style="table-layout:fixed; min-height:800px">
				<tr>
			<? } ?>
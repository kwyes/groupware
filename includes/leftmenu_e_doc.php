<script>
function receive_folder() {
	$("#receive_folder").toggle();
	if($("#show_receive_folder").attr("src") == "../css/img/icon_plus.gif") {
		$("#show_receive_folder").attr("src", "../css/img/icon_minus.gif");
	} else if($("#show_receive_folder").attr("src") == "../css/img/icon_minus.gif") {
		$("#show_receive_folder").attr("src", "../css/img/icon_plus.gif");
	}
}

function offer_folder() {
	$("#offer_folder").toggle();
	if($("#show_offer_folder").attr("src") == "../css/img/icon_plus.gif") {
		$("#show_offer_folder").attr("src", "../css/img/icon_minus.gif");
	} else if($("#show_offer_folder").attr("src") == "../css/img/icon_minus.gif") {
		$("#show_offer_folder").attr("src", "../css/img/icon_plus.gif");
	}
}
</script>

<?
$userID = $_SESSION['memberID'];
$showReceive = ($_GET['showReceive']) ? $_GET['showReceive'] : $_POST['showReceive'];
$showOffer = ($_GET['showOffer']) ? $_GET['showOffer'] : $_POST['showOffer'];
$folderNum = ($_GET['folderNum']) ? $_GET['folderNum'] : $_POST['folderNum'];

// FolderLocation 1.받은결재함 / 2.올린결재함
$receive_folder = "SELECT FolderSeq, FolderName FROM PersonalFolder WHERE UserID = '$userID' AND FolderLocation = 1 AND ContentSeq = 0 ORDER BY FolderSeq ASC";
$receive_folder_result = mssql_query($receive_folder);
$receive_folder_count = mssql_num_rows($receive_folder_result);

$offer_folder = "SELECT FolderSeq, FolderName FROM PersonalFolder WHERE UserID = '$userID' AND FolderLocation = 2 AND ContentSeq = 0 ORDER BY FolderSeq ASC";
$offer_folder_result = mssql_query($offer_folder);
$offer_folder_count = mssql_num_rows($offer_folder_result);
?>


<td width="180" class="left_menu_wrapper">
	<table width="100%">
		<tr>
			<td style="border-bottom: 1px #e7e7e7 solid; padding: 10px 0 10px 15px;">
				<table width="100%">
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
						<td class="left_menu_text"><a href="?page=e_doc"><?=($menu == '') ? "<b>전자결재 메인</b>" : "전자결재 메인";?></a></td>
					</tr>
<?					if($_SESSION['memberLevel'] == 1) { ?>
						<tr>
							<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
							<td class="left_menu_text"><a href="?page=e_doc&menu=all"><?=($menu == 'all' || $menu == 'view_all') ? "<b>전체 문서함</b>" : "전체 문서함";?></td>
						</tr>
<?					} ?>
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_2.gif"></td>
						<td class="left_menu_text"><?=($menu == 'form') ? "<b>결재문서 작성</b>" : "결재문서 작성";?></td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 5px 0 0 10px;">
							<table>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=form&sub=proposal"><?=($sub == 'proposal') ? "<b>기안서</b>" : "기안서";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=form&sub=expense"><?=($sub == 'expense') ? "<b>지출결의서</b>" : "지출결의서";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=form&sub=helpful"><?=($sub == 'helpful') ? "<b>협조문</b>" : "협조문";?></a></td>
								</tr>
							<?	//if($_SESSION['memberCID'] == 1 && ($_SESSION['memberDID'] == 5 || $_SESSION['memberDID'] == 3)) { ?>
							<?	//if($_SESSION['memberLevel'] == 1 || ($_SESSION['memberCID'] == 1 && ($_SESSION['memberDID'] == 5 || $_SESSION['memberDID'] == 3)) || ($_SESSION['memberCID'] == 5 && $_SESSION['memberDID'] == 1 && $_SESSION['memberPosition'] == 6) || ($_SESSION['memberCID'] == 2 && ($_SESSION['memberDID'] == 2 || $_SESSION['memberDID'] == 3 || $_SESSION['memberDID'] == 4))) { ?>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text" style="letter-spacing:0"><a href="?page=e_doc&menu=form&sub=item_spot_check"><?=($sub == 'item_spot_check') ? "<b>Item Spot Check</b>" : "Item Spot Check";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text" style="letter-spacing:0"><a href="?page=e_doc&menu=form&sub=sales_journal"><?=($sub == 'sales_journal') ? "<b>Sales Activities Journal</b>" : "Sales Activities Journal";?></a></td>
								</tr>
								<!-- 출장계획서 /보고서  -->
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line2.gif"></td>
									<td class="left_menu_text" style="letter-spacing:0"><a href="?page=e_doc&menu=form&sub=businesstrip"><?=($sub == 'businesstrip') ? "<b>출장 계획서/보고서</b>" : "출장 계획서/보고서";?></a></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
						<td class="left_menu_text"><?=($menu == 'receive') ? "<b>받은 결재 문서함</b>" : "받은 결재 문서함";?></td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 5px 0 0 10px;">
							<table>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=receive&sub=wait"><?=($sub == 'wait' || $sub == 'view_wait') ? "<b>미결재 문서</b>" : "미결재 문서";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line2.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=receive&sub=done"><?=($sub == 'done' || $sub == 'view_done') ? "<b>결재완료 문서</b>" : "결재완료 문서";?></a></td>
<?									if($receive_folder_count > 0) { ?>
										<td style="padding-left:15px;"><img onClick="receive_folder()" id="show_receive_folder" <?=($showReceive == 1) ? "src='../css/img/icon_minus.gif'" : "src='../css/img/icon_plus.gif'";?>></td>
<?									} ?> 
								</tr>

								<tr id="receive_folder" <?=($showReceive == 1) ? "" : "style='display:none;'";?>>
									<td colspan="3" style="padding: 0 0 0 15px;">
										<table>
<?											while($receive_folder_row = mssql_fetch_array($receive_folder_result)) { ?>
												<tr>
													<td width="12" height="18"><img src="../css/img/folder.gif"></td>
													<td class="left_menu_text" style="padding:0 0 0 5px;">
														<a href="?page=e_doc&menu=receive&sub=receive_folder&showReceive=1&folderNum=<?=$receive_folder_row['FolderSeq']; ?>">
															<div style="width:110px; line-height:15px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">
																<?=($folderNum == $receive_folder_row['FolderSeq'] && $menu == "receive") ? "<b>".Br_iconv($receive_folder_row['FolderName'])."</b>" : Br_iconv($receive_folder_row['FolderName']) ; ?>
															</div>
														</a>
													</td>
												</tr>
<?											} ?>
										</table>
									</td>
								</tr>

							</table>
						</td>
					</tr>
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
						<td class="left_menu_text"><?=($menu == 'offer') ? "<b>올린 결재 문서함</b>" : "올린 결재 문서함";?></td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 5px 0 0 10px;">
							<table>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=offer&sub=save"><?=($sub == 'save' || $sub == 'view_save') ? "<b>임시저장</b>" : "임시저장";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=offer&sub=recovery"><?=($sub == 'recovery' || $sub == 'view_recovery' || $sub == 'edit_recovery') ? "<b>회수문서</b>" : "회수문서";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=offer&sub=submit"><?=($sub == 'submit' || $sub == 'view_submit') ? "<b>상신문서</b>" : "상신문서";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=offer&sub=reject"><?=($sub == 'reject' || $sub == 'view_reject') ? "<b>반려문서</b>" : "반려문서";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line2.gif"></td>
									<td class="left_menu_text"><a href="?page=e_doc&menu=offer&sub=complete"><?=($sub == 'complete' || $sub == 'view_complete') ? "<b>결재완료</b>" : "결재완료";?></a></td>
<?									if($offer_folder_count > 0) { ?>
										<td style="padding-left:15px;position:relative;"><img onClick="offer_folder()" id="show_offer_folder" <?=($showOffer == 1) ? "src='../css/img/icon_minus.gif'" : "src='../css/img/icon_plus.gif'";?>></td>
<?									} ?> 
								</tr>

								<tr id="offer_folder" <?=($showOffer == 1) ? "" : "style='display:none;'";?>>
									<td colspan="3" style="padding: 0 0 0 15px;">
										<table>
<?											while($offer_folder_row = mssql_fetch_array($offer_folder_result)) { ?>
												<tr>
													<td width="12" height="18"><img src="../css/img/folder.gif"></td>
													<td class="left_menu_text" style="padding:0 0 0 5px;">
														<a href="?page=e_doc&menu=offer&sub=offer_folder&showOffer=1&folderNum=<?=$offer_folder_row['FolderSeq']; ?>">
															<div style="width:110px; line-height:15px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">
																<?=($folderNum == $offer_folder_row['FolderSeq'] && $menu == "offer") ? "<b>".Br_iconv($offer_folder_row['FolderName'])."</b>" : Br_iconv($offer_folder_row['FolderName']) ; ?>
															</div>
														</a>
													</td>
												</tr>
<?											} ?>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
						<td class="left_menu_text"><a href="?page=e_doc&menu=dept_receive&sub=list"><?=($menu == 'dept_receive') ? "<b>부서 수신함</b>" : "부서 수신함";?></a></td>
					</tr>
<?					if($_SESSION['memberCID'] == 5 && $_SESSION['memberDID'] == 1) { ?>
						<tr>
							<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
							<td class="left_menu_text"><a href="?page=e_doc&menu=expense_all"><?=($menu == 'expense_all') ? "<b>지출결의서 List (회계팀)</b>" : "지출결의서 List (회계팀)";?></td>
						</tr>
<?					} ?>
					<? //if($_SESSION['memberCID'] == 1 && ($_SESSION['memberDID'] == 5 || $_SESSION['memberDID'] == 3)) { ?>
					<?	if(($_SESSION['memberCID'] == 1 && $_SESSION['memberPosition'] <= 6 && ($_SESSION['memberDID'] == 5 || $_SESSION['memberDID'] == 3)) || ($_SESSION['memberCID'] == 5 && $_SESSION['memberDID'] == 1 && $_SESSION['memberPosition'] == 6) || ($_SESSION['memberCID'] == 2 && ($_SESSION['memberDID'] == 2 || $_SESSION['memberDID'] == 3 || $_SESSION['memberDID'] == 4)) || ($_SESSION['memberLevel'] == 2) || ($_SESSION['memberCID'] == 1 && $_SESSION['memberDID'] == 1 && $_SESSION['memberPosition'] == 5) || ($_SESSION['memberCID'] == 1 && $_SESSION['memberLevel'] <= 4)) { ?>
						<tr>
							<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
							<td class="left_menu_text"><a href="?page=e_doc&menu=itemSpotCheck&sub=itemSpotCheckList"><?=($menu == 'itemSpotCheck') ? "<b>Item Spot Check 리스트</b>" : "Item Spot Check 리스트";?></td>
						</tr>
<?					} ?>

				</table>
			</td>
		</tr>

		<tr>
			<td style="padding:10px 0 10px 15px;">
				<table width="100%">
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_3.gif"></td>
						<td class="left_menu_text"><a href="?page=e_doc&menu=personalFolder"><?=($menu == 'personalFolder') ? "<b>개인폴더 관리</b>" : "개인폴더 관리";?></a></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr height="30"><td></td></tr>
	</table>
</td>

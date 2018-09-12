<td width="180" class="left_menu_wrapper">
	<table width="100%">
		<tr>
			<td style="border-bottom: 1px #e7e7e7 solid; padding: 10px 0 10px 25px;">
				<table width="100%">
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
						<td class="left_menu_text"><a href="?page=admin"><?=($page == 'hr_test') ? "<b>관리자 페이지</b>" : "관리자 페이지";?></a></td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 5px 0 0 10px;">
							<table>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=admin&menu=userRegistration"><?=($menu == 'hr_test' || $menu == '') ? "<b>신규멤버 신청서</b>" : "신규멤버 신청서";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=admin&menu=userManagement"><?=($menu == 'hr_test' || $menu == 'hr_test') ? "<b>멤버관리</b>" : "멤버관리";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line2.gif"></td>
									<td class="left_menu_text"><a href="?page=admin&menu=docManagement"><?=($menu == 'hr_test' || $menu == 'hr_test') ? "<b>반려문서관리</b>" : "반려문서관리";?></a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>

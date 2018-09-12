<td width="180" class="left_menu_wrapper">
	<table width="100%">
		<tr>
			<td style="border-bottom: 1px #e7e7e7 solid; padding: 10px 0 10px 25px;">
				<table width="100%">
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
						<td class="left_menu_text"><a href="?page=hr"><?=($page == 'hr') ? "<b>인사관리</b>" : "인사관리";?></a></td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 5px 0 0 10px;">
							<table>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line2.gif"></td>
									<td class="left_menu_text"><a href="?page=hr&menu=list"><?=($menu == '' || $menu == 'list' || $menu == 'view' || $menu == 'new' || $menu == 'modify') ? "<b>사원리스트</b>" : "사원리스트";?></a></td>
								</tr>
								<!--tr>
									<td width="12" height="18"><img src="../css/img/icon_line2.gif"></td>
									<td class="left_menu_text"><a href="?page=hr&menu=test"><?=($menu == 'test') ? "<b>테스트2</b>" : "테스트2";?></a></td>
								</tr-->
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>

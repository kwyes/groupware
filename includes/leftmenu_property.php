<td width="180" class="left_menu_wrapper">
	<table width="100%">
		<tr>
			<td style="border-bottom: 1px #e7e7e7 solid; padding: 10px 0 10px 25px;">
				<table width="100%">
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
						<td class="left_menu_text"><a href="?page=property"><?=($page == 'hr') ? "<b>건물관리</b>" : "건물관리";?></a></td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 5px 0 0 10px;">
							<table>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=property"><?=($menu == '' || $menu == 'write') ? "<b>점검지</b>" : "점검지";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=property&menu=history"><?=($menu == 'history' || $menu == 'view') ? "<b>점검 History</b>" : "점검 History";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line2.gif"></td>
									<td class="left_menu_text"><a href="?page=property&menu=modifyCL"><?=($menu == 'modifyCL') ? "<b>체크리스트 수정</b>" : "체크리스트 수정";?></a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>
<td width="180" class="left_menu_wrapper">
	<table width="100%">
		<tr>
			<td style="border-bottom: 1px #e7e7e7 solid; padding: 10px 0 10px 25px;">
				<table width="100%">
					<tr>
						<td width="23" height="26" class="left_menu_icon"><img src="../css/img/icon_1.gif"></td>
						<td class="left_menu_text"><a href="?page=meminfor"><?=($page == 'meminfor') ? "<b>개인정보</b>" : "개인정보";?></a></td>
					</tr>
					<tr>
						<td colspan="2" style="padding: 5px 0 0 10px;">
							<table>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=meminfor&menu=inq"><?=($menu == 'inq' || $menu == '') ? "<b>정보조회</b>" : "정보조회";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line1.gif"></td>
									<td class="left_menu_text"><a href="?page=meminfor&menu=up"><?=($menu == 'up') ? "<b>정보수정</b>" : "정보수정";?></a></td>
								</tr>
								<tr>
									<td width="12" height="18"><img src="../css/img/icon_line2.gif"></td>
									<td class="left_menu_text"><a href="?page=meminfor&menu=pwd"><?=($menu == 'pwd') ? "<b>비밀번호 변경</b>" : "비밀번호 변경";?></a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</td>

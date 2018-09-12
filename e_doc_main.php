<!-- e-doc Main START -->
<td width="" align="left" valign="top">
	<table width="100%">
		<!-- e-doc TITLE START -->
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">전자결재 메인</td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<!-- e-doc TITLE END -->

		<!-- e-doc MAIN START -->
		<tr>
			<td align="left" style="border-top:1px #c9c9c9 solid;padding:19px 15px 0 15px;">
				<table width="100%">
					<tr>
						<td height="25">
							<table width="100%">
								<tr>
									<td width="90%" style="padding:8px 0 0 3px; letter-spacing:-1px;"><b>받은 결재 문서함 > 미결재 문서</b></td>
									<td width="10%" align="right" style="padding:8px 6px 0 0;"><a href="?page=e_doc&menu=receive&sub=wait"><img src="../css/img/bt_more.gif"></a></td>
								</tr>								
							</table>
						</td>
					</tr>
					<? include_once "main_receive_wait.php"; ?>	
					<tr>
						<td height="25"></td>
					</tr>
				</table>

				<table width="100%">
					<tr>
						<td height="25">
							<table width="100%">
								<tr>
									<td width="90%" style="padding:8px 0 0 3px; letter-spacing:-1px;"><b>받은 결재 문서함 > 결재완료 문서</b></td>
									<td width="10%" align="right" style="padding:8px 6px 0 0;"><a href="?page=e_doc&menu=receive&sub=done"><img src="../css/img/bt_more.gif"></a></td>
								</tr>
							</table>
						</td>
					</tr>
					<? include_once "main_receive_done.php"; ?>	
					<tr>
						<td height="25"></td>
					</tr>
				</table>

				<table width="100%">
					<tr>
						<td height="25">
							<table width="100%">
								<tr>
									<td width="90%" style="padding:8px 0 0 3px; letter-spacing:-1px;"><b>올린 결재 문서함 > 상신 문서</b></td>
									<td width="10%" align="right" style="padding:8px 6px 0 0;"><a href="?page=e_doc&menu=offer&sub=submit"><img src="../css/img/bt_more.gif"></a></td>
								</tr>
							</table>
						</td>
					</tr>
					<? include_once "main_submit_wait.php"; ?>			
					<tr>
						<td height="25"></td>
					</tr>
				</table>

				<table width="100%">
					<tr>
						<td height="25">
							<table width="100%">
								<tr>
									<td width="90%" style="padding:8px 0 0 3px; letter-spacing:-1px;"><b>올린 결재 문서함 > 결재완료 문서</b></td>
									<td width="10%" align="right" style="padding:8px 6px 0 0;"><a href="?page=e_doc&menu=offer&sub=complete"><img src="../css/img/bt_more.gif"></a></td>
								</tr>								
							</table>
						</td>
					</tr>
					<tr>
					</tr>
					<? include_once "main_submit_done.php"; ?>			
					<tr>
					</tr>
					<tr>
						<td height="25"></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="50"></td>
		</tr>
		<!-- e-doc MAIN END -->
	</table>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Main END -->
<!-- e-doc Main START -->
<?
	if($menu=="free") {
		$strMenu = "직원게시판";
	} else if($menu=="help") {
		$strMenu = "업무협조";
	} else {
		$strMenu = "";
	}

?>
<td height="500" align="left" valign="top">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">커뮤니티 > <?=$strMenu?></td>
						<td align="right" style="padding: 14px;">&nbsp;</td> 
					</tr>
				</table>
			</td>
		</tr>
		<? include_once "freeboard_list.php"; ?>	
		<!-- e-doc MAIN END -->
	</table>
</td>
				</tr>
			</table>
		</td>	
	</tr>
</table>
<!-- e-doc Main END -->
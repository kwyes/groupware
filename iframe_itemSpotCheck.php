<link href="css/style.css" rel="stylesheet" type="text/css" />

<?
include_once "includes/general.php";
include_once "includes/db_configms_WS.php";

$item_category = Br_dconv(($_GET['item']) ? $_GET['item'] : $_POST['item']);
//echo $item_category;
if($item_category) {
	/*if($item_category == 'random') {
		$query_item = "SELECT TOP 10 item_cd FROM random_itemList ORDER BY NEWID()";
		$query_item_result = mssql_query($query_item, $conn);
	} else {*/
		$query = "SELECT top 1 c_code, s_code FROM mfProd_type2 WHERE kname = '$item_category'";
		$query_result = mssql_query($query);
		$row = mssql_fetch_array($query_result);
		//echo $query;
		$type1 = $row['c_code'];
		$type2 = $row['s_code'];
 /* 현재 부터 한달 안 기간 조회 판매량 많은아이템부터 탑10   한달안기간구하는 쿼리문바꿔야댐 */
		/*$query_item = "SELECT top 10 tro.wsCode,i.prodKname, i.prodBalance, avg(tro.tOUprice) as avgprice, sum(tro.tQty) as total, i.myobOnHand FROM trOrderDetail as tro inner join Inventory_Item as i on i.wsCode = tro.wsCode WHERE tdate between '2016-05-27' and '2016-06-27' and tPtype = $type1 AND tPtype2 = $type2 group by i.prodKname, tro.wsCode, i.myobOnHand,i.prodBalance order by total desc";*/

		$query_item = "SELECT wsCode, prodkname, myobOnHand, myobAvgCost From Inventory_Item where useYN = 'Y' and prodType = $type1 and prodType2 = $type2 Order by wsCode";


		$query_item_result = mssql_query($query_item);
		//echo $query_item;

/*	}*/
}
?>

<table width="100%" style="margin-top:10px;" cellspacing="0">
	<tr class="doc_border" height="30px" style="font-size:15px; font-weight:bold;">
		<td width="5%"  align="center" style="padding-top:5px; background-color:#084B8A; color:#FFFFFF;">No</td>
		<td width="15%" align="left" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">Item Code</td>
		<td width="30%" align="left" style="padding:5px 0 0 5px; background-color:#084B8A; color:#FFFFFF;">Item Description</td>
		<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#084B8A; color:#FFFFFF;">Avg. Cost ($)</td>
		<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#084B8A; color:#FFFFFF;">AcctEdge 재고</td>
		<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#DBA901; border-right:1px solid #FFFFFF;">실재고</td>
		<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#C9C799; border-right:1px solid #FFFFFF;">Difference</td>
		<td width="10%" align="right" style="padding:5px 5px 0 0; background-color:#C9C799;">Diff. Amount ($)</td>
	</tr>
	
<?
	if($item_category) {
		$company_cd = $_SESSION['memberCID'];
		$index = 1;
		while($query_item_row = mssql_fetch_array($query_item_result)) {
			/*$item_cd = $query_item_row['item_cd'];
			$query_item_detail = "SELECT top 1 item_cd, item_nm, qty, ave_cost FROM dt_myob_inventory_item_list_summary_com WHERE item_cd = '$item_cd' AND status = 'Active' AND company_cd = $company_cd ORDER BY dt DESC";
			$item_detail = mssql_query($query_item_detail, $conn_TB);
			$item_detail_row = mssql_fetch_array($item_detail);*/
/* 아이템 쿼리문 합침 따로하면 가독성이떨어짐 */
			
?>
				<tr class="doc_border" height="20px"  style="font-size:13px;">
					<td align="center" style="padding-top:3px;"><?=$index++; ?></td>
					<td align="left" style="padding:3px 0 0 5px;"><?=$query_item_row['wsCode']; ?></td>
					<td align="left" style="padding:3px 0 0 5px;"><?=Br_iconv($query_item_row['prodkname']); ?></td>
					<!-- <td align="right" style="padding:3px 5px 0 0;"><?=number_format(round($query_item_row['avgprice'], 2), 2); ?></td> -->
					<td align="right" style="padding:3px 5px 0 0;"><?=$query_item_row['myobAvgCost']; ?></td>
					<td align="right" style="padding:3px 5px 0 0;"><?=$query_item_row['myobOnHand']; ?></td>
					<td></td>
					<!-- <td align="right" style="padding:3px 5px 0 0;"><?=$query_item_row['prodBalance']; ?></td> -->
					<td></td>
					<td></td>
				</tr>
<?
				$item_code[] = $query_item_row['wsCode'];
				$item_description[] = Br_iconv($query_item_row['prodKname']);
				$item_avgCost[] = $query_item_row['myobAvgCost'];
				$item_qty[] = $query_item_row['myobOnHand'];
			
		}
?>
		<script>
			var item_code = new Array("<?=implode("\",\"" , $item_code);?>");
			var item_description = new Array("<?=implode("\",\"" , $item_description);?>");
			var item_avgCost = new Array("<?=implode("\",\"" , $item_avgCost);?>");
			var item_qty = new Array("<?=implode("\",\"" , $item_qty);?>");

			parent.document.forms.itemSpotCheck.item_code.value = item_code;
			parent.document.forms.itemSpotCheck.item_description.value = item_description;
			parent.document.forms.itemSpotCheck.item_avgCost.value = item_avgCost;
			parent.document.forms.itemSpotCheck.item_qty.value = item_qty;
		</script>
<?	} ?>
</table>
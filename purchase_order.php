<?php
	session_start();
	include_once "login_check.php";
	include "includes/db_configms.php";
	include "includes/common_class.php";

	$cId = $_SESSION['staffCID'];
	$staffId = $_SESSION['staffID'];
	$pmdate = ($_GET['pmdate']) ? $_GET['pmdate'] : $_POST['pmdate'];
	$vendorname = ($_GET['vendorname']) ? $_GET['vendorname'] : $_POST['vendorname'];
	$vendorCode = ($_GET['vendorCode']) ? $_GET['vendorCode'] : $_POST['vendorCode'];
	$pono = ($_GET['pono']) ?  $_GET['pono'] :  $_POST['pono'];
	$new = ($_GET['new']) ?  $_GET['new'] :  $_POST['new'];
	$begin = ($_GET['begin']) ?  $_GET['begin'] :  $_POST['begin'];
	$mode = ($_GET['mode']) ?  $_GET['mode'] :  $_POST['mode'];

	if($new == "") $sReadOnly = "readonly";

	if($vendorCode != "")
	{
		if($vendorname == "") {
			$vendorname = get_VendorName($cId, $vendorCode);
		}

		$query = "SELECT COUNT(*) AS numRec FROM VendorItem WHERE vendorId='$vendorCode' AND cId ='$cId' ";
		$rst = mssql_query($query);
		$numRec = 0;
		if($row = mssql_fetch_array($rst))
		{
			$numRec = $row['numRec'];
		}
		if($numRec == 0)	{
?>			<script> alert("<?=$vendorCode?>"+" 로 등록된 아이템이 없습니다!"); </script>	<?
		} else if ($pono != '' )	{

			if($new == "yes")	{
				//$vendorname = Br_dconv(urldecode($vendorname));
				$target_date = date("Y-m-d");
				$target_date_3 = date("Y-m-d",strtotime( $target_date." -3 month" ));

				$from_1year = date("Y-m-d",strtotime( $target_date_3." -1 year" ));
				$to_1year = date("Y-m-d",strtotime( $target_date." -1 year" ));

				$arrive_date = $target_date;

				if($mode == "cancel")
				{
					// ## 임시 테이블 삭제
					$query = "DROP TABLE VendorItems_".$vendorCode."_".$staffId."_".$pono;
//echo $query;
					if($rst = mssql_query($query)) 
					{
						echo "<script> alert('작성 중인 임시 테이블을 취소했습니다.'); window.opener.location.reload(); window.close(); </script>";
					}
					return;
				} 
				// ## 임시 테이블 생성 및 아이템 저장
				$query = "IF OBJECT_ID('VendorItems_".$vendorCode."_".$staffId."_".$pono."') IS NULL
						  BEGIN
							  CREATE TABLE VendorItems_".$vendorCode."_".$staffId."_".$pono." (".
								 "wsCode varchar(20), ".
								 "ProdOwnCode varchar(3), ".
								 "suppCode varchar(10) NULL, ".
								 "prodId varchar(15) NULL, ".
								 "prodKname nvarchar(60) NULL, ".
								 "prodIUprice float NULL, ".
								 "prodQty int NULL, ".
								 "prodWeight float NULL, ".
								 "prodCBM float NULL, ".
								 "prodBalance float NULL, ".
								 "prodcontenteach smallint NULL, ".
								 "ExpiryDate smalldatetime NULL, ".
								 "prodUnit varchar(5) NULL, ". 
								 "prodType varchar(2) NULL, ". 
								 "prodType2 varchar(2) NULL, ".
								 "prodRemark nvarchar(60) NULL".
							 ")".

							 "INSERT INTO VendorItems_".$vendorCode."_".$staffId."_".$pono." ( wsCode,ProdOwnCode,suppCode,prodId,prodIUprice,prodWeight,prodCBM,prodBalance,prodcontenteach,ExpiryDate,prodUnit ) ".
							 "SELECT a.wsCode, a.OwnCode, b.suppCode, prodId, prodIUprice, b.prodWeight, b.prodCBM, prodBalance, prodcontenteach, ExpiryDate, prodUnit ".
							 "FROM VendorItem a LEFT JOIN Inventory_Item b ON a.cId = b.CID and a.wsCode = b.wsCode and a.OwnCode = b.ProdOwnCode ".
							 "WHERE vendorId='$vendorCode' ORDER BY prodType, prodType2 
						  END";
				//echo $query;
				// ## 임시 테이블에 밴더 아이템 저장
				$rst = mssql_query($query);
			} else {
				//update
				$query = "SELECT CONVERT(char(10), pm_date, 120) AS pm_date, CONVERT(char(10), expDate, 120) AS expDate FROM purchase_master ".
							"WHERE CID='$cId' AND pm_date ='$pmdate' AND pm_po_no='$pono'  ";
				$rst2 = mssql_query($query);
				$row2 = mssql_fetch_array($rst2);

				$target_date =$row2['pm_date'];
				$target_date_3 = date("Y-m-d",strtotime( $target_date." -3 month" ));
				
				$from_1year = date("Y-m-d",strtotime( $target_date_3." -1 year" ));
				$to_1year = date("Y-m-d",strtotime( $target_date." -1 year" ));
				
				$arrive_date = $row2['expDate'];
			}
		}
	}
//echo "from : ".$from_1year." to : ".$to_1year;
?>

<?php // ## HTML Start -----------------> ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WholeSale INVENTORY SYSTEM</title>
<link rel="stylesheet" type="text/css" href="css/style.css"/>

<?php // ## JAVA Script S ---------------> ?>
<script language="JavaScript" src="js/date_picker.js" ></script>
<script>
// PO 란 empty check
function check_PO() 
{
	var po = document.getElementById("pono");
	//window.alert(po.value);		
	if(po.value.length <= 0) 
	{
		alert("PO 번호를 먼저 입력해 주세요.");
		po.focus();
		return false;
	}

	return true;
}

function check_po_dup() 
{
	var po = document.getElementById("pono");
	//window.alert(po.value);		
	if(po.value.length <= 0) 
	{
		return false;
	}

	return true;
}

function update_total(idx)
{
	var total_ctn = 0;
	var total_amount = 0;
	var total_weight = 0;
	var total_cbm = 0;

	for(var i = 1 ; ; i++)
	{
		if(document.getElementById("qty"+i) == null)
		{
			break;
		}

		var qty = document.getElementById("qty"+i).value;
		var price = document.getElementById("price"+i).value;
		var weight = document.getElementById("weight"+i).value;
		var cbm = document.getElementById("CBM"+i).value;

		if(price != 0 && qty != '')
		{
			total_amount += price * qty;
			total_weight += weight * qty;
			total_cbm += cbm * qty;
		}
	}
	document.getElementById("totalAmt").value = total_amount.toFixed(2);
	document.getElementById("totalWeight").value = total_weight.toFixed(2);
	document.getElementById("totalCBM").value = total_cbm.toFixed(2);
}

function update_purchase_item(idx)
{
	var code = document.getElementById("code"+idx).innerHTML;
	var price = document.getElementById("price"+idx).value;
	var qty = document.getElementById("qty"+idx).value;
	var remark = document.getElementById("remark"+idx).value;
	var pono = document.getElementById("pono").value;

	if(qty == "" || qty == null) return;

	var xmlhttp = new XMLHttpRequest();
	var param = "vcode=" + <?=$vendorCode?> + "&code=" + code + "&price=" + price + "&qty=" + qty + "&remark=" + remark + "&pono=" + pono;

	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("update_item").innerHTML = xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","purchase_update_temp_tbl.php?" + param, true);
	xmlhttp.send();

	update_total();
}

function update_sales_record()
{
	var itemcode = document.getElementsByName("itemcode");
	var item_record = document.getElementsByName("item_record");
	var three_month_record = document.getElementsByName("three_month_record");
	var data = new FormData();
	var codes = "";
	var i = 0;
	var to = new Date();
	var from = new Date();

	for(i = 0; i < itemcode.length; i++)
	{
		if(codes != "") codes += "::";
		codes += itemcode[i].innerHTML;
	}
	from.setMonth(to.getMonth() - 3);
	data.append('codes',codes);

	var fromS = from.toISOString().substring(0,10);
	var toS = to.toISOString().substring(0,10);

	var xmlhttp = new XMLHttpRequest();
	var param = "mode=2" + "&from=" + fromS + "&to=" + toS;
	
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

			document.getElementById("loading_image").style.display = "none";

			var records = xmlhttp.responseText.split("::");		
			var m3_records = records[0].split("/");

		    // 리턴 값 확인 0:정상 기타:비정상
			if(xmlhttp.responseText[0] != "0")
			{
				alert(xmlhttp.responseText);
				return;
			}
			// 금일 기준 3개월간 판매 실적
			for(i = 1; i <= three_month_record.length; i++)
			{
				three_month_record[i - 1].innerHTML = m3_records[i];
			}

			// 1년전 동일기간 3개월간 판매 실적
			var y1_records = records[1].split("/");
			for(i = 0; i < item_record.length; i++)
			{
				item_record[i].innerHTML = y1_records[i];
			}
			
			//document.getElementById("update_item").innerHTML = xmlhttp.responseText;
			//alert(xmlhttp.responseText);
		}
	}
	xmlhttp.open("POST","purchase_sales_performance.php?" + param, true);
	xmlhttp.send(data);

	document.getElementById("loading_image").style.display = "block";

}

function get_number(event) {
	event = event || window.event;
	var keyID = (event.which) ? event.which : event.keyCode;
	if( ( keyID >=48 && keyID <= 57 ) || ( keyID >=96 && keyID <= 105 ) || 
		  keyID == 8 ||			//backspace
		  keyID == 9 ||			//tab
		  keyID == 13 ||			//enter
		  keyID == 46 ||			//delete
		  keyID == 190 ||		//period
		  keyID == 110 ||		//period(number keypad)
		 (keyID >= 37 && keyID <= 40) )	// arrow key
	{
		return;
	}
	else
	{
		return false;
	}
}

function purchase_register_new_order()
{
	if(!check_PO()) return; 

	var totalAmount = document.getElementById("totalAmt").value;
	var totalWeight = document.getElementById("totalWeight").value;
	var totalCBM = document.getElementById("totalCBM").value;
	var pono = document.getElementById("pono").value;
	var orderDate = document.getElementById("target_date").value;
	var arriveDate = document.getElementById("arrive_date").value
	var vendorCode = document.getElementById("vendorCode").value;
												
	var str = "PO # : " + pono + "\n" + 
			  "Vendor : " + vendorCode +  "\n\n" +
			  "Order Date : " + orderDate + "\n" + 
	 		  "Arrive Date : " + arriveDate + "\n\n" + 
			  "Total Amount : $" + totalAmount + "\n" + 
			  "Total Weight : " + totalWeight + "\n" + 
			  "Total CBM : " + totalCBM + "\n\n" + 
			  "등록하시려면 확인 버튼을 눌러 주세요!";

	if(window.confirm(str))
	{
		// purchase_master에 등록
		var xmlhttp = new XMLHttpRequest();
		var param = "vcode=" + <?=$vendorCode?> + "&pono=" + pono + "&amount=" + totalAmount + "&orderDate=" + orderDate + "&arriveDate=" + arriveDate;

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				//document.getElementById("update_item").innerHTML = xmlhttp.responseText;
				var rtnCode = xmlhttp.responseText[0];
				var msg = xmlhttp.responseText.substr(1);

				window.alert(msg);
				
				if(rtnCode == '0')
				{
					window.parent.location.reload();
					window.close();
				}
			}
			setTimeout(window.parent.location.reload,1000);
		}
		xmlhttp.open("GET","purchase_order_register.php?" + param, true);
		xmlhttp.send();
	}
}

function purchase_register_cancel()
{
	var pono = document.getElementById("pono").value;
	var vendorCode = document.getElementById("vendorCode").value;
												
	if(window.confirm("작성중인 Order를 취소하시겠습니다까?"))
	{
		location.href = "purchase_order.php?vendorCode="+vendorCode+"&pono="+pono+"&new=yes&mode=cancel";	//자식창 OPEN
	}
}

// 구매리스트 Update
function purchase_register_update_order()
{
	if(!check_PO()) return; 

// purchase_master에 등록
	var totalAmount = document.getElementById("totalAmt").value;
	var totalWeight = document.getElementById("totalWeight").value;
	var totalCBM = document.getElementById("totalCBM").value;
	var pono = document.getElementById("pono").value;
	var orderDate = document.getElementById("target_date").value;
	var arriveDate = document.getElementById("arrive_date").value
	var vendorCode = document.getElementById("vendorCode").value;

	var str = "PO # : " + pono + "\n" + 
			  "Vendor : " + vendorCode +  "\n\n" +
			  "Order Date : " + orderDate + "\n" + 
	 		  "Arrive Date : " + arriveDate + "\n\n" + 
			  "Total Amount : $" + totalAmount + "\n" + 
			  "Total Weight : " + totalWeight + "\n" + 
			  "Total CBM : " + totalCBM + "\n\n" + 
			  "UPDATE 하시려면 확인 버튼을 눌러 주세요!";

	if(window.confirm(str))
	{
		var xmlhttp = new XMLHttpRequest();
		var param = "vcode=" + <?=$vendorCode?> + "&pono=" + pono + "&amount=" + totalAmount + "&orderDate=" + orderDate + "&arriveDate=" + arriveDate;

		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				var rtnCode = xmlhttp.responseText[0];
				var msg = xmlhttp.responseText.substr(1);
				// 결과 메세지 출력
				window.alert(msg);
				
				if(rtnCode == '0')	// 정상적으로 종료 되었을 경우 처리
				{
					window.opener.location.reload(true);
					window.close();
				}
			}
		}
		xmlhttp.open("GET","purchase_order_update.php?" + param, true);
		xmlhttp.send();
	}
}

function sales_performance2()
{
	var item_div = document.getElementById("sales_performance2");
	var itemcode = document.getElementsByName("itemcode");
	var ProdOwnCode = document.getElementsByName("ProdOwnCode");
	var item_record = document.getElementsByName("item_record");
	var data = new FormData();
	var codes = "";
	var i = 0;

	item_div.style.display = "none";

	for(i = 0; i < itemcode.length; i++)
	{
		if(codes != "") codes += "::";
		codes += itemcode[i].innerHTML;
        codes += ";;"; 
		codes += ProdOwnCode[i].value;
	}

	data.append('codes',codes);
	
	var fromDate = document.getElementById("salesFrom").value;
	var toDate = document.getElementById("salesTo").value;

	var xmlhttp = new XMLHttpRequest();
	var param = "from=" + fromDate + "&to=" + toDate + "&mode=3";

	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("loading_image").style.display = "none";

			var records = xmlhttp.responseText.split("/");		

		    // 리턴 값 확인 0:정상 기타:비정상
			if(xmlhttp.responseText[0] != "0")
			{
				alert(xmlhttp.responseText);
				return;
			}
			for(i = 1; i <= item_record.length; i++)
			{
				item_record[i - 1].innerHTML = records[i];
			}
		}
//document.getElementById("update_item").innerHTML = xmlhttp.responseText;
	}
	xmlhttp.open("POST","purchase_sales_performance.php?" + param, true);
	xmlhttp.send(data);

	document.getElementById("loading_image").style.display = "block";
}

function stock_query()
{
	var item_div = document.getElementById("sales_performance2");
	var balance = document.getElementsByName("balance");
	var itemcode = document.getElementsByName("itemcode");
	var ProdOwnCode = document.getElementsByName("ProdOwnCode");
	var data = new FormData();
	var codes = "";
	var i = 0;

	item_div.style.display = "none";

	for(i = 0; i < itemcode.length; i++)
	{
		if(codes != "") codes += "::";
		codes += itemcode[i].innerHTML;
        codes += ";;"; 
		codes += ProdOwnCode[i].value;
	}

console.log(codes);
	data.append('codes',codes);
	
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("loading_image").style.display = "none";
console.log(xmlhttp.responseText);
			var records = xmlhttp.responseText.split("/");		

		    // 리턴 값 확인 0:정상 기타:비정상
			if(xmlhttp.responseText[0] != "0")
			{
				alert(xmlhttp.responseText);
				return;
			}
			for(i = 1; i <= balance.length; i++)
			{
				balance[i - 1].innerHTML = records[i];
			}
		}
	}
	xmlhttp.open("POST","purchase_sales_performance.php?mode=4", true);
	xmlhttp.send(data);

	document.getElementById("loading_image").style.display = "block";
}

function query_performance()
{
	var item_div = document.getElementById("sales_performance2");
	var performance_query = document.getElementById("performance_query");
	var element_rect = performance_query.getBoundingClientRect();

	// 클릭한 아이템의 위치로 DIV 위치 조정
	item_div.style.top = parseInt(element_rect.top - 85)+"px";
	item_div.style.left = parseInt(element_rect.left)+"px";

	item_div.style.display = "block";
}

function toggle_item_div2() {
	document.getElementById("sales_performance2").style.display = "none";
}

function search_vendor() {
	var search_key = document.getElementById("vendorname").value;
	if (search_key) {
		document.getElementById("vendor_iframe").src = "purchaselist_search.php?mode=vendor&key="+search_key;
		var pos = document.getElementById("vendorname").getBoundingClientRect();
		document.getElementById("search_vendor_display").style.left = pos.left - 00 + "px";
		document.getElementById("search_vendor_display").style.top = pos.top + 20 + "px";
	} else {
		document.getElementById("vendorname").value = "";
		alert("검색할 Vendor Name 를 입력하세요.");
	}
}

function search_vendor2(ne) {
	var pono = document.getElementById("pono");
	var evt = pono.onblur;
	pono.onblur=null;

	if(ne == "yes" && pono.value == "")
	{
		if(confirm("Order번호가 없습니다. 이 페이지를 종료하시겠습니까?"))
		{
			window.open("about:blank","_self").close();
			return;
		}
		else
		{
			pono.focus();
			pono.onblur = evt;
		}
	}

	if(ne == "yes")
	{
		if(confirm("Order번호 '"+pono.value+"' 로 새 order를 작성하시겠습니까?"))
		{
			document.forms.frm.new.value = ne;
			document.forms.frm.submit();
			pono.readonly = true;
		}
		else
		{
			pono.focus();
			pono.onblur = evt;
		}
	}
	return false;
}

function showhide()	{
	var div = document.getElementById("search_vendor_display");
	if (div.style.display != "none") {
		div.style.display = "none";
	} else {
		div.style.display = "block";
	}
}

function select_supp(code,name) {
	var div = document.getElementById("search_vendor_display");
	document.getElementById("vendorname").value = name;
	document.getElementById("vendorCode").value = code;
	var pono = document.getElementById("pono");
	pono.disabled = false;
	document.getElementById("target_date").disabled = false;
	document.getElementById("arrive_date").disabled = false;
	document.getElementById("btn_register").disabled = false;
	document.getElementById("btn_cancel").disabled = false;
	document.getElementById("btn_print").disabled = false;
	div.style.display = "none";
	pono.focus();
}

function purchase_print_new_order(cid,vname,vcode) {
	//alert(invno);
	var pono = document.getElementById('pono').value;
	var fromDate = document.getElementById("salesFrom").value;
	var toDate = document.getElementById("salesTo").value;
	var popupw;

	if(confirm("Order form 을 출력합니다. \n계속 진행할까요?"))
	{
		if(cid == '1')
			popupw = window.open("FormTBPurchaseOrderForm.php?pono="+pono+"&vname="+encodeURIComponent(vname)+"&vcode="+vcode+"&from="+fromDate+"&to="+toDate);
		else
			popupw = window.open("FormMannaPurchaseOrderForm.php?pono="+pono+"&vname="+encodeURIComponent(vname)+"&vcode="+vcode+"&from="+fromDate+"&to="+toDate);

		try
		{
			popupw.focus();
			window.opener.location.reload();
			window.open('about:blank','_self').close();
		}
		catch(e)
		{
			alert("Pop-up Blocker is enabled! Please add this site to your exception list.");
		}
	}
}

function setHeight()
{
	var height = window.innerHeight - 100;

	if(height < 200) height = 200;

	document.getElementById('item_list_div').style.height = height+"px";
	document.getElementById('item_result').style.height = (height - 39)+"px";
}

function init(ne)
{
	setHeight();
	if(ne != "yes")
	{
		if(document.getElementById("pono").value != "")
			document.getElementById("pono").readOnly = true;
		if(document.getElementById("vendorname").value != "")
		{
			document.getElementById("vendorname").readOnly = true;
			document.getElementById("vendorCode").readOnly = true;
		}
	}
	else
	{
		var pono = document.getElementById("pono");
		pono.disabled = true;
		document.getElementById("target_date").disabled = true;
		document.getElementById("arrive_date").disabled = true;
		document.getElementById("btn_register").disabled = true;
		document.getElementById("btn_cancel").disabled = true;
		document.getElementById("btn_print").disabled = true;
	}
}

</script>
<?php // ## JAVA Script E ---------------> ?>

<style type="text/css">
html, 
body {
	height: 90%;
}
</style>
</head>

<?php // ## BODY Start ---------------> ?>
<body onload="init('<?=$begin?>'); update_total();	update_sales_record();">
<?php // ## Page location S----------> ?>
<!--- <script> window.alert("##"+<?=$vendorCode?>+"_VendorItems"); </script>  -->
<table>
	<tr>
		<td class="doc_title"><b>■ Purchases &gt Register Order</b></td>
	</tr>
</table>
<?php // ## Page location E 

//$query = "SELECT CardID, Name ".
//			"FROM Card WHERE CID='$cId' AND CardID=$vendorCode AND CardType='2' ";
//$query_result = mssql_query($query);
//$row = mssql_fetch_array($query_result);
//$vendorname = Br_iconv($row['Name']);

 // ## Order form basic information S----------> ?>
<div id="container" style="border:2px solid #666666; width:1024px;">
<form name="frm" method="post" action="purchase_order.php" style="margin-bottom:0;">
<input type="hidden" name="new">
<table style="background-color:#663300; width:1024px; border:2px solid #666666; ">
	<tr height="35px">
		<td width="70px" align="right" class="doc_field_r" style="color:#FFFFFF">
			<b>Vendor:</b>
		</td>
<?
if($pono == "")
{
?>
		<td width="200px">
			<input class="doc_field" id="vendorname" name="vendorname" type="text" size="20" value="<?=$vendorname?>" <?=$sReadOnly?> onkeypress="if (event.keyCode==13) { search_vendor(); event.returnValue=false }" autofocus/>
		</td>
		<td width="50px">
			<input style="background-color: #e2e2e2;" class="doc_field_50" id="vendorCode" name="vendorCode" type="text" value="<?=$vendorCode?>" readonly/>
		</td>
		<td width="30px" align="right" class="doc_field_r" style="color:#FFFFFF">
			<b>PO #:</b>
		</td>
		<td width="90px" align="left">
			<input class="doc_field_po" id="pono" name="pono" type="text" value="<?=$pono?>" <?=$sReadOnly?> onblur="return search_vendor2('<?=$new?>')" onkeypress="if (event.keyCode==13) { return search_vendor2('<?=$new?>'); }" />
<?
} else {
?>
		<td width="250px" colspan="2">
			<b><font color="white"><?=$vendorname?> (<?=$vendorCode?>)</font></b>
			<input id="vendorname" name="vendorname" type="hidden" value="<?=$vendorname?>" />
			<input id="vendorCode" name="vendorCode" type="hidden" value="<?=$vendorCode?>" />
		</td>
		<td width="30px" align="right" class="doc_field_r" style="color:#FFFFFF">
			<b>PO #:</b>
		</td>
		<td width="90px" align="left">
			<b><font color="white"><?=$pono?></font></b>
			<input type="hidden" id="pono" name="pono" value="<?=$pono?>"/>
<?
}
?>
		</td>
		<td width="90px" align="right" width="100px" class="doc_field_r" style="color:#FFFFFF">
			<b>Order Date:</b>
		</td>
		<td width="100px" align="left">
			<input style="width:80px" type="text" id="target_date" name="target_date" value="<?=$target_date?>" onClick="datePicker(event,'target_date')" >
		</td>
		<td width="40px" align="right" class="doc_field_r" style="color:#FFFFFF">
			<b>ETA:</b>
		</td>
		<td width="100px" align="left" >
			<input style="width:80px" type="text" id="arrive_date" name="arrive_date" value="<?=$arrive_date?>" onClick="datePicker(event,'arrive_date')" >
		</td>
		<td align="left">
<?		if($new == "yes") {	?>
			<input type="button" id="btn_register" value="Register" class="btn_style" onClick="purchase_register_new_order()" />
		</td>
		<td align="left">
			<input type="button" id="btn_cancel" value="Cancel" class="btn_style" onClick="purchase_register_cancel()" />
		</td>
<?		} else {	?>
			<input type="button" id="btn_update" value=" Update " class="btn_style" onClick="purchase_register_update_order()" />
<?		}	?>
		</td>
		<td >
			<input type="button" id="btn_print" value="Print Order Form" class="btn_style" onClick="purchase_print_new_order(<?=$cId?>,'<?=$vendorname?>','<?=$vendorCode?>')" />
		</td>
	</tr>
</table>
</form> <!-- ## form frm END -->
</div>

<?php // ## Order form basic information E----------> ?>

<?php // ## Vendor Items S----------> 
		$itemListHeight = 630; // ## Item 출력 창 높이 설정
		$totalAmount = 0;
		$totalWeight = 0;
		$totalCBM = 0;
?>
<div id="update_item" style="position:absolute; left:4px; top:750px; border:0px width:1000px; height:100px;"></div>
<div id="item_list_div" style="position:absolute; left:8px; top:80px; border:2px solid #666666; width:1024px; height:<?=$itemListHeight?>px;"> <!-- display:none;" -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="middle" height="35" style="background-color:#C0C0C0;">

		<table style="width:1004px;">
			<tr style="background-color:#C0C0C0; letter-spacing:-1px; font-family:verdana; font-size:13px;">
				<td width="20px" valign="middle">
					&nbsp;
				</td>
				<td width="180px" align="right">
					<b>TOTAL CTN:</b>
				</td>
				<td width="100px" align="left">
					<input style="background-color: #e2e2e2; width:100px" class="doc_total" id="totalCtn" name="totalCtn" type="number" value="<?=number_format($totalCTN,2);?>" readonly>
				</td>
				<td width="180px" align="right">
					<b>TOTAL AMOUNT:</b>
				</td>
				<td width="100px" align="left">
					<input style="background-color: #e2e2e2; width:100px" class="doc_total" id="totalAmt" name="totalAmt" type="number" value="<?=number_format($totalAmount,2);?>" readonly>
				</td>
				<td width="180px" align="right">
					<b>TOTAL WEIGHT:</b>
				</td>
				<td width="100px"align="left">
					<input style="background-color: #e2e2e2; width:100px" class="doc_total" id="totalWeight" name="totalWeight" type="number" value="<?=number_format($totalWeight,2);?>" readonly>
				</td>
				<td width="130px" align="right">
					<b>TOTAL CBM:</b>
				</td>
				<td width="100px"align="left">
					<input style="background-color: #e2e2e2; width:100px" class="doc_total" id="totalCBM" name="totalCBM" type="number" value="<?=number_format($totalCBM,2);?>" readonly>
				</td>
				<td width="20px">
					&nbsp;
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
			<div id="item_result" style="height:<?=$itemListHeight-39?>px; overflow-y:scroll; overflow-x:hidden">
<? // ## Vendor Items S----------> ?>
			<table style="width:1004px; border-collapse:collapse;">
				<tr style="background-color:#663300; letter-spacing:-1px; font-family:verdana; font-size:13px;">
					<td width="60px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">VD CODE</td>
					<td width="160px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">ITEM CODE</td>
					<td width="300px" align="center" style="border:1px solid #BBBBBB; border-rightvimrc에:0; color:#FFFFFF">ITEM NAME</td>
					<td width="30px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF"><div id="performance_query"><a style="color:white;" href="javascript:query_performance()">RCRD</a></div></td>
					<td width="30px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">3MTH</td>
					<td width="60px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">PRICE</td>
					<td width="50px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">U-PRICE</td>
					<td width="30px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">QTY</b></td>
					<td width="50px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">WEIGHT</td>
					<td width="40px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">CBM</td>
					<td width="70px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">SHELFLIFE</td>
					<td width="30px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF"><div id="stock_query"><a style="color:white;" href="javascript:stock_query()">STOCK</a></div></td>
					<td width="150px" align="center" style="border:1px solid #BBBBBB; border-right:0; color:#FFFFFF">REMARK</td>
				</tr>
<?
	if($pono != '') {
		if($new == "yes") {
			$query = "SELECT prodKname, wsCode, ProdOwnCode, suppCode, prodId, prodIUprice, prodQty, prodWeight, prodCBM, prodBalance, prodcontenteach, ExpiryDate, prodUnit, prodRemark ".
					 "FROM VendorItems_".$vendorCode."_".$staffId."_".$pono." ORDER BY prodType, prodType2";
		} else {
			$query = "SELECT prodKname, a.wsCode, a.ProdOwnCode, a.suppCode, a.prodId, ProdPriceUSD as prodIUprice, qty as prodQty, prodWeight, prodCBM, prodBalance, prodcontenteach, expiry_date as ExpiryDate, prodUnit, OrderMemo as prodRemark ".
					 "FROM purchase_detail a LEFT JOIN Inventory_Item b ON a.CID=b.CID AND a.wsCode=b.wsCode AND a.ProdOwnCode=b.ProdOwnCode ".
						"WHERE pd_vendor_cd =".$vendorCode." AND pd_po_no = '".$pono."' ".
						" ORDER BY prodType, prodType2";
		}
		//echo $query;
		$rst = mssql_query($query);
		//$query = "SELECT prodKname, wsCode, prodId, prodIUprice, prodWeight, prodCBM, prodBalance, prodcontenteach, ExpiryDate, prodUnit ".
		//         "FROM Inventory_Item WHERE SuppCode='$vendorCode' ORDER BY prodType, prodType2";
		//echo $query;
		//$rst = mssql_query($query);
		$i = 0; 
		while($row = mssql_fetch_array($rst)) {
			$i++;
			if ($i % 2 == 0)	$doc_field_name = "doc_field_vendor_item_bg";
			else				$doc_field_name = "doc_field_vendor_item";
			$koraname = getItemName($row['wsCode'],$row['ProdOwnCode'],$cId);
	?>
					<tr class="<?=$doc_field_name?>">
						<td width="60px" align="left" style="border:1px solid #BBBBBB; border-right:0">
							<?=$row['suppCode']; ?>
						</td>
						<td width="160px" align="left" style="border:1px solid #BBBBBB; border-right:0">
							<div id="<?='code'.$i?>" name="itemcode"><?=$row['wsCode']?></div>
							<input type="hidden" value="<?=$row['ProdOwnCode']?>" name="ProdOwnCode">
							<!-- <a href="javascript:sales_performance('<?=$row['wsCode']?>',<?=$i?>)"><div id="<?='code'.$i?>" name="itemcode"><?=$row['wsCode']?></div></a> -->
						</td>
						<td width="300px" style="border:1px solid #BBBBBB; border-right:0">
							<div id="<?='name'.$i?>"><?=$koraname[0]; ?></div>
						</td>
						<td width="30px" align="right" style="border:1px solid #BBBBBB; border-right:0;">
							<div name="item_record"></div>
						</td>
						<td width="30px" align="right" style="border:1px solid #BBBBBB; border-right:0;">
							<div name="three_month_record"></div>
						</td>
						<td width="60px" style="border:1px solid #BBBBBB; border-right:0">
							<input style="width:60px; font-size:10px; text-align:right;" type="number" value="<?=number_format($row['prodIUprice'],2);?>" id="<?='price'.$i?>" name="price" onClick="this.select(); return check_PO();" onKeyDown="return get_number(event)"  onblur="return update_purchase_item(<?=$i?>);">
						</td>
						<td width="50px" align="right" style="border:1px solid #BBBBBB; border-right:0">
	<? 
				if($row['prodUnit'] == "BOX" && $row['prodcontenteach'] != "0")
					echo number_format($row['prodIUprice'] / $row['prodcontenteach'],2);
				else 
					echo number_format($row['prodIUprice'],2);
	?>
						</td>
						<td width="30px" style="border:1px solid #BBBBBB; border-right:0">
							<input style="width:30px; font-size:10px; text-align:right;" type="text" id="<?='qty'.$i?>" value="<?=$row['prodQty']?>" name="qty" onClick="this.select(); return check_PO();" onKeyDown="return get_number(event)" onblur="return update_purchase_item(<?=$i?>);">
						</td>
						<td width="50px" align="right" style="border:1px solid #BBBBBB; border-right:0">
							<?=number_format($row['prodWeight'],2); ?>
							<input type="hidden" id="<?='weight'.$i?>" name="weight" value="<?=$row['prodWeight']?>">
						</td>
						<td width="40px" align="right" style="border:1px solid #BBBBBB; border-right:0">
							<?=number_format($row['prodCBM'],4); ?>
							<input type="hidden" id="<?='CBM'.$i?>" name="cbm" value="<?=$row['prodCBM']?>">
						</td>
						<td width="70px" align="right" style="border:1px solid #BBBBBB; border-right:0">
							<?=$row['ExpiryDate'] ?>
						</td>
						<td width="30px" align="right" style="border:1px solid #BBBBBB; border-right:0">
							<div id="<?='balance'.$i?>" name="balance"><?=$row['prodBalance'] ?></div>
						</td>
						<td width="150px" style="border:1px solid #BBBBBB; border-right:0">
							<input style="width:150px; font-size:10px;" type="text" id="<?='remark'.$i?>" name="Remark" value="<?=Br_iconv($row['prodRemark'])?>" onblur="return update_purchase_item(<?=$i?>);" maxlength="50">
	<? 
				if($row['prodUnit'] == "BOX" || $row['prodUnit'] == "PK" || $row['prodUnit'] == "EA")
				{
	?>						<input type="hidden" id="<?='ctn'.$i?>" name="ctn" value="1"">
	<?			}		?>
						</td>
					</tr>
<? // ## Vendor Items E----------> 
		} 
	}
?>
			</table>
			</div>
		</td>
	</tr>
</table>
</div>

<div id="sales_performance2" style="position:absolute; left:4px; top:750px; border:2px width:350px; display:none; background-color:#ccffff;">
<table width="300px" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="middle" style="padding:0 0 0 20px; background-color:#CECEF6;">
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td align="center" style="letter-spacing:-1px; font-weight:bold; color:blue; width:160px;"><div id="performance_title">기간별 판매실적 조회</div></td>
					<td width="22" align="right">&nbsp;<img style="cursor:pointer;" src="css/img/bt_closelayer.gif" onClick="toggle_item_div2()"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="right" width="300px">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" width="300px">
						<input style="width:80px" type="text" id="salesFrom" name="salesFrom" value="<?=$from_1year?>" onClick="datePicker(event,'salesFrom')"> ~ 
						<input style="width:80px" type="text" id="salesTo" name="salesTo" value="<?=$to_1year?>" onClick="datePicker(event,'salesTo')"> 
						<input type="button" value="조회" onClick="sales_performance2()">
					</td>
				</tr>
				<tr>
					<td align="right" width="300px">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>

<div id="search_vendor_display" style="border:1px #666666 solid; background-color:#ffffff; position:absolute; z-index:10; display:none; width:482px; left:0px; top:0px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#808080">
		<tr>
			<td valign="middle" style="padding:0px 0 0 20px;>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr height="20">
						<td class="doc_title2" style="padding-left:15px; letter-spacing:-1px; font-weight:bold;">검색결과</td>
						<td width="22" align="left"><img style="cursor:pointer;" src="css/img/bt_closelayer.gif" onClick="showhide()"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td><iframe id="vendor_iframe" width="100%" height="200" frameborder=0></iframe></td>
		</tr>
	</table>
</div>
<div id="loading_image" style="position:absolute; left:450px; top:150px; display:none; widht:100px height:100px margin:40px 40px 40px 40px; border:1px solid black; text-align:center; background-color:white">
	<img src="css/img/ajax-loader.gif"><br/>자료 처리 중입니다.
</div>
<?
	mssql_close(); // ## Close DB --> 
?>

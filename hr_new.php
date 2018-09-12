<script>
//신상정보
function check_employee_name(name) {
	var company = document.forms.hr_new.hr_company.value;

	if (!name) { 
		document.getElementById("name_check_msg").innerHTML = "";
		return;
	} else {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.forms.hr_new.name_check.value = xmlhttp.responseText;
				if(xmlhttp.responseText == 0) {
					document.getElementById("name_check_msg").innerHTML = "사용 불가능";
				} else {
					document.getElementById("name_check_msg").innerHTML = "사용 가능";
				}
			}
		}
		xmlhttp.open("GET", "hr_iframe.php?mode=employee_name&comp=" + company + "&name=" + name, true);
		xmlhttp.send();
	}
}

function get_employee_sample_code() {
	var company = document.forms.hr_new.hr_company.value;

	if (!company) { 
		document.getElementById("code_check_msg").innerHTML = "";
		return;
	} else {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.forms.hr_new.hr_code.value = xmlhttp.responseText;
				document.forms.hr_new.code_check.value = 1;
				document.getElementById("code_check_msg").innerHTML = "사용 가능";
			}
		}
		xmlhttp.open("GET", "hr_iframe.php?mode=employee_sample_code&comp=" + company, true);
		xmlhttp.send();
	}
}

function check_employee_code(code) {
	var company = document.forms.hr_new.hr_company.value;

	if (!code) { 
		document.getElementById("code_check_msg").innerHTML = "";
		return;
	} else {
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				document.forms.hr_new.code_check.value = xmlhttp.responseText;
				if(xmlhttp.responseText == 0) {
					document.getElementById("code_check_msg").innerHTML = "사용 불가능";
				} else {
					document.getElementById("code_check_msg").innerHTML = "사용 가능";
				}
			}
		}
		xmlhttp.open("GET", "hr_iframe.php?mode=employee_code&comp=" + company + "&code=" + code, true);
		xmlhttp.send();
	}
}

function show_employee_list() {
	var company = document.forms.hr_new.hr_company.value;

	if(company) {
		var pos = document.getElementById("employee_list_btn").getBoundingClientRect();
		document.getElementById("employee_list_div").style.left = pos.left + 81 + "px";
		document.getElementById("employee_list_div").style.top = pos.top + 21 + "px";

		document.getElementById("employee_list_iframe").src = "http://group.t-brothers.com/hr_iframe.php?mode=employee_list&comp=" + company;
		document.getElementById("employee_list_div").style.display = "";
	} else {
		alert("소속회사를 먼저 선택해주세요.")
	}
}

function get_department_list(company) {
	if(company) {
		get_employee_sample_code();
		document.forms.hr_new.hr_nameK.disabled = "";
		document.forms.hr_new.hr_code.disabled = "";
		document.getElementById("employee_list_div").style.display = "none";
		document.getElementById("department_list_iframe").src = "hr_iframe.php?mode=department_list&comp=" + company;
	} else {
		document.getElementById("department_list_iframe").src = "hr_iframe.php?mode=department_list&comp=0";
		document.forms.hr_new.hr_nameK.value = "";
		document.forms.hr_new.hr_nameK.disabled = "disabled";
		document.getElementById("name_check_msg").innerHTML = "";

		document.forms.hr_new.hr_code.value = "";
		document.forms.hr_new.hr_code.disabled = "disabled";
		document.getElementById("code_check_msg").innerHTML = "";
	}
}

function onlyNumber(event) {
	event = event || window.event;
	var keyID = (event.which) ? event.which : event.keyCode;
	if ( (keyID >= 48 && keyID <= 57) || (keyID >= 96 && keyID <= 105) || keyID == 8 || keyID == 46 || keyID == 37 || keyID == 39 || keyID == 9) {
		return;
	} else {
		return false;
	}
}

function resize(obj) {
	obj.style.height = obj.style.minHeight;
	if((obj.scrollHeight + 2) > parseInt(obj.style.minHeight)) {
		obj.style.height = (obj.scrollHeight + 2)+"px";
	} else {
		obj.style.height = obj.style.minHeight;
	}
}

//인사정보
function wage_add(wage_type) {
	wage_type = wage_type.split("_");

	var table = document.getElementById("table_wage");
	var row_num = table.getElementsByTagName("tr").length;
	//var col_num = table.rows[0].cells.length;
	var row = table.insertRow(row_num);
	row.className = "doc_border";
	row.style.height = "20px";

	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);

	cell0.style.textAlign = "center";
	cell0.innerHTML = "<input name='hr_wage_date[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this)' >";

	cell1.style.textAlign = "center";
	cell1.innerHTML = "<select name='hr_wage_type[]' style='width:100%; font-size:15px;'>";
	for(var i = 0; i < wage_type.length; i++) {
		var option = document.createElement("option");
		option.text = wage_type[i];
		option.value = i;
		cell1.childNodes[0].add(option);
	}

	cell2.style.textAlign = "center";
	cell2.innerHTML = "<input name='hr_wage[]' type='text' style='width:100%; text-align:right;'>";

	cell3.style.textAlign = "center";
	cell3.innerHTML = "<input name='hr_wage_bigo[]' type='text' style='width:100%; text-align:left;'>";

	cell4.style.textAlign = "center";
	cell4.style.paddingTop = "2px";
	cell4.innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\"table_wage\", " + row_num + ");'>X</span>";
}

function point_add(point_type) {
	point_type = point_type.split("_");

	var table = document.getElementById("table_point");
	var row_num = table.getElementsByTagName("tr").length;
	var row = table.insertRow(row_num);
	row.className = "doc_border";
	row.style.height = "20px";

	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);

	cell0.style.textAlign = "center";
	cell0.innerHTML = "<input name='hr_point_date[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this)'>";
	
	cell1.style.textAlign = "center";
	cell1.innerHTML = "<select name='hr_point_desc[]' style='width:100%; font-size:15px;'>";
	for(var i = 0; i < point_type.length; i++) {
		var option = document.createElement("option");
		option.text = point_type[i];
		option.value = point_type[i];
		cell1.childNodes[0].add(option);
	}

	cell2.style.textAlign = "center";
	cell2.innerHTML = "<input name='hr_point_reward[]' type='text' style='width:100%; text-align:center;'>";

	cell3.style.textAlign = "center";
	cell3.innerHTML = "<input name='hr_point_bigo[]' type='text' style='width:100%; text-align:left;'>";

	cell4.style.textAlign = "center";
	cell4.style.paddingTop = "2px";
	cell4.innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\"table_point\", " + row_num + ");'>X</span>";
}

function deposit_add(deposit_cd, deposit_nm) {
	deposit_cd = deposit_cd.split("_");
	deposit_nm = deposit_nm.split("_");

	var table = document.getElementById("table_deposit");
	var row_num = table.getElementsByTagName("tr").length;
	var row = table.insertRow(row_num);
	row.className = "doc_border";
	row.style.height = "20px";

	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);
	var cell5 = row.insertCell(5);


	cell0.style.textAlign = "center";
	cell0.innerHTML = "<select name='hr_deposit_name[]' style='width:100%; font-size:15px;'>";
	for(var i = 0; i < deposit_nm.length; i++) {
		var option = document.createElement("option");
		option.text = deposit_cd[i] + ". " + deposit_nm[i];
		option.value = deposit_cd[i];
		cell0.childNodes[0].add(option);
	}

	cell1.style.textAlign = "center";
	cell1.innerHTML = "<input name='hr_deposit_size[]' type='text' style='width:100%; text-align:center;'>";

	cell2.style.textAlign = "center";
	cell2.innerHTML = "<input name='hr_deposit_amount[]' type='text' style='width:100%; text-align:center;'>";

	cell3.style.textAlign = "center";
	cell3.innerHTML = "<input name='hr_deposit_receiver[]' type='text' style='width:100%; text-align:center;'>";

	cell4.style.textAlign = "center";
	cell4.innerHTML = "<input name='hr_deposit_bigo[]' type='text' style='width:100%; text-align:left;'>";

	cell5.style.textAlign = "center";
	cell5.style.paddingTop = "2px";
	cell5.innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\"table_deposit\", " + row_num + ");'>X</span>";
}

function schedule_add(schedule_type) {
	schedule_type = schedule_type.split("_");

	var table = document.getElementById("table_schedule");
	var row_num = table.getElementsByTagName("tr").length;
	var row = table.insertRow(row_num);
	row.className = "doc_border";
	row.style.height = "20px";

	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);
	var cell4 = row.insertCell(4);
	var cell5 = row.insertCell(5);

	cell0.style.textAlign = "center";
	cell0.innerHTML = "<select name='hr_schedule_type[]' style='width:100%; font-size:15px;' onChange='schedule_change_type(this)'>";
	for(var i = 0; i < schedule_type.length; i++) {
		var option = document.createElement("option");
		option.text = schedule_type[i];
		option.value = i+1;
		cell0.childNodes[0].add(option);
	}

	cell1.style.textAlign = "center";
	cell1.innerHTML = "<input name='hr_schedule_sDate[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this, \"sDate\")'>";

	cell2.style.textAlign = "center";
	cell2.innerHTML = "<input name='hr_schedule_eDate[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this, \"eDate\")'>";

	cell3.style.textAlign = "center";
	cell3.innerHTML = "0";

	cell4.style.textAlign = "center";
	cell4.innerHTML = "<input name='hr_schedule_bigo[]' type='text' style='width:100%; text-align:left;'>";

	cell5.style.textAlign = "center";
	cell5.style.paddingTop = "2px";
	cell5.innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\"table_schedule\", " + row_num + ");'>X</span>";
}

function schedule_change_type(target) {
	if(target.value != 1 && target.value != 2) {
		var sDate = target.parentNode.nextSibling.childNodes[0].value;
		if(sDate)	target.parentNode.nextSibling.nextSibling.childNodes[0].value = sDate;
		else		target.parentNode.nextSibling.nextSibling.childNodes[0].value = "";
		target.parentNode.nextSibling.nextSibling.childNodes[0].disabled = "disabled";
	} else {
		target.parentNode.nextSibling.nextSibling.childNodes[0].disabled = "";
	}
	schedule_calculate_day("type" , target);
}

function schedule_calculate_day(mode, target) {
	if(mode == "sDate") {
		var sDate = target.value;
		var type = target.parentNode.previousSibling.childNodes[0].value;
		if(type != 1 && type != 2) {
			var eDate = sDate;
			target.parentNode.nextSibling.childNodes[0].value = eDate;
		} else {
			var eDate = target.parentNode.nextSibling.childNodes[0].value;
		}
		
		if(sDate > eDate || !eDate ) {
			eDate = sDate;
			target.parentNode.nextSibling.childNodes[0].value = eDate;
		}

		sDateArray = sDate.split("-");
		sDateObj = new Date(sDateArray[0], Number(sDateArray[1])-1, sDateArray[2]);
		eDateArray = eDate.split("-");
		eDateObj = new Date(eDateArray[0], Number(eDateArray[1])-1, eDateArray[2]);

		var betweenDay = ((eDateObj.getTime() - sDateObj.getTime()) / 1000 / 60 / 60 / 24) + 1;
		var betweenDay = Math.ceil(betweenDay);
		target.parentNode.nextSibling.nextSibling.innerHTML = betweenDay;

	} else if(mode == "eDate") {
		var eDate = target.value;
		var sDate = target.parentNode.previousSibling.childNodes[0].value;

		if(sDate) {
			if(sDate > eDate) {
				alert("입력일 오류");
				eDate = sDate;
				target.value = eDate;
			}

			sDateArray = sDate.split("-");
			sDateObj = new Date(sDateArray[0], Number(sDateArray[1])-1, sDateArray[2]);
			eDateArray = eDate.split("-");
			eDateObj = new Date(eDateArray[0], Number(eDateArray[1])-1, eDateArray[2]);

			var betweenDay = ((eDateObj.getTime() - sDateObj.getTime()) / 1000 / 60 / 60 / 24) + 1;
			var betweenDay = Math.ceil(betweenDay);
		} else {
			var betweenDay = 0;
		}

		target.parentNode.nextSibling.innerHTML = betweenDay;

	} else {
		var type = target.value;

		if(type != 1 && type != 2) {
			var sDate = target.parentNode.nextSibling.childNodes[0].value;
			
			if(sDate) {
				var eDate = sDate;

				sDateArray = sDate.split("-");
				sDateObj = new Date(sDateArray[0], Number(sDateArray[1])-1, sDateArray[2]);
				eDateArray = eDate.split("-");
				eDateObj = new Date(eDateArray[0], Number(eDateArray[1])-1, eDateArray[2]);

				var betweenDay = ((eDateObj.getTime() - sDateObj.getTime()) / 1000 / 60 / 60 / 24) + 1;
				var betweenDay = Math.ceil(betweenDay);
			} else {
				var betweenDay = 0;
			}

		} else {
			var sDate = target.parentNode.nextSibling.childNodes[0].value;
			var eDate = target.parentNode.nextSibling.nextSibling.childNodes[0].value;

			if(sDate && eDate) {
				sDateArray = sDate.split("-");
				sDateObj = new Date(sDateArray[0], Number(sDateArray[1])-1, sDateArray[2]);
				eDateArray = eDate.split("-");
				eDateObj = new Date(eDateArray[0], Number(eDateArray[1])-1, eDateArray[2]);

				var betweenDay = ((eDateObj.getTime() - sDateObj.getTime()) / 1000 / 60 / 60 / 24) + 1;
				var betweenDay = Math.ceil(betweenDay);
			} else {
				var betweenDay = 0;
			}
		}

		target.parentNode.nextSibling.nextSibling.nextSibling.innerHTML = betweenDay;
	}
}

function file_add() {
	var table = document.getElementById("table_file");
	var row_num = table.getElementsByTagName("tr").length;
	var row = table.insertRow(row_num);
	row.className = "doc_border";
	row.style.height = "20px";

	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);

	cell0.style.textAlign = "center";
	cell0.innerHTML = "<input name='hr_file_subject[]' type='text' style='width:100%; text-align:left;'>";

	cell1.style.textAlign = "center";
	cell1.innerHTML = "<input name='hr_file[]' type='file' style='width:100%;'>";

	cell2.style.textAlign = "center";
	cell2.innerHTML = "<input name='hr_file_bigo[]' type='text' style='width:100%; text-align:left;'>";

	cell3.style.textAlign = "center";
	cell3.style.paddingTop = "2px";
	cell3.innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\"table_file\", " + row_num + ");'>X</span>";
}

function del_row(table_name, seq) {
	var table = document.getElementById(table_name);
	var total_row = table.getElementsByTagName("tr").length;
	var col_num = table.rows[0].cells.length;
	table.deleteRow(seq);

	if(seq < (total_row - 1)) {
		for(var i = seq; i < (total_row - 1); i++) {
			table.rows[i].cells[col_num-1].innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\""+ table_name + "\", " + i + ");'>X</span>";
		}
	}
}

function check_before_submit() {
	var target = document.forms.hr_new;

	if(target.submitCheck.value == "n") {
		if(target.hr_company.value == null || target.hr_company.value == "") {
			alert("소속회사 오류");
			return false;
		} else if(target.code_check.value == 0) {
			alert("직원코드 오류");
			return false;
		} else if(target.name_check.value == 0) {
			alert("한글성명 오류");
			return false;
		}
		
		var answer = confirm("등록 하시겠습니까?");
		if(answer) {
			//alert(1);
			//document.frames["iframe_image"].hr_image_upload.submit();
			//document.getElementById("iframe_image").contentWindow.document.hr_image_upload.submit();
			//document.getElementById("iframe_image").contentDocument.document.body.style.backgroundColor = "red";
			//var obj = document.getElementById("hr_new").name;
			//alert(obj);
			//alert(2);

			target.hr_department.value = ($('#department_list_iframe').contents().find('#hr_department').val());
			target.submitCheck.value = "y";
			target.mode.value = "new";
			target.submit();
		}
	} else if(target.submitCheck.value == "y") {
		alert("이미 처리중입니다.");
		return false;
	}
}

// preview image
var InputImage = (function loadImageFile() {
    if (window.FileReader) {
        var ImagePre; 
        var ImgReader = new window.FileReader();
        var fileType = /^(?:image\/bmp|image\/gif|image\/jpeg|image\/png|image\/x\-xwindowdump|image\/x\-portable\-bitmap)$/i; 

        ImgReader.onload = function (Event) {
            if (!ImagePre) {
                var newPreview = document.getElementById("imagePreview");
                ImagePre = new Image();
                ImagePre.style.maxWidth = "198px";
                ImagePre.style.maxHeight = "208px";
                newPreview.appendChild(ImagePre);
            }
            ImagePre.src = Event.target.result;
			document.getElementById("image").style.display = "none";
			document.getElementById("image_del").style.display = "";
        };
        return function () {
            var img = document.getElementById("image").files;
           
            if (!fileType.test(img[0].type)) { 
            	alert("이미지 파일을 업로드 하세요"); 
            	return; 
            }
            ImgReader.readAsDataURL(img[0]);
        }
    }
    //document.getElementById("imagePreview").src = document.getElementById("image").value; 
})();

function RemoveImage() {
	var preview_div = document.getElementById("imagePreview");
	//preview_div.removeChild(preview_div.firstChild);
	preview_div.firstChild.src = "";

	document.getElementById("image_del").style.display = "none";
	document.getElementById("image").value = "";
	document.getElementById("image").style.display = "";
}

function Image_upload() {
	newWindow=window.open("http://184.69.79.114:8000/memberlist/photoUpload.php"
, "imageupload", "scrollbars=yes,toolbar=yes,resizable=yes,width=500,height=300,left=0,top=0'");
}
</script>


</script>

<?
include_once "includes/db_configms_HN.php";

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];

if($mode == "new") {
	$hr_company = $_POST['hr_company'];
	$hr_code = $_POST['hr_code'];
	$hr_nameK = $_POST['hr_nameK'];
	$hr_fNameE = $_POST['hr_fNameE'];	$hr_lNameE = $_POST['hr_lNameE'];
	$hr_department = $_POST['hr_department'];
	$hr_position = $_POST['hr_position'];
	$hr_title = $_POST['hr_title'];

	$hr_birth_gubun = $_POST['hr_birth_type'];
	$hr_tel11 = $_POST['hr_tel11'];		$hr_tel12 = $_POST['hr_tel12'];		$hr_tel13 = $_POST['hr_tel13'];
	$hr_tel21 = $_POST['hr_tel21'];		$hr_tel22 = $_POST['hr_tel22'];		$hr_tel23 = $_POST['hr_tel23'];
	$hr_email = $_POST['hr_email'];
	$hr_street = $_POST['hr_street'];	$hr_city = $_POST['hr_city'];		$hr_province = $_POST['hr_province'];
	$hr_visaStatus = $_POST['hr_visaStatus'];
	$hr_sin1 = $_POST['hr_sin1'];		$hr_sin2 = $_POST['hr_sin2'];		$hr_sin3 = $_POST['hr_sin3'];
	$hr_status = $_POST['hr_status'];

	$hr_employeecard = $_POST['hr_employeecard'];
	if($hr_employeecard == 1){
		$hr_employeecard =  "GETDATE()"; 
	}
	else{
		$hr_employeecard =  "NULL"; 
	}



	$hr_memo = $_POST['hr_memo'];

	$hr_wage_date = $_POST['hr_wage_date'];
	$hr_wage_type = $_POST['hr_wage_type'];
	$hr_wage = $_POST['hr_wage'];
	$hr_wage_bigo = $_POST['hr_wage_bigo'];
	$hr_point_date = $_POST['hr_point_date'];
	$hr_point_desc = $_POST['hr_point_desc'];
	$hr_point_reward = $_POST['hr_point_reward'];
	$hr_point_bigo = $_POST['hr_point_bigo'];
	$hr_deposit_name = $_POST['hr_deposit_name'];
	$hr_deposit_size = $_POST['hr_deposit_size'];
	$hr_deposit_amount = $_POST['hr_deposit_amount'];
	$hr_deposit_receiver = $_POST['hr_deposit_receiver'];
	$hr_deposit_bigo = $_POST['hr_deposit_bigo'];

	$hr_schedule_type = $_POST['hr_schedule_type'];
	$hr_schedule_sDate = $_POST['hr_schedule_sDate'];
	$hr_schedule_eDate = $_POST['hr_schedule_eDate'];
	$hr_schedule_bigo = $_POST['hr_schedule_bigo'];

	$hr_file_subject = $_POST['hr_file_subject'];
	$hr_file = $_POST['hr_file'];
	$hr_file_bigo = $_POST['hr_file_bigo'];

	if($_POST['hr_visaY'] && $_POST['hr_visaM'] && $_POST['hr_visaD'])	$hr_visa = $_POST['hr_visaY']."-".$_POST['hr_visaM']."-".$_POST['hr_visaD'];
	else	$hr_visa = null;
	if($_POST['hr_FjoinY'] && $_POST['hr_FjoinM'] && $_POST['hr_FjoinD'])	$hr_Fjoin = $_POST['hr_FjoinY']."-".$_POST['hr_FjoinM']."-".$_POST['hr_FjoinD'];
	else	$hr_Fjoin = null;
	if($_POST['hr_joinY'] && $_POST['hr_joinM'] && $_POST['hr_joinD'])	$hr_join = $_POST['hr_joinY']."-".$_POST['hr_joinM']."-".$_POST['hr_joinD'];
	else	$hr_join = null;
	if($_POST['hr_resignY'] && $_POST['hr_resignM'] && $_POST['hr_resignD'])	$hr_resign = $_POST['hr_resignY']."-".$_POST['hr_resignM']."-".$_POST['hr_resignD'];
	else	$hr_resign = null;
	if($_POST['hr_birthY'] && $_POST['hr_birthM'] && $_POST['hr_birthD']) {
		$hr_birth = $_POST['hr_birthY']."-".$_POST['hr_birthM']."-".$_POST['hr_birthD'];
		$hr_this_birth = date(Y)."-".$_POST['hr_birthM']."-".$_POST['hr_birthD'];
	} else {
		$hr_birth = null;
		$hr_this_birth = null;
	}
	if($_POST['hr_postalCode1'] && $_POST['hr_postalCode2'])	$hr_postalCode = $_POST['hr_postalCode1']." ".$_POST['hr_postalCode2'];
	else	$hr_postalCode = null;

	$hr_passwd = 1234;
	$hr_payroll_cd = 1;
	$hr_stf_company = ($hr_company == 3) ? 10 : 60 ;
	$hr_seq = 99;
	$hr_ipsawon = $_SESSION['hr_code'];

	/*
	echo "hr_company - ".$hr_company."<br>";
	echo "hr_code - ".$hr_code."<br>";
	echo "hr_nameK - ".$hr_nameK."<br>";
	echo "hr_NameE - ".$hr_fNameE." ".$hr_lNameE."<br>";
	echo "hr_department - ".$hr_department."<br>";
	echo "hr_position - ".$hr_position."<br>";
	echo "hr_title - ".$hr_title."<br>";

	echo "hr_birth - ".$hr_birth."<br>";
	echo "hr_gubun - ".$hr_gubun."<br>";
	echo "hr_tel1 - ".$hr_tel11."-".$hr_tel12."-".$hr_tel13."<br>";
	echo "hr_tel2 - ".$hr_tel21."-".$hr_tel22."-".$hr_tel23."<br>";
	echo "hr_email - ".$hr_email."<br>";
	echo "hr_street - ".$hr_street."<br>";
	echo "hr_city - ".$hr_city."<br>";
	echo "hr_province - ".$hr_province."<br>";
	echo "hr_postalCode - ".$hr_postalCode."<br>";
	echo "hr_visaStatus - ".$hr_visaStatus."<br>";
	echo "hr_visa - ".$hr_visa."<br>";
	echo "hr_sin - ".$hr_sin1."-".$hr_sin2."-".$hr_sin3."<br>";
	echo "hr_status - ".$hr_status."<br>";
	echo "hr_Fjoin - ".$hr_Fjoin."<br>";
	echo "hr_join - ".$hr_join."<br>";
	echo "hr_resign - ".$hr_resign."<br>";
	echo "hr_memo - ".$hr_memo."<br>";

	echo "hr_wage_date - ".$hr_wage_date."<br>";
	echo "hr_wage - ".$hr_wage."<br>";
	echo "hr_point_date - ".$hr_point_date."<br>";
	echo "hr_point_desc - ".$hr_point_desc."<br>";
	echo "hr_point_reward - ".$hr_point_reward."<br>";
	echo "hr_point_bigo - ".$hr_point_bigo."<br>";

	echo "hr_wage_date - ".var_dump($hr_wage_date)."<br>";
	echo "hr_wage_type - ".var_dump($hr_wage_type)."<br>";
	echo "hr_wage - ".var_dump($hr_wage)."<br>";
	echo "hr_wage_bigo - ".var_dump($hr_wage_bigo)."<br>";
	echo "hr_point_date - ".var_dump($hr_point_date)."<br>";
	echo "hr_point_desc - ".var_dump($hr_point_desc)."<br>";
	echo "hr_point_reward - ".var_dump($hr_point_reward)."<br>";
	echo "hr_point_bigo - ".var_dump($hr_point_bigo)."<br>";

	echo "hr_deposit_name - ".var_dump($hr_deposit_name)."<br>";
	echo "hr_deposit_size - ".var_dump($hr_deposit_size)."<br>";
	echo "hr_deposit_amount - ".var_dump($hr_deposit_amount)."<br>";
	echo "hr_deposit_receiver - ".var_dump($hr_deposit_receiver)."<br>";
	echo "hr_deposit_bigo - ".var_dump($hr_deposit_bigo)."<br>";
	*/
	
	/* 
	1.부서에 사용될 DB
	-dt_trans_buseo_회사	:	부서 변경시

	2.직위에 사용될 DB
	-dt_trans_position_회사	:	직위 변경시

	3.정보 변경에 사용될 DB
	-dt_buseo_id_회사		:	직원 정보 변경시
	-dt_buseo_id1_회사		:	?
	*/

	$company_name = array("tb", "manna", "bby", "sry", "wv");
	$company_code = array(80, 90, 10, 60, 30);

	
	// STAFF DB - dt_stf_회사
	$hr_nameK = Br_dconv($hr_nameK);
	$hr_memo = Br_dconv($hr_memo);
	$stf_query = "INSERT INTO dt_stf_".$company_name[$hr_company-1]." ".
				 "(id, hnm, last_nm, first_nm, passwd, sin1, sin2, sin3, tel11, tel12, tel13, tel21, tel22, tel23, street, city, province, p_status, birth_gubun, payroll_cd, company, email, bigo, seq, ipdt, employeecard_dt) VALUES ".
				 "($hr_code, '$hr_nameK', '$hr_lNameE', '$hr_fNameE', '$hr_passwd', '$hr_sin1', '$hr_sin2', '$hr_sin3', '$hr_tel11', '$hr_tel12', '$hr_tel13', '$hr_tel21', '$hr_tel22', '$hr_tel23', '$hr_street', $hr_city, $hr_province, $hr_visaStatus, $hr_birth_gubun, $hr_payroll_cd, $hr_stf_company, '$hr_email', '$hr_memo', $hr_seq, GETDATE(), $hr_employeecard) ";
	mssql_query($stf_query);

	// STAFF DB2 - dt_stf_회사 (날짜항목 & postal code 만 따로)
	$stf_query2 = "UPDATE dt_stf_".$company_name[$hr_company-1]." SET ".
				  (($hr_visa) ? "visa_dt = '$hr_visa'" : "visa_dt = NULL" ).", ".
				  (($hr_Fjoin) ? "ipsa_sdt = '$hr_Fjoin'" : "ipsa_sdt = NULL" ).", ".
				  (($hr_join) ? "ipsa_dt = '$hr_join'" : "ipsa_dt = NULL" ).", ".
				  (($hr_resign) ? "term_dt = '$hr_resign'" : "term_dt = NULL" ).", ".
				  (($hr_birth) ? "birth_dt = '$hr_birth'" : "birth_dt = NULL" ).", ".
				  (($hr_this_birth) ? "this_birth_dt = '$hr_this_birth'" : "this_birth_dt = NULL" ).", ".
				  (($hr_postalCode) ? "postal_cd = '$hr_postalCode'" : "postal_cd = NULL" )." ".
				  "WHERE id = $hr_code";
	mssql_query($stf_query2);

	// DEPARTMENT DB - dt_trans_buseo_회사
	if($hr_department) {
		$depart_query = "INSERT INTO dt_trans_buseo_".$company_name[$hr_company-1]." ".
						"(id, dt, company, buseo, ipdt) VALUES ".
						"($hr_code, CONVERT(char(10), GETDATE(), 126), ".$company_code[$hr_company-1].", $hr_department, GETDATE())";
		mssql_query($depart_query);
	}

	// POSITION, TITLE DB - hr_stf_position
	if($hr_position && $hr_title) {

		$get_seq_query = "SELECT TOP 1 seq FROM hr_stf_position WHERE company_cd = $hr_company AND id = $hr_code ORDER BY seq DESC";
		$get_seq_query_result = mssql_query($get_seq_query);
		$get_seq_row = mssql_fetch_array($get_seq_query_result);
		if($get_seq_row['seq'])	$get_seq = $get_seq_row['seq'] + 1;
		else					$get_seq = 1;

		$get_seq = 1;

		$posiTitle_query = "INSERT INTO hr_stf_position ".
						   "(company_cd, id, seq, hr_position, hr_title, dt) VALUES ".
						   "($hr_company, $hr_code, $get_seq, $hr_position, $hr_title, CONVERT(char(10), GETDATE(), 126))";
		mssql_query($posiTitle_query);
	}

	// WAGE DB - dt_trans_wage_회사
	for($i = 0; $i < sizeof($hr_wage_date); $i++) {
		if($hr_wage_date[$i] != "") {
			if($hr_wage[$i] == "")	$hr_wage[$i] = 0;
			$hr_wage_bigo[$i] = Br_dconv($hr_wage_bigo[$i]);
			$wage_query = "INSERT INTO dt_trans_wage_".$company_name[$hr_company-1]." ".
						  "(id, dt, pay_gubun, wage, bigo, ipdt) VALUES ".
						  "($hr_code, '".$hr_wage_date[$i]."', ".$hr_wage_type[$i].", ".$hr_wage[$i].", '".$hr_wage_bigo[$i]."', GETDATE()) ";
			mssql_query($wage_query);
		}
	}

	// REWARD DB - dt_stf_reward
	$company_sName = strtoupper($company_name[$hr_company-1]);
	for($i = 0; $i < sizeof($hr_point_date); $i++) {
		if($hr_point_date[$i] != "") {
			if($hr_point_reward[$i] == "")	$hr_point_reward[$i] = 0;
			$hr_point_bigo[$i] = Br_dconv($hr_point_bigo[$i]);
			$hr_point_desc[$i] = Br_dconv($hr_point_desc[$i]);
			$point_query = "INSERT INTO dt_stf_reward ".
						   "(company, id, rDate, rDesc, reward, bigo, ipdt) VALUES ".
						   "('$company_sName', $hr_code, '".$hr_point_date[$i]."', '".$hr_point_desc[$i]."', ".$hr_point_reward[$i].", '".$hr_point_bigo[$i]."', GETDATE()) ";
			mssql_query($point_query);
		}
	}

	// DEPOSIT DB - dt_deposit_회사
	if($hr_deposit_name) {
		$get_seq_query = "SELECT TOP 1 seq FROM dt_deposit_".$company_name[$hr_company-1]." ORDER BY seq DESC";
		$get_seq_query_result = mssql_query($get_seq_query);
		$get_seq_row = mssql_fetch_array($get_seq_query_result);
		if($get_seq_row['seq'])	$get_seq = $get_seq_row['seq'] + 1;
		else					$get_seq = 1;

		for($i = 0; $i < sizeof($hr_deposit_name); $i++) {
			if($hr_deposit_amount[$i] == "")	$hr_deposit_amount[$i] = 0;
			$hr_deposit_receiver[$i] = Br_dconv($hr_deposit_receiver[$i]);
			$hr_deposit_bigo[$i] = Br_dconv($hr_deposit_bigo[$i]);
			$deposit_query = "INSERT INTO dt_deposit_".$company_name[$hr_company-1]." ".
							 "(seq, stf_id, kind, unit, amt, received_nm, bigo,  ip_dt) VALUES ".
							 "($get_seq, $hr_code, ".$hr_deposit_name[$i].", '".$hr_deposit_size[$i]."', ".$hr_deposit_amount[$i].", '".$hr_deposit_receiver[$i]."', '".$hr_deposit_bigo[$i]."', GETDATE()) ";
			mssql_query($deposit_query);
			$get_seq++;
		}
	}

	// SEHEDULE DB - hr_stf_schedule
	if($hr_schedule_type) {
		$j = 0;
		for($i = 0; $i < sizeof($hr_schedule_type); $i++) {
			$hr_schedule_bigo[$i] = Br_dconv($hr_schedule_bigo[$i]);
			if($hr_schedule_type[$i] != 1 && $hr_schedule_type[$i] != 2) {
				$end_date = $hr_schedule_sDate[$i];
			} else {
				$end_date = $hr_schedule_eDate[$j];
				$j++;
			}

			$schedule_query = "INSERT INTO hr_stf_schedule ".
							  "(company_cd, id, start_date, end_date, type, bigo, dt) VALUES ".
							  "($hr_company, $hr_code, '".$hr_schedule_sDate[$i]."', '$end_date', ".$hr_schedule_type[$i].", '".$hr_schedule_bigo[$i]."', GETDATE())";
			mssql_query($schedule_query);
		}
	}

	// FILE DB = hr_files
	if($_FILES['hr_file']['name']) {
		$filepath = "upload/hr/".strtoupper($company_name[$hr_company-1])."/";
		for($i = 0; $i < count($_FILES['hr_file']['name']); $i++) {
			if(!($_FILES['hr_file']['error'][$i] > 0)) {
				$temp = $i+1;
				$filename = $hr_code."__".$temp."__".$_FILES['hr_file']['name'][$i];

				$fullpath = $filepath.$filename;

				// 한글 파일명 업로드 에러
				if(move_uploaded_file($_FILES['hr_file']['tmp_name'][$i], $fullpath)) {
					$hr_file_subject[$i] = Br_dconv($hr_file_subject[$i]);
					$hr_file_bigo[$i] = Br_dconv($hr_file_bigo[$i]);
					$file_query = "INSERT INTO hr_files ".
								  "(company_cd, id, seq, subject, bigo, file_name, dt) VALUES ".
								  "($hr_company, $hr_code, ".$temp.", '".$hr_file_subject[$i]."', '".$hr_file_bigo[$i]."', '".$filename."', GETDATE())";
					mssql_query($file_query);
				}
			}
		}
	}
	

	

	//echo ("<script>document.location.href='?page=hr'</script>"); 
}


$company_row = array("T-Brothers Food & Trading Ltd.", "Manna International Ltd.", "Hannam Supermaket Burnaby", "Hannam Supermaket Surrey", "Westview Investment Inc");

$position_query = "SELECT cd, nm FROM hr_position ORDER BY cd";
$position_query_result = mssql_query($position_query);

$title_query = "SELECT cd, nm FROM hr_title ORDER BY cd";
$title_query_result = mssql_query($title_query);

$city_query = "SELECT * FROM ft_city_com ORDER BY cd";
$city_query_result = mssql_query($city_query);

$province_query = "SELECT province_cd, nm, long_nm FROM ft_province_com WHERE country_cd = 0";
$province_query_result = mssql_query($province_query);

// 달력
$todayY = date('Y');
$todayM = date('n');
$todayD = date('j');
$startY = $todayY - 70;
$endY = $todayY + 20;
// 달력

$photo_path = "http://184.69.79.114:8000/photo2/".Br_iconv($row['hnm']).".jpg";
$photo_path2 = "http://184.69.79.114:8000/photo2/".$row['hnm'].".jpg";
?>
<td height="500" align="left" valign="top">
<form id="hr_new" name="hr_new" action="?page=hr&menu=new" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="">
<input type="hidden" name="submitCheck" value="n">
<input type="hidden" name="name_check" value="0">
<input type="hidden" name="code_check" value="0">
	<table width="100%">
		<tr>
			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">인사관리 > 사원 신규등록</td>
						<td align="right" style="padding: 14px;">&nbsp;</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><input type="button" class="doc_submit_btn_style" onClick="return check_before_submit();" value="등록하기"></td>
									<td width="5"></td>
									<td><input type="button" class="doc_submit_btn_style" onClick="location.replace('?page=hr');" value="취소하기"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td height="10"></td>
		</tr>

		<tr><td>
		<div id="css_tabs">
			<input id="tab1" type="radio" name="tab" value="hr1" checked="checked">
			<input id="tab2" type="radio" name="tab" value="hr2">
			<input id="tab3" type="radio" name="tab" value="hr3">
			<input id="tab4" type="radio" name="tab" value="hr4">
			<label for="tab1">신상정보</label>
			<label for="tab2">인사정보</label>
			<label for="tab3">근태정보</label>
			<label for="tab4">업로드파일</label>


			<div class="tab1_content">
				<table>
				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table align="left" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="150" style="font-size:18px; border:0;"><b>인사정보</b></td>
											<td style="border:0;"></td>
											<td width="200" style="border:0;"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>소속회사 <span style="color:red;">*</span></b></td>
											<td class="doc_field_content">
												<select name="hr_company" style="width:90%; max-width:250px;" onChange="get_department_list(this.value)">
													<option value=""> --- 회사 선택 --- </option>
													<? for($i = 0; $i < sizeof($company_row); $i++) { ?>
														<option value="<?=$i+1 ?>"><?=Br_iconv($company_row[$i]); ?></option>
													<? } ?>
												</select>
											</td>
											<td rowspan=7><div id="imagePreview" style="width:100%; height:100%;"></div></td>
											<!--td rowspan=8>
												<iframe id="iframe_image" name="iframe_image" src="http://184.69.79.114:8000/memberlist/groupware_hr/iframe_hr.php" width="200px%" height="100%" frameborder=0></iframe>
											</td-->

											<td style="padding-left:10px;width:139px;" class="doc_field_name"><b>근무상태</b></td>
											<td class="doc_field_content" style="width:655px;">
												<input type="radio" name="hr_status" value="1" checked>근무
												<input type="radio" name="hr_status" value="2">퇴사
											</td>

											


										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직원코드 <span style="color:red;">*</span></b></td>
											<td class="doc_field_content" style="width:500px;">
												<input type="text" name="hr_code" style="width:250px;" onblur="check_employee_code(this.value)" disabled>
												<input type="button" id="employee_list_btn" value="직원리스트" onClick="show_employee_list()">
												<span id="code_check_msg" style="font-weight:bold; color:red; padding-left:10px; font-size:14px;"></span>
											</td>
											
											<td style="padding-left:10px;width:139px;" class="doc_field_name"><b>사원증 지급일자</b></td>
											<td class="doc_field_content" style="width:655px;">
												<input type="radio" name="hr_employeecard" value="1">발급
												<input type="radio" name="hr_employeecard" value="2">미발급
											</td>
											

										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>한글성명 <span style="color:red;">*</span></b></td>
											<td class="doc_field_content">
												<input type="text" name="hr_nameK" style="width:250px;" onblur="check_employee_name(this.value)" disabled>
												<span id="name_check_msg" style="font-weight:bold; color:red; padding-left:8px; font-size:14px;"></span>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>최초입사일</b></td>
											<td class="doc_field_content">
												<select name="hr_FjoinY" style="width:55px;">
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayY) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_FjoinM" style="width:42px;">
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayM) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_FjoinD" style="width:42px;">
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayD) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
											


										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>First Name</b></td>
											<td class="doc_field_content"><input type="text" name="hr_fNameE" style="width:250px;"></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>입사일</b></td>
											<td class="doc_field_content">
												<select name="hr_joinY" style="width:55px;">
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayY) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_joinM" style="width:42px;">
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayM) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_joinD" style="width:42px;">
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayD) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>	
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>Last Name</b></td>
											<td class="doc_field_content"><input type="text" name="hr_lNameE" style="width:250px;"></td>

											<td style="padding-left:10px;" class="doc_field_name" rowspan="5"><b>메모</b></td>
											<td class="doc_field_content" style="padding-right:12px;" rowspan=5><textarea wrap="hard" name="hr_memo" style="width:500px; height:100%; min-height:75px; resize:none;" maxlength="500" onblur="resize(this)"></textarea></td>

										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>소속부서</b></td>
											<td class="doc_field_content">
												<iframe id="department_list_iframe" src="hr_iframe.php?mode=department_list&comp=0" height="29" frameborder=0 scrolling="no"></iframe>
												<input type="hidden" name="hr_department">
											</td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직급</b></td>
											<td class="doc_field_content">
												<select name="hr_position" style="width:90%; max-width:250px;">
													<option value=""> --- 직급 선택 --- </option>
													<? while($position_row = mssql_fetch_array($position_query_result)) { ?>
														<option value="<?=$position_row['cd']; ?>"><?=Br_iconv($position_row['nm']); ?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직책</b></td>
											<td class="doc_field_content">
												<select name="hr_title" style="width:90%; max-width:250px;">
													<option value=""> --- 직책 선택 --- </option>
													<? while($title_row = mssql_fetch_array($title_query_result)) { ?>
														<option value="<?=$title_row['cd']; ?>"><?=Br_iconv($title_row['nm']); ?></option>
													<? } ?>
												</select>
											</td>
											<td class="doc_field_content">
												<? $file_headers = @get_headers($photo_path2); ?>
												<? if($file_headers[0] == 'HTTP/1.1 404 Not Found') { ?>
													<input id="image" type="file" name="hr_image" style="width:176;" onchange="InputImage();">
<!--													<input id="image_del" type="button" style="width:176; display:none;" value="삭제2" onClick="RemoveImage();"> -->
													<input id="image_up" type="button" style="width:176;" value="UPLOAD" onClick="Image_upload();">
												<? } else { ?>
													<input id="image" type="file" name="hr_image" style="width:176; display:none;" onchange="InputImage();">
													<input id="image_del" type="button" style="width:176;" value="삭제" onClick="RemoveImage();">
												<? } ?>
											</td>
											<!--
											<td class="doc_field_content">
												<input id="image" type="file" name="hr_image" style="width:176;" onchange="InputImage();">
												<input id="image_del" type="button" style="width:176; display:none;" value="삭제" onClick="RemoveImage();">
											</td>
											-->
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>개인정보</b></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>생년월일</b></td>
											<td class="doc_field_content" colspan=5>
												<select name="hr_birthY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $todayY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>"><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_birthM" style="width:42px;">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>"><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_birthD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>"><?=$i; ?></option>
													<? } ?>
												</select>
												<input type="radio" name="hr_birth_type" value="0" checked>양력
												<input type="radio" name="hr_birth_type" value="1">음력
											</td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>연락처 (Home)</b></td>
											<td class="doc_field_content">
												<input type="text" name="hr_tel11" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)'> - 
												<input type="text" name="hr_tel12" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)'> -
												<input type="text" name="hr_tel13" style="width:40px; text-align:right;" maxlength="4" onkeydown='return onlyNumber(event)'>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>연락처 (Cell)</b></td>
											<td class="doc_field_content">
												<input type="text" name="hr_tel21" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)'> - 
												<input type="text" name="hr_tel22" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)'> -
												<input type="text" name="hr_tel23" style="width:40px; text-align:right;" maxlength="4" onkeydown='return onlyNumber(event)'>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>E-mail</b></td>
											<td class="doc_field_content"><input type="email" name="hr_email" style="width:90%; max-width:300px;"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;"" class="doc_field_name"><b>Address</b></td>
											<td class="doc_field_content" colspan=5><input type="text" name="hr_street" style="width:500px;"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>City</b></td>
											<td class="doc_field_content">
												<select id="hr_city" name="hr_city" style="width:90%; max-width:300px;">
													<? while($city_row = mssql_fetch_array($city_query_result)) { ?>
														<option value="<?=$city_row['cd']; ?>" <?=(($city_row['cd'] == 0) ? "selected" : "" ); ?>><?=$city_row['nm']; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>Province</b></td>
											<td class="doc_field_content">
												<select name="hr_province" style="width:90%; max-width:300px;">
													<option value=""> --- Province 선택 --- </option>
													<? while($province_row = mssql_fetch_array($province_query_result)) { ?>
														<option value="<?=$province_row['province_cd']; ?>" <?=(($province_row['province_cd'] == 2) ? "selected" : "" ); ?>><?=$province_row['nm']; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>Postal Code</b></td>
											<td class="doc_field_content">
												<input type="text" name="hr_postalCode1" style="width:40px; text-align:right;" maxlength="3">&nbsp;
												<input type="text" name="hr_postalCode2" style="width:40px; text-align:right;" maxlength="3">
											</td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>비자상태</b></td>
											<td class="doc_field_content">
												<input type="radio" name="hr_visaStatus" value="0" checked>시민
												<input type="radio" name="hr_visaStatus" value="1">영주
												<input type="radio" name="hr_visaStatus" value="2">비자
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>비자만료일</b></td>
											<td class="doc_field_content">
												<select name="hr_visaY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>"><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_visaM" style="width:42px;">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>"><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_visaD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>"><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>SIN</b></td>
											<td class="doc_field_content">
												<input type="text" name="hr_sin1" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)'>&nbsp; 
												<input type="text" name="hr_sin2" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)'>&nbsp;
												<input type="text" name="hr_sin3" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<!--tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>추가정보</b></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>근무상태</b></td>
											<td class="doc_field_content">
												<input type="radio" name="hr_status" value="1" checked>근무
												<input type="radio" name="hr_status" value="2">퇴사
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>최초입사일</b></td>
											<td class="doc_field_content">
												<select name="hr_FjoinY" style="width:55px;">
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayY) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_FjoinM" style="width:42px;">
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayM) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_FjoinD" style="width:42px;">
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayD) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>입사일</b></td>
											<td class="doc_field_content">
												<select name="hr_joinY" style="width:55px;">
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayY) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_joinM" style="width:42px;">
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayM) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_joinD" style="width:42px;">
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $todayD) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr class="doc_border" height="80">
											<td style="padding-left:10px;" class="doc_field_name"><b>메모</b></td>
											<td class="doc_field_content" style="padding-right:12px;" colspan=5><textarea wrap="hard" name="hr_memo" style="width:500px; height:100%; min-height:75px; resize:none;" maxlength="500" onblur="resize(this)"></textarea></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr-->
				</table>
			</div>

			<div class="tab2_content">
			<table>
				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>부서</b></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
										</tr>
										<tr>
											<td colspan="6">
												<table width="300px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td class="doc_field_name" align="center"><b>부서</b></td>
													</tr>
													<tr class="doc_border" height="20">
														<td align="center" colspan=2><b>신상정보에서 입력</b></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" class="doc_wrapper">
						<table>
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>직위/직책</b></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
											<td width="100" style="border:0;"></td>
											<td style="border:0;"></td>
										</tr>
										<tr>
											<td colspan="6">
												<table width="300px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td class="doc_field_name" align="center"><b>직위</b></td>
													</tr>
													<tr class="doc_border" height="20">
														<td align="center" colspan=2><b>신상정보에서 입력</b></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" class="doc_wrapper">
						<table>
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<?
										$wage_type = "시급_월급";
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>급여</b></td>
											<td width="460" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="wage_add('<?=$wage_type; ?>');"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_wage" width="560px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td width="60" class="doc_field_name" align="center"><b>Type</b></td>
														<td width="100" class="doc_field_name" align="center"><b>Wage</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" class="doc_wrapper">
						<table>
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<?
										$point_query = "SELECT nm FROM ft_reward_gubun_com ORDER BY cd";
										$point_query_result = mssql_query($point_query);
										$point_query_num = mssql_num_rows($point_query_result);

										while($point_row = mssql_fetch_array($point_query_result)) {
											$point_query_num--;
											if($point_query_num == 0)	$point_type = $point_type.Br_iconv($point_row['nm']);
											else						$point_type = $point_type.Br_iconv($point_row['nm'])."_";
										}
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>상/벌점</b></td>
											<td width="500" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="point_add('<?=$point_type; ?>');"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_point" width="600px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td width="60" class="doc_field_name" align="center"><b>내용</b></td>
														<td width="80" class="doc_field_name" align="center"><b>포인트</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" class="doc_wrapper">
						<table>
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<?
										$deposit_query = "SELECT cd, nm FROM ft_deposit_kind_com ORDER BY cd";
										$deposit_query_result = mssql_query($deposit_query);
										$deposit_query_num = mssql_num_rows($deposit_query_result);

										while($deposit_row = mssql_fetch_array($deposit_query_result)) {
											$deposit_query_num--;
											if($deposit_query_num == 0) {
												$deposit_cd = $deposit_cd.$deposit_row['cd'];
												$deposit_nm = $deposit_nm.Br_iconv($deposit_row['nm']);
											} else {
												$deposit_cd = $deposit_cd.$deposit_row['cd']."_";
												$deposit_nm = $deposit_nm.Br_iconv($deposit_row['nm'])."_";
											}
										}
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>직원디파짓</b></td>
											<td width="900" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="deposit_add('<?=$deposit_cd; ?>', '<?=$deposit_nm; ?>');"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_deposit" width="1000" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="200" class="doc_field_name" align="center"><b>디파짓 물품명</b></td>
														<td width="50" class="doc_field_name" align="center"><b>Size</b></td>
														<td width="100" class="doc_field_name" align="center"><b>Amount</b></td>
														<td width="100" class="doc_field_name" align="center"><b>받은사람</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>

			<div class="tab3_content">
			<table>
				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<?
										$schedule_type = "유급휴가_무급휴가_반차_조퇴_지각_결근";
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>근태정보</b></td>
											<td width="900" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="schedule_add('<?=$schedule_type; ?>')"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_schedule" width="1000" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="100" class="doc_field_name" align="center"><b>Type</b></td>
														<td width="120" class="doc_field_name" align="center"><b>Start Date</b></td>
														<td width="120" class="doc_field_name" align="center"><b>End Date</b></td>
														<td width="50" class="doc_field_name" align="center"><b>Day</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>

			<div class="tab4_content">
			<table>
				<tr>
					<td align="center" class="doc_wrapper">
						<table width="100%">
							<tr>
								<td align="center" valign="top">
									<table width="100%" style="table-layout:fixed;">
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>업로드 파일</b></td>
											<td width="700" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="file_add()"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_file" width="800" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="250" class="doc_field_name" align="center"><b>Subject</b></td>
														<td width="200" class="doc_field_name" align="center"><b>Upload</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			</div>
		</div>
		</td></tr>

		<tr>
			<td height="30" class="doc_submit_area">
				<table width="100%">
					<tr>
						<td align="right" style="padding: 0 12px 0 0;">
							<table>
								<tr>
									<td><input type="button" class="doc_submit_btn_style" onClick="return check_before_submit();" value="등록하기"></td>
									<td width="5"></td>
									<td><input type="button" class="doc_submit_btn_style" onClick="location.replace('?page=hr');" value="취소하기"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="30"></td>
		</tr>
	</table>
</form>
</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<div id="employee_list_div" style="border:2px #666666 solid; background-color:#ffffff; position:absolute; z-index:10; display:none; width:300px; height:400px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="middle" style="padding:5 0 5 20px; background-color:#F6CECE;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td style="letter-spacing:-1px;"><b>직원리스트</b></td>
						<td width="22" align="left"><img src="css/img/bt_closelayer.gif" style="cursor:pointer;" onClick="jQuery('#employee_list_div').hide()"></td>
					</tr>
				</table>
			</td>
		</tr>

		<tr>
			<td><iframe id="employee_list_iframe" width="100%" height="368px" frameborder=0></iframe></td>
		</tr>
	</table>
</div>
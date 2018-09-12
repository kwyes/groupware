<script>
var tabchked = document.querySelector('input[name="tab"]:checked').value;
document.getElementById('tabchked').value=tabchked;
function check_employee_name(name) {
	var company = document.forms.hr_new.hr_company.value;
	var current_name = document.forms.hr_new.hr_nameK_current.value;

	if (!name) { 
		document.getElementById("name_check_msg").innerHTML = "";
		return;
	} else if(current_name == name) {
		document.getElementById("name_check_msg").innerHTML = "현재 직원이름";
		document.forms.hr_new.name_check.value = 1;
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
				document.forms.hr_new.name_check.value = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "http://184.70.148.122/hr_iframe.php?mode=employee_name&comp=" + company + "&name=" + name, true);
		xmlhttp.send();
	}
}
function selectfunction(){
	var select = document.getElementById("myselect").value;
	if(select == "123"){
		document.getElementById("myselect").style.display = "none";
		document.getElementById("mytext").style.display = "";
	}
	
}
function check_employee_code(code) {
	var company = document.forms.hr_new.hr_company.value;
	var current_code = document.forms.hr_new.hr_code_current.value;

	if (!code) { 
		document.getElementById("code_check_msg").innerHTML = "";
		return;
	} else if(current_code == code) {
		document.getElementById("code_check_msg").innerHTML = "현재 직원코드";
		document.forms.hr_new.code_check.value = 1;
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
				document.forms.hr_new.code_check.value = xmlhttp.responseText;
			}
		}
		xmlhttp.open("GET", "http://184.70.148.122/hr_iframe.php?mode=employee_code&comp=" + company + "&code=" + code, true);
		xmlhttp.send();
	}
}

function show_employee_list() {
	var company = document.forms.hr_new.hr_company.value;

	var pos = document.getElementById("employee_list_btn").getBoundingClientRect();
	document.getElementById("employee_list_div").style.left = pos.left + 81 + "px";
	document.getElementById("employee_list_div").style.top = pos.top + 21 + "px";

	document.getElementById("employee_list_iframe").src = "http://group.t-brothers.com//hr_iframe.php?mode=employee_list&comp=" + company;
	document.getElementById("employee_list_div").style.display = "";
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
function depart_add(depart_cd, depart_nm) {
	depart_cd = depart_cd.split("_");
	depart_nm = depart_nm.split("_");

	var table = document.getElementById("table_depart");
	var row_num = table.getElementsByTagName("tr").length;
	var row = table.insertRow(row_num);
	row.className = "doc_border";
	row.style.height = "20px";

	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);

	cell0.style.textAlign = "center";
	cell0.innerHTML = "<input name='hr_depart_date[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this)'>";

	cell1.style.textAlign = "center";
	cell1.innerHTML = "<select name='hr_depart[]' style='width:100%; font-size:15px;'>";
	for(var i = 0; i < depart_nm.length; i++) {
		var option = document.createElement("option");
		option.text = depart_nm[i];
		option.value = depart_cd[i];
		cell1.childNodes[0].add(option);
	}

	cell2.style.textAlign = "center";
	cell2.style.paddingTop = "2px";
	cell2.innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\"table_depart\", " + row_num + ");'>X</span>";
}

function posiTitle_add(posi_cd, posi_nm, title_cd, title_nm) {
	posi_cd = posi_cd.split("_");
	posi_nm = posi_nm.split("_");
	title_cd = title_cd.split("_");
	title_nm = title_nm.split("_");

	var table = document.getElementById("table_posiTitle");
	var row_num = table.getElementsByTagName("tr").length;
	var row = table.insertRow(row_num);
	row.className = "doc_border";
	row.style.height = "20px";

	var cell0 = row.insertCell(0);
	var cell1 = row.insertCell(1);
	var cell2 = row.insertCell(2);
	var cell3 = row.insertCell(3);

	cell0.style.textAlign = "center";
	cell0.innerHTML = "<input name='hr_title_date[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this)'>";

	cell1.style.textAlign = "center";
	cell1.innerHTML = "<select name='hr_position[]' style='width:100%; font-size:15px;'>";
	for(var i = 0; i < posi_nm.length; i++) {
		var option = document.createElement("option");
		option.text = posi_nm[i];
		option.value = posi_cd[i];
		cell1.childNodes[0].add(option);
	}

	cell2.style.textAlign = "center";
	cell2.innerHTML = "<select name='hr_title[]' style='width:100%; font-size:15px;'>";
	for(var i = 0; i < title_nm.length; i++) {
		var option = document.createElement("option");
		option.text = title_nm[i];
		option.value = title_cd[i];
		cell2.childNodes[0].add(option);
	}

	cell3.style.textAlign = "center";
	cell3.style.paddingTop = "2px";
	cell3.innerHTML = "<span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row(\"table_posiTitle\", " + row_num + ");'>X</span>";
}

function wage_add(wage_type) {
	wage_type = wage_type.split("_");

	var table = document.getElementById("table_wage");
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
	cell0.innerHTML = "<input name='hr_wage_date[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this)'>";

	cell1.style.textAlign = "center";
	cell1.innerHTML = "<select name='hr_wage_type[]' style='width:100%; font-size:15px;'>";
	for(var i = 0; i < wage_type.length; i++) {
		var option = document.createElement("option");
		option.text = wage_type[i];
		option.value = i;
		cell1.childNodes[0].add(option);
	}

	cell2.style.textAlign = "center";
	cell2.innerHTML = "<input name='hr_wage[]' type='text' style='width:100%; text-align:right;' required>";

	cell3.style.textAlign = "center";
	cell3.innerHTML = "<input name='hr_wage_bigo[]' type='text' style='width:100%; text-align:left;' required>";

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
	cell2.innerHTML = "<input name='hr_point_reward[]' type='text' style='width:100%; text-align:center;' required>";

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
	cell1.innerHTML = "<input name='hr_deposit_size[]' type='text' style='width:100%; text-align:center;' required>";

	cell2.style.textAlign = "center";
	cell2.innerHTML = "<input name='hr_deposit_amount[]' type='text' style='width:100%; text-align:center;' required>";

	cell3.style.textAlign = "center";
	cell3.innerHTML = "<input name='hr_deposit_receiver[]' type='text' style='width:100%; text-align:center;' required>";

	cell4.style.textAlign = "center";
	cell4.innerHTML = "<input name='hr_deposit_bigo[]' type='text' style='width:100%; text-align:leftk;'>";

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
		
		sDateArray = sDate.split("-");
		sDateObj = new Date(sDateArray[0], Number(sDateArray[1])-1, sDateArray[2]);
		eDateArray = eDate.split("-");
		eDateObj = new Date(eDateArray[0], Number(eDateArray[1])-1, eDateArray[2]);
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
	//cell0.innerHTML = "<input name='hr_file_subject[]' type='text' style='width:100%; text-align:left;'>";
	cell0.innerHTML = "<select id='myselect' name='hr_file_subject[]' style='width:100%; text-align:center;' onchange='selectfunction()'><option value=''>선택</option><option value='고용계약서'>고용계약서</option><option value='비밀협정서'>비밀협정서</option><option value='경업금지협약서'>경업금지협약서</option><option value='Void Check'>Void Check</option><option value='ID'>ID</option></select><input id='mytext' type='text' name='directinput' style='width:100%; text-align:left; display: none;'>";
	

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

	if(table_name == "table_file") {
		var temp = document.forms.hr_new.hr_file_del.value;

		if(temp) {
			var temp2 = temp.split("_").length;
			document.forms.hr_new.hr_file_del.value = temp + "_" + (seq+temp2);
		} else {
			document.forms.hr_new.hr_file_del.value = seq;
		}
	}
}

function check_before_submit() {
	var target = document.forms.hr_new;

	if(target.name_check.value == 0) {
		alert("직원코드 오류");
		return false;
	}
	if(target.code_check.value == 0) {
		alert("한글성명 오류");
		return false;
	}

	var answer = confirm("수정 하시겠습니까?");
	if(answer) {
		//target.hr_department.value = ($('#department_list_iframe').contents().find('#hr_department').val());
		target.submit();
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
	var current_image = document.getElementById("cur_image");
	var preview_div = document.getElementById("imagePreview");
	if(current_image) {
		preview_div.removeChild(preview_div.firstChild);
	} else {
		preview_div.firstChild.src = "";
	}

	document.getElementById("image_del").style.display = "none";
	document.getElementById("image").value = "";
	document.getElementById("image").style.display = "";
}

function Image_upload() {
	newWindow=window.open("http://184.69.79.114:8000/memberlist/photoUpload.php"
, "imageupload", "scrollbars=yes,toolbar=yes,resizable=yes,width=500,height=300,left=0,top=0'");
}
</script>

<?
include_once "includes/db_configms_HN.php";

$mode = ($_GET['mode']) ? $_GET['mode'] : $_POST['mode'];
$tab = ($_GET['tab']) ? $_GET['tab'] : $_POST['tab'];


if($mode == "modify") {
	$hr_company = $_POST['hr_company'];
	$hr_code = $_POST['hr_code'];	//stf - id
	$hr_code_current = $_POST['hr_code_current'];
	$hr_nameK = $_POST['hr_nameK'];	//stf - hnm
	$hr_nameK_current = $_POST['hr_nameK_current'];
	$hr_fNameE = $_POST['hr_fNameE'];	$hr_lNameE = $_POST['hr_lNameE'];	//stf - last_nm & first_nm

	if($_POST['hr_birthY'] && $_POST['hr_birthM'] && $_POST['hr_birthD']) {
		$hr_birth = $_POST['hr_birthY']."-".$_POST['hr_birthM']."-".$_POST['hr_birthD'];
		$hr_this_birth = date(Y)."-".$_POST['hr_birthM']."-".$_POST['hr_birthD'];
	} else {
		$hr_birth = null;
		$hr_this_birth = null;
	}
	$hr_birth_gubun = $_POST['hr_birth_type'];	//stf - birth_gubun
	$hr_tel11 = $_POST['hr_tel11'];		$hr_tel12 = $_POST['hr_tel12'];		$hr_tel13 = $_POST['hr_tel13'];		//stf - tel1 (3)
	$hr_tel21 = $_POST['hr_tel21'];		$hr_tel22 = $_POST['hr_tel22'];		$hr_tel23 = $_POST['hr_tel23'];		//stf - tel2 (3)
	$hr_email = $_POST['hr_email'];	//stf - email
	$hr_street = $_POST['hr_street'];	$hr_city = $_POST['hr_city'];		$hr_province = $_POST['hr_province'];	//stf - (2)
	if($_POST['hr_postalCode1'] && $_POST['hr_postalCode2'])	$hr_postalCode = $_POST['hr_postalCode1']." ".$_POST['hr_postalCode2'];
	else	$hr_postalCode = null;
	$hr_visaStatus = $_POST['hr_visaStatus'];	//stf - p_status
	if($_POST['hr_visaY'] && $_POST['hr_visaM'] && $_POST['hr_visaD'])	$hr_visa = $_POST['hr_visaY']."-".$_POST['hr_visaM']."-".$_POST['hr_visaD'];
	else	$hr_visa = null;
	$hr_sin1 = $_POST['hr_sin1'];		$hr_sin2 = $_POST['hr_sin2'];		$hr_sin3 = $_POST['hr_sin3'];	//stf - sin (3)
	$hr_status = $_POST['hr_status'];

	$hr_employeecard = $_POST['hr_employeecard'];
	if($hr_employeecard == 1){
		$hr_employeecard =  "GETDATE()"; 
	}
	else{
		$hr_employeecard =  "NULL"; 
	}

	if($_POST['hr_FjoinY'] && $_POST['hr_FjoinM'] && $_POST['hr_FjoinD'])	$hr_Fjoin = $_POST['hr_FjoinY']."-".$_POST['hr_FjoinM']."-".$_POST['hr_FjoinD'];
	else	$hr_Fjoin = null;
	if($_POST['hr_joinY'] && $_POST['hr_joinM'] && $_POST['hr_joinD'])	$hr_join = $_POST['hr_joinY']."-".$_POST['hr_joinM']."-".$_POST['hr_joinD'];
	else	$hr_join = null;
	if($_POST['hr_resignY'] && $_POST['hr_resignM'] && $_POST['hr_resignD'])	$hr_resign = $_POST['hr_resignY']."-".$_POST['hr_resignM']."-".$_POST['hr_resignD'];
	else	$hr_resign = null;
	$hr_memo = $_POST['hr_memo'];	//stf - bigo
	
	$hr_payroll_cd = 1;
	$hr_stf_company = ($hr_company == 3) ? 10 : 60 ;
	$hr_seq = $_POST['hr_seq'];
	$hr_upid = $_SESSION['hr_code'];

	$hr_depart_date = $_POST['hr_depart_date'];
	$hr_depart = $_POST['hr_depart'];
	$hr_title_date = $_POST['hr_title_date'];
	$hr_position = $_POST['hr_position'];
	$hr_title = $_POST['hr_title'];
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
	
	/*if($_POST['hr_file_subject'] == '123'){
		$hr_file_subject = $_POST['directinput'];
	}
	else{
		$hr_file_subject = $_POST['hr_file_subject'];
	}*/

	$hr_file = $_POST['hr_file'];
	$hr_file_bigo = $_POST['hr_file_bigo'];
	$hr_file_del = $_POST['hr_file_del'];


	//stf - company			bby-10, sry/tb/manna/wvi-60
	//stf - void_chq_dt
	//stf - resume_dt
	//stf - up_id (수정자)
	//stf - up_dt (수정날짜)
	//stf - ipsawon (입력자)
	//stf - ipdt (입력날짜)

	/*
	echo "hr_company - ".$hr_company."<br>";
	echo "hr_code - ".$hr_code."<br>";
	echo "hr_code_current - ".$hr_code_current."<br>";
	echo "hr_nameK - ".$hr_nameK."<br>";
	echo "hr_nameK_current - ".$hr_nameK_current."<br>";
	echo "hr_fNameE - ".$hr_fNameE."<br>";
	echo "hr_lNameE - ".$hr_lNameE."<br>";

	echo "hr_birth - ".$hr_birth."<br>";
	echo "hr_this_birth - ".$hr_this_birth."<br>";
	echo "hr_birth_gubun - ".$hr_birth_gubun."<br>";
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
	$stf_query = "UPDATE dt_stf_".$company_name[$hr_company-1]." SET ".
				  "id = $hr_code, hnm = '$hr_nameK', last_nm = '$hr_lNameE', first_nm = '$hr_fNameE', ".
				  "sin1 = '$hr_sin1', sin2 = '$hr_sin2', sin3 = '$hr_sin3', tel11 = '$hr_tel11', tel12 = '$hr_tel12', tel13 = '$hr_tel13', tel21 = '$hr_tel21', tel22 = '$hr_tel22', tel23 = '$hr_tel23', ".
				  "street = '$hr_street', city = $hr_city, province = $hr_province, p_status = $hr_visaStatus, birth_gubun = $hr_birth_gubun, ".
				  "payroll_cd = $hr_payroll_cd, company = $hr_stf_company, email = '$hr_email', bigo = '$hr_memo', seq = $hr_seq, up_dt = GETDATE(), employeecard_dt = $hr_employeecard, ".
				  (($hr_visa) ? "visa_dt = '$hr_visa'" : "visa_dt = NULL" ).", ".
				  (($hr_Fjoin) ? "ipsa_sdt = '$hr_Fjoin'" : "ipsa_sdt = NULL" ).", ".
				  (($hr_join) ? "ipsa_dt = '$hr_join'" : "ipsa_dt = NULL" ).", ".
				  (($hr_resign) ? "term_dt = '$hr_resign'" : "term_dt = NULL" ).", ".
				  (($hr_birth) ? "birth_dt = '$hr_birth'" : "birth_dt = NULL" ).", ".
				  (($hr_this_birth) ? "this_birth_dt = '$hr_this_birth'" : "this_birth_dt = NULL" ).", ".
				  (($hr_postalCode) ? "postal_cd = '$hr_postalCode'" : "postal_cd = NULL" )." ".
				  "WHERE id = $hr_code_current";
	mssql_query($stf_query);

	// DEPARTMENT DB - dt_trans_buseo_회사
	
	if($hr_depart) {
		$depart_query = "DELETE FROM dt_trans_buseo_".$company_name[$hr_company-1]." WHERE id = $hr_code_current";
		mssql_query($depart_query);
		for($i = 0; $i < sizeof($hr_depart); $i++) {
			$depart_query = "INSERT INTO dt_trans_buseo_".$company_name[$hr_company-1]." ".
							"(id, dt, company, buseo, ipdt) VALUES ".
							"($hr_code, '".$hr_depart_date[$i]."', ".$company_code[$hr_company-1].", ".$hr_depart[$i].", GETDATE())";
			mssql_query($depart_query);
		}
	}

	// POSITION, TITLE DB - hr_stf_position
	if($hr_position && $hr_title) {
		$posiTitle_query = "DELETE FROM hr_stf_position WHERE company_cd = $hr_company AND id = $hr_code ";
		mssql_query($posiTitle_query);
		$get_seq = 1;
		for($i = 0; $i < sizeof($hr_title_date); $i++) {
			$posiTitle_query = "INSERT INTO hr_stf_position ".
							   "(company_cd, id, seq, hr_position, hr_title, dt) VALUES ".
							   "($hr_company, $hr_code, $get_seq, ".$hr_position[$i].", ".$hr_title[$i].", '".$hr_title_date[$i]."')";
			mssql_query($posiTitle_query);
			$get_seq++;
		}
	}

	// WAGE DB - dt_trans_wage_회사
	if($hr_wage_date) {
		$wage_query = "DELETE FROM dt_trans_wage_".$company_name[$hr_company-1]." WHERE id = $hr_code_current";
		mssql_query($wage_query);
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
	}

	// REWARD DB - dt_stf_reward
	if($hr_point_date) {
		$company_sName = strtoupper($company_name[$hr_company-1]);
		$point_query = "DELETE FROM dt_stf_reward WHERE company = '$company_sName' AND id = $hr_code_current";
		mssql_query($point_query);
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
	}

	// DEPOSIT DB - dt_deposit_회사
	if($hr_deposit_name) {
		$deposit_query = "DELETE FROM dt_deposit_".$company_name[$hr_company-1]." WHERE stf_id = $hr_code_current";
		mssql_query($deposit_query);

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
							 "(seq, stf_id, kind, unit, amt, received_nm, bigo, ip_dt) VALUES ".
							 "($get_seq, $hr_code, ".$hr_deposit_name[$i].", '".$hr_deposit_size[$i]."', ".$hr_deposit_amount[$i].", '".$hr_deposit_receiver[$i]."', '".$hr_deposit_bigo[$i]."',  GETDATE()) ";
			mssql_query($deposit_query);
			$get_seq++;
		}
	}

	// SEHEDULE DB - hr_stf_schedule
	if($hr_schedule_type) {
		$schedule_query = "DELETE FROM hr_stf_schedule WHERE company_cd = $hr_company and id = $hr_code";
		mssql_query($schedule_query);
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

	// FILE DB = hr_files (수정해야함)
	if($hr_file_del) {
		$del_file_seq = explode("_", $hr_file_del);
		$filepath = "upload/hr/".strtoupper($company_name[$hr_company-1])."/";
		for($i = 0; $i < sizeof($del_file_seq); $i++) {
			$file_query = "SELECT file_name FROM hr_files WHERE company_cd = $hr_company AND id = $hr_code AND seq = ".$del_file_seq[$i];
			$file_query_result = mssql_query($file_query);
			$file_row = mssql_fetch_array($file_query_result);
			$fullpath = $filepath.$file_row['file_name'];

			unlink($fullpath);

			$file_del_query = "DELETE FROM hr_files WHERE company_cd = $hr_company AND id = $hr_code AND seq = ".$del_file_seq[$i];
			mssql_query($file_del_query);
		}
	}

	if($_FILES['hr_file']['name']) {
		$get_seq = "SELECT TOP 1 seq FROM hr_files WHERE company_cd = $hr_company AND id = $hr_code ORDER BY seq DESC";
		$get_seq_result = mssql_query($get_seq);
		$get_seq_row = mssql_fetch_array($get_seq_result);
		$file_seq = $get_seq_row['seq'] + 1;

		$filePath = "upload/hr/".strtoupper($company_name[$hr_company-1])."/";
		for($i = 0; $i < count($_FILES['hr_file']['name']); $i++) {
			if(!($_FILES['hr_file']['error'][$i] > 0)) {
				$fileName = $hr_code."__".$file_seq."__".$_FILES['hr_file']['name'][$i];
				$fileName = Br_dconv($fileName);
				$fullPath = $filePath.$fileName;

				// 한글 파일명 업로드 에러
				if(move_uploaded_file($_FILES['hr_file']['tmp_name'][$i], $fullPath)) {
					$hr_file_subject[$i] = Br_dconv($hr_file_subject[$i]);
					$hr_file_bigo[$i] = Br_dconv($hr_file_bigo[$i]);
					$file_query = "INSERT INTO hr_files ".
								  "(company_cd, id, seq, subject, bigo, file_name, dt) VALUES ".
								  "($hr_company, $hr_code, ".$file_seq.", '".$hr_file_subject[$i]."','".$hr_file_bigo[$i]."', '".$fileName."', GETDATE())";
					mssql_query($file_query);
				}
				$file_seq++;
			}
		}
	}

	if($_FILES['hr_image']['name']) {
		$url = "http://184.69.79.114:8000/memberlist/view_counter/photoUpload.php";
		header("location: ".$url);
	}

	$id = $hr_code;
	$company = $hr_company;
} else {
	$id = ($_GET['id']) ? $_GET['id'] : $_POST['id'];
	$company = ($_GET['company']) ? $_GET['company'] : $_POST['company'];
}

switch ($company) {
	default: 
		$tablename = "dt_stf_tb";
		$tablename2 = "dt_trans_buseo_tb";
		$tablename3 = "ft_buseo_tb";
		$dt_trans_position = "dt_trans_position_tb";
		$company_name = "T-Brothers Food & Trading Ltd.";
		$company_Sname = "TB";
		$wage_table = "dt_trans_wage_tb";
		$deposit_table = "dt_deposit_tb";
		break;
	case "1" : 
		$tablename = "dt_stf_tb";
		$tablename2 = "dt_trans_buseo_tb";
		$tablename3 = "ft_buseo_tb";
		$dt_trans_position = "dt_trans_position_tb";
		$company_name = "T-Brothers Food & Trading Ltd.";
		$company_Sname = "TB";
		$wage_table = "dt_trans_wage_tb";
		$deposit_table = "dt_deposit_tb";
		break;
	case "2" : 
		$tablename = "dt_stf_manna";
		$tablename2 = "dt_trans_buseo_manna";
		$tablename3 = "ft_buseo_manna";
		$dt_trans_position = "dt_trans_position_manna";
		$company_name = "Manna International Ltd.";
		$company_Sname = "MANNA";
		$wage_table = "dt_trans_wage_manna";
		$deposit_table = "dt_deposit_manna";
		break;
	case "3" : 
		$tablename = "dt_stf_bby";
		$tablename2 = "dt_trans_buseo_bby";
		$tablename3 = "ft_buseo_bby";
		$dt_trans_position = "dt_trans_position_bby";
		$company_name = "Hannam Supermaket Burnaby";
		$company_Sname = "BBY";
		$wage_table = "dt_trans_wage_bby";
		$deposit_table = "dt_deposit_bby";
		break;
	case "4" :
		$tablename = "dt_stf_sry";
		$tablename2 = "dt_trans_buseo_sry";
		$tablename3 = "ft_buseo_sry";
		$dt_trans_position = "dt_trans_position_sry";
		$company_name = "Hannam Supermaket Surrey";
		$company_Sname = "SRY";
		$wage_table = "dt_trans_wage_sry";
		$deposit_table = "dt_deposit_sry";
		break;
	case "5" : 
		$tablename = "dt_stf_wv";
		$tablename2 = "dt_trans_buseo_wv";
		$tablename3 = "ft_buseo_wv";
		$dt_trans_position = "dt_trans_position_wv";
		$company_name = "Westview Investment Inc";
		$company_Sname = "WV";
		$wage_table = "dt_trans_wage_wv";
		$deposit_table = "dt_deposit_wv";
		break;
}
$tablename4 = "ft_city_com";
$tablename5 = "ft_province_com";
$ft_position_com = "ft_position_com";

$query = "SELECT *, CONVERT(char(10), birth_dt, 126) AS birth_dt, CONVERT(char(10), visa_dt, 126) AS visa_dt, CONVERT(char(10), ipsa_dt, 126) AS ipsa_dt, CONVERT(char(10), term_dt, 126) AS term_dt, CONVERT(char(10), employeecard_dt, 126) AS employeecard_dt ".
		 "FROM $tablename ".
		 "WHERE id = $id ";
$query_result = mssql_query($query) or die ('Database connetion failed');
$row = mssql_fetch_array($query_result);

$hr_depart_query = "select nm from $tablename3 where cd IN (select top 1 buseo from $tablename2 where id = ".$row['id']." order by ipdt desc)";
$hr_depart_query_result = mssql_query($hr_depart_query) or die ('Database connetion failed');
$hr_depart_row = mssql_fetch_array($hr_depart_query_result);

$hr_position_query = "SELECT nm FROM hr_position WHERE cd = (SELECT TOP 1 hr_position FROM hr_stf_position WHERE company_cd = $company AND id = ".$row['id']." ORDER BY dt DESC)";
$hr_position_query_result = mssql_query($hr_position_query);
$hr_position_row = mssql_fetch_array($hr_position_query_result);

$hr_title_query = "SELECT nm FROM hr_title WHERE cd = (SELECT TOP 1 hr_title FROM hr_stf_position WHERE company_cd = $company AND id = ".$row['id']." ORDER BY dt DESC)";
$hr_title_query_result = mssql_query($hr_title_query);
$hr_title_row = mssql_fetch_array($hr_title_query_result);

$hr_city_query = "select nm from $tablename4 where cd = ".$row['city'];
$hr_city_query_result = mssql_query($hr_city_query) or die ('Database connetion failed');
$hr_city_row = mssql_fetch_array($hr_city_query_result);

$hr_province_query = "select nm, long_nm from $tablename5 where country_cd = 0 AND province_cd = ".$row['province'];
$hr_province_query_result = mssql_query($hr_province_query) or die ('Database connetion failed');
$hr_province_row = mssql_fetch_array($hr_province_query_result);

$photo_path = "http://184.69.79.114:8000/photo2/".Br_iconv($row['hnm']).".jpg";
$photo_path2 = "http://184.69.79.114:8000/photo2/".$row['hnm'].".jpg";

// 달력
$todayY = date('Y');
$todayM = date('m');
$todayD = date('d');
$startY = $todayY - 70;
$endY = $todayY + 20;
// 달력
?>

<td height="500" align="left" valign="top">
<form name="hr_new" action="?page=hr&menu=modify" enctype="multipart/form-data" method="post" accept-charset="utf-8">
<input type="hidden" name="mode" value="modify">
<input type="hidden" name="hr_company" value="<?=$company; ?>">
<input type="hidden" name="name_check" value="1">
<input type="hidden" name="code_check" value="1">
<input type="hidden" name="hr_seq" value=<?=$row['seq']; ?>>
	<table width="100%">
		<tr>

			<td height="40">
				<table width="100%">
					<tr>
						<td width="360" align="left" class="content_title">인사관리 > 사원 정보변경</td>
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
									<td><input type="button" class="doc_submit_btn_style" onClick="check_before_submit();" value="저장하기"></td>
									<td width="5"></td>
									<td><input type="button" class="doc_submit_btn_style" onClick="window.history.back();" value="취소하기"></td>
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
			<input id="tab1" type="radio" name="tab" value="hr1" <?=($tab == 'hr1') ? checked : ""; ?>>
			<input id="tab2" type="radio" name="tab" value="hr2"<?=($tab == 'hr2') ? checked : ""; ?>>
			<input id="tab3" type="radio" name="tab" value="hr3"<?=($tab == 'hr3') ? checked : ""; ?>>
			<input id="tab4" type="radio" name="tab" value="hr4"<?=($tab == 'hr4') ? checked : ""; ?>>
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
											<td style="padding-left:20px;" class="doc_field_name"><b>소속회사</b></td>
											<td class="doc_field_content" style="width:439px;"><?=$company_name; ?></td>
											<td rowspan=7><div id="imagePreview" style="width:100%; height:100%;"><img id="cur_image" style="max-width:198px; max-height:208px; padding:1px;" src="<?=$photo_path; ?>" onError="this.src=''"></div></td>
											<td style="padding-left:10px;" class="doc_field_name"><b>근무상태</b></td>
											<td class="doc_field_content" style="width:716px;">
												<input type="radio" name="hr_status" value="1" <?=($row['term_dt'] == NULL) ? "checked" : ""; ?>>근무
												<input type="radio" name="hr_status" value="2" <?=($row['term_dt'] == NULL) ? "" : "checked"; ?>>퇴사
											</td>

										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직원코드 <span style="color:red;">*</span></b></td>
											<td class="doc_field_content">
												<input type="hidden" name="hr_code_current" value="<?=$row['id']; ?>">
												<input type="text" name="hr_code" style="width:166px;" value="<?=$row['id']; ?>" onblur="check_employee_code(this.value)">
												<input type="button" id="employee_list_btn" value="직원리스트" onClick="show_employee_list()">
												<span id="code_check_msg" style="font-weight:bold; color:red; padding-left:10px; font-size:14px;"></span>
											</td>
											<td style="padding-left:10px;width:101px;" class="doc_field_name"><b>사원증 지급일자</b></td>
											<td class="doc_field_content" style="width:754px;">
												<input type="radio" name="hr_employeecard" value="1" <?=($row['employeecard_dt'] == NULL) ? "" : "checked"; ?>>발급
												<input type="radio" name="hr_employeecard" value="2" <?=($row['employeecard_dt'] == NULL) ? "checked" : ""; ?>>미발급
											</td>

										
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>한글성명 <span style="color:red;">*</span></b></td>
											<td class="doc_field_content">
												<input type="hidden" name="hr_nameK_current" value="<?=Br_iconv($row['hnm']); ?>">
												<input type="text" name="hr_nameK" style="width:250px;" value="<?=Br_iconv($row['hnm']); ?>" onblur="check_employee_name(this.value)">
												<span id="name_check_msg" style="font-weight:bold; color:red; padding-left:8px; font-size:14px;"></span>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>최초입사일</b></td>
											<td class="doc_field_content">
												<?
													$FjoinDate = explode("-", $row['ipsa_sdt']);
												?>
												<select name="hr_FjoinY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $FjoinDate[0]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_FjoinM" style="width:42px;" onChange="select_month(this.value)">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $FjoinDate[1]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_FjoinD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $FjoinDate[2]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>


											
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>First Name</b></td>
											<td class="doc_field_content"><input type="text" name="hr_fNameE" style="width:250px;" value="<?=$row['first_nm']; ?>"></td>

											<td style="padding-left:10px;" class="doc_field_name"><b>입사일</b></td>
											<td class="doc_field_content">
												<?
													$joinDate = explode("-", $row['ipsa_dt']);
												?>
												<select name="hr_joinY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $joinDate[0]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_joinM" style="width:42px;" onChange="select_month(this.value)">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $joinDate[1]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_joinD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $joinDate[2]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
											
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>Last Name</b></td>
											<td class="doc_field_content"><input type="text" name="hr_lNameE" style="width:250px;" value="<?=$row['last_nm']; ?>"></td>

											<td style="padding-left:10px;" class="doc_field_name"><b>퇴사일</b></td>
											<td class="doc_field_content">
												<?
													$resignDate = explode("-", $row['term_dt']);
												?>
												<select name="hr_resignY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $resignDate[0]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_resignM" style="width:42px;" onChange="select_month(this.value)">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $resignDate[1]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_resignD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $resignDate[2]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>

											
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>소속부서</b></td>
											<td class="doc_field_content"><?=Br_iconv($hr_depart_row['nm']); ?></td>

											<td style="padding-left:10px;" class="doc_field_name" rowspan=4><b>메모</b></td>
											<td class="doc_field_content" style="padding-right:12px;" rowspan=4><textarea wrap="hard" name="hr_memo" style="width:500px; height:100%; min-height:75px; resize:none;" maxlength="500" onblur="resize(this)"><?=Br_iconv($row['bigo']); ?></textarea></td>

										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직급</b></td>
											<td class="doc_field_content"><?=Br_iconv($hr_position_row['nm']); ?></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:20px;" class="doc_field_name"><b>직책</b></td>
											<td class="doc_field_content"><?=Br_iconv($hr_title_row['nm']); ?></td>
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
												<?
													$bDay = explode("-", $row['birth_dt']);
												?>
												<select name="hr_birthY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $todayY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $bDay[0]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_birthM" style="width:42px;">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $bDay[1]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_birthD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $bDay[2]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<input type="radio" name="hr_birth_type" value="0" <?=($row['birth_gubun'] == 0) ? "checked" : ""; ?>>양력
												<input type="radio" name="hr_birth_type" value="1" <?=($row['birth_gubun'] == 1) ? "checked" : ""; ?>>음력
											</td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>연락처 (Home)</b></td>
											<td class="doc_field_content">
												<input type="text" name="hr_tel11" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['tel11']; ?>"> - 
												<input type="text" name="hr_tel12" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['tel12']; ?>"> -
												<input type="text" name="hr_tel13" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['tel13']; ?>">
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>연락처 (Cell)</b></td>
											<td class="doc_field_content">
												<input type="text" name="hr_tel21" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['tel21']; ?>"> - 
												<input type="text" name="hr_tel22" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['tel22']; ?>"> -
												<input type="text" name="hr_tel23" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['tel23']; ?>">
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>E-mail</b></td>
											<td class="doc_field_content"><input type="email" name="hr_email" style="width:90%; max-width:300px;" value="<?=$row['email']; ?>"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;"" class="doc_field_name"><b>Address</b></td>
											<td class="doc_field_content" colspan=5><input type="text" name="hr_street" style="width:500px;" value="<?=$row['street']; ?>"></td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>City</b></td>
											<td class="doc_field_content">
												<select name="hr_city" style="width:90%; max-width:300px;">
													<?
														$city_query = "SELECT * FROM ft_city_com ORDER BY cd";
														$city_query_result = mssql_query($city_query) or die ('Database connetion failed');
													?>
													<option value=""> --- City 선택 --- </option>
													<? while($city_row = mssql_fetch_array($city_query_result)) { ?>
														<option value="<?=$city_row['cd']; ?>" <?=(($city_row['nm'] == $hr_city_row['nm']) ? "selected" : ""); ?>><?=$city_row['nm']; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>Province</b></td>
											<td class="doc_field_content">
												<select name="hr_province" style="width:90%; max-width:300px;">
													<?
														$province_query = "SELECT province_cd, nm, long_nm FROM ft_province_com WHERE country_cd = 0";
														$province_query_result = mssql_query($province_query) or die ('Database connetion failed');
													?>
													<option value=""> --- Province 선택 --- </option>
													<? while($province_row = mssql_fetch_array($province_query_result)) { ?>
														<option value="<?=$province_row['province_cd']; ?>" <?=(($province_row['nm'] == $hr_province_row['nm']) ? "selected" : ""); ?>><?=$province_row['nm']; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>Postal Code</b></td>
											<td class="doc_field_content">
												<? $postalCode = explode(" ", $row['postal_cd']); ?>
												<input type="text" name="hr_postalCode1" style="width:40px; text-align:right;" maxlength="3" value="<?=$postalCode[0]; ?>">&nbsp;
												<input type="text" name="hr_postalCode2" style="width:40px; text-align:right;" maxlength="3" value="<?=$postalCode[1]; ?>">
											</td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>비자상태</b></td>
											<td class="doc_field_content">
												<input type="radio" name="hr_visaStatus" value="0" <?=(($row['p_status'] == 0) ? "checked" : ""); ?>>시민
												<input type="radio" name="hr_visaStatus" value="1" <?=(($row['p_status'] == 1) ? "checked" : ""); ?>>영주
												<input type="radio" name="hr_visaStatus" value="2" <?=(($row['p_status'] == 2) ? "checked" : ""); ?>>비자
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>비자만료일</b></td>
											<td class="doc_field_content">
												<?
													$visaExpireDate = explode("-", $row['visa_dt']);
												?>
												<select name="hr_visaY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $visaExpireDate[0]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_visaM" style="width:42px;" onChange="select_month(this.value)">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $visaExpireDate[1]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_visaD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $visaExpireDate[2]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>SIN</b></td>
											<td class="doc_field_content">
												<input type="text" name="hr_sin1" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['sin1']; ?>">&nbsp; 
												<input type="text" name="hr_sin2" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['sin2']; ?>">&nbsp;
												<input type="text" name="hr_sin3" style="width:40px; text-align:right;" maxlength="3" onkeydown='return onlyNumber(event)' value="<?=$row['sin3']; ?>">
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
											<td class="doc_field_content" colspan=5>
												<input type="radio" name="hr_status" value="1" <?=($row['term_dt'] == NULL) ? "checked" : ""; ?>>근무
												<input type="radio" name="hr_status" value="2" <?=($row['term_dt'] == NULL) ? "" : "checked"; ?>>퇴사
											</td>
										</tr>
										<tr class="doc_border" height="30">
											<td style="padding-left:10px;" class="doc_field_name"><b>최초입사일</b></td>
											<td class="doc_field_content">
												<?
													$FjoinDate = explode("-", $row['ipsa_sdt']);
												?>
												<select name="hr_FjoinY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $FjoinDate[0]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_FjoinM" style="width:42px;" onChange="select_month(this.value)">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $FjoinDate[1]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_FjoinD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $FjoinDate[2]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>입사일</b></td>
											<td class="doc_field_content">
												<?
													$joinDate = explode("-", $row['ipsa_dt']);
												?>
												<select name="hr_joinY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $joinDate[0]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_joinM" style="width:42px;" onChange="select_month(this.value)">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $joinDate[1]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_joinD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $joinDate[2]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
											<td style="padding-left:10px;" class="doc_field_name"><b>퇴사일</b></td>
											<td class="doc_field_content">
												<?
													$resignDate = explode("-", $row['term_dt']);
												?>
												<select name="hr_resignY" style="width:55px;">
													<option value="">Y</option>
													<? for($i = $endY; $i >= $startY; $i--) { ?>
														<option value="<?=$i; ?>" <?=(($i == $resignDate[0]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_resignM" style="width:42px;" onChange="select_month(this.value)">
													<option value="">M</option>
													<? for($i = 1; $i <= 12; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $resignDate[1]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
												<select name="hr_resignD" style="width:42px;">
													<option value="">D</option>
													<? for($i = 1; $i <= 31; $i++) { ?>
														<option value="<?=$i; ?>" <?=(($i == $resignDate[2]) ? "selected" : ""); ?>><?=$i; ?></option>
													<? } ?>
												</select>
											</td>
										</tr>
										<tr class="doc_border" height="80">
											<td style="padding-left:10px;" class="doc_field_name"><b>메모</b></td>
											<td class="doc_field_content" style="padding-right:12px;" colspan=5><textarea wrap="hard" name="hr_memo" style="width:500px; height:100%; min-height:75px; resize:none;" maxlength="500" onblur="resize(this)"><?=Br_iconv($row['bigo']); ?></textarea></td>
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
										<?
										$buseo_query = "SELECT buseo, CONVERT(char(10), dt, 126) AS dt FROM $tablename2 WHERE id = ".$row['id']." ORDER BY dt";
										$buseo_query_result = mssql_query($buseo_query);
										$buseo_query_num = mssql_num_rows($buseo_query_result);

										$depart_list_query = "SELECT cd, nm FROM $tablename3 WHERE active = 1 ORDER BY cd";
										$depart_list_query_result = mssql_query($depart_list_query);
										$depart_list_query_num = mssql_num_rows($depart_list_query_result);
										while($depart_list_row = mssql_fetch_array($depart_list_query_result)) {
											$depart_list_query_num--;
											if($depart_list_query_num == 0) {
												$depart_list_cd = $depart_list_cd.$depart_list_row['cd'];
												$depart_list_nm = $depart_list_nm.Br_iconv($depart_list_row['nm']);
											} else {
												$depart_list_cd = $depart_list_cd.$depart_list_row['cd']."_";
												$depart_list_nm = $depart_list_nm.Br_iconv($depart_list_row['nm'])."_";
											}
										}
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>부서</b></td>
											<td width="200" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="depart_add('<?=$depart_list_cd; ?>', '<?=$depart_list_nm; ?>');"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_depart" width="300px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td class="doc_field_name" align="center"><b>부서</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
													<? if($buseo_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=3><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? $i = 1; ?>
														<? while($buseo_row = mssql_fetch_array($buseo_query_result)) { ?>
															<tr class="doc_border">
																<td align="center">
																	<input name="hr_depart_date[]" type="text" style="width:100%; text-align:center;" value="<?=$buseo_row['dt']; ?>" onClick='datePicker(event, this)'>
																</td>
																<td align="center">
																	<select name="hr_depart[]" style="width:100%; font-size:15px;">
																		<? $depart_list_query_result = mssql_query($depart_list_query); ?>
																		<? while($depart_list_row = mssql_fetch_array($depart_list_query_result)) { ?>
																			<option value="<?=$depart_list_row['cd']; ?>" <?=(($buseo_row['buseo'] == $depart_list_row['cd']) ? "selected" : "" ); ?>><?=Br_iconv($depart_list_row['nm']); ?></option>
																		<? } ?>
																	</select>
																</td>
																<td align="center"><span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row("table_depart", <?=$i++; ?>);'>X</span></td>
															</tr>
														<? } ?>
													<? } ?>
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
										$posiTitle_query = "SELECT CONVERT(char(10), dt, 126) AS dt, hr_position, hr_title FROM hr_stf_position WHERE company_cd = $company AND id = ".$row['id']." ORDER BY seq";
										$posiTitle_query_result = mssql_query($posiTitle_query);
										$posiTitle_query_num = mssql_num_rows($posiTitle_query_result);

										$posi_list_query = "SELECT cd, nm FROM hr_position ORDER BY cd";
										$posi_list_query_result = mssql_query($posi_list_query);
										$posi_list_query_num = mssql_num_rows($posi_list_query_result);
										while($posi_list_row = mssql_fetch_array($posi_list_query_result)) {
											$posi_list_query_num--;
											if($posi_list_query_num == 0) {
												$posi_list_cd = $posi_list_cd.$posi_list_row['cd'];
												$posi_list_nm = $posi_list_nm.Br_iconv($posi_list_row['nm']);
											} else {
												$posi_list_cd = $posi_list_cd.$posi_list_row['cd']."_";
												$posi_list_nm = $posi_list_nm.Br_iconv($posi_list_row['nm'])."_";
											}
										}
										
										$title_list_query = "SELECT cd, nm FROM hr_title ORDER BY cd";
										$title_list_query_result = mssql_query($title_list_query);
										$title_list_query_num = mssql_num_rows($title_list_query_result);
										while($title_list_row = mssql_fetch_array($title_list_query_result)) {
											$title_list_query_num--;
											if($title_list_query_num == 0) {
												$title_list_cd = $title_list_cd.$title_list_row['cd'];
												$title_list_nm = $title_list_nm.Br_iconv($title_list_row['nm']);
											} else {
												$title_list_cd = $title_list_cd.$title_list_row['cd']."_";
												$title_list_nm = $title_list_nm.Br_iconv($title_list_row['nm'])."_";
											}
										}
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>직급/직책</b></td>
											<td width="233" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="posiTitle_add('<?=$posi_list_cd; ?>', '<?=$posi_list_nm; ?>', '<?=$title_list_cd; ?>', '<?=$title_list_nm; ?>');"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_posiTitle" width="330px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td width="89" class="doc_field_name" align="center"><b>직급</b></td>
														<td width="89" class="doc_field_name" align="center"><b>직책</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
													<? if($posiTitle_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=4><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? $i = 1; ?>
														<? while($posiTitle_row = mssql_fetch_array($posiTitle_query_result)) { ?>
															<tr class="doc_border">
																<td align="center"><input name="hr_title_date[]" type="text" style="width:100%; text-align:center;" value="<?=$posiTitle_row['dt']; ?>" onClick='datePicker(event, this)'></td>
																<td align="center">
																	<select name="hr_position[]" style="width:100%; font-size:15px;">
																		<? $posi_list_query_result = mssql_query($posi_list_query); ?>
																		<? while($posi_list_row = mssql_fetch_array($posi_list_query_result)) { ?>
																			<option value="<?=$posi_list_row['cd']; ?>" <?=(($posiTitle_row['hr_position'] == $posi_list_row['cd']) ? "selected" : "" ); ?>><?=Br_iconv($posi_list_row['nm']); ?></option>
																		<? } ?>
																	</select>
																</td>
																<td align="center">
																	<select name="hr_title[]" style="width:100%; font-size:15px;">
																		<? $title_list_query_result = mssql_query($title_list_query); ?>
																		<? while($title_list_row = mssql_fetch_array($title_list_query_result)) { ?>
																			<option value="<?=$title_list_row['cd']; ?>" <?=(($posiTitle_row['hr_title'] == $title_list_row['cd']) ? "selected" : "" ); ?>><?=Br_iconv($title_list_row['nm']); ?></option>
																		<? } ?>
																	</select>
																</td>
																<td align="center"><span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row("table_posiTitle", <?=$i++; ?>);'>X</span></td>
															</tr>
														<? } ?>
													<? } ?>
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
										$wage_query = "SELECT wage, pay_gubun, bigo, CONVERT(char(10), dt, 126) AS dt FROM $wage_table WHERE id = ".$row['id']." ORDER BY dt";
										$wage_query_result = mssql_query($wage_query);
										$wage_query_num = mssql_num_rows($wage_query_result);

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
													<? if($wage_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=5><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? $i = 1; ?>
														<? while($wage_row = mssql_fetch_array($wage_query_result)) { ?>
															<tr class="doc_border">
																<td align="center"><input name="hr_wage_date[]" type="text" style="width:100%; text-align:center;" value="<?=$wage_row['dt']; ?>" onClick='datePicker(event, this)'></td>
																<td align="center">
																	<select name="hr_wage_type[]" style="width:100%; font-size:15px;">
																		<option value=<?=$wage_row['pay_gubun']; ?> <?=(($wage_row['pay_gubun'] == 0) ? "selected" : "" ); ?>>시급</option>
																		<option value=<?=$wage_row['pay_gubun']; ?> <?=(($wage_row['pay_gubun'] == 1) ? "selected" : "" ); ?>>월급</option>
																	</select>
																</td>
																<td align="center"><input name="hr_wage[]" type="text" style="width:100%; text-align:right;" value="<?=$wage_row['wage']; ?>" required></td>
																<td align="center"><input name='hr_wage_bigo[]' type='text' style='width:100%; text-align:left;' value="<?=Br_iconv($wage_row['bigo']); ?>" required></td>
																<td align="center"><span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row("table_wage", <?=$i++; ?>);'>X</span></td>
															</tr>
														<? } ?>
													<? } ?>
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
										$point_query = "SELECT rDesc, reward, bigo, CONVERT(char(10), rDate, 126) AS dt FROM dt_stf_reward WHERE company = '$company_Sname' AND id = ".$row['id']." ORDER BY rDate";
										$point_query_result = mssql_query($point_query);
										$point_query_num = mssql_num_rows($point_query_result);

										$point_list_query = "SELECT cd, nm FROM ft_reward_gubun_com ORDER BY cd";
										$point_list_query_result = mssql_query($point_list_query);
										$point_list_query_num = mssql_num_rows($point_list_query_result);
										while($point_list_row = mssql_fetch_array($point_list_query_result)) {
											$point_list_query_num--;
											if($point_list_query_num == 0)	$point_list_type = $point_list_type.Br_iconv($point_list_row['nm']);
											else							$point_list_type = $point_list_type.Br_iconv($point_list_row['nm'])."_";
										}
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>상/벌점</b></td>
											<td width="500" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="point_add('<?=$point_list_type; ?>');"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_point" width="600px" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="120" class="doc_field_name" align="center"><b>Date</b></td>
														<td width="80" class="doc_field_name" align="center"><b>내용</b></td>
														<td width="80" class="doc_field_name" align="center"><b>포인트</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
													<? if($point_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=5><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? $i = 1; ?>
														<? while($point_row = mssql_fetch_array($point_query_result)) { ?>
															<tr class="doc_border">
																<td align="center"><input name="hr_point_date[]" type="text" style="width:100%; text-align:center;" value="<?=$point_row['dt']; ?>" onClick='datePicker(event, this)'></td>
																<td align="center">
																	<select name="hr_point_desc[]" style="width:100%; font-size:15px">
																		<? $point_list_query_result = mssql_query($point_list_query); ?>
																		<? while($point_list_row = mssql_fetch_array($point_list_query_result)) { ?>
																			<option value="<?=Br_iconv($point_list_row['nm']); ?>" <?=((Br_iconv($point_row['rDesc']) == Br_iconv($point_list_row['nm'])) ? "selected" : "" ); ?>><?=Br_iconv($point_list_row['nm']); ?></option>
																		<? } ?>
																	</select>
																</td>
																<td align="center"><input name="hr_point_reward[]" type="text" style="width:100%; text-align:center;" value="<?=$point_row['reward']; ?>" required></td>
																<td align="center"><input name='hr_point_bigo[]' type='text' style='width:100%; text-align:left;' value="<?=Br_iconv($point_row['bigo']); ?>"></td>
																<td align="center"><span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row("table_point", <?=$i++; ?>);'>X</span></td>
															</tr>
														<? } ?>
													<? } ?>
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
										$deposit_query = "SELECT kind, serial_num, unit, amt, received_nm, bigo, ip_id, CONVERT(char(10), ip_dt, 126) AS dt FROM $deposit_table WHERE stf_id = ".$row['id']." ORDER BY ip_dt";
										$deposit_query_result = mssql_query($deposit_query);
										$deposit_query_num = mssql_num_rows($deposit_query_result);

										$deposit_list_query = "SELECT cd, nm FROM ft_deposit_kind_com ORDER BY cd";
										$deposit_list_query_result = mssql_query($deposit_list_query);
										$deposit_list_query_num = mssql_num_rows($deposit_list_query_result);
										while($deposit_list_row = mssql_fetch_array($deposit_list_query_result)) {
											$deposit_list_query_num--;
											if($deposit_list_query_num == 0) {
												$deposit_list_cd = $deposit_list_cd.$deposit_list_row['cd'];
												$deposit_list_nm = $deposit_list_nm.Br_iconv($deposit_list_row['nm']);
											} else {
												$deposit_list_cd = $deposit_list_cd.$deposit_list_row['cd']."_";
												$deposit_list_nm = $deposit_list_nm.Br_iconv($deposit_list_row['nm'])."_";
											}
										}
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>직원디파짓</b></td>
											<td width="900" style="border:0; text-align:right; padding-top:5px;"><input type="button" value="추가" onClick="deposit_add('<?=$deposit_list_cd; ?>', '<?=$deposit_list_nm; ?>');"></td>
											<td style="border:0;" colspan=4></td>
										</tr>
										<tr>
											<td colspan="6">
												<table id="table_deposit" width="1000" style="table-layout:fixed; padding:0;">
													<tr class="doc_border" height="30">
														<td width="300" class="doc_field_name" align="center"><b>디파짓 물품명</b></td>
														<td width="50" class="doc_field_name" align="center"><b>Size</b></td>
														<td width="100" class="doc_field_name" align="center"><b>Amount</b></td>
														<td width="100" class="doc_field_name" align="center"><b>받은사람</b></td>
														<td class="doc_field_name" align="center"><b>비고</b></td>
														<td width="30" class="doc_field_name"></td>
													</tr>
													<? if($deposit_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=6><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? $i = 1; ?>
														<? while($deposit_row = mssql_fetch_array($deposit_query_result)) { ?>
															<tr class="doc_border">
																<td align="center">
																	<select name="hr_deposit_name[]" style="width:100%; font-size:15px">
																		<? $deposit_list_query_result = mssql_query($deposit_list_query); ?>
																		<? while($deposit_list_row = mssql_fetch_array($deposit_list_query_result)) { ?>
																			<option value="<?=$deposit_list_row['cd']; ?>" <?=(($deposit_row['kind'] == $deposit_list_row['cd']) ? "selected" : "" ); ?>><?=$deposit_list_row['cd'].". ".Br_iconv($deposit_list_row['nm']); ?></option>
																		<? } ?>
																	</select>
																</td>
																<td align="center"><input name='hr_deposit_size[]' type='text' style='width:100%; text-align:center;' value="<?=str_replace(' ', '', $deposit_row['unit']); ?>" required></td>
																<td align="center"><input name='hr_deposit_amount[]' type='text' style='width:100%; text-align:center;' value="<?=$deposit_row['amt']; ?>" required></td>
																<td align="center"><input name='hr_deposit_receiver[]' type='text' style='width:100%; text-align:center;' value="<?=str_replace(' ', '', Br_iconv($deposit_row['received_nm'])); ?>" required></td>
																<td align="center"><input name='hr_deposit_bigo[]' type='text' style='width:100%; text-align:left;' value="<?=Br_iconv($deposit_row['bigo']); ?>"></td>
																<td align="center"><span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row("table_deposit", <?=$i++; ?>);'>X</span></td>
															</tr>
														<? } ?>
													<? } ?>
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
										$schedule_query = "SELECT CONVERT(char(10), start_date, 126) AS start_date, CONVERT(char(10), end_date, 126) AS end_date, type, bigo FROM hr_stf_schedule WHERE company_cd = $company AND id = ".$row['id']." ORDER BY start_date";
										$schedule_query_result = mssql_query($schedule_query);
										$schedule_query_num = mssql_num_rows($schedule_query_result);
										$schedule_type_list = array("유급휴가","무급휴가","반차","조퇴","지각","결근");
										$schedule_type = "유급휴가_무급휴가_반차_조퇴_지각_결근";
										//$schedule_type_list = array("지각", "조퇴", "반차", "결근", "무급휴가", "유급휴가");
										//$schedule_type = "지각_조퇴_반차_결근_무급휴가_유급휴가";
										?>
										<tr class="doc_border" height="30">
											<td width="100" style="font-size:18px; border:0;"><b>근태정보</b></td>
											<!--
											<td width="800" style="border:0; text-align:center; padding-top:5px;">
												<? $current_month = date("Y-m"); ?>
												<input type="button" value="<" onClick="PrevNext_month('prev')">
												<input size="12" type="text" name="select_month" style="margin:5px; text-align:center; width:60px;" value="<?=$current_month; ?>" disabled>
												<input type="button" value=">" onClick="PrevNext_month('next')">
												<input type="button" value="전체보기" style="margin-left:20px;" onClick="">
											</td>-->
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
													<? if($schedule_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=6><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? $i = 1; ?>
														<? while($schedule_row = mssql_fetch_array($schedule_query_result)) { ?>
															<tr class="doc_border" height="20">
																<td align="center"><select name='hr_schedule_type[]' style='width:100%; font-size:15px;' onChange='schedule_change_type(this)'>
																		<? for($j = 0; $j < sizeof($schedule_type_list); $j++) { ?>
																			<option value="<?=$j+1; ?>" <?=(($schedule_row['type'] == $j+1) ? "selected" : ""); ?>><?=$schedule_type_list[$j]; ?></option>
																		<? } ?>
																	</select>
																</td>
																<td align="center"><input name='hr_schedule_sDate[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this, "sDate")' value="<?=$schedule_row['start_date']; ?>"></td>
																<td align="center"><input name='hr_schedule_eDate[]' type='text' style='width:100%; text-align:center;' onClick='datePicker(event, this, "eDate")' value="<?=$schedule_row['end_date']; ?>" <?=(($schedule_type_list[$schedule_row['type']-1] !== '유급휴가' && $schedule_type_list[$schedule_row['type']-1] !== '무급휴가') ? "disabled" : ""); ?>></td>
																<td align="center">
																<?
																
																$vday = ceil((strtotime($schedule_row['end_date']) - strtotime($schedule_row['start_date'])) / (60*60*24));
																	if($schedule_type_list[$schedule_row['type']-1] !== '지각'){
																		$vday = $vday + 1;
																	}
																	if($schedule_type_list[$schedule_row['type']-1] == '반차' || $schedule_type_list[$schedule_row['type']-1] == '조퇴'){
																		$vday = 0.5;
																	}
																echo $vday;
																
																?></td>
																<td align="center"><input name='hr_schedule_bigo[]' type='text' style='width:100%; text-align:left;' value="<?=Br_iconv($schedule_row['bigo']); ?>"></td>
																<td align="center"><span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row("table_schedule", <?=$i++; ?>);'>X</span></td>
															</tr>
														<? } ?>
													<? } ?>
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
										<?
										$file_query = "SELECT subject, bigo, file_name FROM hr_files WHERE company_cd = $company AND id = ".$row['id']." ORDER BY dt";
										$file_query_result = mssql_query($file_query);
										$file_query_num = mssql_num_rows($file_query_result);

										$filepath = "upload/hr/";
										?>
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
													<? if($file_query_num == 0) { ?>
														<tr class="doc_border" height="20">
															<td align="center" colspan=4><b>등록된 정보 없음</b></td>
														</tr>
													<? } else { ?>
														<? $i = 1; ?>
														<? while($file_row = mssql_fetch_array($file_query_result)) { ?>
															<? $fullpath = $filepath.$company_Sname."/".$file_row['file_name']; ?>
															<? $temp = explode("__", $file_row['file_name']); ?>
															<? $filename = $temp[2]; ?>
															<tr class="doc_border" height="20">
																<td align="center"><?=Br_iconv($file_row['subject']); ?></td>
																<td align="left" style="padding-left:3px;"><a href="<?=$fullpath; ?>" target="_blank"><?=Br_iconv($filename); ?></a></td>
																<td align="left" style="padding-left:3px;"><?=Br_iconv($file_row['bigo']); ?></td>
																<td align="center"><span style='color:red; font-weight:bold; cursor:pointer;' onClick='del_row("table_file", <?=$i++; ?>);'>X</span></td>
															</tr>
														<? } ?>
													<? } ?>
													<input type="hidden" name="hr_file_del">
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
									<td><input type="button" class="doc_submit_btn_style" onClick="check_before_submit();" value="저장하기"></td>
									<td width="5"></td>
									<td><input type="button" class="doc_submit_btn_style" onClick="window.history.back();" value="취소하기"></td>
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

<?

?>

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
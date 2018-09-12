$(document).ready(function () {

    var $tb_map, default_options, $mask, $checkList_div, $checkList_iframe

    $tb_map = $('#TB_map_image');
	$mask = $('#mask');
	$checkList_div = $("#tb_checkList_popup");
	$checkList_iframe = $("#tb_checkList_iframe");

	function showCheckList(area_id) {
		var maskHeight = $(document).height();
		var maskWidth = $(document).width();
	
		$mask.css({ 'width':maskWidth, 'height':maskHeight });
		$mask.fadeTo("slow", 0.6);
		$checkList_iframe.attr("src", "property_checkList.php?area=" + area_id);
		//$checkList_div.css("left", 906);
		$checkList_div.fadeTo("slow", 1);

		$tb_map.mapster('set', false, area_id);
		$tb_map.mapster('set', true, area_id, {
			strokeColor: '22FF00'
		});
		$tb_map.mapster('tooltip');

		setTimeout(function(){
			loadCheckList(area_id);
		}, 300); 
	}

	function resize_frame() {
		var frame_size = parent.document.getElementById("all_table").style.minWidth;

		if(frame_size == "1024px")
			parent.document.getElementById("all_table").style.minWidth = '1700px';
	}

	function loadCheckList(area_id) {
		var completed_area_list = parent.document.forms.property_inspection_form.completed_area.value;
		var cl_table = document.getElementById("tb_checkList_iframe").contentWindow.document.getElementById("checkList_table");

		if(completed_area_list.indexOf(area_id + "_") > -1) {
			var result_list = document.getElementById("result_table").children[0].children;
		
			for(var i = 0; i < result_list.length; i++) {
				if(result_list[i].id.indexOf(area_id + "_0") > -1) {
					var list_row = result_list[i];

					for(var j = 2; j < cl_table.rows.length; j++) {
						list_row = list_row.nextElementSibling;

						if(list_row.cells[1].innerHTML == "Good") {
							cl_table.rows[j].cells[1].childNodes[0].checked = true;
						} else if(list_row.cells[1].innerHTML == "Bad") {
							cl_table.rows[j].cells[2].childNodes[0].checked = true;
						}
						cl_table.rows[j].cells[3].childNodes[0].value = list_row.cells[2].innerHTML;
					}
					break;
				}
			}
		}
	}

	function buildAreas() {
		var numberOfarea = 17;
		var nameOfarea = 'area';
		var areaArray = [];

		for(var i = 1; i <= numberOfarea; i++) {
			areaArray.push({ 
				key: nameOfarea + i,
				toolTip: buildToolTipArea(nameOfarea + i)
			});
		}
		return areaArray;
	}

	function buildToolTipArea(fullName) {
		return $('<p align="center"><b><font size="3" color="blue">' + fullName + '</font></b></p>');
	}

	default_options = {
		mapKey: 'area_shortname',
		isSelectable: false,
		stroke: true,
		strokeWidth: 3,

		render_highlight: {
			//strokeColor: 'FF0000'
			strokeColor: '22FF00'
		},

		showToolTip: true,
		toolTipClose: ["area-mouseout"],
		areas: buildAreas()
	};

	resize_frame();
	$tb_map.mapster(default_options);

	function loadSavedTemp() {
		var completed_area_list = parent.document.forms.property_inspection_form.completed_area.value;
		var area_list = completed_area_list.split("_");
		area_list.pop();

		$tb_map.mapster('set', true, area_list.join(','), {
			strokeColor: '0000FF'
		});
	}

	loadSavedTemp();

	$("area").click(function() {
		showCheckList(this.getAttribute("area_shortname"));
	});
});


/*
$(document).ready(function () {

    var $tb_map, default_options, $mask, $checkList_div, $checkList_iframe

    $tb_map = $('#TB_map_image');
	$mask = $('#mask');
	$checkList_div = $("#tb_checkList_popup");
	$checkList_iframe = $("#tb_checkList_iframe");

	function showCheckList(area_id) {
		var maskHeight = $(document).height();
		var maskWidth = $(document).width();
	
		$mask.css({ 'width':maskWidth, 'height':maskHeight });
		$mask.fadeTo("slow", 0.6);
		$checkList_iframe.attr("src", "property_checkList.php?area=" + area_id);
		$checkList_div.css("left", 906);
		$checkList_div.fadeTo("slow", 1);

		$tb_map.mapster('set', false, area_id);
		$tb_map.mapster('set', true, area_id, {
			strokeColor: '22FF00'
		});
		$tb_map.mapster('tooltip');

		setTimeout(function(){
			loadCheckList(area_id);
		}, 500); 
	}

	function resize_frame() {
		var frame_size = parent.document.getElementById("all_table").style.minWidth;

		if(frame_size == "1024px")
			parent.document.getElementById("all_table").style.minWidth = '1700px';
	}

	function loadCheckList(area_id) {
		var completed_area_list = parent.document.forms.property_inspection_form.completed_area.value;
		var cl_table = document.getElementById("tb_checkList_iframe").contentWindow.document.getElementById("checkList_table");

		if(completed_area_list.indexOf(area_id + "_") > -1) {
			var area_list = document.getElementById("tb_inspection_result_div").children;
		
			for(var i = 0; i < area_list.length; i++) {
				if(area_list[i].id.indexOf(area_id + "_") > -1) {
					var list = area_list[i].children;
					var k = 1;

					for(var j = 1; j < list.length; j++) {			// j = 1, skip the first element (label)
						if(list[j].tagName == "LI") {
							k++;
						}

						if(list[j].tagName == "INPUT") {
							if(list[j].name.indexOf("list") > -1) {
								if(list[j].value == "y") {
									cl_table.rows[k].cells[1].childNodes[0].checked = true;
								} else {
									cl_table.rows[k].cells[2].childNodes[0].checked = true;
								}
							} else if(list[j].name.indexOf("comment") > -1) {
								cl_table.rows[k].cells[3].childNodes[0].value = list[j].value;
							}
						}
					}
					break;
				}
			}
		}
	}

	function buildAreas() {
		var numberOfarea = 17;
		var nameOfarea = 'area';
		var areaArray = [];

		for(var i = 1; i <= numberOfarea; i++) {
			areaArray.push({ 
				key: nameOfarea + i,
				toolTip: buildToolTipArea(nameOfarea + i)
			});
		}
		return areaArray;
	}

	function buildToolTipArea(fullName) {
		return $('<p align="center"><b><font size="3" color="blue">' + fullName + '</font></b></p>');
	}

	default_options = {
		mapKey: 'area_shortname',
		isSelectable: false,
		stroke: true,
		strokeWidth: 3,

		render_highlight: {
			strokeColor: 'FF0000',
		},

		showToolTip: true,
		toolTipClose: ["area-mouseout"],
		areas: buildAreas()
	};

	resize_frame();
	$tb_map.mapster(default_options);

	$("area").click(function() {
		showCheckList(this.getAttribute("area_shortname"));
	});
});
*/
var startAt = 0;
var ie  = (navigator.appVersion.indexOf("MSIE") == -1)? false:true;
var today       = new Date();
var dateNow     = today.getDate();
var monthNow    = today.getMonth()+1;

var monthName   = new Array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12")
var dayName     = new Array ("일","월","화","수","목","금","토")

var monthSelected, yearSelected, dateSelected, omonthSelected, oyearSelected, odateSelected
var ctlYY, ctlMM, ctlDD;
var ctlYmd;
var cleft;
var ctop;
var maxYear ;


var oPopup;

var _hideFunc = "oPopup.hide();";
var yearNow   = (ie)? today.getYear() : today.getYear() + 1900;

yearNow = (yearNow < 1900)? yearNow + 1900 : yearNow;



document.write("<div id='_CalendarDiv' style='z-index:999;background:#ffffcc; width:150px; display:none; position:absolute'>");
document.write( getCalendarString() );
document.write( getCalendarMonth() );
document.write( getCalendarYear() );
document.write("</div>");

oPopup =
{
    obj         :   document.getElementById("_CalendarDiv"),
    calendar    :   document.getElementById("calendar"),
    content     :   document.getElementById("dcontent"),
    year        :   document.getElementById("spanYear"),
    month       :   document.getElementById("spanMonth"),
    hide        :   function()
	                {
	                    oPopup.calendar.style.display = 'none';
	                    oPopup.obj.style.display='none';
	                },
    show        :   function(cleft, ctop, cwidth, cheight, cwhere)
        	        {
                        oPopup.obj.style.left = cleft + "px";
                        oPopup.obj.style.top = ctop + "px";
                        oPopup.obj.style.display = 'block';
                        oPopup.calendar.style.display = 'block';
        	        }
}


function dateFormat(val)
{
	if(val < 10)	return "0" + val;
	return ""+val+"";
}

function closeCalendar()
{
    oPopup.hide();

	if(ctlYY && ctlMM && ctlDD)
	{
		ctlYY.value = yearSelected;
		ctlMM.value = dateFormat(parseInt(monthSelected) + 1);
		ctlDD.value = dateFormat(parseInt(dateSelected));
	}
	else if(ctlYmd)
	{
		ctlYmd.value = yearSelected + '-' + dateFormat( parseInt(monthSelected) + 1) + '-' + dateFormat( parseInt(dateSelected) );
		onSelectDate(ctlYmd.value);
	}

}

function onSelectDate(dd)
{
}


function constructCalendar()
{
    var aNumDays = Array (31,0,31,30,31,30,31,31,30,31,30,31);
    var startDate = new Date (yearSelected,monthSelected,1);
    var endDate;
    var intWeekCount = 1;

    if(monthSelected==1)    numDaysInMonth = ((yearSelected % 4 == 0 && yearSelected % 100 != 0) || yearSelected % 400 == 0)? 29:28;
    else                    numDaysInMonth = aNumDays[monthSelected];

    datePointer = 0;
    dayPointer = startDate.getDay() - startAt;

    if(dayPointer<0)        dayPointer = 6;

    sHTML = "<table width='140' border='0' cellspacing='0' cellpadding='0' class='tbl_calendar'><tr height=7><td></td></tr><tr height='20'>";

    for(var i=1; i <= dayPointer; i++) sHTML += "<td></td>";

    for(datePointer=1; datePointer<=numDaysInMonth; datePointer++)
    {
        dayPointer++;

        sStyle = "text-decoration:none;cursor:hand;";

		fontCr = "#767676";
        if((datePointer==odateSelected) && (monthSelected==omonthSelected) && (yearSelected==oyearSelected))
        {
        	// 현재 선택된 날짜
        	fontCr = "#010101";
        	sStyle += "border:1px solid #a0a0a0;font-weight:bold;background-color:#f1f1f1";
        }
        if((datePointer == dateNow) && (monthSelected+1 == monthNow) && (yearSelected == yearNow)) 				fontCr = '#000000'; // 현재 오늘 날짜
        else if((dayPointer % 7 == (startAt * -1))) 															fontCr = '#0252e4';	// 토요일일때
        else if((dayPointer % 7 == (startAt * -1)+1)) 															fontCr = '#e10000'; // 일요일일때


        sHTML += "<td style='"+sStyle+"'>";
        sHTML += "<div align='absmiddle' style='width:100%;height:100%' onclick='dateSelected="+datePointer + ";closeCalendar();'><font color='"+fontCr+"'>" + datePointer + "</font></div>";
        sHTML += "</td>";
        if((dayPointer+startAt) % 7 == startAt)
        {
            sHTML += "</tr><tr height='20'>";
            intWeekCount ++;
        }
    }

    sHTML += "</tr></table>";

    sHTML += "<center><table width='130' border='0' cellspacing='0' cellpadding='0' class='tbl_calendar'><tr height=4><td></td></tr><tr height=1><td width='100%'></td></tr><tr height='22'>";
    sHTML += "<td width='100%' style='border-top:1px solid #ededed;border-bottom:1px solid #ededed' align='left'><a href='javascript:' onClick='yearSelected="+yearNow+";monthSelected="+(monthNow-1)+";dateSelected="+dateNow+";closeCalendar();'><img src='/img/calendar/btn_today.gif' align='absmiddle'></a> &nbsp;"+yearNow+"."+monthNow+"."+dateNow+"</td>";
    sHTML += "</tr></table></center>";


    if (((dayPointer+startAt) % 7) == 0) intWeekCount--;

    var yearSelectBox = "<select id='__year' onChange=\"_newDate(this.options[this.selectedIndex].value, '')\">";
    for(var i=yearSelected-5; i <= maxYear; i++)
    {
        yearSelectBox += "<option value='"+i+"' " + ((i==yearSelected)? "selected":"") +">"+i+"</option>";
    }
    yearSelectBox += "</select>";

    var monthSelectBox = "<select id='__month' onChange=\"_newDate('', this.options[this.selectedIndex].value)\">";
    for(var i=0; i < 12; i++)
    {
        monthSelectBox += "<option value='"+i+"' " + ((i==monthSelected)? "selected":"") +">"+monthName[i]+"</option>";
    }
    monthSelectBox += "</select>";

    var baseHeight= 199;
    var popHeight;
    if (intWeekCount == 5) 		popHeight = baseHeight+20;
    else if(intWeekCount == 6) 	popHeight = baseHeight+20+20;
    else				   		popHeight = baseHeight;

    //if(ie)
    //{
    //    oPopup.document.getElementById("dcontent").innerHTML = sHTML;
    //    oPopup.document.getElementById("spanYear").innerHTML =  yearSelectBox;
    //    oPopup.document.getElementById("spanMonth").innerHTML =  monthSelectBox;
    //}
    //else
    {
        oPopup.content.innerHTML = sHTML;
        oPopup.year.innerHTML =  yearSelectBox;
        oPopup.month.innerHTML =  monthSelectBox;
    }

    oPopup.show(cleft, ctop, 159, popHeight, document.body);
}

function _newDate(year, month)
{
    if(year != '')  yearSelected = year;
    if(month != '') monthSelected = month;
    constructCalendar();
}

function popUpCalendar(ctlyy, ctlmm, ctldd)
{
	ctlYY = ctlyy;
	ctlMM = ctlmm
	ctlDD = ctldd;
    yearSelected 	= Number(ctlYY.value);
    monthSelected 	= Number(ctlMM.value) - 1;
    dateSelected 	= Number(ctlDD.value);

    popUp();
}

function popUpCalendarYmd(ctlymd, pid)
{
    if(ctlymd.length > 1)
    {
        for(i=0; i < ctlymd.length; i++)
        {
            if(ctlymd[i].type=='text')
            {
                ctlYmd = ctlymd[i];
                break;
            }
        }
    }
    else
    {
        ctlYmd = ctlymd;
    }

	var dateValue = ctlYmd.value.split("-");
	if(dateValue.length == 3)
	{
        yearSelected 	= Number(dateValue[0]);
        monthSelected 	= Number(dateValue[1]) - 1;
        dateSelected 	= Number(dateValue[2]);
    }
    popUp(ctlymd, pid);
}


function popUp(obj, pid)
{
    var pos = $(obj).position();
	cleft = pos.left;
	ctop = pos.top + $(obj).height()+3;

	if(pid && pid !== undefined)
	{
	    var ppos = $("#"+pid).position();
    	cleft += ppos.left+3;
    	ctop += ppos.top+3;
	    //alert(cleft);
	    //alert(ctop);
	}

    if(isNaN(dateSelected) || isNaN(monthSelected) || isNaN(yearSelected))
    {
        dateSelected 	= dateNow;
        monthSelected 	= monthNow-1;
        yearSelected 	= yearNow;
    }
    odateSelected	= dateSelected;
    omonthSelected	= monthSelected;
    oyearSelected	= yearSelected;

    maxYear = (yearSelected > yearNow)? yearSelected:yearNow;
    maxYear++;

    constructCalendar (1, monthSelected, yearSelected);
}






function getCalendarString()
{
    var dis = (ie)? 'block':'none';
    strCalendar = "";
    strCalendar += "<div id='calendar' style='z-index:999;position:absolute; display:"+dis+"'>";
    strCalendar += "<table width='150' border='1' cellspacing='0' cellpadding='0' bordercolor='#858585' bgcolor='#ffffff'>";
    strCalendar += "  <tr>";
    strCalendar += "    <td align='center' valign='top'>";
    strCalendar += "    <table width='140' border='0' cellspacing='0' cellpadding='0'>";
    strCalendar += "      <tr>";
    strCalendar += "        <td height='20' valign='top'><span id='caption'>";
    strCalendar += "          <table width='140' border='0' cellspacing='0' cellpadding='0'>";
    strCalendar += "            <tr>";
    strCalendar += "              <td width='70' style='padding:5 0 0 12'><span id='spanYear'></span></td>";
    strCalendar += "              <td width='57' style='padding:5 0 0 2'><span id='spanMonth'></span></td>";
    strCalendar += "              <td width='13' align='right' valign='middle' style='padding:2 2 0 0;'><span style='cursor:hand' onClick=\""+_hideFunc+"\"><img src='/img/bt_calendar_close.gif' border='0' align='absmiddle'></a></td>";
    strCalendar += "            </tr>";
    strCalendar += "          </table>";
    strCalendar += "        </span></td>";
    strCalendar += "      </tr>";
    strCalendar += "      <tr height=1>";
    strCalendar += "        <td style='padding:3px' bgcolor=#ffffff>"+ getCalendarContent() + "</td>";
    strCalendar += "      </tr>";
    strCalendar += "    </table>";
    strCalendar += "    </td>";
    strCalendar += "  </tr>";
    strCalendar += "</table>";
    strCalendar += "</div>";

    return strCalendar;
}

function getCalendarContent()
{
    strCalendar = "<div id='dcontent'></div>";
    return strCalendar;
}

function getCalendarYear()
{
    strCalendar = "<div id='selectYear' style='z-index:+999;position:absolute;display:none;'></div>";
    return strCalendar;
}

function getCalendarMonth()
{
    strCalendar = "<div id='selectMonth' style='z-index:+999;position:absolute;display:none;'></div> ";
    return strCalendar;
}
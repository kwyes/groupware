var g_UploadDir = '/home/hangroup/www/uploads/';

$(document).ready(function()
{
    $("input[type='checkbox']").click ( function(){ this.blur() } );
    $("input[type='radio']").click ( function(){ this.blur() } );

    $(".bt_type2").live('mouseover', function(){$(this).attr('class', 'bt_type1'); });
    $(".bt_type1").live('mouseout' , function(){$(this).attr('class', 'bt_type2'); });
    /* DOCTYPE 선언시 왼쪽메뉴 크기 sync 위해..
    if(window.addEventListener) window.addEventListener("resize", gSyncHeight, false);
    else if(window.attachEvent) window.attachEvent("onresize", gSyncHeight);

    gSyncHeight();
    */
});

function _mobile_mail(mail, rtn)
{
    document.location.href = "/mobile/mail_write.php?to="+mail+"&rtn_url="+rtn;
}

function selGoto(sel)
{
    document.location.href = sel.options[sel.selectedIndex].value;
}

function popupNote()
{
    window.open('/service/load/note_list.php','__noteList__',
                'toolbar=0,location=0,directories=0, status=0,menubar=0,scrollbars=yes,resizable=0,width=360,height=680');
}

function right_frame_menu(tab)
{
    if(tab == 'sms')
    {
        $("#__right__frame__").attr("src", "/service/load/frame_sms.php");
    }
    else // if(tab == 'note')
    {
        $("#__right__frame__").attr("src", "/service/load/note_list.php");
    }
}

function getScheduleIndex(schmgno)
{
    for(var i=0; i < schedules.length; i++)
    {
        if( schedules[i].schmgno == schmgno ) return i;
    }

    return false;
}

function getAnniversaryIndex(annmgno)
{
    for(var i=0; i < anniversaries.length; i++)
    {
        if( anniversaries[i].annmgno == annmgno ) return i;
    }

    return false;
}

function getCategoryIndex(catmgno)
{
    for(var i=0; i < categories.length; i++)
    {
        if( categories[i].catmgno == catmgno ) return i;
    }

    return false;
}

function getAnniversaryHtml(ann)
{
    var orgdate = (ann.solar == '1')? "(양력)" + ann.orgdate : "(음력)" + ann.orgdate;
    var html =
    "<b>기념일</b> : "+ ann.summary + " ("+ann.anndate+")<br><b>실제날짜</b> : " + orgdate;

    return html;
}

function getScheduleHtml(cat, sch)
{
    var dateStr = '';
    if( sch.dtstart.substr(0,10) != sch.dtend.substr(0,10) )
    {
        dateStr = "(" + sch.dtstart.substr(0,10) + " ~ " + sch.dtend.substr(0,10) + ")<br>" ;
    }

    var html =
    "<b><font color='"+cat.catrgb+"'>"+cat.catname+"</font></b> : <b>"+ sch.summary + "</b><br><br>\n"+
    dateStr + sch.description.replace(/\n/g, "<br>") ;

    if(cat.catshare != 'N') html += "<br>작성자 : " + sch.writer;

    return html;
}


var __addressList = [];
var __g =
{
    version : '1.0',

    __ua : navigator.userAgent.toLowerCase(),

    isIE        : function() { return this.__ua.indexOf("msie") > -1; },
	isOpera     : function() { return this.__ua.indexOf("opera") > -1 },
	isChrome    : function() { return this.__ua.indexOf("chrome") > -1 },
	isFF        : function() { return this.__ua.indexOf("firefox") > -1 },
	isSafari    : function() { return (/webkit|khtml/).test(this.__ua) },
	isSafari3   : function() { return (__isSafari && this.__ua.indexOf("webkit/5") !== -1) },
	isIE7       : function() { return (!__isOpera && this.__ua.indexOf("msie 7") > -1) },
	isIE8       : function() { return (!__isOpera && this.__ua.indexOf("msie 8") > -1) },
	isGecko     : function() { return (!__isSafari && this.__ua.indexOf("gecko") > -1) },
	isGecko3    : function() { return (!__isSafari && this.__ua.indexOf("rv:1.9") > -1) }
};


__g.util = { package : 'util' };
__g.util.StringBuffer = function (str)
{
	var buffer = [], len = 0;

	if (typeof str === 'string')
	{
		buffer.push(str);
		len += str.length;
	}

	return {
		append  : function (s)
		{
			if (typeof s !== 'string') {
				var str = s.toString();
				buffer.push(str);
				len += str.length;
			} else {
				buffer.push(s);
				len += s.length;
			}
			return this;
		},
		push    : function (s) { return this.append(s); },
		join    : function (s) { return buffer.join(s); },
		isEmpty : function ()  { return buffer.length === 0 ? true : false; },
		length  : function ()  { return len; },
		size    : function ()  { return buffer.length; },
		toString: function ()  { return buffer.join(''); }
	};
};


/* YYYY-MM-DD HH24:MI:SS */
function getDateObj(dateString)
{
    var tt ;
    if(dateString)
    {
        var bufStr = dateString.replaceAll("-", "");
        bufStr = bufStr.replaceAll(" ", "");
        bufStr = bufStr.replaceAll(":", "");

        var yy = bufStr.substr(0, 4);
        var mm = bufStr.substr(4, 2)-1;
        var dd = bufStr.substr(6, 2);
        var hh = bufStr.substr(8, 2);
        var mi = bufStr.substr(10, 2);
        var ss = bufStr.substr(12, 2);

        tt = new Date(yy, mm , dd, hh, mi, ss);
    }
    else
    {
        tt = new Date();
    }

/*
    var today = {
      szDateStr   : tt.getFullYear() + '-' + zeroFill(tt.getMonth()+1, 2) + '-' + zeroFill(tt.getDate(), 2),
      szDateStr2  : tt.getFullYear() + '' + zeroFill(tt.getMonth()+1, 2) + '' + zeroFill(tt.getDate(), 2),
      szHour      : zeroFill(tt.getHours(),2),
      szMinute    : zeroFill(tt.getMinutes(),2),
      szTime      : zeroFill(tt.getHours(),2) + ':' + zeroFill(tt.getMinutes(),2),
      objDate     : tt
    };

    return today;
*/

    return tt;
}

function ShowFlash(sfilename, iwidth, iheight)	 //파일명, 폭, 높미
{
	var _showflash = '';

	_showflash = '<object classid=clsid:D27CDB6E-AE6D-11cf-96B8-444553540000 codebase=https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,22,0 width='+ iwidth +' height='+ iheight +'>';
	_showflash = _showflash + '<param name=movie value='+ sfilename +'>';
	_showflash = _showflash + '<param name=allowScriptAccess value=always>';
	_showflash = _showflash + '<param name=wmode value=transparent>';
	_showflash = _showflash + '<param name=quality value=high>';
	_showflash = _showflash + '<embed src='+ sfilename +' quality=high pluginspage=https://www.macromedia.com/go/getflashplayer type=application/x-shockwave-flash allowscriptaccess=always swliveconnect=true width=237 height=476></embed></object>';

    document.write(_showflash);
}

//플래쉬2:

function newflash(path, width, height, name){
	document.write('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'+width+'" height="'+height+'" codebase="https://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" id="'+name+'">'
	+'<param name="movie" value="'+path+'">'
	+'<param name="quality" value="high">'
	+'<param name="wmode" value="transparent">'
	+'<param name="allowScriptAccess" value="always">'
	+'<embed src="'+path+'" quality="high" wmode="transparent" pluginspage="https://www.macromedia.com/go/getflashplayer" width="'+width+'" height="'+height+'" type="application/x-shockwave-flash" showLiveConnect="true" name="'+name+'" allowScriptAccess="always"></embed>'
	+'</object>');
}

String.prototype.trim = function() { return this.replace(/(^\s*)|(\s*$)/gi, ""); }
String.prototype.startsWith = function(str)
{

    n_val = this.substr(0, str.length);
    /*
    alert(
        'org : ' + this + '\n'+
        'str : ' + str + '\n'+
        'n_val : ' + n_val
    );
    */
    return (n_val == str)? true:false;
}

var topMenuInterval;
function chgSubMenuHeight(k){
	clearTimeout(topMenuInterval);
	if(k=="1") document.getElementById("menuDiv").style.height=210;
	else if(k=="0") topMenuInterval = setTimeout(function(){document.getElementById("menuDiv").style.height=69;},500);
}


function toggle_obj(id)
{
    $("#"+id).toggle();
}


function getSelectedRadioStr(r)
{
	if(!r) return '';

	if(r.length == null)
	{
		// is not radio button.
		return r.value;
	}

	for(var i=0; i < r.length; i++)
	{
		if(r[i].checked) return r[i].value;
	}

	return false;
}


function getSelectedCheckboxStr(cbox, delim)
{
	if(!cbox) return '';

	if(cbox.length == null)
	{
		// is not checkbox
		return cbox.value;
	}

	var szValue = '';
	for(var i=0; i < cbox.length; i++)
	{
		if(cbox[i].checked)
		{
		    if(szValue != '') szValue += delim;
		    szValue += cbox[i].value ;
		}
	}

	return szValue;
}



function openWindow(theURL,winName,features)
{
    window.open(theURL,winName,features);
}

function loading_message(txt)
{
    var rtnStr =
     "<br><br><br><br><br><br><br><br><center><div style='border-width:1px;border-style:solid;border-color:#d2d2d2;background-color:#ffffff;z-index:10;PADDING:0px 0px 0px 0px;display:block;width:95%;height:53px;'>\n"
    +"<table width='100%' border='0' cellspacing='0' cellpadding='0' class='left_menu'>\n"
    +"  <tr>\n"
    +"    <td height='53' align='center' background='/img/webmail/loading_bg.gif' bgcolor='#f4f4f4' style='padding:3px 0 0 0px;'>\n"
    +"    <table border='0' cellspacing='0' cellpadding='0'>\n"
    +"      <tr>\n"
    +"        <td><img src='/img/webmail/loading.gif' width='32' height='32'></td>\n"
    +"        <td width='5'></td>\n"
    +"        <td id='loading_message'>"+txt+"</td>\n"
    +"      </tr>\n"
    +"    </table>\n"
    +"    </td>\n"
    +"  </tr>\n"
    +"</table>\n"
    +"</div></center>\n";

    return rtnStr;
}




function chkall(id, chked)
{
    $("#"+id+" .chkbox").attr("checked", chked);

    if(chked)
    {
        $("#"+id+" tr[rel='_list']").find("td").addClass("seled");
    }
    else
    {
        $("#"+id+" tr[rel='_list']").find("td").removeClass("seled");
    }
}

var lastcheckedseq = 0;
function bind_tableList(tid)
{
    var obj = document.getElementById(tid);
    if(obj)
    {
        $(".chkbox").click(function(e){
            _chkbox(this, e);
        });

        // selection
    	$("#"+tid+" td[rel='selection']").mousedown(function(e)
    	{
    	    lineSelection(this, e);
    	    $("#"+tid+" td[rel='selection']").bind('mouseenter', function(){
    	        lineSelection(this, e);
    	    });

    	    $("body").mouseup(function(){
    	        $("td[rel='selection']").unbind('mouseenter');
    	    });

    	    $("body").mouseleave(function(){
    	        $("td[rel='selection']").unbind('mouseenter');
    	    });
    	});
    }
}

function lineSelection(obj, e)
{
    var mgno = $(obj).attr("mgno");
    var chked = !$("#_chk"+mgno).attr("checked");
    $("#_chk"+mgno).attr("checked", chked);

    var shift = (e)? e.shiftKey:event.shiftKey;
    if($("#_chk"+mgno).attr("type") == "checkbox")
    {
        if(chked)
        {
            $(obj).parent().addClass("seled");
            if(shift && lastcheckedseq > 0)
            {
                //alert(lastcheckedseq);
                rangeSelect($("#_chk"+mgno).attr("seq"), chked);
            }
            lastcheckedseq = $("#_chk"+mgno).attr("seq");
        }
        else
        {
            $(obj).parent().removeClass("seled");
            if(shift && lastcheckedseq > 0)
            {
                rangeSelect($("#_chk"+mgno).attr("seq"), chked);
            }
            lastcheckedseq = $("#_chk"+mgno).attr("seq");
        }
    }
}

function _chkbox(obj, e)
{
    var chked = $(obj).attr("checked");
    var shift = (e)? e.shiftKey:event.shiftKey;

    if(chked)
    {
        $(obj).parent().parent().addClass("seled");
        if(shift && lastcheckedseq > 0)
        {
            rangeSelect($(obj).attr("seq"), chked);
        }
        lastcheckedseq = $(obj).attr("seq");
    }
    else
    {
        $(obj).parent().parent().removeClass("seled");
        if(shift && lastcheckedseq > 0)
        {
            rangeSelect($(obj).attr("seq"), chked);
        }
        lastcheckedseq = $(obj).attr("seq");
    }
}

function rangeSelect(to, flag)
{
    to = parseInt(to, 10);
    lastcheckedseq = parseInt(lastcheckedseq, 10);

    var _min = (to > lastcheckedseq)? lastcheckedseq:to;
    var _max = (to > lastcheckedseq)? to:lastcheckedseq;

    for(var i=_min; i < _max; i++)
    {
        $(".chkbox:eq("+i+")").attr("checked", flag);
        if(flag)    $(".chkbox:eq("+i+")").parent().parent().addClass("seled");
        else        $(".chkbox:eq("+i+")").parent().parent().removeClass("seled");
    }
}














function getMobile(i_mobile)
{
    if(i_mobile.length == 0) return '';

    var mobile = i_mobile.replace(/[^0-9]?/g,'');
    if(isNaN(mobile) || (mobile.length != 10 && mobile.length != 11) )
    {
        return false;
    }
    var _f = mobile.substr(0, 3);
    var _m = mobile.substr(3, (mobile.length == 10)? 3:4);
    var _l = mobile.substr( mobile.length-4);

    if(_f != '010' && _f != '011' && _f != '016' && _f != '017' && _f != '018' && _f != '019')
    {
        return false;
    }
    mobile = _f + '-' + _m + '-' + _l;

    return mobile;
}

function isValidEmail(email)
{
    var reg_email=/^[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[@]{1}[-A-Za-z0-9_]+[-A-Za-z0-9_.]*[.]{1}[A-Za-z]{2,5}$/;
    //var reg_email=/^([0-9a-zA-Z_\.-]+)@([0-9a-zA-Z_-]+)(\.[0-9a-zA-Z_-]+){1,2}$/;
    //if(email.match(/^([\w-]+)@([\w-]+)[.]([\w-]+)$/ig) == null && email.match(/^([\w-]+)@([\w-]+)[.]([\w-]+)[.]([\w-]+)$/ig) == null)
    if (email.search(reg_email) == -1)
    {
        return false;
    }

    return true;
}

/*
function getChosung(name)
{
    a = name;
    hanTable=new Array();
    hanTable[0]='ㄱㄲㄴㄷㄸㄹㅁㅂㅃㅅㅆㅇㅈㅉㅊㅋㅌㅍㅎ'; // 19 초성
    hanTable[1]='ㅏㅐㅑㅒㅓㅔㅕㅖㅗㅘㅙㅚㅛㅜㅝㅞㅟㅠㅡㅢㅣ'; //21 중성
    hanTable[2]=' ㄱㄲㄳㄴㄵㄶㄷㄹㄺㄻㄼㄽㄾㄿㅀㅁㅂㅄㅅㅆㅇㅈㅊㅋㅌㅍㅎ'; //28 종성

    chostr = "";
    str="";
    for(i=0;i<a.length;i++)
    {
        b=a.charCodeAt(i);
        hcode=b-0xAC00;
        //hanTable='ㄱㄲㄳㄴㄵㄶㄷㄸㄹㄺㄻㄼㄽㄾㄿㅀㅁㅂㅃㅄㅅㅆㅇㅈㅉㅊㅋㅌㅍㅎㅏㅐㅑㅒㅓㅔㅕㅖㅗㅘㅙㅚㅛㅜㅝㅞㅟㅠㅡㅢㅣ ';
        cho=new Array();
        cho[0]=parseInt(hcode / 588); //초성
        hcode2=hcode % 588;
        cho[1]=parseInt(hcode2 / 28); //중성
        cho[2]=hcode2 % 28; //종성 ㄱ,,,ㄴ

        m=new Array();

        //초성
        m[0]=Math.floor((b-0xAC00)/(21*28));
        //중성
        m[1]=Math.floor(((b-0xAC00)%(21*28))/28);
        //종성
        m[2]=(b-0xAC00)%28;


        mun=new Array();
        mun[0]=hanTable[0].charAt(cho[0]);
        mun[1]=hanTable[1].charAt(cho[1]); //자음
        mun[2]=hanTable[2].charAt(cho[2]); //0번은 종성유무
        //0xAC00 + 초성순서번호(0번부터)*중성갯수*종성갯수 + 중성순서번호*종성갯수 + 종성순서번호

        //hap=String.fromCharCode(0xAC00+(cho[0]*21*28)+(cho[1]*28)+cho[2]);
        //str+=mun+"\n";

        //str+= mun[0]+mun[1]+mun[2]+"|";
        str+= mun[0];
    }
    return str;
}
*/

function replace_size(size)
{
    var csize = '';
    var msize = parseInt(size, 10);
    if(msize >= 1048576)
    {
        // 1024*1024
        csize = number_format( Math.round(msize/1048576) ) + "MB";
    }
    else if(msize >= 1024)
    {
        csize = number_format( Math.round(msize/1024) ) + "KB";
    }
    else
    {
        csize = number_format(msize) + "B";
    }

    return csize;
}


function number_format( number, decimals, dec_point, thousands_sep )
{
    var n = number, prec = decimals, dec = dec_point, sep = thousands_sep;

    n = !isFinite(+n) ? 0 : +n;
    prec = !isFinite(+prec) ? 0 : Math.abs(prec);
    sep = sep == undefined ? ',' : sep;

    var s = n.toFixed(prec), abs = Math.abs(n).toFixed(prec),
        _, i;

    if (abs >= 1000)
    {
        _ = abs.split(/\D/);
        i = _[0].length % 3 || 3;

        _[0] = s.slice(0,i + (n < 0)) + _[0].slice(i).replace(/(\d{3})/g, sep+'$1');

        s = _.join(dec || '.');
    }
    else
    {
        //alert('1:' + s);
        //s = abs.replace('.', dec_point);
        //alert('1:' + s);
    }

    return s;
}


function constraintValue(ctkind, strObj)
{
	var val = strObj.value;
	var chkval = '';
	var len = 0;


	if(ctkind == 'MOBILE')
	{
	    chkval = getMobile(strObj.value);
	    if(chkval == false)
	    {
	        strObj.value = '';
	    }
	    else
        {
	        strObj.value = chkval;
        }
	}
	else if(ctkind == 'FEE')
	{
		for(var i=0; i < val.length; i++)
		{
			if( (val.charAt(i) >= '0' && val.charAt(i) <= '9') || val.charAt(i) == '-')
			{
				chkval += val.charAt(i);
				len++;
			}
		}
		strObj.value = (chkval.length == 0)? '' : number_format(chkval);
	}
	else if(ctkind == 'NUMBER')
	{
		for(var i=0; i < val.length; i++)
		{
			if( (val.charAt(i) >= '0' && val.charAt(i) <= '9'))
			{
				chkval += val.charAt(i);
				len++;
			}
		}

		if(isNaN(parseInt(chkval, 10))) strObj.value = '';
		else						strObj.value = chkval;
	}
	else if(ctkind == 'YEAR')
	{
		for(var i=0; i < val.length; i++)
		{
			if( (val.charAt(i) >= '0' && val.charAt(i) <= '9'))
			{
				chkval += val.charAt(i);
				len++;
			}
		}

		if(chkval.length == 2)
		{
		    if(parseInt(chkval, 10) > 10) strObj.value = '19' + '' + chkval;
		    else                      strObj.value = '20' + '' + chkval;
		}
		else if(chkval.length == 4)
	    {
            strObj.value = chkval;
        }
        else
        {
            strObj.value = '';
        }
	}
	else if(ctkind == 'MONTH')
	{
		for(var i=0; i < val.length; i++)
		{
			if( (val.charAt(i) >= '0' && val.charAt(i) <= '9'))
			{
				chkval += val.charAt(i);
				len++;
			}
		}

		if(parseInt(chkval, 10) > 12 || isNaN(parseInt(chkval, 10))) strObj.value = '';
		else
		{
			if(chkval.length == 1) 		strObj.value = '0' + '' + chkval;
			else if(chkval.length == 2)	strObj.value = chkval;
		}
	}
	else if(ctkind == 'DAY')
	{
		for(var i=0; i < val.length; i++)
		{
			if( (val.charAt(i) >= '0' && val.charAt(i) <= '9'))
			{
				chkval += val.charAt(i);
				len++;
			}
		}
		if(parseInt(chkval, 10) > 31 || isNaN(parseInt(chkval, 10))) strObj.value = '';
		else
		{
			if(chkval.length == 1) 		strObj.value = '0' + '' + chkval;
			else if(chkval.length == 2)	strObj.value = chkval;
		}
	}
	else if(ctkind == 'LDAY')
	{
		for(var i=0; i < val.length; i++)
		{
			if( (val.charAt(i) >= '0' && val.charAt(i) <= '9'))
			{
				chkval += val.charAt(i);
				len++;
			}
		}
		if(parseInt(chkval, 10) > 30 || isNaN(parseInt(chkval, 10))) strObj.value = '';
		else
		{
			if(chkval.length == 1) 		strObj.value = '0' + '' + chkval;
			else if(chkval.length == 2)	strObj.value = chkval;
		}
	}
	else if(ctkind == 'HOUR')
	{
		for(var i=0; i < val.length; i++)
		{
			if( (val.charAt(i) >= '0' && val.charAt(i) <= '9'))
			{
				chkval += val.charAt(i);
				len++;
			}
		}

		if(parseInt(chkval, 10) > 24 || isNaN(parseInt(chkval, 10))) strObj.value = '';
		else
		{
			if(chkval.length == 1) 		strObj.value = '0' + '' + chkval;
			else if(chkval.length == 2)	strObj.value = chkval;
		}
	}
	else if(ctkind == 'MINUTE' || ctkind == 'SECOND')
	{
		for(var i=0; i < val.length; i++)
		{
			if( (val.charAt(i) >= '0' && val.charAt(i) <= '9'))
			{
				chkval += val.charAt(i);
				len++;
			}
		}

		if(parseInt(chkval, 10) > 59 || isNaN(parseInt(chkval, 10))) strObj.value = '';
		else
		{
			if(chkval.length == 1) 		strObj.value = '0' + '' + chkval;
			else if(chkval.length == 2)	strObj.value = chkval;
		}
	}
	else if(ctkind == 'DATEFORMAT')
    {
        // YYYY.MM.DD
        chkval = val.replace(/[^\-0-9]?/g,'');

        if(chkval.length == 10)
        {
            if(chkval.charAt(4) == '-' && chkval.charAt(7) == '-')
            {
                if( isValidYear(chkval.substr(0, 4)) && isValidMonth(chkval.substr(5, 2)) && isValidDay(chkval.substr(8, 2)) )
                {
                    strObj.value = chkval;
                    return;
                }
            }
        }
        strObj.value = '';
    }
}
function isValidDay(dd)
{
    if( parseInt(dd, 10) > 0 && parseInt(dd, 10) < 32 ) return true;
    return false;
}

function isValidMonth(mm)
{
    if( parseInt(mm, 10) > 0 && parseInt(mm, 10) < 13 ) return true;
    return false;
}
function isValidYear(yyyy)
{
    if( parseInt(yyyy.substr(0,2), 10) >= 19 && parseInt(yyyy.substr(0,2), 10) <= 21 ) return true;
    return false;
}

function post_exe(_url, _param)
{
    $.post( _url,
            _param,
            function(data) {

        if(data) alert(data);
        else
        {
            //<![CDATA[
            document.location.reload();
            //]]>
        }
    });
}



function getAddressString(id)
{
    var str = "";
    $.each(__addressList,
        function(i, data)
        {
            if(data.mgno == id)
            {
                str = ""+data.name + " <" + data.email + ">";
            }
        });

    return str;
}


function zeroFill(str, cnt)
{
    str = '0000000000000000000'+str;
    return str.substr(str.length-cnt, cnt);
}



jQuery.fn.selectedOption = function() {
return $("#"+$(this).attr("id")+" :selected");
}

jQuery.fn.selectedText = function() {
return $(this).selectedOption().text();
}
jQuery.fn.selectedValue = function() {
return $(this).selectedOption().val();
}

jQuery.fn.setSelected = function(value)
{
    var sel = this;
    options = $(this).find('option');

    $.each(options, function(idx) {

        if ($(this).val() == value)
        {
            //alert(value + ' : ' + idx);
            //$(sel).selectedIndex = idx;
            $(this).attr("selected", true);
        }
        else
        {
            //$(this).attr("selected", false);
        }
    });
}

jQuery.fn.setSelectAll = function() {
options = $(this).find('option');
$.each(options, function() {
    $(this).attr("selected", "selected");
});
}

jQuery.fn.getOptionValue = function(seq) {
return $(this).find('option').eq(seq).val();
}

jQuery.fn.isExistOption = function(value) {
var rtn = false;
options = $(this).find('option');
$.each(options, function() {
if ($(this).val()==value) rtn = true;
});
return rtn;
}

jQuery.fn.addOption = function(value, text) {
id = "#"+$(this).attr("id");
if($(id))
{
    var option = document.createElement("option");
    option.value = value;
    option.text = text;
    $(id).get(0)[$(id+' option').length] = option;
}
}

jQuery.fn.clear = function() {
$("#"+$(this).attr("id")).children().remove();
}

jQuery.fn.removeSelectedOption = function() {
id = "#"+$(this).attr("id");
o = $(id).selectedOption();
$(o).remove();
}



Date.prototype.getWeek = function (dowOffset) {
/*getWeek() was developed by Nick Baicoianu at MeanFreePath: http://www.meanfreepath.com */

	dowOffset = typeof(dowOffset) == 'int' ? dowOffset : 0; //default dowOffset to zero
	var newYear = new Date(this.getFullYear(),0,1);
	var day = newYear.getDay() - dowOffset; //the day of week the year begins on
	day = (day >= 0 ? day : day + 7);
	var daynum = Math.floor((this.getTime() - newYear.getTime() -
	(this.getTimezoneOffset()-newYear.getTimezoneOffset())*60000)/86400000) + 1;
	var weeknum;
	//if the year starts before the middle of a week
	if(day < 4) {
		weeknum = Math.floor((daynum+day-1)/7) + 1;
		if(weeknum > 52) {
			nYear = new Date(this.getFullYear() + 1,0,1);
			nday = nYear.getDay() - dowOffset;
			nday = nday >= 0 ? nday : nday + 7;
			/*if the next year starts before the middle of
 			  the week, it is week #1 of that year*/
			weeknum = nday < 4 ? 1 : 53;
		}
	}
	else {
		weeknum = Math.floor((daynum+day-1)/7);
	}
	return weeknum;
};


String.prototype.replaceAll = function(exp1, exp2) { return this.split(exp1).join(exp2);};


function array_unique_json_push(arr, json)
{
    var exist = false;
    for(var i=0; i < arr.length; i++)
    {
        if(arr[i].key == json.key)
        {
            exist = true;
            break;
        }
    }
    if(!exist) arr[ arr.length ] = json;
}

function array_json_delete(arr, key)
{
    for(var i=0; i < arr.length; i++)
    {
        if(arr[i].key == key)
        {
            arr[i].key = "";
            arr[i].val = "";
        }
    }
}

function in_array(val, arr)
{
    if(arr.length == 0) return false;
    for(var len=0; len < arr.length; len++)
    {
        if(val == arr[len]) return true;
    }
    return false;
}

function dateDiff(sTday, eNday)
{
    var sTyear  = sTday.substr(0,4);
    var sTmonth = sTday.substr(4,2);
    var sTday   = sTday.substr(6,2);
    var eNyear  = eNday.substr(0,4);
    var eNmonth = eNday.substr(4,2);
    var eNday   = eNday.substr(6,2);

    var sTall=new Date(sTyear, sTmonth-1, sTday);
    var eNall=new Date(eNyear, eNmonth-1, eNday);
    return ((eNall.getTime()-sTall.getTime())/(24*60*60*1000))+1;
}



function getDateDiff(sDateStr, eDateStr)
{
    var sTday = sDateStr.replaceAll('-','');
    var eNday = eDateStr.replaceAll('-','');

    return dateDiff(sTday, eNday);
}


function chkRegNumber(num)
{
	if (num.length == 13)
	{
		A = num.charAt(0);
		B = num.charAt(1);
		C = num.charAt(2);
		D = num.charAt(3);
		E = num.charAt(4);
		F = num.charAt(5);
		G = num.charAt(6);
		H = num.charAt(7);
		I = num.charAt(8);
		J = num.charAt(9);
		K = num.charAt(10);
		L = num.charAt(11);
		Osub = num.charAt(12);

		SUMM = A*2 + B*3 + C*4 + D*5+ E*6+ F*7+G*8+H*9+I*2+J*3+K*4+L*5;
		N = SUMM % 11;
		Modvalue = 11 - N;
		LastVal =  Modvalue % 10 ;
	}
	else if (num.length == 10)
	{
		a = num.charAt(0);
		b = num.charAt(1);
		c = num.charAt(2);
		d = num.charAt(3);
		e = num.charAt(4);
		f = num.charAt(5);
		g = num.charAt(6);
		h = num.charAt(7);
		i = num.charAt(8);
		Osub = num.charAt(9);

		suma = a*1 + b*3 + c*7 + d*1 + e*3 + f*7 + g*1 + h*3;
		sumb = (i*5) %10;
		sumc = parseInt((i*5) / 10,10);
		sumd = sumb + sumc;
		sume = suma + sumd;
		sumf = a + b + c + d + e + f + g + h + i;
		k = sume % 10;
		Modvalue = 10 - k;
		LastVal = Modvalue % 10;

		if (sumf == 0)
		{
			return false;
		}
	}
	else
	{
		return false;
	}

	return ( Osub == LastVal );
}

function str_repeat (input, multiplier) {
  // http://kevin.vanzonneveld.net
  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // +   improved by: Ian Carter (http://euona.com/)
  // *     example 1: str_repeat('-=', 10);
  // *     returns 1: '-=-=-=-=-=-=-=-=-=-='

  var y = '';
  while (true) {
    if (multiplier & 1) {
      y += input;
    }
    multiplier >>= 1;
    if (multiplier) {
      input += input;
    }
    else {
      break;
    }
  }
  return y;
}

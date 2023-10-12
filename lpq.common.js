

if(!String.prototype.padStart) {
    String.prototype.padStart = function(len,val) {
        var s = this;
        var v = val.toString();
        if(v.length > 0) {
            while(s.length < len) {
                s = v+""+s;
            }
        }
        return s;
    }
}
if(!String.prototype.padEnd) {
    String.prototype.padEnd = function(len,val) {
        var s = this;
        var v = val.toString();
        if(v.length > 0) {
            while(s.length < len) {
                s = s+""+v;
            }
        }
        return s;
    }
}

//function ce(tag) {return document.createElement(tag);}
function qs(str) {return document.querySelector(str);}
function qsa(str) {return document.querySelectorAll(str);}
function oqs(o,str) {if(o) return o.querySelector(str);return null;}
function oqsa(o,str) {if(o) return o.querySelectorAll(str);return null;}
function ce(str) {
	if(!str) str = "";
	var el = null;
	var tag = null;
	var id = null;
	var cls = [];
	var p1 = str.indexOf("#");
	if(p1 > 0) {
		var p2 = str.substr(p1+1).indexOf(".");
		if(p2 < 0) {
			id = str.substr(p1+1);
			str = str.substr(0,p1);
		} else {
			id = str.substr(p1+1,p2);
			str = str.substr(0,p1)+""+str.substr(p1+p2+1);
		}
	}
	var cls = str.split(".");
	if(cls.length > 0) tag = cls.shift();
	if(tag)  el = document.createElement(tag);
	if(el && id) el.setAttribute("id",id);
	if(el && cls && cls.length > 0) {
		for(var i = 0; i < cls.length; i++) {
			el.classList.add(cls[i]);
		}
	}
	return el;
}

function has_cls(o,cls) {
	if(!cls) cls = [];
	if(typeof(cls) == "string") {
		cls = [cls];
	} else if(!is_array(cls)) {
		cls = [];
	}
	if(o && cls.length > 0) {
		for(var i = 0; i < cls.length; i++) {
			if(!o.classList.contains(cls[i])) return false;
		}
		return true;
	}
	return false;
}
function add_cls(o,cls) {
	if(!cls) cls = [];
	if(typeof(cls) == "string") {
		cls = [cls];
	} else if(!is_array(cls)) {
		cls = [];
	}
	if(o && cls.length > 0) {
		for(var i = 0; i < cls.length; i++) {
			o.classList.add(cls[i]);
		}
	}
}
function rmv_cls(o,cls) {
	if(!cls) cls = [];
	if(typeof(cls) == "string") {
		cls = [cls];
	} else if(!is_array(cls)) {
		cls = [];
	}
	if(o && cls.length > 0) {
		for(var i = 0; i < cls.length; i++) {
			o.classList.remove(cls[i]);
		}
	}
}

function _attr(o,attrs) {
	if(o && Object.prototype.toString.call(attrs) == "[object Object]") {
		for(var x in attrs) {
			if(attrs[x]) o.setAttribute(x,attrs[x]);
		}
	}
}

function _append(o,ar) {
	if(o && ar.length > 0) {
		for(var i = 0; i < ar.length; i++) {
			o.appendChild(ar[i]);
		}
	}
}


//區間隨機數
function random(min,max) {
	return Math.floor(Math.random()*(max-min+1)+min);
}

//判斷是否數字
function is_numeric(val) {
	//return !(parseFloat(val).toString() == "NaN");
	return /^\-*?\d+(\.{0}|\.\d+)$/.test(val);
}
//判斷是否數組
function is_array(o) {
	return Object.prototype.toString.call(o) == "[object Array]";
}

function getcookie(key) {
	var ar = document.cookie.split(";");
	for(var i = 0; i < ar.length; i++) {
		var a = ar[i].trim().split("=");
		if(a.length == 2 && a[0] == key) {
			return a[1];
		}
	}
	return '';
}

function setcookie(key,val,exdays=0) {
	if(!key) {
		return false;
	}
	var str = '';
	str += key+"="+val;
	if(exdays != 0) {
		var d = new Date();
		d.setTime(d.getTime() + exdays*24*3600*1000);
		str += "; expires="+d.toGMTString();
	}
	document.cookie = str;
}


function ihttp(fd,fn) {
	
	function show_http_error(e) {
		var icon_close_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="20" height="20"><path d="M4,4 L16,16 M4,16 L16,4" fill="none" stroke="#000000" stroke-width="2"/></svg>';
		var html = '';
		html += '<div style="position:fixed;top:0;left:0;display:flex;flex-direction:column;z-index:9999;background-color:#fff;border:1px solid #c6c6c6;max-width:100%;max-height:70%;">';
		html += '<div style="display:flex;flex-direction:row;border-bottom:1px solid #c6c6c6;padding:5px;background-color:#d6d6d6;"><div style="flex:1;">發生錯誤</div><a style="cursor:pointer;">'+icon_close_svg+'</a></div>';
		html += '<div style="flex:1;overflow:auto;white-space:pre-wrap;padding:5px;">'+e.stack+'<hr>'+xhr.responseText+'</div>';
		html += '</div>';
		var div = document.createElement("div");
		div.innerHTML = html;
		div = div.children[0];
		var close = div.querySelector("a");
		close.onclick = function() {div.remove();}
		document.body.appendChild(div);
		//居中
		div.style.top = parseInt(window.innerHeight/2-div.offsetHeight/2)+"px";
		div.style.left = parseInt(window.innerWidth/2-div.offsetWidth/2)+"px";
	}
	
	var url = "json.php";
	var xhr = new XMLHttpRequest();
	//xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	xhr.onreadystatechange = function() {
		if(xhr.readyState == 4) {
			if(xhr.status == 200) {
				//console.log(xhr.responseText);
				try {
					var json = JSON.parse(xhr.responseText);
					fn(json);
				} catch(e) {
					show_http_error(e);
					console.log(xhr.responseText);
					console.log(e);
				}
			} else {
				console.error(xhr.status);
			}
		}
	};
	xhr.open("POST",url,true);
	xhr.send(fd);
}


function uuidCreate() {
    var d = new Date().getTime();
    if (window.performance && typeof window.performance.now === "function") {
        d += performance.now(); //use high-precision timer if available
    }
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r = (d + Math.random() * 16) % 16 | 0;    // d是随机种子
        d = Math.floor(d / 16);
        return (c == 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
    return uuid;
}
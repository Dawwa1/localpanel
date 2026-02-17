var t, w = window,
    d = document,
    shl = false,
    h = d.getElementsByTagName("HEAD")[0],
    m = 0;

function getWW() {
    return w.innerWidth;
}

function getWH() {
    return w.innerHeight;
}

function getDW() {
    return d.body.clientWidth;
}

function getDH() {
    return d.body.scrollHeight;
}

function gid(v) {
    return d.getElementById(v);
}

function gcn(v) {
    return d.getElementsByClassName(v);
}

function gtn(v) {
    return d.getElementsByTagName(v);
}

function out(v) {
    return d.write(v);
}

function showBr(v) {
    (getWW() < v) ? out("<br>"): out("&nbsp;|&nbsp;");
}

function ssset(k, v){
    return sessionStorage.setItem(k, v);
}

function ssget(k){
    return sessionStorage.getItem(k);
}

function ssrm(k){
    return sessionStorage.removeItem(k);
}

function callAPI(onload, method, endpoint, data=null){
    const xhr = new XMLHttpRequest();
    xhr.open(method, endpoint, true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = () => onload(xhr);
    if (data != null) { 
        const payload = { data: data }
        xhr.send(JSON.stringify(payload));
        return;
    }
    xhr.send();
}

function random(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function addCSS(css) {
    var st = d.createElement("style");
    st.type = "text/css";
    if (st.styleSheet) {
        st.styleSheet.cssText = css;
    } else st.appendChild(d.createTextNode(css));
    h.appendChild(st);
}

function eqHeight(cls) {
    var max = 0;
    var eqh = gcn(cls);
    for (var i = 0; i < eqh.length; i++) {
        var ps = eqh[i].getBoundingClientRect();
        if (ps.height > max) max = ps.height;
    }
    for (var i = 0; i < eqh.length; i++) eqh[i].style.height = max + "px";
}

function toTop() {
    if (d.body.scrollTop != 0 || d.documentElement.scrollTop != 0) {
        w.scrollBy(0, -80);
        t = setTimeout("toTop()", 5);
    } else clearTimeout(t);
}

function addOnLoad(func) {
    var ol = w.onload;
    if (typeof w.onload != "function") {
        w.onload = func;
    } else {
        w.onload = function() {
            if (ol) {
                ol();
            }
            func();
        }
    }
}

function addOnResize(func) {
    var ol = w.onresize;
    if (typeof w.onresize != "function") {
        w.onresize = func;
    } else {
        w.onresize = function() {
            if (ol) {
                ol();
            }
            func();
        }
    }
}

function addGoogleFonts(fs) {
    var tmp = fs.split('|');
    for (var i = 0; i < tmp.length; i++) {
        var f = d.createElement("link");
        f.rel = "stylesheet";
        f.href = "https://fonts.googleapis.com/css?family=" + tmp[i];
        h.appendChild(f);
    }
}

var t, w = window,
    d = document,
    shl = false,
    h = d.getElementsByTagName("HEAD")[0],
    m = 0;
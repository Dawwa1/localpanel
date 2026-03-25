function modal(content){
    let ul = gid('modal-list');

    var li = document.createElement('li');
    var modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = content;

    li.appendChild(modal);
    ul.appendChild(li);
    setTimeout(()=>{
        ul.removeChild(li);
    }, 5000)
}

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

function isset(v){
    if (typeof v === 'undefined' || v === null){
        return false;
    } else if (Array.isArray(v)){
        return v.length > 0;
    }
    return true;
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

function ssget(k=null){
    if (k){ return sessionStorage.getItem(k); }
    else {
        let characterData = {};

        let c=0;
        while (sessionStorage.key(c) != undefined || sessionStorage.key(c) != null){
            let k = sessionStorage.key(c);
            let v = sessionStorage.getItem(k);
            characterData[`${k}`] = v;
            c++;
        }

        return characterData;
    }
    
}

function ssclear(){
    return sessionStorage.clear();
}

function ssrm(k){
    return sessionStorage.removeItem(k);
}

function callAPI(onload, method, endpoint, action, data=null){
    const xhr = new XMLHttpRequest();
    xhr.open(method, endpoint, true);
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    if (onload != null){
        xhr.onload = () => onload(xhr);
    }
    if (data != null) { 
        const payload = {data: data, action: action}
        //console.log(payload);
        xhr.send(JSON.stringify(payload));
        return;
    }
    xhr.send();
}

function random(min, max, decimalPlaces=0){
    const rand = Math.random() * (max - min) + min;
    const fixed = rand.toFixed(decimalPlaces);
    return Number.parseFloat(fixed);
}

// has a {percentChance}% chance of returning true
function chance(percentChance){
    let chance = random(0, 100);
    if (chance < percentChance){
        return true;
    } else{
        return false
    }
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
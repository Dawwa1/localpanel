<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$shlRet = null;
if (count($_GET) > 0){
    $shlRet = shell_exec("sudo ./create_vhost " . $_GET['dirName'] . " " . $_GET['ip']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalPanel</title>
    <link rel="stylesheet" href="index.css">
    <script>
    // credit to http://yuryi.com
    var t,w=window,d=document,shl=!1,h=d.getElementsByTagName("HEAD")[0],m=0;function getWW(){return w.innerWidth}function getWH(){return w.innerHeight}function getDW(){return d.body.clientWidth}function getDH(){return d.body.scrollHeight}function gid(e){return d.getElementById(e)}function gcn(e){return d.getElementsByClassName(e)}function gtn(e){return d.getElementsByTagName(e)}function out(e){return d.write(e)}function showBr(e){getWW()<e?out("<br>"):out("&nbsp;|&nbsp;")}function ssset(e,n){return sessionStorage.setItem(e,n)}function ssget(e){return sessionStorage.getItem(e)}function ssrm(e){return sessionStorage.removeItem(e)}function callAPI(e,n,o,r=null){let i=new XMLHttpRequest;if(i.open(n,o,!0),i.setRequestHeader("Content-Type","application/json;charset=UTF-8"),i.onload=()=>e(i),null!=r){i.send(JSON.stringify({data:r}));return}i.send()}function random(e,n){return Math.floor(Math.random()*(n-e+1))+e}function addCSS(e){var n=d.createElement("style");n.type="text/css",n.styleSheet?n.styleSheet.cssText=e:n.appendChild(d.createTextNode(e)),h.appendChild(n)}function eqHeight(e){for(var n=0,o=gcn(e),r=0;r<o.length;r++){var i=o[r].getBoundingClientRect();i.height>n&&(n=i.height)}for(var r=0;r<o.length;r++)o[r].style.height=n+"px"}function toTop(){0!=d.body.scrollTop||0!=d.documentElement.scrollTop?(w.scrollBy(0,-80),t=setTimeout("toTop()",5)):clearTimeout(t)}function addOnLoad(e){var n=w.onload;"function"!=typeof w.onload?w.onload=e:w.onload=function(){n&&n(),e()}}function addOnResize(e){var n=w.onresize;"function"!=typeof w.onresize?w.onresize=e:w.onresize=function(){n&&n(),e()}}function addGoogleFonts(e){for(var n=e.split("|"),o=0;o<n.length;o++){var r=d.createElement("link");r.rel="stylesheet",r.href="https://fonts.googleapis.com/css?family="+n[o],h.appendChild(r)}}var t,w=window,d=document,shl=!1,h=d.getElementsByTagName("HEAD")[0],m=0;</script>
</head>
<body>
    <div id="panel">
        <div id="top">
            <h1>LocalPanel</h1>
            <?php
            if (isset($shlRet)){"<h2>$shlRet</h2>";}
            ?>
        </div>

        <div id="mid">
            <div id="sites" style="display:flex;">
                <ul>
                    <?php
                    $sites = shell_exec('ls /var/www');
                    $op = preg_split('/\s+/', $sites, -1, PREG_SPLIT_NO_EMPTY);

                    //for ($i=0;$i<10;$i++){
                    //    array_push($op, bin2hex(random_bytes(5)));
                    //}

                    foreach ($op as $s) {
                        echo "
                        <li>
                        <button>
                        <a href='http://localhost/$s'><img src='imgs/site.svg'></a>
                        </button>
                        <label>$s</label>
                        </li>
                        ";
                    }
                    ?>
                </ul>
            </div>
            <div id="create-vhost-panel" style="display:none;">
                <form action="/" method="get">
                    <input type="text" name="dirName" id="dirname-fld" placeholder="Site Directory Name: " required>
                    <input type="text" name="ip" id="ip" placeholder="Site IP (ex: 127.0.0.1): " required>
                    <input type="submit" id="sbmt" value="Create VirtualHost">
                </form>
            </div>
        </div>

        <div id="btm">
            <ul>
                <li>
                    <button id="pma">
                        <a href="http://localhost/phpmyadmin"><img src="imgs/pma.svg" alt="phpmyadmin"></a>
                    </button>
                </li>
                <li>
                    <button id="add-vhost" onclick="'none'==gid('create-vhost-panel').style.display?(gid('create-vhost-panel').style.display='flex',gid('sites').style.display='none'):(gid('create-vhost-panel').style.display='none',gid('sites').style.display='flex'); return false;">
                        <img src="imgs/add-vhost.svg" alt="add vhost">
                    </button>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
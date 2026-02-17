<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$shlRet = null;
if (count($_GET) > 0 && isset($_GET['dirName'], $_GET['ip'])){
    if (preg_match("^127\.\d\.\d\.\d$", $_GET['ip'])){
        $shlRet = shell_exec("sudo ./create_vhost " . $_GET['dirName'] . " " . $_GET['ip']);
    }
}
if (isset($_GET['db-fld'])){
    $dbStr = explode("/", $_GET['db-fld']);
    $dbN = trim($dbStr[0]);
    $dbU = isset($dbStr[1]) ? trim($dbStr[1]) : $dbN;
    $dbP = isset($dbStr[2]) ? trim($dbStr[2]) : $dbN;
    $db_con = new mysqli("localhost", "root", "");
    $db_con->query("CREATE USER '$dbU'@'localhost' IDENTIFIED BY '$dbP'");
    $db_con->query("CREATE DATABASE $dbN");
    $db_con->query("GRANT ALL ON $dbN.* TO '$dbU'@'localhost'");
    $db_con->close();
    var_dump($shlRet);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalPanel</title>
    <link rel="stylesheet" href="index.css">
    <script src="fw.js"></script>
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
                    <label for="vh-chkbx">Create Virtual Host?</label>
                    <input type="checkbox" name="vh-chkbx" id="vh-chkbx" value="1">
                    
                    <input class="vh" type="text" name="dirName" id="dirname-fld" placeholder="Site Directory Name: ">
                    <input class="vh" type="text" name="ip" id="ip" placeholder="Site IP (ex: 127.0.0.1): ">

                    <label for="db-chkbx">Create Database?</label>
                    <input type="checkbox" name="db-chkbx" id="db-chkbx" value="1">

                    <input type="text" class="db" name="db-fld" id="db-fld" placeholder="Name / User / Pass">
                    <script>
                        
                    </script>
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
<script>
    function odl(){
        let db_chk = gid("db-chkbx");
        let vh_chk = gid("vh-chkbx");
        let db = [...document.querySelectorAll(".db")];
        let vh = [...document.querySelectorAll(".vh")];
        db_chk.addEventListener('change', function(){
            db.forEach(e => {
                if (db_chk.checked){
                    db.forEach(e => {
                        e.style.display="flex";
                    });
                }else{
                    e.style.display="none";
                }
            });
        });
        vh_chk.addEventListener('change', function(){
            vh.forEach(e => {
                if (vh_chk.checked){
                    vh.forEach(e => {
                        e.style.display="flex";
                    });
                }else{
                    e.style.display="none";
                }
            });
        });
    }
    addOnLoad(odl);
</script>
</body>
</html>
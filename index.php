<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DBO
{
    protected static $inst;
    private $dbc;
    private function __construct()
    {
        $this->dbc = new mysqli("localhost", "locpan", "locpan", "locpan");
        if ($this->dbc->connect_error) {
            die("Connection failed: " . $this->dbc->connect_error);
        }
    }
    public static function getInstance()
    {
        if (!self::$inst) {
            self::$inst = new DBO();
        }
        return self::$inst;
    }
    public function q($sql)
    {
        return $this->dbc->query($sql);
    }
    public function s($sql)
    {
        $res = $this->q($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : false;
    }
}

function liveExecuteCommand($cmd)
{

    while (@ob_end_flush())
        ; // end all output buffers if any
    $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');
    $live_output = "";
    $complete_output = "";
    while (!feof($proc)) {
        $live_output = fread($proc, 4096);
        $complete_output .= $live_output;
        @flush();
    }
    pclose($proc);
    // get exit status
    preg_match('/[0-9]+$/', $complete_output, $matches);
    // return exit status and intended output
    return ['exit_status' => intval($matches[0]), 'output' => str_replace("Exit status : " . $matches[0], '', $complete_output)];
}

function siteExists($dirName)
{
    $dbo = DBO::getInstance();
    $res = $dbo->s("SELECT * FROM sites WHERE name='$dirName'");
    return !empty($res);
}

function addSite($dirName, $ip, $dom)
{
    $dbo = DBO::getInstance();
    //$shlRet = liveExecuteCommand("sudo ./create_vhost $dirName $ip $dom");
    if (!siteExists($dirName)) {
        $dbo->q("INSERT INTO sites (name, ip, dom) VALUES ('$dirName', '$ip', '$dom')");
    } else{
        return ['exit_status' => 1, 'output' => "Site with the name '$dirName' already exists."];
    }


    //return $shlRet;
}

$shlRet = null;
if (count($_GET) > 1 && isset($_GET['dirName'], $_GET['ip'], $_GET['dom']) && $_GET['vh-chkbx'] == "1") {
    if (preg_match("^127\.\d\.\d\.\d$^", $_GET['ip'])) {
        $shlRet = addSite($_GET['dirName'], $_GET['ip'], $_GET['dom']);
    } else {
        $shlRet = ['exit_status' => 1, 'output' => "Invalid IP address format. Please use the format 127.x.x.x"];
    }
}
if (isset($_GET['db-fld']) && $_GET['db-chkbx'] == "1") {
    $dbStr = explode("/", $_GET['db-fld']);
    $dbN = trim($dbStr[0]);
    $dbU = isset($dbStr[1]) ? trim($dbStr[1]) : $dbN;
    $dbP = isset($dbStr[2]) ? trim($dbStr[2]) : $dbN;
    $db_con = new mysqli("localhost", "admin", "1122");
    $db_con->query("CREATE USER '$dbU'@'localhost' IDENTIFIED BY '$dbP'");
    $db_con->query("CREATE DATABASE $dbN");
    $db_con->query("GRANT ALL ON $dbN.* TO '$dbU'@'localhost'");
    $db_con->close();
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Figtree:ital,wght@0,300..900;1,300..900&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap');
        html, body{
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: "Figtree", sans-serif;
            color: white;
            scrollbar-width: none;
            overflow-y:visible;
            overflow-x:hidden;
            background-color: #37353E;
        }
        body {
            display: grid;
            grid-template-columns: 1fr 5fr 1fr;
            grid-template-rows: 1fr 5fr 1fr;
            place-items: center;
            height: 100vh;
            min-height: 0;
        }
        @keyframes modal-popin {
            0%   { opacity: 0; transform: translateY(-20px); }
            50%  { opacity: 1; transform: translateY(0); }
            75%  { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-20px); display: none;}
        }
        #modal-loc{
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        #modal-loc ul{
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .modal{
            height: fit-content;
            margin-top: 10px;
            background-color: black;
            padding: 15px;
            text-align: center;
            border-radius: 15px;
            animation: modal-popin 5s ease-out forwards;
        }
        #panel{
            grid-row: 2;
            grid-column: 2;
            display: grid;
            grid-template-rows: 1fr 4fr 1fr;
            
            background-color: #44444E;
            height: 100%;
            width: 90%;
            border: 5px solid #715A5A;
            border-radius: 15px;
            min-height: 0;
            overflow: hidden;
        }
        #panel #top{
            grid-row: 1;
            display: flex;
            height: 100%;

            background-color: #61616F;
            border-radius: 10px;
            justify-content: center;
            align-items: center;
        }

        #panel #mid{
            grid-row: 2;
            display: grid;
            width: 100%;
            place-items: center;
            min-height: 0;
        }
        #panel #mid #sites{
            min-height: 0;
            display: flex;
            align-items: flex-start;
            width: 100%;
            height: calc(100% - 20px);
            overflow-y: auto;
            overflow-x: hidden;
            box-sizing: border-box;
            padding: 10px;
        }
        #panel #mid #sites ul{
            display: flex; 
            width: 100%; 
            height: 100%;
            list-style-type: none;
            padding: 0;
            margin: 0;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        #panel #mid #sites ul li{
            width: 150px;
            height: fit-content;
            display: flex;
            flex-direction: column;
            text-align: center;
            background-color: #33333AFF;
            padding: 5px;
            border-radius: 15px;
            transition: background-color 0.25s ease-in-out;
        }
        #panel #mid #sites ul li:hover{
            background-color: rgb(86, 86, 92);
        }
        #panel #mid #sites ul button{
            border: none;
            cursor: pointer;
            all: unset;
        }
        #panel #mid #sites ul img{
            height: 128px;
            width: 128px;
        }

        #panel #mid #create-vhost-panel {
            align-items: center;
            justify-content: center;

            width: 40%;
            height: 90%;
            background-color: #000;
            border-radius: 15px;
        }
        #panel #mid form {
            display: flex;
            flex-direction: column;
            text-align: center;
        }
        #panel #mid form input {
            padding: 10px;
            margin: 10px auto;
            border-radius: 10px;
            border: none;
        }
        input[type='text']{
            display: none;
        }
        #panel #btm {
            grid-row: 3;
            background-color: #61616f;
            border-radius: 10px;
            padding: 5px;
            display: flex; 
            align-items: center; 
        }

        #panel #btm ul {
            display: flex; 
            width: 100%; 
            list-style-type: none;
            padding: 0;
            margin: 0;
            justify-content: space-around; 
            align-items: center;
        }

        #panel #btm ul li button {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        #panel #btm ul #add-vhost img{
            height: 64px;
        }
        #panel #btm ul #pma img{
            margin-top: 5px;
            height: 80px;
        }
    </style>
</head>

<body>
    <div id="modal-loc">
        <ul id="modal-list">
            <script>
                <?php
                if ($shlRet['exit_status'] == 0) {
                    $ret = $shlRet['output'];
                    echo "modal('Virtual Host created successfully');";
                    echo "console.log(`$ret`);";
                } else if (isset($shlRet) && $shlRet['exit_status'] != 0) {
                    $ret = $shlRet['output'];
                    $code = $shlRet['exit_status'];
                    echo "modal(`Error ($code): $ret`);";
                    echo "console.error(`Error ($code): $ret`);";
                }
                ?>
            </script>
        </ul>
    </div>
    <div id="panel">
        <div id="top">
            <h1>LocalPanel</h1>
        </div>

        <div id="mid">
            <div id="sites" style="display:flex;">
                <ul>
                    <?php
                    $dbo = DBO::getInstance();
                    $sites = $dbo->s("SELECT * FROM sites");

                    foreach ($sites as $s) {
                        $name = $s['name'];
                        $ip = $s['ip'];
                        $dom = $s['dom'];

                        echo "
                        <li class='site'>
                            <button title='$ip'>
                                <a href='http://$dom'><img src='imgs/site.svg'></a>
                            </button>

                            <label>$name</label>
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
                    <input class="vh" type="text" name="dom" id="dom"
                        placeholder="/etc/hosts Domain (ex: example.dd): ">

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
                    <button id="add-vhost"
                        onclick="'none'==gid('create-vhost-panel').style.display?(gid('create-vhost-panel').style.display='flex',gid('sites').style.display='none'):(gid('create-vhost-panel').style.display='none',gid('sites').style.display='flex'); return false;">
                        <img src="imgs/add-vhost.svg" alt="add vhost">
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <script>
        function odl() {
            let db_chk = gid("db-chkbx");
            let vh_chk = gid("vh-chkbx");
            let db = [...document.querySelectorAll(".db")];
            let vh = [...document.querySelectorAll(".vh")];
            db_chk.addEventListener('change', function () {
                db.forEach(e => {
                    if (db_chk.checked) {
                        db.forEach(e => {
                            e.style.display = "flex";
                        });
                    } else {
                        e.style.display = "none";
                    }
                });
            });
            vh_chk.addEventListener('change', function () {
                vh.forEach(e => {
                    if (vh_chk.checked) {
                        vh.forEach(e => {
                            e.style.display = "flex";
                        });
                    } else {
                        e.style.display = "none";
                    }
                });
            });
        }
        addOnLoad(odl);
    </script>
</body>

</html>
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DBO{
    protected static $inst;
    private $dbc;
    private function __construct(){ $this->dbc = new mysqli("localhost", "locpan", "locpan", "locpan"); if ($this->dbc->connect_error) { die("Connection failed: " . $this->dbc->connect_error); }}
    public static function getInstance(){ if (!self::$inst) {self::$inst = new DBO(); } return self::$inst; }
    public function q($sql){ return $this->dbc->query($sql); }
}

function liveExecuteCommand($cmd){

    while (@ ob_end_flush()); // end all output buffers if any
    $proc = popen("$cmd 2>&1 ; echo Exit status : $?", 'r');
    $live_output     = "";
    $complete_output = "";
    while (!feof($proc))
    {
        $live_output     = fread($proc, 4096);
        $complete_output .= $live_output;
        @ flush();
    }
    pclose($proc);
    // get exit status
    preg_match('/[0-9]+$/', $complete_output, $matches);
    // return exit status and intended output
    return ['exit_status'  => intval($matches[0]), 'output' => str_replace("Exit status : " . $matches[0], '', $complete_output)];
}

function addSite($dirName, $ip, $dom){
    $dbo = DBO::getInstance();
    $shlRet = liveExecuteCommand("sudo ./create_vhost $dirName $ip $dom");
    $dbo->q("INSERT INTO sites (dir_name, ip, domain) VALUES ('$dirName', '$ip', '$dom')");


    return $shlRet;
}

$shlRet = null;
if (count($_GET) > 1 && isset($_GET['dirName'], $_GET['ip'], $_GET['dom']) && $_GET['vh-chkbx'] == "1"){
    if (preg_match("^127\.\d\.\d\.\d$^", $_GET['ip'])){
        $shlRet = addSite($_GET['dirName'], $_GET['ip'], $_GET['dom']);
    } else {
        $shlRet = ['exit_status' => 1, 'output' => "Invalid IP address format. Please use the format 127.x.x.x"];
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
    if (!$shlRet) {
        $shlRet = ['exit_status' => 0, 'output' => "Database '$dbN' created successfully with user '$dbU'"];
    } else {
        $shlRet['output'] .= "\nDatabase '$dbN' created successfully with user '$dbU'";
    }
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
    <div id="modal-loc">
        <ul id="modal-list">
            <script>
            <?php
            if ($shlRet['exit_status'] == 0){
                $ret = $shlRet['output'];
                echo "modal('Virtual Host created successfully');";
                echo "console.log(`$ret`);";
            } else if (isset($shlRet) && $shlRet['exit_status'] != 0){
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
                    <input class="vh" type="text" name="dom" id="dom" placeholder="/etc/hosts Domain (ex: example.dd): ">

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
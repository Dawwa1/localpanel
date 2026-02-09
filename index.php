<?php

echo $_SERVER['REQUEST_URI'];

//if ($_SERVER['REQUEST_URI'] == ){
//
//}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LocalPanel</title>
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
            grid-template-rows: 1fr 4fr 1fr;
            place-items: center;
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
        }

        #panel #top, #panel #btm{
            background-color: #61616F;
            border-radius: 10px;
            padding: 5px;
            display: flex;
        }

        #panel #top{
            grid-row: 1;
            justify-content: center;
            align-items: center;
        }

        #panel #mid{
            grid-row: 2;
            display: grid;
            grid-template-rows: 1fr 2fr 1fr;

            height: 100%;
            width: 100%;
        }

        #panel #btm{
            grid-row: 3;
        }
    </style>
</head>
<body>
    <div id="panel">
        <div id="top">
            <h1>LocalPanel</h1>
        </div>

        <div id="mid">
            test
            <?php
                echo "TEST";
            ?>
        </div>

        <div id="btm">

        </div>
    </div>
</body>
</html>
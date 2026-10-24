<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>LocalPanel</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arimo:ital,wght@0,400..700;1,400..700&family=Figtree:ital,wght@0,300..900;1,300..900&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap');
        html, body{
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            font-family: "Roboto Mono", sans-serif;
            color: white;
            scrollbar-width: none;
            overflow-y:visible;
            overflow-x:hidden;
            background-color: #37353E;
        }
        body {
            display: grid;
            grid-template-columns: 1fr 6fr;
            grid-template-rows: 50px 5fr;
            place-items: center;
            height: 100vh;
            min-height: 0;
        }
        @keyframes fadeIn-FromTop{
            0%{
                opacity: 0;
                transform: translateY(-50px);
            }
            50%{
                opacity: .5;
                transform: translateY(5px);
            }
            100%{
                opacity: 1;
                transform: translateY(0);
            }
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
        header {
            display: flex;
            background-color: #27262d;
            grid-column: 2;
            grid-row: 1;
            width: 100%;
            height: 100%;
            border-left: #232329 solid 5px;
            flex-direction: row;
            align-items: center;
            box-sizing: border-box;
        }
        header #status-bars {
            width: min(60%, 520px);
        }
        header #status-bars{
            margin-left: auto;
        }
        header #warn {
            display: flex;
            width: min(60%, 150px);
            align-items: center;
            justify-content: center;
        }
        header #warn img{
            display: none;
            width: 32px;
            height: 32px;
            transform: scale(1);
        }
        header #warn img:hover {
            cursor: pointer;
            transform: scale(1.15);
        }
        #stat-list {
            display: flex;
            align-items: center;
            justify-content: space-around;
            width: 100%;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        #stat-list li {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }
        #stat-list img {
            display: block;
            width: 20px;
            height: 20px;
        }
    </style>
</head>
<body>

<div id="modal-loc">
    <ul id="modal-list">
    </ul>
</div>

<?= \Localpanel\View::render('partials/header', [
    'site_count' => $site_count ?? 0,
    'db_count' => $db_count ?? 0,
    'db_storage' => $db_storage ?? 0,
    'storage_used' => $storage_used ?? 0,
    'max_storage' => $max_storage ?? 0,
]) ?>

<?= \Localpanel\View::render('partials/sidebar', ['nonce' => $nonce]) ?>

<?= $content ?>

</body>
</html>
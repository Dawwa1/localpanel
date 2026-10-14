<?php
use Localpanel\Models\DatabaseModel;
use function React\Async\await;

$database = DatabaseModel::getInstance();

$site_count = await($database->countSites());
$db_count = await($database->query("SELECT COUNT(*) FROM information_schema.SCHEMATA WHERE SCHEMA_NAME NOT IN ('mysql', 'information_schema', 'performance_schema', 'sys')"))[0]['COUNT(*)'] ?? 0;
$db_storage = await($database->query("SELECT SUM(data_length + index_length) FROM information_schema.TABLES WHERE table_schema NOT IN ('mysql', 'information_schema', 'performance_schema', 'sys')"))[0]['SUM(data_length + index_length)'] / 1024 ?? 0;
?>

<header>
    <div id="status-bars">
        <ul id="stat-list">
            <li id="servers-running">
                <img src="/assets/comps.svg" alt="db">
                <span><?= $site_count?></span>
            </li>
            <li id="dbs-running">
                <img src="/assets/database.svg" alt="db">
                <span><?= $db_count?> : <?= $db_storage ?>KB</span>
            </li>
        </ul>
    </div>
</header>
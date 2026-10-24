<style>
    .c-menu {
        display: none;
        justify-content: center;
        align-items: center;
        position: absolute;
        background-color: #535761;
        border: 5px solid #6a6874;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        border-radius: 5px;
        padding: 10px;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .c-menu ul {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px 10px;
        gap: 15px;
        margin: 0;
        list-style-type: none;
    }
    .c-menu ul li {
        cursor: pointer;
        padding: 10px 15px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .c-menu ul li:hover {
        background-color: #716c7e;
    }
</style>

<div id="site-c-menu" class="c-menu">
    <ul>
        <li>Manage Site</li>
        <li>Delete Site</li>
        <li>View Logs</li>
        <?php if (isset($untracked) && $untracked) {
            echo '<li>Track Site</li>';
        }
        ?>
    </ul>
</div>
<style>
    #site-cards {
        display: grid;
        grid-column: 2;
        grid-row: 2;
        width: 100%;
        height: 100%;
        min-width: 0;
        min-height: 0;
        padding: 15px;
        box-sizing: border-box;
        overflow-y: auto;
        grid-template-rows: 1fr 50px;
        gap: 10px;
    }
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }
    #site-table {
        display: block;
        width: 100%;
        height: 100%;
        box-sizing: border-box;
        overflow: auto;
        border: 3px solid #3b3944;
        border-radius: 12px;
        padding: 4px;
    }
    #sites {
        display: table;
        height: auto;
        width: 100%;
        margin: 0 auto;
        border-collapse: separate;
        border-spacing: 0 12px;
    }
    #sites thead th {
        padding: 0 18px 6px;
        color: #9996a5;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .1em;
        text-align: left;
        text-transform: uppercase;
    }
    #sites tbody td {
        padding: 18px;
        background: #302f38;
        border-top: 1px solid #3b3944;
        border-bottom: 1px solid #3b3944;
        color: #d8d6df;
        font-size: 16px;
        vertical-align: middle;
    }
    #sites tbody td.sel-row-active,
    #sites tbody td:first-child.sel-row-active,
    #sites tbody td:last-child.sel-row-active{
        border-color: #29aa56;
    }
    #sites tbody td:first-child {
        border-left: 1px solid #3b3944;
        border-radius: 10px 0 0 10px;
    }
    #sites tbody td:last-child {
        border-right: 1px solid #3b3944;
        border-radius: 0 10px 10px 0;
        text-align: right;
    }
    #sites .site-name {
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        letter-spacing: .05em;
    }
    #sites .site-domain,
    #sites .site-root {
        color: #aaa7b4;
        font-size: 13px;
    }
    #sites a{
        text-decoration: underline;
        color: #cfcfcf;
        text-underline-offset: 5px;
        text-decoration-color: #6e6e6e;
        transition: color .2s ease, text-decoration-color .2s ease, font-weight .2s ease;
    }
    #sites a:hover,
    #sites a:focus {
        text-decoration: none;
        font-weight: bold;
    }
    .site-actions {
        display: flex;
        gap: 16px;
    }
    .site-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        padding: 0 15px;
        border: 1px solid #514d63;
        border-radius: 7px;
        background: #3b3849;
        color: #e8e6ef;
        font: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color .2s ease, border-color .2s ease;
    }
    .site-button:hover,
    .site-button:focus-visible {
        border-color: #797293;
        background: #49445b;
        outline: none;
    }
    .table-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 36px;
        width: 36px;
        min-height: 0;
        font-size: 24px;
        text-align: center;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        line-height: 1;
    }
    .sort-btn {
        display: inline-block;
        all: unset;
        color: #e8e6ef;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: transform .2s ease;
        user-select: none;
    }
    .sort-btn[data-sortby="true"] {
        transform: rotate(180deg);
    }
    .sort-btn[data-sortby="false"] {
        transform: rotate(0deg);
    }
    .delete-button {
        background: #633c43;
        border-color: #81505a;
    }
    .delete-button img{
        width: 24px;
        height: 24px;
    }
    .delete-button:hover,
    .delete-button:focus-visible {
        background: #78464f;
        border-color: #9c6c75;
    }
    .create-button {
        background: #0c7a4e;
        border-color: #29aa56;
    }
    .create-button img{
        width: 24px;
        height: 24px;
    }
    .create-button:hover, .create-button:focus-visible {
        background: #138f5e;
        border-color: #37cf6c;
    }
    #page-changer {
        grid-row: 2;
        background: #302f38;
        border-radius: 15px;
        box-shadow: 0 15px 15px rgba(0, 0, 0, .16), 0 5px 5px rgba(0, 0, 0, .08);
        display: flex;
        align-items: center;
        justify-content: space-around;
        justify-self: end;
        margin: 0 10% 0 0;
        width: 35%;
        text-align: center;
        user-select: none;
    }
    #pg-in-entry{
        all: unset;
        background: #302f38;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
    }
    .pg-button {
        all: unset;
        background: #302f38;
        color: #fff;
        font-size: 16px;
        cursor: pointer;
    }
    .page-shown{
        display: table-row;
    }
    .page-hidden{
        display: none;
    }
    .sel-row {
        width: 12px;
        height: 12px;
        border: 1px solid #514d63;
        border-radius: 4px;
        background: #3b3849;
        cursor: pointer;
    }
</style>

<main id="site-cards">
    <div id="site-table">
        <table id="sites">
            <thead>
            <tr>
                <th scope="col">Name <button class="sort-btn" data-sortby="false">↑</button></th>
                <th scope="col">IP Address <button class="sort-btn" data-sortby="false">↑</button></th>
                <th scope="col">Domain <button class="sort-btn" data-sortby="false">↑</button></th>
                <th scope="col">Document Root <button class="sort-btn" data-sortby="false">↑</button></th>
                <th scope="col">Date Created <button class="sort-btn" data-sortby="false">↑</button></th>
                <th scope="col">
                    <div class="site-actions">
                        <button class="site-button table-button create-button" type="button" onclick="togglePopup(document.getElementById('create-cont'), 'block'); return false;" title="Create a new virtual host">
                                <img src="/assets/add-vhost.svg" alt="add"></button>
                        <button class="site-button table-button delete-button" type="button" onclick="togglePopup(document.getElementById('delete-cont'), 'block'); return false;" title="Delete selected virtual host">
                                <img src="/assets/delete.svg" alt="trash"></button>
                    </div>
                </th>
                <th scope="col"><span class="sr-only">Actions</span></th>
            </tr>
            </thead>
            <tbody>
            <?php
            // $sites will be passed into home.php, will contain array of entire database
            // optimization could be made in future to only fetch current page
            $pageNum = 1;
            $currentPage = 1;
            $maxPerPage = 10; // Default value, can be changed based on user selection
            if (isset($sites) && is_array($sites)) {
                for ($i = 0; $i < count($sites); $i++) {
                    $site = $sites[$i];

                    $pageStatus = ($pageNum === $currentPage) ? 'page-shown' : 'page-hidden';

                    if (($i+1) % $maxPerPage === 0 && $i !== 0) {
                        $pageNum++;
                    }
                    echo '<tr class="site-card ' . $pageStatus . '" data-page="' . $pageNum . '">';
                    echo '<td class="site-name"> <input type="checkbox" class="sel-row">' . htmlspecialchars($site->name) . '</td>';
                    echo '<td class="site-ip">' . htmlspecialchars($site->ip) . '</td>';
                    echo '<td class="site-domain"><a href="http://' . htmlspecialchars($site->domain) . '">' . htmlspecialchars($site->domain) . '</a></td>';
                    echo '<td class="site-root">' . htmlspecialchars($site->root_path) . '</td>';
                    echo '<td class="site-date">' . htmlspecialchars($site->created_at->format('m-d-Y')) . '</td>';
                    echo '<td><button class="site-button manage-button" type="button"><p title="Manage settings of this site">Manage</p></button></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="6">No sites found.</td></tr>';
            }
            ?>
        </table>
    </div>
    <div id="page-changer">
        <span id="page-info">
            <button id="prev-page" class="pg-button" onclick="pageDown(); return false;"><</button> Page <span id="page-num">1</span> of <span id="total-pages">1</span> <button id="next-page" class="pg-button" onclick="pageUp(); return false;">></button>
        </span>
        <select name="pg-in-entry" id="pg-in-entry" onchange="addSites(document.getElementById('pg-in-entry').value); return false;">
            <option value="5">5 per page</option>
            <option value="10" selected>10 per page</option>
            <option value="15">15 per page</option>
        </select>
    </div>
</main>

<?= \Localpanel\View::render('partials/context', ['nonce' => $nonce]) ?>

<script src="/scripts/menu.js" nonce="<?= $nonce ?>"></script>

<script nonce="<?= $nonce ?>">
    let sortbtns = document.querySelectorAll('.sort-btn');
    sortbtns.forEach(btn => {
        btn.addEventListener('click', (() => {
            const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;

            const comparer = (idx, asc) => (a, b) => ((v1, v2) =>
                    v1 !== '' && v2 !== '' && !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.toString().localeCompare(v2)
            )(getCellValue(asc ? a : b, idx), getCellValue(asc ? b : a, idx));

            const th = btn.closest('th');
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            Array.from(tbody.querySelectorAll('tr'))
                .sort(comparer(Array.from(th.parentNode.children).indexOf(th), this.asc = !this.asc))
                .forEach(tr => tbody.appendChild(tr) );

            Array.from(sortbtns).forEach(btn => {
                btn.setAttribute('data-sortby', 'false');
            });

            btn.setAttribute('data-sortby', this.asc ? 'true' : 'false');
        }));
    });

    let selBtns = document.querySelectorAll('.sel-row');
    selBtns.forEach(btn => {
        btn.addEventListener('change', () => {
            let row = btn.closest('tr');
            let rowtd = row.querySelectorAll('td');
            if (btn.checked) {
                row.classList.add('row-selected');
                rowtd.forEach(td => {
                    td.classList.add('sel-row-active');
                });
                deleteListAdd(row);
            } else {
                row.classList.remove('row-selected');
                rowtd.forEach(td => {
                    td.classList.remove('sel-row-active');
                });
                deleteListRemove(row);
            }
        });
    });

</script>

<script nonce="<?= $nonce ?>">

    function pageUp() {
        let pageNum = parseInt(document.getElementById('page-num').textContent);
        changePage(pageNum + 1);
    }

    function pageDown() {
        let pageNum = parseInt(document.getElementById('page-num').textContent);
        changePage(pageNum - 1);
    }

    function changePage(pageNum){
        let maxPage = document.getElementById('total-pages').textContent;
        let curPage = document.getElementById('page-num');
        if (pageNum < 1 || pageNum > maxPage) {
            console.error(`Invalid page number: ${pageNum}. Must be between 1 and ${maxPage}.`);
            return;
        }
        let tbody = document.querySelector('#sites tbody');
        let siteCards = tbody.querySelectorAll('.site-card');
        siteCards.forEach(siteCard => {
            if (siteCard.getAttribute('data-page') === pageNum.toString()) {
                siteCard.classList.remove('page-hidden');
                siteCard.classList.add('page-shown');
            } else {
                siteCard.classList.remove('page-shown');
                siteCard.classList.add('page-hidden');
            }
        });
        curPage.textContent = pageNum;
    }

    function deleteRow(row){
        fetch()
    }

    function deleteSite(){
        let selectedRow = document.querySelectorAll('.row-selected');
        if (selectedRow.length > 0) {
            let confirmDelete = confirm(`Are you sure you want to delete ${selectedRow.length} sites?`);

            if (confirmDelete) {
                selectedRow.forEach(row => {
                    row.remove();
                });

                addSites();
            }
        }
    }

    //debug for plus button
    function __createSite() {
        // Implementation for creating a new site
        let perPage = parseInt(document.getElementById('pg-in-entry').value);
        let tbody = document.querySelector('#sites tbody');
        let tPages = document.querySelector('#total-pages');
        let oCard = document.querySelector('.site-card');
        let siteCard = oCard.cloneNode(true);

        let currentPage = parseInt(document.getElementById('page-num').textContent);
        let pageNum = Math.floor((tbody.childElementCount) / perPage) + 1;
        siteCard.setAttribute('data-page', pageNum);

        if (currentPage !== pageNum) {
            siteCard.classList.remove('page-shown');
            siteCard.classList.add('page-hidden');
        } else {
            siteCard.classList.remove('page-hidden');
            siteCard.classList.add('page-shown');
        }

        tPages.textContent = (Math.floor((tbody.childElementCount) / perPage)+1).toString();

        function generateRandomDate(daysAhead=30) {
            const now = new Date().getTime();
            const msAhead = Math.random() * daysAhead * 24 * 60 * 60 * 1000;
            return new Date(now + msAhead);
        }

        siteCard.querySelector('.site-name').textContent = 'new-site-'+(tbody.childElementCount + 1);
        siteCard.querySelector('.site-date').textContent = generateRandomDate().toLocaleDateString();

        tbody.appendChild(siteCard);
    }

    // production, will sort added sites from PHP into pages
    function addSites(){
        let perPage = parseInt(document.getElementById('pg-in-entry').value);
        let tbody = document.querySelector('#sites tbody');
        let tPages = document.querySelector('#total-pages');
        let siteCards = tbody.querySelectorAll('.site-card');
        let currentPage = 1;
        let pageNum = 1;

        siteCards.forEach((siteCard, index) => {
            if (index % perPage === 0 && index !== 0) {
                pageNum++;
            }
            siteCard.setAttribute('data-page', pageNum);
            if (pageNum !== currentPage) {
                siteCard.classList.remove('page-shown');
                siteCard.classList.add('page-hidden');
            } else {
                siteCard.classList.remove('page-hidden');
                siteCard.classList.add('page-shown');
            }
        });

        tPages.textContent = pageNum.toString();
    }
</script>
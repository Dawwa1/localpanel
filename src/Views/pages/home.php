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
    .restart-button {
        background: #633c43;
        border-color: #81505a;
    }
    .restart-button:hover,
    .restart-button:focus-visible {
        background: #78464f;
        border-color: #9c6c75;
    }
    .create-button {
        background: #0c7a4e;
        border-color: #29aa56;
    }
    .create-button:hover, #create-button:focus-visible {
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
</style>

<main id="site-cards" onload="addSites(); return false;">
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
                        <button class="site-button table-button create-button" type="button" onclick="createPage(); return false;"><p title="Create a new virtual host">+</p></button>
                        <button class="site-button table-button restart-button" type="button"><p title="Restart Apache">⟳</p></button>
                    </div>
                </th>
                <th scope="col"><span class="sr-only">Actions</span></th>
            </tr>
            </thead>
            <tbody>
            <?php
                // $sites will be passed into home.php, will contain array of entire database
                $pageNum = 1;
                $currentPage = 1;
                if (isset($sites) && is_array($sites)) {
                    for ($i = 0; $i < count($sites); $i++) {
                        $site = $sites[$i];

                        $pageStatus = ($pageNum === $currentPage) ? 'page-shown' : 'page-hidden';

                        if ($i % 10 === 0 && $i !== 0) {
                            $pageNum++;
                        }
                        echo '<tr class="site-card ' . $pageStatus . '" data-page="' . $pageNum . '">';
                        echo '<td class="site-name">' . htmlspecialchars($site->name) . '</td>';
                        echo '<td>' . htmlspecialchars($site->ip) . '</td>';
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

<!--suppress CssUnknownTarget -->
<style>
    #create-cont{
        grid-row: 2;
        grid-column: 2;
        position: relative;
        height: 100%;
        width: 100%;
        pointer-events: none;
    }
    .create-cont{
        pointer-events: all;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 500px;
        max-height: 550px;
        width: 50%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        background-color: rgb(62 62 62 / 0.95);
        border-radius: 30px;
        border: 3px solid #00000055;
        padding: 25px 5px;
    }
    .create-cont form{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        margin-bottom: 20px;
        min-height: fit-content;
        animation: fadeIn-FromTop 0.5s ease-out forwards;
    }
    .create-cont form label {
        color: #fff;
        font-size: 18px;
        margin-bottom: 10px;
    }
    .create-cont form label[for="create-db"] {
        font-size: 15px;
    }
    .create-cont form input {
        width: 80%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
    }
    .create-cont form input[type="text"] {
        background-color: #1c1c1c;
        color: #fff;
    }
    .create-cont form button {
        padding: 10px 20px;
        width: 80%;
        background-color: #0c7a4e;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        margin-top: auto;
    }
    .create-cont form button:hover {
        background-color: #138f5e;
    }
    .create-cont #db-option {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 50%;
    }
    .create-cont #db-option input {
        margin: 0 0 7px 0;
    }
    .create-cont #db-option input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }
    .create-cont #db-option input[type="checkbox"]:checked {
        background-color: #0c7a4e;
    }
    .create-cont #db-option label {
        white-space: nowrap;
    }
    .create-cont #db-create {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: none;
    }
    .create-cont #db-create.is-opening {
        animation: fadeIn-FromTop 0.5s ease-out forwards;
    }
    @keyframes fadeOut-ToTop {
        0%{
            opacity: 1;
            transform: translateY(0);
        }
        50%{
            opacity: .5;
            transform: translateY(5px);
        }
        100%{
            opacity: 0;
            transform: translateY(-50px);
        }
    }

    .create-cont #db-create.is-closing {
        animation: fadeOut-ToTop 0.5s ease-out forwards;
    }
    .create-cont #db-create p {
        display: flex;
        align-items: center;
    }
    .create-cont .db-separator {
        flex: 0 0 auto;
        margin: 0 8px;
        font-size: 24px;
        line-height: 1;
    }
    .create-cont .blank-in {
        display: inline-block; /* Flows naturally with the surrounding text */
        width: 100px;          /* Gives a defined size to the "blank" box */
        border: none;
        border-bottom: 2px solid #333; /* Transforms the box into a blank line underline */
        padding: 0 5px;
        font-size: inherit;    /* Keeps text consistency */
        text-align: center;
        background: transparent;
        white-space: nowrap;
    }
    .create-cont .vert-blanks{
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0;
        vertical-align: middle;
    }
    .create-cont .vert-blanks .blank-in {
        width: 100px;
        margin-bottom: 0;
    }
    .create-cont .vert-blanks label {
        font-size: 12px;
        margin-bottom: 0;
    }
    .create-cont #create-terminal {
        width: 95%;
        background-color: #1c1c1c;
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        font-size: 14px;
        margin-top: auto;
        overflow-x: auto;
    }
    @font-face {
        font-family: 'DOS';
        src: url('/assets/dos.ttf') format('truetype');
    }
    .create-cont #create-terminal code {
        font-family: 'DOS' !important;
        font-size: 16px;
        white-space: pre-wrap; /* Allows the code to wrap onto the next line */
        word-wrap: break-word; /* Breaks long words to prevent overflow */
    }
</style>

<div id="create-cont" style="display: none">
    <div class="create-cont">
        <form action="/api/site/create" method="GET">
            <label for="site-name">Site Name:</label>
            <input type="text" id="site-name" name="site-name" placeholder="Enter site name" required><br><br>

            <label for="site-ip">IP Address:</label>
            <input type="text" id="site-ip" name="site-ip" placeholder="Enter IP address" required><br><br>

            <label for="site-domain">Domain:</label>
            <input type="text" id="site-domain" name="site-domain" placeholder="Enter domain" required><br><br>

            <div id="db-option">
                <label for="create-db">Create Database:</label>
                <input type="checkbox" id="create-db" name="create-db" onclick="toggleDatabaseFields(this.checked); return true;">
            </div>
            <div id="db-create" style="visibility: hidden;">
                <p>
                    <span class="vert-blanks">
                        <input class="blank-in" type="text" name="db-name" placeholder="DB Name">
                        <label for="db-name">Database Name</label>
                    </span>
                    <span class="db-separator">/</span>
                    <span class="vert-blanks">
                        <input class="blank-in" type="text" name="db-user" placeholder="DB User">
                        <label for="db-user">Database User</label>
                    </span>
                    <span class="db-separator">/</span>
                    <span class="vert-blanks">
                        <input class="blank-in" type="text" name="db-pass" placeholder="DB Password">
                        <label for="db-pass">Database Password</label>
                    </span>
                </p>
            </div>
            <div id="create-terminal">
                <code>$: </code>
            </div>
            <button type="submit">Create Site</button>
        </form>
    </div>
</div>

<script nonce="<?= $nonce ?>">
    const form = document.querySelector('.create-cont form');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let formData = new FormData(form);

        const subbtn = form.querySelector('button[type="submit"]');
        subbtn.disabled = true;

        fetch("/api/run/test", {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}`);
                }

                const terminal = document.querySelector('#create-terminal code');
                terminal.textContent = '$: ';

                if (!response.body) {
                    throw new Error('Streaming response is not supported by this browser');
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder();

                while (true) {
                    const {value, done} = await reader.read();
                    if (done) {
                        terminal.textContent += decoder.decode();
                        break;
                    }

                    terminal.textContent += decoder.decode(value, {stream: true});
                }
            })
            .catch(error => {
                const terminal = document.querySelector('#create-terminal code');
                terminal.textContent += `\n$: ${error.message}`;
                console.error('Error:', error);
            })
            .finally(() => {
                subbtn.disabled = false;
            });
    })

</script>

<script nonce="<?= $nonce ?>">
    function toggleDatabaseFields(isChecked) {
        const databaseFields = document.getElementById('db-create');

        if (isChecked) {
            databaseFields.style.visibility = 'visible';
            databaseFields.classList.remove('is-closing', 'is-opening');
            void databaseFields.offsetWidth;
            databaseFields.classList.add('is-opening');
            return;
        }

        databaseFields.classList.remove('is-opening');
        databaseFields.classList.add('is-closing');
        databaseFields.addEventListener('animationend', () => {
            if (!document.getElementById('create-db').checked) {
                databaseFields.style.visibility = 'hidden';
                databaseFields.classList.remove('is-closing');
            }
        }, {once: true});
    }

    let blanks = document.querySelectorAll('.blank-in');
    let sName = document.getElementById('site-name');

    sName.addEventListener('input', e => {
        blanks[0].value = e.target.value;
        blanks[0].dispatchEvent(new Event('input'));
    });

    for (let i = 0; i < blanks.length; i++) {
        blanks[i].addEventListener('input', e => {
            blanks[i].value = e.target.value;
            for (let j = i+1; j < blanks.length; j++) {
                blanks[j].value = blanks[i].value;
            }
        });
    }
</script>

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

    function createPage(){
        let crPage = document.getElementById('create-cont');

        document.addEventListener('click', (event) => {
            if (!crPage.contains(event.target) && event.target.classList.item('create-button') === null && event.target.title !== "Create a new virtual host") {
                crPage.style.display = 'none';
            }
        });

        switch (crPage.style.display) {
            case 'none':
                crPage.style.display = 'block';
                break;
            case 'block':
                crPage.style.display = 'none';
                break;
            default:
                crPage.style.display = 'block';
        }
    }

    //debug for plus button
    function createSite() {
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
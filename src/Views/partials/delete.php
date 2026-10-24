<style>
    #delete-cont {
        grid-row: 2;
        grid-column: 2;
        position: relative;
        height: 100%;
        width: 100%;
        pointer-events: none;
        animation: fadeIn-FromTop 0.5s ease-out forwards;
    }

    .delete-cont {
        display: grid;
        grid-template-columns: auto;
        grid-template-rows: 1fr minmax(250px, 100%) 1fr;
        pointer-events: all;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        max-width: 400px;
        max-height: 480px;
        width: 50%;
        height: 100%;
        align-items: center;
        justify-content: center;
        z-index: 10;
        background-color: rgb(62 62 62 / 0.95);
        border-radius: 30px;
        border: 3px solid #00000055;
    }

    .delete-cont h2 {
        grid-row: 1;
        grid-column: 1;
        font-size: 22px;
        margin: 0;
        padding: 15px;
        text-align: center;
        place-self: center;
    }

    .delete-cont #delete-site-list {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: center;
        grid-row: 2;
        grid-column: 1;
        list-style-type: none;
        padding: 0;
        margin: 0;
        overflow-y: auto;
        height: 100%;
        width: 100%;
        place-self: center;
        gap: 10px;
    }

    .delete-cont #action-buttons {
        display: flex;
        justify-content: space-around;
        grid-row: 3;
        grid-column: 1;
        place-self: center;
        padding: 20px;
    }

    .delete-cont li {
        display: flex;
        flex-direction: column;
        background-color: #2e2e2e;
        border-radius: 8px;
        transition: transform 0.2s ease-in-out;
        width: 90%;
        box-sizing: border-box;
        position: relative;
        padding: 10px 25px;
    }

    .delete-cont li:hover {
        transform: scale(1.03);
    }

    .delete-cont li .timestamp {
        font-size: 8px;
        color: #888;
        position: absolute;
        bottom: 5px;
        left: 10px;
    }

    .delete-cont li p{
        padding: 0;
        margin: 0;
        font-size: 16px;
        text-align: center;
    }

    .delete-cont li p a{
        color: #0f9860;
        text-decoration: none;
    }

    .delete-cont li p a:hover{
        text-decoration: underline;
    }

    .delete-cont button {
        padding: 10px 20px;
        margin: 5px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: .25s all;
    }

    .delete-cont button:hover {
        opacity: 0.8;
    }

    .delete-cont button.confirm {
        background-color: #d9534f; /* Red for delete */
        color: white;
    }

    .delete-cont button.cancel {
        background-color: #0f9860; /* Blue for cancel */
        color: white;
    }
    .red-minus {
        font-size: 14px;
        color: red;
        font-weight: bold;
    }
    .gray-minus{
        font-size: 14px;
        color: gray;
        font-weight: bold;
    }
</style>

<div id="delete-cont" style="display:block;">
    <div class="delete-cont">
        <h2>Are you sure you want to delete the following sites?</h2>
        <ul id="delete-site-list">

        </ul>
        <div id="action-buttons">
            <button class="confirm" id="confirm-delete">Yes, Delete</button>
            <button class="cancel" id="cancel-delete" onclick="togglePopup(document.getElementById('delete-cont'), 'none')">Cancel</button>
        </div>
    </div>
</div>

<script nonce="<?= $nonce ?>">
    function deleteListAdd(selRow){
        const deleteSiteList = document.getElementById('delete-site-list');
        const rowData = {
            name: selRow.querySelector('.site-name').textContent.trim(),
            ip: selRow.querySelector('.site-ip').textContent.trim(),
            root: selRow.querySelector('.site-root').textContent.trim()
        };

        let li = document.createElement('li');
        let siteInfo = document.createElement('p');
        let sitePath = document.createElement('p');
        let timestamp = document.createElement('p');

        siteInfo.innerHTML = `<span class="red-minus">[-]</span> ${rowData.name} @ <a href="http://${rowData.ip}">${rowData.ip}</a>`;
        sitePath.innerHTML = `<span class="red-minus">[-]</span> ${rowData.root}`;
        timestamp.className = 'timestamp';
        timestamp.textContent = new Date().toLocaleString();

        li.appendChild(siteInfo);
        li.appendChild(sitePath);
        li.appendChild(timestamp);

        deleteSiteList.appendChild(li);
    }

    function deleteListRemove(selRow){
        const deleteSiteList = document.getElementById('delete-site-list');
        const rowData = {
            name: selRow.querySelector('.site-name').textContent.trim(),
            ip: selRow.querySelector('.site-ip').textContent.trim(),
            root: selRow.querySelector('.site-root').textContent.trim()
        };

        const items = deleteSiteList.getElementsByTagName('li');
        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            const siteInfo = item.querySelector('p:first-child').textContent;
            const sitePath = item.querySelector('p:nth-child(2)').textContent;

            if (siteInfo.includes(rowData.name) && siteInfo.includes(rowData.ip) && sitePath.includes(rowData.root)) {
                deleteSiteList.removeChild(item);
                break;
            }
        }
    }

</script>

<script nonce="<?= $nonce ?>">
    function deletePage(){
        const selectedRows = document.getElementsByClassName('row-selected');
        const deleteSiteList = document.getElementById('delete-site-list');

        for (let i = 0; i < selectedRows.length; i++) {
            const row = selectedRows[i];

            console.log(row);

            const rowData = {
                name: row.querySelector('.site-name').textContent.trim(),
                ip: row.querySelector('.site-ip').textContent.trim(),
                root: row.querySelector('.site-root').textContent.trim()
            };

            let li = document.createElement('li');
            let siteInfo = document.createElement('p');
            let sitePath = document.createElement('p');
            let timestamp = document.createElement('p');

            siteInfo.innerHTML = `<span class="red-minus">[-]</span> ${rowData.name} @ <a href="http://${rowData.ip}">${rowData.ip}</a>`;
            sitePath.innerHTML = `<span class="red-minus">[-]</span> ${rowData.root}`;
            timestamp.className = 'timestamp';
            timestamp.textContent = new Date().toLocaleString();

            li.appendChild(siteInfo);
            li.appendChild(sitePath);
            li.appendChild(timestamp);

            deleteSiteList.appendChild(li);
        }
    }

</script>
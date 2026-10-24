<!--suppress CssUnknownTarget -->
<style>
    #create-cont{
        grid-row: 2;
        grid-column: 2;
        position: relative;
        height: 100%;
        width: 100%;
        pointer-events: none;
        animation: fadeIn-FromTop 0.5s ease-out forwards;
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
        overflow-y: scroll;
        overflow-x: hidden;
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

<div id="create-cont" style="display: none;">
    <div class="create-cont">
        <form action="/api/site/create" method="POST">
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

<script>
    //let crPage = document.getElementById('create-cont');
    //let createBtn = document.querySelector('.create-button');
    //document.addEventListener('click', (event) => {
    //    if (crPage.style.display !== 'none' && !crPage.contains(event.target) && !createBtn.contains(event.target)) {
    //        crPage.style.display = 'none';
    //    }
    //});
</script>

<script>
    //function createPage(){
    //    let crPage = document.getElementById('create-cont');
//
    //    switch (crPage.style.display) {
    //        case 'none':
    //            crPage.style.display = 'block';
    //            break;
    //        case 'block':
    //            crPage.style.display = 'none';
    //            break;
    //        default:
    //            crPage.style.display = 'block';
    //    }
    //}
</script>

<script nonce="<?= $nonce ?>">
    const form = document.querySelector('.create-cont form');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let formData = new FormData(form);

        const subbtn = form.querySelector('button[type="submit"]');
        subbtn.disabled = true;

        fetch("/api/site/create", {
            method: 'POST',
            body: formData
        })
            .then(async response => {
                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}`);
                }

                const terminal = document.querySelector('#create-terminal code');
                const termContainer = document.getElementById('create-terminal');

                terminal.textContent = '$: ';

                if (!response.body) {
                    throw new Error('Streaming response is not supported by this browser');
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder();

                function addOut(text) {
                    terminal.textContent += text;
                    termContainer.scrollTop = termContainer.scrollHeight;
                }

                while (true) {
                    const {value, done} = await reader.read();
                    if (done) {
                        addOut(decoder.decode(value));
                        break;
                    }

                    addOut(decoder.decode(value, {stream: true}));
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
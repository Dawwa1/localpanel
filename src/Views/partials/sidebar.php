<style>
    #sidebar-nav a{
        text-decoration: none;
        color: inherit;
    }

    #sidebar{
        display: flex;
        flex-direction: column;
        grid-column: 1;
        grid-row: 1 / span 2;
        background-color: #27262d;
        height: 100%;
        width: 100%;
    }
    #sidebar #logo {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        width: 100%;
        padding: 10px 0;

        font-size: clamp(12px, 2vw, 32px);
        text-overflow: ellipsis;
        text-decoration: underline;
        text-underline-offset: 5px;
        text-decoration-thickness: 3px;
    }
    #sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 10px;
        width: 100%;
        height: 100%;
        margin-top: auto;
        padding: 24px 12px;
        box-sizing: border-box;
    }
    #sidebar-nav a.sidebar-btn,
    #sidebar-nav button {
        display: flex;
        box-sizing: border-box;
        align-items: center;
        width: 100%;
        min-height: 48px;
        padding: 0 16px;
        border: 1px solid #3b3944;
        border-radius: 10px;
        background: #302f38;
        color: #b9b7c3;
        font: inherit;
        font-size: 18px;
        text-align: left;
        cursor: pointer;
        box-shadow: 0 3px 8px rgba(0, 0, 0, .18);
        transition: background-color .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
    }
    #sidebar-nav a.sidebar-btn:hover,
    #sidebar-nav a.sidebar-btn:focus-visible,
    #sidebar-nav button:hover,
    #sidebar-nav button:focus-visible {
        outline: none;
        border-color: #56536a;
        background-color: #3a3845;
        color: #fff;
        transform: translateY(-1px);
    }
    #sidebar-nav .nav-chevron {
        margin-left: auto;
        font-size: 15px;
        transition: transform .2s ease;
    }
    #sidebar-nav .tools-toggle[aria-expanded="true"] .nav-chevron {
        transform: rotate(90deg);
    }
    #tools-menu {
        display: flex;
        flex-direction: column;
        gap: 4px;
        max-height: 0;
        padding-top: 5px;
        padding-left: 12px;
        overflow: hidden;
        transition: max-height .2s ease;
    }
    #tools-menu.is-open {
        max-height: 160px;
    }
    #tools-menu button {
        min-height: 34px;
        padding: 0 12px;
        font-size: 16px;
        border-radius: 8px;
        background: #292832;
    }
</style>

<aside id="sidebar">
    <div id="logo">
    LocalPanel
    </div>
    <nav id="sidebar-nav" aria-label="Sidebar navigation">
        <a class="sidebar-btn" href="#">
            <span>Settings</span>
        </a>
        <a class="sidebar-btn" href="#">
            <span>Database</span>
        </a>
        <a class="sidebar-btn" href="#">
            <span>Logs</span>
        </a>
        <button class="tools-toggle" type="button" aria-expanded="false" aria-controls="tools-menu">
            <span>Tools</span>
            <span class="nav-chevron" aria-hidden="true">></span>
        </button>
        <div id="tools-menu" aria-label="Tools submenu">
            <a class="sidebar-btn" href="#"><span>File Manager</span></a>
            <a class="sidebar-btn" href="#"><span>Terminal</span></a>
        </div>
    </nav>
</aside>

<script nonce="<?= $nonce ?>">
    const sidebarLinks = document.querySelectorAll('#sidebar-nav a.sidebar-btn');

    sidebarLinks.forEach((link) => {
        const label = link.textContent.trim().toLowerCase();
        link.href = label.replace(/\s+/g, '-');
    });
</script>

<script nonce="<?= $nonce ?>">
    const toolsToggle = document.querySelector('.tools-toggle');
    const toolsMenu = document.getElementById('tools-menu');

    toolsToggle.addEventListener('click', () => {
        const isOpen = toolsToggle.getAttribute('aria-expanded') === 'true';
        toolsToggle.setAttribute('aria-expanded', String(!isOpen));
        toolsMenu.classList.toggle('is-open', !isOpen);
    });
</script>
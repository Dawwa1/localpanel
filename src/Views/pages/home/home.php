<?= \Localpanel\View::render('partials/sitecards', ['nonce' => $nonce, 'sites' => $sites]) ?>

<?= \Localpanel\View::render('partials/create', ['nonce' => $nonce]) ?>

<?= \Localpanel\View::render('partials/delete', ['nonce' => $nonce]) ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        addSites();
    });
</script>
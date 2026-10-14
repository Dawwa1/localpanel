<script nonce="<?= $nonce; ?>">
    document.addEventListener("DOMContentLoaded", function () {
        var rmenu = gcn("c-menu");
        var rmenuBtn = gcn("main-btn");

        // Equivalent of: $("#test").on("contextmenu", ...)
        if (rmenuBtn && rmenu) {
            for (var i = 0; i < rmenuBtn.length; i++) {
                let btn = rmenuBtn[i];
                btn.addEventListener("contextmenu", function (e) {
                    for (var i = 0; i < rmenu.length; i++) {
                        rmenu[i].classList.add("hide");
                    }
                    let parent = btn.parentElement;
                    let cmenu = parent.lastElementChild;
                    cmenu.classList.remove("hide");
                    cmenu.style.top = (e.clientY + window.scrollY) + "px";
                    cmenu.style.left = (e.clientX + window.scrollX) + "px";

                    e.preventDefault();
                });
            }
        }

        // Equivalent of: $(document).bind("click", ...)
        document.addEventListener("click", function () {
            for (var i = 0; i < rmenu.length; i++) {
                rmenu[i].classList.add("hide");
            }
        });
    });
</script>
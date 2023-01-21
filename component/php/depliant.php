if ($_SESSION['isOpen'] ?? false){ ?>
                <script>
        $(document).ready(function(){
                $(".panneau-depliant").click(function(){
                        $("#password-reset-form").slideToggle();
                isOpen = true;
                <?php $_SESSION['isOpen'] = true; ?>
        });
        });
        </script>
        <?php 
        



        <div class="form-group" id="password-reset-form" style="display: none;">









        <script>
        var isOpen = false;
$(document).ready(function(){
        $(".panneau-depliant").click(function(){
                $("#password-reset-form").slideToggle();
                isOpen = true;
                <?php $_SESSION['isOpen'] = true; ?>
        });
});

</script>
<?php
if (!is_null($page)) {
    if (!empty($_SESSION)) {
        if (isset($_SESSION['id'])) {
            include_once $page;
        }
    } else {
        ?>
        <div class="bs-callout bs-callout-danger"> 
            <h4 class="danger">
                <i class="fa fa-exclamation-triangle"></i> Pour accéder à cette page, vous devez d'abord vous connecter
                <br>
            </h4>  
        </div>
        <script>setTimeout(function () {
                javascript:location.replace('./')
            }, 2000)</script>
        <?php
    }
}
?>
<script type="text/javascript">
    var anc_onglet = '1';
</script>
<?php

//include_once 'include/footer.inc.php';

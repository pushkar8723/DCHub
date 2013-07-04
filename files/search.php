<?php
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if (isset($_GET['code'])) {
    $_GET = secure($_GET);
    $body = "from dchub_content where title like '%$_GET[code]%'";
    $res = DB::findAllWithCount("select *", $body, $page, 20);
    $data = $res['data'];
    ?>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('form').submit(function() {
                $(location).attr('href', '<?php echo SITE_URL; ?>/search/' + $('#search').val());
                return false;
            });
        });
    </script>
    <form class='pull-right' style='margin-top: 10px;' method='post' action='<?php echo SITE_URL; ?>/process.php'>
        <input id='search' name='search' type='text' class='search-query' placeholder='Search'/>
    </form>
    <h1>Search Results</h1>

    <?php
    contentshow($data);
    ?>

    <?php
    pagination($res['noofpages'], SITE_URL . "/search/" . $_GET['code'], $page, 10);
} else {
    
}
?>

<?php
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
if (isset($_GET['code'])) {
    $_GET = secure($_GET);
    $clause = array();
    foreach (explode(' ', $_GET['code']) as $term) {
        array_push($clause, "title like '%$term%'");
    }
    $table = implode(" AND ", $clause);
    $body = "from dchub_content where $table order by timestamp desc";
//    echo $body;
    $msc = microtime(true);
    $res = DB::findAllWithCount("select *", $body, $page, 20);
    $msc = microtime(true) - $msc;
      
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
        <input id='search' name='search' type='text' class='search-query' placeholder='Search' value='<?php echo $_GET['code']; ?>'/>
    </form>
    <h1>Search Results</h1>
    <?php
    echo '('.$res['total'].' total, Query took '.number_format($msc, 3, '.', ','). ' seconds)<br/><br/>'; // in second  
    contentshow($data, $_GET['code']);
    ?>

    <?php
    pagination($res['noofpages'], SITE_URL . "/search/" . $_GET['code'], $page, 10);
} else {
    ?>
    <script type='text/javascript'>
        $(document).ready(function() {
            $('form').submit(function() {
                $(location).attr('href', '<?php echo SITE_URL; ?>/search/' + $('#search').val());
                return false;
            });
        });
    </script>
    <form style='margin-top: 10px;' method='post' action='<?php echo SITE_URL; ?>/process.php'>
        <input id='search' name='search' type='text' class='search-query' placeholder='Search'/>
    </form>
    <?php
}
?>

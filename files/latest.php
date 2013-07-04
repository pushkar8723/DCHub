<?php
if (isset($_GET['code'])) {
    $tab = addslashes($_GET['code']);
} else {
    $tab = '';
}
if (isset($_GET['page']) && $_GET['page'] > 0) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

?>
<script type='text/javascript'>
    $(document).ready(function() {
        $('form').submit(function(){
            $(location).attr('href', '<?php echo SITE_URL; ?>/search/' + $('#search').val());
            return false;
        });
        $('.recommend, .discourage').click(function(event) {
            $('#' + event.target.id).html("Processing...");
            if ($('#' + event.target.id).attr('class') == "btn discourage") {
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "discourage": '',
                    "cid": event.target.id
                }, function(result) {
                    if (result == '1') {
                        $('#' + event.target.id).removeClass('discourage').addClass('recommend').html('Recommend');
                        $('#' + event.target.id + "_count").html(parseInt($('#' + event.target.id + "_count").html()) - 1);
                    }
                    else {
                        $('#' + event.target.id).html(result);
                    }
                });
            }
            else {
                $.post("<?php echo SITE_URL; ?>/process.php", {
                    "recommend": '',
                    "cid": event.target.id
                }, function(result) {
                    if (result == '1') {
                        $('#' + event.target.id).removeClass('recommend').addClass('discourage').html('Discourage');
                        $('#' + event.target.id + "_count").html(parseInt($('#' + event.target.id + "_count").html()) + 1);

                    } else {
                        $('#' + event.target.id).html(result);
                    }
                });
            }
        });
    });
</script>
<form class='pull-right' style='margin-top: 10px;' method='post' action='<?php echo SITE_URL; ?>/process.php'>
    <input id='search' name='search' type='text' class='search-query' placeholder='Search'/>
</form>
<h1>Latest Contents</h1>
<ul class="nav nav-tabs">
    <?php
    foreach ($categories as $key => $value) {
        echo "<li " . (($tab == $value) ? ("class='active'") : ('')) . "><a href='" . SITE_URL . "/latest" . (($value != '') ? ("/$value") : ('')) . "'>$key</a></li>";
    }
    ?>
    <?php if (!in_array($tab, $categories)) { ?>
        <li class="active"><a href="<?php echo SITE_URL . "/latest/$tab"; ?>"><?php echo $tab; ?></a></li>
    <?php } ?>
</ul>
    <?php
    $query = "from dchub_content where deleted=0";
    if ($tab != "")
        $query .= " and tag like '%$tab%'";
    $query .= " order by timestamp desc";
    $res = DB::findAllWithCount("select *", $query, $page, 25);
    $data = $res['data'];
    contentshow($data);
    ?>
<?php
pagination($res['noofpages'], SITE_URL . "/latest" . (($tab != '') ? ("/" . $tab) : ('')), $page, 10);
?>
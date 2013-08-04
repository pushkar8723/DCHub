<?php
if (!isset($_SESSION['loggedin']) || $_SESSION['user']['accesslevel'] < 9) {
    echo "<br/><br/><br/><h1>Permission Denied :(</h1>You don't have enough previledges.<br/><br/><br/>";
    return;
}
$_GET = secure($_GET);
?>
<script type='text/javascript'>
$(document).ready(function(){
    $('#nick').change(function(){
        $(location).attr('href', '<?php echo SITE_URL; ?>/admin/' + escape($('#nick').val()));
    });
});
</script>
<h1>Administration</h1>
<?php
    $query = "select * from (select nick1 as nick from dchub_users union select nick2 as nick from dchub_users)t where nick != '' order by nick";
    $res = DB::findAllFromQuery($query);
    echo "<div class='form-horizontal'><div class='control-group'>
        <div class='control-label'><label>Select Nick</label></div>
        <div class='controls'><select id='nick'>";
    if(!isset($_GET['code']))
        echo "<option>Select a nick</option>";
    foreach($res as $row){
        echo "<option value='".urlencode($row['nick'])."' ".((isset($_GET['code']) && $_GET['code'] == $row['nick'])?("selected"):("")).">$row[nick]</option>";
    }
    echo "</select></div></div></div>";
    if(isset($_GET['code'])){
        $query = "select ipaddress, id, class, nick1, nick2, groups, password_, fullname, roll_course, roll_number, roll_year, hostel, room, branch, phone, friend, deleted  from dchub_users where nick1 = '$_GET[code]' or nick2 = '$_GET[code]'";
        $user = DB::findOneFromQuery($query);
        $fields = array();
        foreach ($user as $key => $value){
            if($key == 'id'){
                $fields['data[id]'] = array($key, 'hidden', $value);
            } else if($key == 'class'){
                $cstr = array();
                foreach($class as $ckey => $cvalue){
                    array_push($cstr, "$ckey:$cvalue ($ckey)");
                }
                $cstr = implode(',', $cstr);
                $fields['data['.$key."]"] = array($key, 'select', $cstr, $value);
            }else if($key == 'roll_course'){
                $fields['data['.$key."]"] = array($key, 'select', "BE:BE,ME:ME,MEEE:MEEE,MESE:MESE,MESER:MESER,MCA:MCA,MBA:MBA,MBI:MBI,BPH:BPH,IPH:IPH,ICH:ICH,MPH:MPH,BT:BT,MT/CS:MT/CS,MT/IS:MT/IS,MT/RS:MT/RS,MSC:MSC,BARCH:BARCH,BHMCT:BHMCT,BMI:BMI,MUP:MUP,IMH:IMH,PHD:PHD,EMP:EMP", $value);
            } else if($key == 'roll_year'){
                $fields['data['.$key."]"] = array($key, 'select', "2013:2013,2012:2012,2011:2011,2010:2010,2009:2009,2008:2008,2007:2007,2006:2006,2005:2005", $value);
            } else if($key == 'branch'){
                $query = "select * from dchub_branch order by branch";
                $res = DB::findAllFromQuery($query);
                $str =array();
                foreach($res as $row){
                    array_push($str, "$row[id]:$row[branch]");
                }
                $str = implode(',', $str);
                $fields['data['.$key."]"] = array($key, 'select', $str, $value);
            }else {
                $fields['data['.$key.']'] = array($key, 'text', $value);
            }
        }
        createForm('adminupdate', $fields, 'Update Account');
    }
?>

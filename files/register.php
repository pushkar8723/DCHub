<?php
if (isset($_SESSION['loggedin'])) {
    echo "<br/><br/><br/><h1>Already registered :D</h1>You have already registed!<br/><br/><br/>";
    return;
}
?>
<script type='text/javascript'>
    function check() {
        var flag = 0;
        var message = '';
        if ($('#pass1').val().trim() !== '' && $('#pass1').val().trim() !== $('#repass1').val().trim()) {
            flag = 1;
            message += 'Password do not match<br/>';
        }
        if ($('#terms').attr('checked') !== 'checked') {
            flag = 1;
            message += 'You must agree to Terms and Conditions<br/>';
        }
        if ($('#nick1').val() == $('#nick2').val()) {
            flag = 1;
            message += 'Nicks must be different<br/>';
        }
        if (flag === 1) {
            $('#hell > #message').html(message);
            $('#hell > #message').show();
            window.scrollTo(0, 0);
            return false;
        }
        else {
            return true;
        }
    }
    $(document).ready(function() {
        $('#branch').change(function() {
            if ($('#branch').val() === 'others') {
                $("#others").show();
            } else {
                $("#others").hide();
            }
        });
        $('#form').submit(function() {
            return check();
        });
        $('#ajaxFetch').click(function() {
            $.post("<?php echo SITE_URL; ?>/process.php", $("#modalForm").serialize(), function(data){
                if (data != "Incorrect nick / password") {
                    var JSON = eval("("+data+")");
                    $("#fname").val(JSON.data[0].fullname);
                    $("#roll_course").val(JSON.data[0].roll_course);
                    $("#roll_number").val(JSON.data[0].roll_number);
                    $("#roll_year").val(JSON.data[0].roll_year);
                    $("#email").val(JSON.data[0].email);
                    $("#mobile").val(JSON.data[0].phone);
                    $("#nick1").val($('#nick').val());
                    $('#myModal').modal('hide');
                    alert('Fill in the remaining details');
                } else {
                    alert(data);
                }
            });
        });
    });
</script>
<h1>Register</h1>
<hr/>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Import Data</h3>
    </div>
    <div class="modal-body">
        <form id="modalForm" class="form-horizontal">
            <div class="control-group">
                <label class="control-label" for="nick">Nick</label>
                <div class="controls">
                    <input type="text" name='nick' id="nick" />
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="lpass">Password</label>
                <div class="controls">
                    <input type="password" name='password' id="lpass" />
                </div>
            </div>
            <input type='hidden' name='ajaxFetch' />
        </form>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button id="ajaxFetch" class="btn btn-primary">Import</button>
    </div>
</div>
<form id='form' method='post' action='<?php echo SITE_URL; ?>/process.php'>
    <!-- STEP 1 -->
    <div class="row">        
        <div id='hell' class="span7">
            <div id='message' class='alert' style='display: none;'></div>
            <div class="form-horizontal">
                <span class='req'>*</span> Required
                <div class="control-group">
                    <label class="control-label" for="ip">IP Address</label>
                    <div class="controls">
                        <input type="text" disabled="disabled" id="ip" name="data[ipaddress]" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="fname">Full Name <span class='req'>*</span></label>
                    <div class="controls">
                        <input type="text" id="fname" name="data[fullname]" <?php if (isset($_SESSION['data'])) echo "value='" . $_SESSION['data']['fullname'] . "'"; ?> required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="roll_number">Roll No <span class='req'>*</span></label>
                    <div class="controls">
                        <select id="roll_course" name='data[roll_course]' style="width: 90px;">
                            <option>BE</option><option>ME</option><option>MCA</option><option>MBA</option><option>MBI</option><option>BPH</option><option>MPH</option><option>BT</option><option>MT</option><option>MSC</option><option>BARCH</option><option>BHMCT</option><option>BMI</option><option>MUP</option><option>IMH</option><option>PHD</option><option>EMP</option>
                        </select>
                        <input id="roll_number" name='data[roll_number]' type='text' style="width: 100px;"  required <?php if (isset($_SESSION['data'])) echo "value='" . $_SESSION['data']['roll_number'] . "'"; ?>>
                        <select id="roll_year" name='data[roll_year]' style="width: 70px;">
                            <option>2013</option><option>2012</option><option>2011</option><option>2010</option><option>2009</option><option>2008</option><option>2007</option><option>2006</option><option>2005</option>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="branch">Branch <span class='req'>*</span></label>
                    <div class="controls">
                        <select id="branch" name="data[branch]">
                            <?php
                            $query = "select * from dchub_branch order by branch";
                            $res = DB::findAllFromQuery($query);
                            foreach ($res as $row) {
                                echo "<option value='$row[id]'>$row[branch]</option>";
                            }
                            ?>
                            <option value="others">Others</option>
                        </select>                 <br/> <br/>  
                        <input name="data[others]" id="others" type="text" style='display: none;'/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="room">Hostel & Room no <span class='req'>*</span></label>
                    <div class="controls">
                        <select name='data[hostel]' id="hostel" style="width:70px;"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>13</option><option>RS</option></select>
                        <input type='text' name='data[room]' style="width:100px;" id="room" <?php if (isset($_SESSION['data'])) echo "value='" . $_SESSION['data']['room'] . "'"; ?>  required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="email">Email <span class='req'>*</span></label>
                    <div class="controls">
                        <input type="text" id="email" name="data[email]"<?php if (isset($_SESSION['data'])) echo "value='" . $_SESSION['data']['email'] . "'"; ?>  required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">Gender</label>
                    <div class="controls">
                        <label class="radio">
                            <input type="radio" name="data[gender]" id="optionsRadios1" value="M" checked="">
                            Male
                        </label>
                        <label class="radio">
                            <input type="radio" name="data[gender]" id="optionsRadios2" value="F">
                            Female
                        </label>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="mobile">Mobile</label>
                    <div class="controls">
                        <input type="text" id="mobile" name="data[phone]" <?php if (isset($_SESSION['data'])) echo "value='" . $_SESSION['data']['phone'] . "'"; ?>>
                    </div>
                </div>
                <hr/>
                <div class="control-group">
                    <label class="control-label" for="nick1">Public Nick <span class='req'>*</span></label>
                    <div class="controls">
                        <input type="text" id="nick1" name="data[nick1]" <?php if (isset($_SESSION['data'])) echo "value='" . $_SESSION['data']['nick1'] . "'"; ?>  required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="nick2">Secret Nick</label>
                    <div class="controls">
                        <input type="text" id="nick2" name="data[nick2]" <?php if (isset($_SESSION['data'])) echo "value='" . $_SESSION['data']['nick2'] . "'"; ?>>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="pass1">Password <span class='req'>*</span></label>
                    <div class="controls">
                        <input type="password" id="pass1" name="data[password_]"  required>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="repass1">Retype Password <span class='req'>*</span></label>
                    <div class="controls">
                        <input type="password" id="repass1" name="data[repassword_]"  required>
                    </div>
                </div>
                <label class="checkbox" for="terms">
                    <input type="checkbox" value="" id="terms">
                    I agree to <a href='terms'>Terms and Condition</a>
                </label>

                <div class='control-group'>
                    <div class='control-label'></div>
                    <div class='controls'>
                        <input type='submit' class='btn btn-danger btn-large' value='Submit' name='register'/>
                    </div>
                </div>
            </div>
        </div>
        <div class='span5'>
            <div class="span5" align="center" style='position: relative; margin-top: 40px;'>
                <a role="button" class="btn btn-block btn-danger btn-large" data-toggle="modal" href='#myModal'>Import from last year's Nick</a>
            </div>
        </div>
    </div>
</form>
<?php unset($_SESSION['data']); ?>
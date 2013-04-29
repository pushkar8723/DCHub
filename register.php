<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php head(); ?>
    </head>
    <body>
        <?php navbar(); ?>
        <div class="container">
            <h1>Register</h1>
            <div class="row">
                <div class="span4"><strong>Step 1:</strong> Basic Data</div>
                <div class="span4" style="text-align: center;"><strong>Step 2:</strong> Nicks and Passwords</div>
                <div class="span4" style="text-align: right;"><strong>Step 3:</strong> Authentication (optional)</div>
            </div>
            <div class="progress">
                <div id="regbar" class="bar" style="width: 5%;"></div>
            </div>

            <form id='form' method='post' action='process'>
                <!-- STEP 1 -->
                <div id="step1">

                    <!-- Import Data -->
                    <div align='center'>
                        <a role="button" class="btn btn-danger btn-large" data-toggle="modal" href='#myModal'>Import from last year's Nick</a>
                    </div>
                    <div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h3 id="myModalLabel">Import Data</h3>
                        </div>
                        <div class="modal-body form-horizontal">
                            <div class="control-group">
                                <label class="control-label" for="nick">Nick</label>
                                <div class="controls">
                                    <input type="text" id="nick" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="lpass">Password</label>
                                <div class="controls">
                                    <input type="password" id="lpass" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                            <button class="btn btn-primary">Import</button>
                        </div>
                    </div>
                    <hr>
                    <div style='float:left; left: 50%; position: relative; margin-left: -30px; margin-top: -42px; background: #fff; padding: 0 10px;'><h3>OR</h3></div><br/>

                    <!-- Data Entry -->
                    <div class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="ip">IP Address</label>
                            <div class="controls">
                                <input type="text" disabled="disabled" id="ip" name="ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="fname">Full Name</label>
                            <div class="controls">
                                <input type="text" id="fname" name="data[fname]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="roll_number">Roll No</label>
                            <div class="controls">
                                <select id="roll_course" name='data[roll_course]' style="width: 90px;">
                                    <option>BE</option><option>ME</option><option>MCA</option><option>MBA</option><option>MBI</option><option>BPH</option><option>MPH</option><option>BT</option><option>MT</option><option>MSC</option><option>BARCH</option><option>BHMCT</option><option>BMI</option><option>MUP</option><option>IMH</option><option>PHD</option><option>EMP</option>
                                </select>
                                <input id="roll_number" name='data[roll_number]' type='text' style="width: 100px;">
                                <select id="roll_year" name='data[roll_year]' style="width: 70px;">
                                    <option>2005</option><option>2006</option><option>2007</option><option>2008</option><option>2009</option><option>2010</option><option>2011</option><option>2012</option><option>2013</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="branch">Branch</label>
                            <div class="controls">
                                <input type="text" id="branch" name="data[branch]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="room">Hostel & Room no</label>
                            <div class="controls">
                                <select name='data[hostel]' id="hostel" style="width:70px;"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option><option>6</option><option>7</option><option>8</option><option>9</option><option>10</option><option>11</option><option>12</option><option>13</option><option>RS</option></select>
                                <input type='text' name='data[room]' style="width:100px;" id="room">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="email">Email</label>
                            <div class="controls">
                                <input type="text" id="email" name="data[email]">
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
                            <label class="control-label" for="mobile">Mobile (optional)</label>
                            <div class="controls">
                                <input type="text" id="mobile" name="data[mobile]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="secques">Security Question</label>
                            <div class="controls">
                                <input type="text" id="secques" name="data[secques]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="secans">Security Answer</label>
                            <div class="controls">
                                <input type="password" id="secans" name="data[secans]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 2 -->
                <div id="step2" style='display:none'>
                    <div class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label" for="nick1">Nick 1</label>
                            <div class="controls">
                                <input type="text" id="nick1" name="data[nick1]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pass1">Password</label>
                            <div class="controls">
                                <input type="password" id="pass1" name="data[pass1]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repass1">Retype Password</label>
                            <div class="controls">
                                <input type="password" id="repass1" name="data[repass1]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="nick2">Nick 2 (optional)</label>
                            <div class="controls">
                                <input type="text" id="nick2" name="data[nick2]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="pass2">Password (optional)</label>
                            <div class="controls">
                                <input type="password" id="pass2" name="data[pass2]">
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="repass2">Password (optional)</label>
                            <div class="controls">
                                <input type="password" id="repass2" name="data[repass2]">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- STEP 3 -->
                <div id="step3" style='display:none'>

                    <!-- Cyberoam -->
                    <div class="form-horizontal">
                        <h3>Cyberoam Authentication</h3>
                        <div class="control-group">
                            <label class="control-label" for="cyberpass">Cyberoam Password</label>
                            <div class="controls">
                                <input type="password" id="cyberpass" name="data[cyberpass]">
                                <br/><span style='color: #777; font-size: 11px;'>Your password will not be saved. Pinky promise.</span>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div style='float:left; left: 50%; position: relative; margin-left: -30px; margin-top: -42px; background: #fff; padding: 0 10px;'><h3>OR</h3></div><br/>

                    <!-- Authentication By Friend -->
                    <div class="form-horizontal">
                        <h3>Ask a friend</h3>
                        <div class="control-group">
                            <label class="control-label" for="friendnick">Friend's nickname</label>
                            <div class="controls">
                                <input type="text" id="friendnick" name="data[friendnick]">
                            </div>
                        </div>
                    </div>
                    <label class="checkbox" for="checkbox1">
                        <input type="checkbox" value="" id="terms">
                        I agree to <a href='terms'>Terms and Condition</a>
                    </label>
                </div>
            </form>

            <div style="padding-left: 180px"><a href="#" id="next" class="btn btn-large btn-danger">Next</a></div>
            <?php footer(); ?>
        </div>
    </body>
</html>
<?php if (!isset($_SESSION['loggedin'])) redirectTo(SITE_URL); ?>
<center><h1>Account Settings</h1></center>
<?php if ($_SESSION['user']['accesslevel'] == 0) { ?>
    <div class="auth" style="padding: 15px;">
        <h3>Authentication</h3>
        <hr>
        <h4><b>There are two methods to authenticate yourself</b></h4><br/>
        <div class="row-fluid">
            <div class="span6 auth">
                <h4><b>Method 1:</b> Use your Cyberoam Password</h4><br/>
                <form class="form-horizontal" action="<?php echo SITE_URL; ?>/process.php" method="post">
                    <div class="control-group">
                        <div class="control-label"><label for="cyberpass">Cyberoam Password</label></div>
                        <div class="controls">
                            <input type="password" name="cyberpass" id="cyberpass" required/>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"></div>
                        <div class="controls">
                            <input type="submit" class="btn" value="Authenticate" name="cyberauth" /> 
                        </div>
                    </div>
                </form>
            </div>
            <div class="span6 auth">
                <h4><b>Method 2:</b> Ask a friend</h4><br/>
                <?php
                $query = "select friend from dchub_users where id = " . $_SESSION['user']['id'];
                $res = DB::findOneFromQuery($query);
                ?>
                <form class="form-horizontal" action="<?php echo SITE_URL; ?>/process.php" method="post">
                    <div class="control-group">
                        <div class="control-label"><label for="friend">Friend's nick</label></div>
                        <div class="controls">
                            <input type="text" name="friend" id="friend" value="<?php echo $res['friend']; ?>" required/>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label"></div>
                        <div class="controls">
                            <input type="submit" class="btn" value="Submit" name="friendauth"/> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php head(); ?>
    </head>
    <body>
        <?php navbar(); ?>
        <div class="container">
            <div class="row">
                <div class="span8">
                    <div class="todo mrm">
                        <div class="todo-search">
                            <span style="font-size: 16px;">What's New on DC</span>
                        </div>
                        <ul style="height: 300px; overflow-y: auto;">
                            <?php
                                $query = "SELECT * FROM dchub_users LIMIT 0 , 10";
                                $result = DB::findAllFromQuery($query);
                                foreach ($result as $row){ ?>
                            <li>
                                <div class="todo-icon fui-man-24"></div>
                                <div class="todo-content">
                                    <h4 class="todo-name">
                                        <strong><?php echo $row['title'] ?></strong>
                                    </h4>
                                    by <strong>
                                        <?php 
                                            $query = "SELECT nickname FROM dchub_users where uid = ".$row['uid'];
                                            $user = DB::findOneFromQuery($query);
                                            echo $user['nickname'];
                                        ?>
                                    </strong>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
                <div class="span4">
                    <div class="palette palette-bright-dark">
                        <h3>Sign In</h3>
                    </div>
                    <div class="palette palette-bright">
                        <form>
                            <label for="username">Username:</label>
                            <input id='username' type="text" name="data[username]" style="width:95%"/><br/><br/>
                            <label for="password">Password:</label>
                            <input id="password" type="password" name="data[password]" style="width:95%"/><br/>
                            <input class="btn btn-danger" type="submit" value="Sign In"/> <br/><br/>
                            <a href="#" style="color: #fff">Forgot Password?</a>
                        </form>
                    </div>
                </div>
            </div>
            <hr>
            <h3>New Features of DC Hub</h3>
            <div class="row">
                <div class="span3">
                    <div class="palette palette-firm-dark">
                        <strong>Groups</strong>
                    </div>
                    <div class="palette palette-firm" style="height: 100px;">
                        Users of DC Hub are divided in various branches and years.
                        Any user can convey message to a particular group after approval from group moderator. 
                    </div>
                </div>
                <div class="span3">
                    <div class="palette palette-pumpkin">
                        <strong>Request Page</strong>
                    </div>
                    <div class="palette palette-carrot" style="height: 100px;">
                        On request page anyone can make a request for some content.
                        Remaining users can volunteer to download and share it.
                        
                    </div>
                </div>
                <div class="span3">
                    <div class="palette palette-info-dark">
                        <strong>HOT Page</strong>
                    </div>
                    <div class="palette palette-info" style="height: 100px;">
                        Like button has been added to latest content. 
                        Contents with largest no of like in past 24 hr makes it to HOT page.
                    </div>
                </div>
                <div class="span3">
                    <div class="palette palette-asbestos">
                        <strong>Hall of Fame</strong>
                    </div>
                    <div class="palette palette-concrete" style="height: 100px;">
                        Users who contribute to make DC a better place will end up here.
                        And there names will remain here till eternity!!!
                    </div>
                </div>
            </div>
            <?php footer(); ?>
        </div>
    </body>
</html>
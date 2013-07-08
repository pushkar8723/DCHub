<h2>Getting Started</h2>
<ol class='customol'>
    <li>
        The first thing you need to do to start using DC is to <a href='<?php echo SITE_URL; ?>/register' target='new'>create a new account</a>. After Registration you will be able to log onto the Hub but with certain Restrictions (IP and Chat). You need to get Authenticated to start using all the facilities and services associated with the Hub.<br/>
        <b>Authentication</b> is required to ascertain your identity and to ensure that a fake account is not being created using your details. You may Authenticate your account using 2 methods:
        <ol type='a'>
            <li>Since your <b>Cyberoam ID-Password</b> is confidential information, it can be used to ascertain your identity. An automated script will attempt to log into Cyberoam using the information provided. Depending on the response from the server, your account will be authenticated. Rest assured, the information provided by you will not be stored in any form.</li>
            <li>On choosing to <b>Authenticate by a Friend</b>, you will have to provide the nickname of a friend who has already been authenticated. A request will be sent to the specified friend (with some info), who can then authenticate you by using "+authenticate" or by visiting the Web Interface. </li>
        </ol>
    </li>
    <li>
        Download and install a DC client from the Internet, or from one of the following links:<br/>
        <b>Windows:</b> <a href='download/ApexDC++_1.5.6_Setup.exe'>ApexDC++ 1.5.6</a> (Recommended), <a href='download/StrongDC-2.42.exe'>StrongDC++ 2.42</a>, <a href='download/DCPlusPlus-0.825.exe'>DC++ 0.825</a>, <a href='download/EiskaltDC++-2.2.8-x86.exe'>EiskaltDC++ 2.2.8</a>
        <br/><b>Linux:</b> Install from your Software Center (Recommended), <a href='download/linuxdcpp-1.1.0.tar.bz2'>Linux DC++ 1.1.0</a>, <a href='download/eiskaltdcpp-2.2.8.tar.xz'>EiskaltDC++</a>
    </li>
    <li>After installation, go to <span>File -> Settings</span>, and under the "Personal Information" section (possibly under the "General" section), enter the Nickname which you registered above. If you feel the need, you may go to the "Downloads" section and change the default Downloads Directory.<br/>
        Then go the section titles "Sharing", and select a directories whose contents you wish to share with others. You can login with 0 share but will be given limited functionality. There is a minimum requirement to share at least 20GB of files and folders to attain complete functionality and be able to use all services available on the Hub. Press OK and the window will close.
        <br/><b>Minimum Share Criterion of 20GB</b> is required to ensure seeding of files takes place in the Hub. For Example, Suppose only 1 user shares "The Dark Knight", then when he goes offline the movie cannot be found on the Hub. Also sharing contents that are Unique is encouraged as it improves the variety of content available on the Hub.
    </li>
    <li>
        Click on the Star Icon in toolbar, or go to <span>View -> Favorite Hubs</span>, or press Ctrl+F to open the Favorite Hubs tab. There will be a button labeled "New" near the bottom of the tab (above the Transfers bar), which when clicked on, will result in a another window being opened. Enter "DC Hub" in the Name field, "172.16.32.222" in the Address field, along with the Nickname and Password you registered above. Press OK and the window will close.
    </li>
    <li>
        You can now double-click on the newly created row, in order to connect to the DC Hub. If there is a checkbox for "AutoConnect", tick that, so that the next time you start the program, you are automatially connected to the Hub. A new tab will open for each Hub that you connect to, called the Main-Chat, where you can send messages that can been seen by everyone connected to that Hub.
    </li>
    <li>
        In order to search for a particular item, go to <span>View -> Search</span>, or click on the Magnifying Glass icon in the Toolbar, or press Ctrl+S to open a new Search Tab. On the left side, towards the top, there will be a box in which you are supposed to enter keywords to identify the file you want. Enter the Search term(s) and press the "Search" button that is right underneath it. You will get search results from users connected to all the Hubs that you are connected to. You can double-click on any of the search results to start downloading the file.
    </li>
    <li>
        Additionally, for each hub, to the right of the Main-Chat, there is a list of users currently online and connected to this hub. You may double-click on any of them to download and view their Filelists (a complete list of all the files that that user has shared). You may double-click on any file/directory and start downloading that too.
    </li>
    <li>
        Also, if you want to send a private message (that no one other than the recipient can see) to a particular user, you can right-click on the user's nickname and click on "Send Private Message". A new tab will open, and you can type and send the private message.
        In case of any problems or suggestions, please do not hesitate from contacting a DC Administrator (one of the Green Names in the UserList).
    </li>
</ol>

<hr/><h2>Access Levels</h2>
<div class='accesslevel'>
    <h4>Access Level 0 : Novice</h4><HR/>
    This is the default state of all newly registered users. Users can now connect to the Hub and start downloading/sharing content. But all other services of the Hub are not available to the user.<br/>
    <br/><b>Restrictions Placed</b>
    <ul>
        <li>User cannot use MainChat or send Private Messages (PM) to other users.</li>
        <li>The IP from which a user can log into the Hub is restricted to the IP which was used for Registration.</li>
    </ul><br/>
    <b>Commands Available</b>:
    <table class='table table-hover'>
        <tr><th>Command</th><th>Description</th></tr>
        <tr><td>+hot</td><td>Shows top 10 recommended content.</td></tr>
        <tr><td>+help</td><td>Displays a list of all commands available to you.</td></tr>
        <tr><td>+notice</td><td>Displays the Message of the Day Notifications containing Club Notices, Lost&Found etc.</td></tr>
        <tr><td>+latest</td><td>Displays the Top 10 latest shared content.</td></tr>
        <tr><td>+myinfo</td><td>Shows you information about yourself.</td></tr>
        <tr><td>+password &lt;old-password&gt; &lt;new-password&gt;</td><td>Allows you to update your password.</td></tr>
        <tr><td>+share &lt;content&gt; &amp;M &lt;magnet-link&gt; &lt;comma separated tag&gt;</td><td>Lets you share new content. The special words "&M" is used to indicate that what follows is the magnet-link. It is optional.</td></tr>
        <tr><td>+share &lt;magnet-link&gt; &lt;comma separated tag&gt;</td><td>This is an alternate way to use the +share command, that extracts and uses the file name as the item name.</td></tr>
    </table>   
</div>

<div class='accesslevel'>
    <h4>Access Level 1 : Experienced</h4><hr/>
    Users are upgraded to Level 1 when they Authenticate themselves AND Share a minimum of 20GB. All restrictions are removed.
    <br/><br/><b>Additional Commands Available</b><br/>
    <table class='table table-hover'>
        <tr><th>Command</th><th>Description</th></tr>
        
        <tr><td>+authenticate &lt;nick&gt;</td><td>Allows you to authenticate a friend who has sent you a request.</td></tr>
        <tr><td>+me &lt;message&gt;</td><td>Lets you 'flash' a message on the Main Chat.</td></tr>
        <tr><td>+notify &lt;message&gt;</td><td>Allows you to broadcast message to all users, subject to Admin approval.</td></tr>
        <tr><td>+offline &lt;nick&gt; &lt;message&gt;</td><td>Allows you to send an offline message to the specified user.</td></tr>
        <tr><td>+request &lt;content&gt;</td><td>Allows you to post on the Request Page, from where others can download the content requested by you.</td></tr>
        <tr><td>+schedule</td><td>Shows the Schedule of various TV Shows for the upcoming week.</td></tr>
        <tr><td>+schedule &lt;showname&gt;</td><td>Shows the upcoming episodes of the given show.</td></tr>
    </table>
</div>

<div class='accesslevel'>
    <h4>Access Level 2 : Famous</h4><hr/>
    Users are upgraded to Level 2 when they share more than 500GB OR occasionally download New Content. 
    <br/>Level-2 Users are granted privileges to manage the Request and Recommendation Pages via the Web Interface.
    <br/>
    <br/>
</div>

<div class='accesslevel'>
    <h4>Access Level 3 : Moderator</h4><hr/>
    Users are upgraded to Level-3 when they share more than 800GB OR Regularly download New Content.<br/>
    Level-3 Users are granted privileges to manage the Latest Content Page and Minimum Share Limit is Removed [0 GB Share Log-In].
    <br/><br/><b>Additional Commands Available</b>
    <table class='table table-hover'>
        <tr><th>Command</th><th>Description</th></tr>
        <tr><td>+unshare &lt;pattern&gt;</td><td>Lets you delete shared content. Applicable only within the latest ten items, and deletes only one item at a time.</td></tr>
        <tr><td>+clear</td><td>Allows you to Clear MainChat of DC Hub</td></tr>
    </table>
</div>

<div class='accesslevel'>
    <h4>Access Level 4 : Pseudo-Admin</h4><hr/>
    Users are upgraded to Level-4 when they share more than 1 TB AND Regularly download New Content.<br/>
    Level-4 Users are granted privileges to send Broadcast messages to all users.
    <br/><br/>
    <b>Additional Commands Available</b>
    <table class='table table-hover'>
        <tr><th>Command</th><th>Description</th></tr>
        <tr><td>+view</td><td>Displays pending broadcast messages.</td></tr>
        <tr><td>+send &lt;ID&gt;</td><td>Approves and broadcast specified pending messages.</td></tr>
        <tr><td>+send decline &lt;ID&gt;</td><td>Denies specified pending messages.</td></tr>
    </table>
</div>
<br/>
There exist Access Levels higher than those listed above.
If you wish to upgrade your Access Level, please do something that will be beneficial to the DC Hub Community as a whole (such as introducing new items to the LAN or sharing massive amounts of useful and properly organized stuff). If you feel that you deserve to be upgraded, please contact an Administrator, who will verify your claim before upgrading you.

<hr/><h2>More Options</h2>
You may add your friends to the "Favorite Users" list so that you get notifications whenever they come online. In addition, you can also choose to grant them Download Slots directly (without waiting in a queue) should they need one.
<br/><br/>Depending on whether your client supports this, you can set an "Away Message" (<span>File -> Settings -> General -> Away Message</span>). Later, if you are going to leave your room for a while but leave your system switched on and connected to DC, you can set your status as "AWAY" (by pressing the "AWAY" toggle button in the toolbar). Now, if anyone sends you a private message, your "Away Message" will immediately be sent back as a response.
<br/><br/>If you prefer to download items to different folders based on what they are, you could add "Favorite Download Directories" under File -> Settings -> Downloads -> Favorites, so that from the next time, you can right-click on an item, and under the "Download to" option, you will find this list.
<br/><br/>It is recommended that you enable Sounds for notifications when you get a new Private Message, or at least when a new PM window is opened. You can do this by ticking the appropriate checkboxes under <span>File -> Settings -> Appearance -> Sounds</span>.

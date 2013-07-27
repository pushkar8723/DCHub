<h1 id="gettingstarted">Getting Started</h1><hr/>
<div class='accesslevel'>
  	<h4 id="step1">Step-1: Registration</h4><hr/>
        <p>The first thing you need to do to start using DC is to <a href='http://172.16.32.222/dchub/register' target='new'>create a new account</a>. After Registration you will be able to log onto the Hub but with certain Restrictions (IP and Chat).</p>
		<p><b>Caution:</b> If you havent registered and try to connect to the Hub you will get an error message like the following: </p>
		<center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Not Registered.png"></center><br>
</div>
<div class='accesslevel'>
    <h4 id="step2">Step-2: Authentication</h4>    <hr/>
        <p>You need to <a href="http://172.16.32.222/dchub/account">Authenticated Yourself</a> to start using all the facilities and services associated with the Hub.</p>
        <p><b>Authentication</b> is required to ascertain your identity and to ensure that a fake account is not being created using your details. You may Authenticate your account using 2 methods:</p>
        <ol type='a'>
            <li>Since your <b>Cyberoam ID-Password</b> is confidential information, it can be used to ascertain your identity. An automated script will attempt to log into Cyberoam using the information provided. Depending on the response from the server, your account will be authenticated. Rest assured, the information provided by you will not be stored in any form.</li>
            <li>On choosing to be <b>Authenticated by a Friend</b>, you will have to provide the nickname of a friend who has already been authenticated. A request will be sent to the specified friend (with some info), who can then authenticate you by using "+authenticate" or by visiting the Web Interface. </li>
        </ol>
        <p><b>Caution:</b> If you have not Authenticated yourself you'll keep receiving error messages like this when you use Chat facilities or change your IP.</p>
        <br><center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Not Authenticated.png"></center><br>
</div>
<div class='accesslevel'>
    <h4 id="step3">Step-3: Download a DC Software (Client)</h4><hr/>
        <p>Download and install a DC client from the Internet, or from one of the following links:</p>
        <b>Windows:</b> <a href='download/ApexDC++_1.5.6_Setup.exe'>ApexDC++ 1.5.6</a> (Recommended), <a href='download/sdc242-32.exe'>StrongDC++ 2.42</a> (32 bit), <a href="download/sdc242-64.exe">StrongDC++ 2.42</a> (64 bit), <a href='download/DCPlusPlus-0.825.exe'>DC++ 0.825</a>, <a href='download/EiskaltDC++-2.2.6-x86.exe'>EiskaltDC++ 2.2.8</a>
        <br/><b>Linux:</b> Install from your Software Center (Recommended), <a href='download/linuxdcpp-1.1.0.tar.bz2'>Linux DC++ 1.1.0</a>, <a href='download/eiskaltdcpp-2.2.8.tar.xz'>EiskaltDC++</a>
	<br/><b>Mac OS:</b> <a href="download/eiskaltdcpp-2.2.8.tar.xz">EiskaltDC++</a><br/><br/>
</div>
<div class='accesslevel'>
    <h4 id="step4">Step-4: Setting up DC Software</h4><hr/>
    <ol><li><p>After installation, go to <span><b>File -> Settings</b></span>, and under the <b>"Personal Information"</b> section (possibly under the "General" section), enter the Nickname which you registered above. Also change your Line Speed (upload) to 10 Mbps.</p></li>
        <br/><center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Nickname.png" alt="Nickaname Settings Image"></center>
        <br/></li>
        <li>Now go to <b>"Downloads"</b> section and customise the folder you want your downloaded files to be saved in.</li> 
        <br/><center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Downloads.png"></center><br/>
        <li>Then go the section titled <b>"Sharing"</b>, and select directories whose contents you wish to share with others. While you are at it, also increase the number of Upload Slots to a minimum of 5.<br/><br/>
        <center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Sharing.png"></center> <br/> 
        <p>You can login with 0 share but will be given limited functionality. There is a minimum requirement to share at least 20GB of files and folders to attain complete functionality and be able to use all services available on the Hub. Press OK and the window will close.</p>
        <p><b>Minimum Share Criterion of 20GB</b> is required to ensure seeding of files takes place in the Hub. For Example, Suppose only 1 user shares "The Dark Knight", then when he goes offline the movie cannot be found on the Hub. Also sharing contents that are Unique is encouraged as it improves the variety of content available on the Hub.</p>
    	<p><b>Caution:</b> If you havent shared the required minimum 20 GB you will keep receiving error messages like this when you try using Chat Facilities or change your IP Address.</p>
    	<br><center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Not Shared.png"></center><br>
    </ol>
</div>
<div class='accesslevel'>
    <h4 id="step5">Step-5: Optimal Connection Settings</h4><hr/>
    	<p>After this make sure that you enter the Hub as an <b>Active User</b>. Passive Users get limited search and download facilities from the Hub since they are behind a firewall [We will remove Firewall Next]. A <b>Passive User</b> setting looks like this:</p><br/>
    	<center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Passive.png"></center><br/>
    	<p>To Change your settings to an <b>Active User</b> take the following steps:</p>
    	<ul><li>Goto settings -- Connection Settings</li><li>If Let "DC determine the best settings" is selected, then deselect it. </li><li>Select the direct connection option.</li><li>Close settings and rejoin the Hub.</li>
    	<li>If you are connected to an <b>Ad-Hoc Network (Wireless)</b> then only select the Firewalled passive (last) option.</li></ul>
    	<br/><center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Active.png"></center><br/>
    	<p><b>Problems you may face</b> if you are in Passive User Mode:</p>
    	<ol><li>If you cannot open a person's filelist</li><li>A Red-Bar appears underneath your username</li><li>You search something and no result shows up</li><li>You get error messages like "Minimum Search interval is X seconds".</li></ol>
		<p>Hence to rectify these problems change your settings to <b>Active User</b> mode as described above.</p>
</div>
<div class='accesslevel'>
    <h4 id="step6">Step-6: Firewall Exception</h4><hr/>
    <p>You need to create a <b>Firewall Exception</b> for your DC Software. Your search facility will be limited if you have a firewalled connection.</p>
    <br/><center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Firewall.png"></center><br/>
	<p><b>Problems you may face</b> if you are Firewalled:</p>
    <ol><li>If you cannot open a person's filelist</li><li>A Red-Bar appears underneath your username</li><li>You search something and no result shows up</li><li>You get error messages like "Minimum Search interval is X seconds".</li></ol>
	<p>Hence to rectify these problems create a <b>Firewall Exception</b> as described above.</p> 	
</div>
<div class='accesslevel'>
    <h4 id="step7">Step-7: Favorite Hub Setting</h4><hr/>
        <p>Click on the Star Icon in toolbar, or go to <span><b>View -> Favorite Hubs</b></span>, or press Ctrl+F to open the Favorite Hubs tab. There will be a button labeled "New" near the bottom of the tab (above the Transfers bar), which when clicked on, will result in a another window being opened. Enter "DC Hub" in the Name field, "172.16.32.222" in the Address field, along with the Nickname and Password you registered above. Press OK and the window will close.<p>
    	<br/><center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/Favorite.png"></center><br/>
		<p id="NoPass">If you are fed up with <b>Entering Password</b> every time you connect to DC Hub or you keep getting error messages like <b>Incorrect Password</b> then make the changes described above. Your Incorrect Passord error will look somewhat like this:</p><br>
		<center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/IncorrectPassword.png"></center><br/> 
	<p><b>Note:</b> If you <b>Do Not Remember</b> your password contact an Admin by sending an <a href="http://172.16.32.222/dchub/offline">Offline Message</a> or contact us via email at <i><u>admins@dc.bitmesra.net</u></i>. You may also contact us on any other Hub.</p>
</div>
<div class='accesslevel'>
    <h4 id="step8">Step-8: Connect to DC Hub [Finally *Whew*]</h4><hr/>
        <p>You can now double-click on the newly created row, in order to connect to the DC Hub. If there is a checkbox for "AutoConnect", tick that, so that the next time you start the program, you are automatially connected to the Hub. A new tab will open for each Hub that you connect to, called the Main-Chat, where you can send messages that can been seen by everyone connected to that Hub.</p>
</div>
<div class='accesslevel'>
    <h4 id="step9">Step-9: Search</h4><hr/>
        <p>In order to search for a particular item, go to <span>View -> Search</span>, or click on the Magnifying Glass icon in the Toolbar, or press Ctrl+S to open a new Search Tab. On the left side, towards the top, there will be a box in which you are supposed to enter keywords to identify the file you want. Enter the Search term(s) and press the "Search" button that is right underneath it. You will get search results from users connected to all the Hubs that you are connected to. You can double-click on any of the search results to start downloading the file.</p>
       <center><img class="img-polaroid" src="<?php echo IMAGE_URL; ?>/TheHub.png"></center><br/>
</div>
<div class='accesslevel'>
    <h4 id="step10">Step-10: Getting to know the Hub</h4><hr/>
        <p>Additionally, for each hub, to the right of the Main-Chat, there is a list of users currently online and connected to this hub. You may double-click on any of them to download and view their Filelists (a complete list of all the files that that user has shared). You may double-click on any file/directory and start downloading that too.</p>
        <p>Also, if you want to send a private message (that no one other than the recipient can see) to a particular user, you can right-click on the user's nickname and click on "Send Private Message". A new tab will open, and you can type and send the private message.</p>
        <p>In case of any problems or suggestions, please do not hesitate from contacting a DC Administrator (one of the Green Names in the UserList).</p>
</div>
<hr/><h2>More Options</h2>
<p>You may add your friends to the "<b>Favorite Users</b>" list so that you get notifications whenever they come online. In addition, you can also choose to grant them Download Slots directly (without waiting in a queue) should they need one.</p>
<p>Depending on whether your client supports this, you can set an "<b>Away Message</b>" (<span>File -> Settings -> General -> Away Message</span>). Later, if you are going to leave your room for a while but leave your system switched on and connected to DC, you can set your status as "AWAY" (by pressing the "AWAY" toggle button in the toolbar). Now, if anyone sends you a private message, your "Away Message" will immediately be sent back as a response.</p>
<p>If you prefer to download items to different folders based on what they are, you could add "<b>Favorite Download Directories</b>" under File -> Settings -> Downloads -> Favorites, so that from the next time, you can right-click on an item, and under the "Download to" option, you will find this list.</p>
<p>It is recommended that you enable <b>Sounds</b> for notifications when you get a new Private Message, or at least when a new PM window is opened. You can do this by ticking the appropriate checkboxes under <span>File -> Settings -> Appearance -> Sounds</span>.

<html>
    <head>
        <title><?php wp_title(); ?></title>
        <?php wp_head(); ?>
        <link rel='stylesheet' href='\wp-content\themes\stallions-theme\style.css' type='text/css' media='all'>
        <link rel="stylesheet" type="text/css" href="\wp-content\themes\stallions-theme\fontawesome\css/all.css" media="all">
        <link rel="stylesheet" href="/wp-content/themes/stallions-theme/fontawesome/css/fontawesome.min.css">

<!-- Global site tag (gtag.js) - Google Analytics -->

<!--<script async src="https://www.googletagmanager.com/gtag/js?id=UA-192648970-1%22%3E"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-192648970-1');
</script>-->

    </head>

<body>

        <?php // header // ?>
        <div class="headerw">
            <a href="/"><div class="bannerw">
                <div class="logow">
                    <img src="/wp-content/uploads/2021/01/saugerties-large-logo.png">
                </div>
            </div></a>
            <section class="lowerheaderw">
                <div class="numberw">
                    <h2 class="number" style="color=#bb952e;"><a href="tel:845-802-3564">845.802.3564</a></h2>
                    
                    <h2 class="stayconnected">
                        STAY CONNECTED
                        <a href="https://www.instagram.com/thesaugertiesstallions/"><i class="fab fa-instagram-square" style="margin-left:8px"></i></a>
                        <a href="https://www.facebook.com/Saugerties-Stallions-632691506871356/?fref=ts&ref=br_tf"><i class="fab fa-facebook-square" style="color:#585858"></i></a>
                        <a href="https://twitter.com/PGCBLStallions?ref_src=twsrc%5Egoogle%7Ctwcamp%5Eserp%7Ctwgr%5Eauthor"><i class="fab fa-twitter-square" style="color:#585858"></i></a>
                        <a href="https://www.youtube.com/channel/UCLhlYnJj7LA6-8hcDB5kLtw"><i class="fab fa-youtube-square" style="color:#585858"></i></a>
                          
                    </h2>
                    </div>
            </section>
                
            
                
            </div>
                <nav>
                <div class="menuw">
                    <input type="checkbox" class="mobilecheck">
                    <ul id="mobilemenu">
                        <li id="mobilemenu">MENU <i class="fas fa-bars"></i></li>
                    </ul>
                    <ul id="mainmenu">
                        <li id="mainmenu"><a href="/" id="mainmenu">HOME</a></li>
                        <li id="mainmenu" class="teammenu">
                            <input type="checkbox" class="teamcheck">
                            <a href="/team/roster/" id="mainmenu">TEAM <i class="fas fa-caret-right"></i></a>
                            <ul id="teamdropdown">
                                <a href="/team/roster/" class="menulink"><li id="teamdropdown">ROSTER</li></a>
                                <a href="http://pgcbl.bbstats.pointstreak.com/team_stats.html?teamid=153596&seasonid=32993" target="_blank" class="menulink"><li id="teamdropdown">STATISTICS</li></a>
								<a href="http://pgcbl.bbstats.pointstreak.com/standings.html?leagueid=1710&seasonid=32993" target="_blank" class="menulink"><li id="teamdropdown">STANDINGS</li></a>
                                <a href="/team/advisory-board/" class="menulink"><li id="teamdropdown">ADVISORY BOARD</li></a>
                                <a href="/team/front-office/" class="menulink"><li id="teamdropdown">FRONT OFFICE STAFF</li></a>
                                <a href="/team/coaches/" class="menulink"><li id="teamdropdown">COACHES</li></a>
                            </ul>
                        </li>
                        <li id="mainmenu"><a href="/event/" id="mainmenu">SCHEDULE</a></li>
                        <li id="mainmenu" class="ticketsmenu">
                            <input type="checkbox" class="ticketcheck">
                            <a href="/tickets/info" id="mainmenu">TICKETS <i class="fas fa-caret-right"></i></a>
                            <ul id="ticketsdropdown">
                                <a href="/tickets/info" class="menulink"><li id="ticketsdropdown">GENERAL INFORMATION</li></a>
                                <a href="/tickets/buy-tickets/" class="menulink"><li id="ticketsdropdown">BUY TICKETS</li></a>
                            </ul>
                        </li>
                        <li id="mainmenu"><a href="http://shop.saugertiesstallions.com/" id="mainmenu">STORE</a></li>
                        <li id="mainmenu"><a href="/news/" id="mainmenu">NEWS</a></li>
						<li id="mainmenu" class="cantinemenu">
                            <input type="checkbox" class="cantinecheck">
                            <a href="/stallions/cantine-field/" id="mainmenu">CANTINE FIELD <i class="fas fa-caret-right"></i></a>
                            <ul id="cantinedropdown">
                                <a href="/cantine-field/" class="menulink parentlink"><li id="cantinedropdown">CANTINE FIELD</li></a>
                                <a href="/cantine-field/directions-parking/" class="menulink"><li id="cantinedropdown">DIRECTIONS/PARKING</li></a>
                                <a href="/cantine-field/stadium-policies/" class="menulink"><li id="cantinedropdown">STADIUM POLICIES</li></a>
                            </ul>
                        </li>
                        <li id="mainmenu" class="fanmenu">
                            <input type="checkbox" class="fancheck">
                            <a href="/fan-zone/host-families/" id="mainmenu">FAN ZONE <i class="fas fa-caret-right"></i></a>
                            <ul id="fandropdown">
                                <a href="/fan-zone/host-families/" class="menulink"><li id="fandropdown">HOST FAMILIES</li></a>
                                <a href="/fan-zone/summer-camps/" class="menulink"><li id="fandropdown">SUMMER CAMPS</li></a>
                                <a href="/fan-zone/birthday-group-parties/" class="menulink"><li id="fandropdown">BIRTHDAY/GROUP PARTIES</li></a>
                                <a href="/fan-zone/appearances-request/" class="menulink"><li id="fandropdown">COMMUNITY APPEARANCES REQUEST</li></a>
                            </ul> 
                        </li>
                        <li id="mainmenu" class="sponsorshipmenu">
                            <input type="checkbox" class="sponsorshipcheck">
                            <a href="/sponsorship/" id="mainmenu">SPONSORSHIP <i class="fas fa-caret-right"></i></a>
                            <ul id="sponsorshipdropdown">
                                <a href="/sponsorship/current-partners/" class="menulink"><li id="sponsorshipdropdown">CURRENT PARTNERS</li></a>
                                <a href="/sponsorship/opportunity/" class="menulink"><li id="sponsorshipdropdown">OPPORTUNITY</li></a>
                            </ul> 
                        </li>
                        <li id="mainmenu" class="contactmenu">
                            <input type="checkbox" class="contactcheck">
                            <a href="/contact/" id="mainmenu">CONTACT US <i class="fas fa-caret-right"></i></a>
                            <ul id="contactdropdown">
                                <a href="/contact/" class="menulink parentlink"><li id="cantinedropdown">CONTACT US</li></a>
                                <a href="/contact/internship/" class="menulink"><li id="contactdropdown">INTERNSHIP OPPORTUNITIES</li></a>
                                <a href="/contact/donation-requests/" class="menulink"><li id="contactdropdown">DONATION REQUESTS</li></a>
                            </ul>
                        </li>
                    </ul>

                </div>

            </nav>
            
<meta content="width=device-width, initial-scale=1" name="viewport">
<?php // main content // ?>
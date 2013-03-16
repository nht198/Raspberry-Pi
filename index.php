<?php

// Configuration des variables, ci-dessous
$code = "raspberry";
$title = "Raspberry Pi";
$mac = "54:04:A6:C1:16:60";
$ip = "192.168.0.8";
$vnc = "lululombard.fr";
$api = '/var/www/pinfo/twitterapi.pl';

// Ne rien modifier après cette ligne si vous ne savez pas ce que vous faites !
session_start();

if ($_GET["operation"] == "boot") {
    exec('sudo etherwake '. $mac);
}

elseif ($_GET["vnc"] == "start") {
    exec('sudo vncserver :1');
}

if (isset($_POST['tweet'])) {
        $tweet=$_POST['tweet'];
        $ip=  explode(".",$_SERVER['REMOTE_ADDR']);
        if ($_SERVER['REMOTE_ADDR']!="192.168.0.1") {
            $shownip = 'x.x.'.$ip["2"].'.'.$ip["3"];
        }
        else {
            $shownip = "local";
        }
        $cmd = exec('sudo perl '.$api.' -status="'.$tweet.' (via '.$shownip.')"');
	if (strpos($cmd, 'SUCCEEDED!') == FALSE) {
		echo '<script>window.alert("Erreur lors de l\'envoi de votre tweet. Erreur : '.$cmd.'");</script>';
	}
	else {
		echo '<script>window.alert("Tweet envoyé !");</script>';
	}
}

if (isset($_POST['killall'])) {
        $process=$_POST['killall'];
        if ($process == ("apache2" OR "sshd" OR "getty" OR "mysqld" OR "sftp-server" OR "ifplugd")) {
            echo '<script>window.alert("Vous ne pouvez pas kill '.$process.' !");</script>';
        }
        else {
            exec('sudo killall '.$process);
            echo '<script>window.alert("Processus killé !");</script>';
        }
}
    
elseif ($_GET["vnc"] == "stop") {
    exec('sudo killall Xtightvnc');
}

if (file_exists("pinfo/.disabled")) {
    session_destroy();
    include('pinfo/inc/header.php');
    echo "<p align='center'><br><br><img src='pinfo/img/pinfo.png'><br><br><b>pinfo à été desactivé suite à un trop grand nombre d'erreurs lors de l'identification !</b><br><br>Vous pouvez débloquer l'accès en supprimant le fichier <i>./pinfo/.disabled</i></p>";
    die();
}

if (isset($_POST['code'])) {
    if ($_POST['code'] == $code) { 
        $_SESSION['user'] = 'logged';
    } else {
        if ($_SESSION['tries'] == "") { $_SESSION['tries'] = "0"; }
        if ($_SESSION['tries'] >= "5") { fclose(fopen("pinfo/.disabled", "w")); header("Location: index.php"); }
        $_SESSION['tries']++;
    }
}

if (isset($_GET['disconnect'])) { session_destroy(); header("Location: index.php?m=Réussi: Vous avez été déconnecté."); die(); }

include('pinfo/inc/header.php'); 

?>

<body>
    <div class="container">
        <div class="navbar">
            <div class="row-fluid">      
                <span class="span2 offset5"><center><a href="index.php"><img src="pinfo/img/pinfo.png" width="48"></a></center></span>
                <span class="span5">
                    <!--
                    <div class="pull-right">
                       <?php if (!isset($_SESSION['user']) | empty($_SESSION['user'])) { ?>
                        <form class="form" action="index.php" method="POST">
                            <div class="input-append">
                                <input type="password" id="appendedInputButton" name="code" class="input-small" placeholder="Code">
                                <button type="submit" class="btn"><i class="icon-arrow-right"></i></button>
                            </div>
                        </form>
                        <?php } else { ?>
                        <a href="index.php?disconnect" class="btn"><i class="icon-remove"></i> Déconnexion</a>
                        <?php } ?>
                    </div>
                    -->
                </span>
            </div>
        </div>
        <div class="row">
            <span class="span8">

                <div class="widget widget-table">
                    <div class="widget-header">
                        <h3><i class="icon-table"></i> Informations <button onclick="refresh();" class="btn btn-mini"><i class="icon-refresh icon-spin"></i> Actualiser</button></h3>
                    </div>
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr><th class="span2">Nom</th><th class="span10" colspan="2">Valeur</th></tr>
                            </thead>
                            <tbody>
                                <tr><td><img src="pinfo/img/time.png"> Uptime</td><td colspan="2"><div id="uptime"></div></td></tr>
                                <tr><td><img src="pinfo/img/cpu.png"> CPU</td><td class="span5"><div id="cpu_use"></div></td><td class="span5"><div id="cpu_temp"></div></td></tr>
                                <tr><td><img src="pinfo/img/ram.png"> Util. RAM</td><td colspan="2"><div id="ram"></div></td></tr>
                            </tbody>
                        </table>       
                    </div>
                </div>
                
                <div class="widget widget-table">
                    <div class="widget-header"><h3><i class="icon-home"></i> Racine</h3></div>
                    <div class="widget-content">
                        <table class="table table-striped table-bordered">
                        <?php
                            $files = scandir('./', 1);
                            foreach ($files as $file) {
                                if  (   $file != '..' &&
                                        $file != '.' &&
                                        $file != 'pinfo' &&
                                        $file != 'favicon.ico' &&
                                        $file != 'index.php' &&
                                        $file != '.pulse-cookie' //&&
                                    ) {

                                    $i = 'icon-file-alt'; if (is_dir($file)) { $i = 'icon-folder-open'; }
                                    echo '<tr><td class="span1"><i class="'.$i.'"></i></td><td><a href="'.$file.'">'.$file.'</a></td></tr>';
                                }
                            }
                        ?>
                        </table>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-header"><h3><i class="icon-hdd"></i> Stockage</h3></div>
                    <div class="widget-content"><div id="stockage"></div></div>
                </div>
                
                <div class="widget">
                    <div class="widget-header"><h3><i class=" icon-twitter"></i> Twitter</h3></div>
                    <div class="widget-content">
                        <center>
                        <form method="post">
                            <div class="form-horizontal">
                                <input name="tweet" type="text" maxlength="122" class="input-large" placeholder="Votre tweet">
                                <input type="submit" class="btn btn-primary" value="Tweet !"/>
                            </div>
                        </form>
                        <br /><a href="http://twitter.com/RaspberryPi_">Compte Twitter lié</a>
                        </center>
                    </div>
                </div>

            </span>

            <span class="span4">
                
                <?php if (file_exists('pinfo/data.xml')) { ?>

                <div class="widget">
                    <div class="widget-header"><h3><i class="icon-cog"></i> Administration</h3></div>
                    <div class="widget-content">
                        <?php 
                        
                        $xml = simplexml_load_file('pinfo/data.xml');
                        for ($i = sizeof($xml->command)-1; $i > -1; $i = $i-1) { 
                            echo '<a href="pinfo/actions.php?a='.$i.'" class="btn btn-block">'.$xml->command[$i]->name.'</a>';
                        }

                        ?>
                    </div>
                </div> 

                <?php } ?>

                <div class="widget">
                    <div class="widget-header"><h3><i class="icon-rss"></i> Réseau</h3></div>
                    <div class="widget-content">
                        <center><div id="internet"></div></center>
                        <hr>
                        <center><canvas id="internet_chart" height="70"></canvas></center>
                        <hr>
                        &bull; <b>Adresse IP</b> (locale) : <?php echo $_SERVER['SERVER_ADDR']; ?><br>
                        &bull; <b>Accès actuel depuis</b> : <?php echo $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT']; ?><br>
                        &bull; <b>HTTP</b> : <?php echo $_SERVER['SERVER_SOFTWARE']; ?>
                    </div>
                </div>
                
                <div class="widget">
                    <div class="widget-header"><h3><i class="icon-sitemap"></i> Etat du PC</h3></div>
                    <div class="widget-content">
                        Ping : <?php if (strpos(shell_exec("ping -t 1 -c 1 ".$ip), "100%") != FALSE) echo "Timeout"; else echo "1ms"; ?>
                        <hr>
                        <center>
                        <a href="?operation=boot" class="btn btn-primary btn-block">Wake PC</a> <br />
                        <a href="vnc://<?php echo $vnc; ?>:5900" class="btn btn-danger btn-block">VNC</a>
                        </center>
                    </div>
                </div>
                
                <div class="widget">
                    <div class="widget-header"><h3><i class=" icon-picture"></i> Serveur VNC</h3></div>
                    <div class="widget-content">
                        <center>
                        <a href="?vnc=start" class="btn btn-success btn-block">Démarrer le serveur</a><br />
                        <a href="?vnc=stop" class="btn btn-danger btn-block">Stopper le serveur</a><br />
                        <a href="vnc://<?php echo $_SERVER['SERVER_ADDR']; ?>:5901" class="btn btn-primary btn-block">Se connecter au serveur</a>
                        </center>
                    </div>
                </div>

                <div class="widget">
                    <div class="widget-header"><h3><i class="icon-cogs"></i> Killall debug</h3></div>
                    <div class="widget-content">
                       <center>
                        <form method="post">
                            <div class="form-horizontal">
                                <input name="killall" type="text" class="input-large" value="screen">
                                <input type="submit" class="btn btn-danger" value="Killall !"/>
                            </div>
                        </form>
                        </center>
                    </div>
                </div>

            </span>
        </div>

        <footer class="footer">
            <a href="http://creativecommons.org/licenses/by-nc-sa/3.0/fr/">Creative Commons BY-NC-SA</a> 2013 - mGeek <a href="http://twitter.com/mGeek_"><i class='icon-twitter'></i></a> (lululombard freestyle edition)
        </footer>
        <br>

    </div>

<script>
        
    var internet_chart = new SmoothieChart({grid: {strokeStyle:'rgb(30, 30, 30)', fillStyle:'rgb(20, 20, 20)', lineWidth: 1, millisPerLine: 250, verticalSections: 6 }});
    var rx = new TimeSeries(); var tx = new TimeSeries();
    internet_chart.addTimeSeries(rx, {strokeStyle:'rgb(0, 140, 0)', fillStyle:'rgba(0, 140, 0, 0.3)', lineWidth:3 });
    internet_chart.addTimeSeries(tx, { strokeStyle:'rgb(255, 116, 0)', fillStyle:'rgba(255, 116, 0, 0.3)', lineWidth:3 });
    internet_chart.streamTo(document.getElementById("internet_chart"), 1000);

    function refresh_min() { $("#uptime").load('pinfo/infos.php?i=uptime'); $("#stockage").load('pinfo/infos.php?i=stockage'); }

    function refresh_sec() {
        $.get('pinfo/infos.php?i=refresh_sec', function(data) {
            var array = data.split('|');
            $("#cpu_use").html(array[0]);
            $("#cpu_temp").html(array[1]);
            $("#ram").html(array[2]);
            $("#internet").html(array[5]);
            rx.append(new Date().getTime(), array[3]); tx.append(new Date().getTime(), array[4]);
        });
    }

    function refresh() { refresh_sec(); refresh_min(); }

    function reboot() { alert("IT'S GONNA EXPLODE !"); }
    function killscreens() { alert("Toutes les instances ont été terminées !"); }

    window.setInterval(function(){ refresh_min() }, 60000);
    window.setInterval(function(){ refresh_sec() }, 1000);
    
    refresh();

    <?php if (isset($_GET['m']) && !empty($_GET['m'])) { echo 'alert("'.$_GET["m"].'");'; } ?>

</script>

</body>
</html>
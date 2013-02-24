<?php
// Wake on lan with PHP
// CC BY-NC-SA 2012 lululombard
// You need to make an "apt-get install etherwake" to use this script. 
// You need to add "www-data" to "/etc/sudoers"

$mac = "54:04:A6:C1:16:60";
$ip = "192.168.0.8";
$vnc = "lululombard.fr";
include 'header.html';

if ($_GET["operation"] == "boot") {
    exec('sudo etherwake '. $mac);
    ?>
        <div id="circle1boot">
        <div id="circle2boot">
        <p>Boot<br />in process<br />...</p>
    <?
}

else {
    $ping = shell_exec("ping -t 1 -c 1 ".$ip);
    if (strpos($ping, "100%") != FALSE) {
        ?>
            <div id="circle1off">
            <div id="circle2off">
            <p><a href="?operation=boot">&#xF011;</a></p>
        <?
    }
    else {
        ?>
            <div id="circle1on">
            <div id="circle2on">
            <p><a href="<? echo 'vnc://'.$vnc.':5900';?>">VNC</a></p>
        <?
    }
}
include 'footer.html';
?>
<?php
// Wake on lan with PHP
// CC BY-NC-SA 2012 lululombard
// You need to make an "apt-get install etherwake" to use this script. 
// You need to add "www-data" to "/etc/sudoers"

$mac = "54:04:A6:C1:16:60";                                                     // Mac address of your target
$ip = "192.168.0.8";

echo "<h3>Wake on lan with PHP</h3>";                                           // Header

if ($_GET["operation"] == "boot") {                                             // Check the URL
	exec('sudo etherwake '. $mac);                                          // Send the magic command
	echo $mac .' will boot.';                                               // Say what it does
}

else {                                                                          // If not in URL
	echo '<a href="?operation=boot">Boot '. $mac .'</a>';                   // Show the link to reboot
}

$ping = shell_exec("ping -t 1 -n 1 ".$ip);
echo $ping;
//        if (strpos($ping, "100%") == FALSE) {
//            echo 'PC OFF';
//        }
//        else {
//            echo 'PC ON';
//        }

?>
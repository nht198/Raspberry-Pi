<?php
// Reboot Raspberry Pi with PHP
// CC BY-NC-SA 2012 lululombard
// You need to add "www-data" to "/etc/sudoers"

echo "<h3>Stop screens with PHP</h3>";                                          // Header

if ($_GET["operation"] == "stop") {                                             // Check the URL
	exec('sudo killall screen');                                            // Send the magic command
	echo "All screens has been killed";                                     // Say what it does
}

else {                                                                          // If not in URL
	echo '<a href="?operation=stop">Kill screens</a>';                      // Show the link to kill screens
}
?>
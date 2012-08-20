<?php
// Reboot Raspberry Pi with PHP
// CC BY-NC-SA 2012 lululombard
// You need to add "www-data" to "/etc/sudoers"

echo "<h3>Reboot Raspberry Pi with PHP</h3>";                                   // Header

if ($_GET["operation"] == "reboot") {                                           // Check the URL
	exec('sudo reboot');                                                    // Send the magic command
	echo "Your Raspberry Pi will now reboot...";                            // Say what it does
}

else {                                                                          // If not in URL
	echo '<a href="?operation=reboot">Reboot Raspberry Pi</a>';             // Show the link to reboot
}
?>
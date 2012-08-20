<?
// Play music on Raspberry Pi with PHP
// CC BY-NC-SA 2012 lululombard
// You need "screen", "mpg123" and "alsa-utils". Get it by running "sudo apt-get install alsa-utils mpg123 screen"
// If you're not running Raspbian, you should run "modprobe snd_bcm2835" as root before, if you need it at each boot, just make a crontab ;)

$music = "/var/www/Paradise.mp3";                                               // Your file
$screen_name = "phplay";                                                        // Name of the screen (backgrounding task)

if ($_GET["control"] == "play") {                                               // Check URL
	$extension = substr($music, -3, 3);                                     // Get filetype. alsa can only play .wav

        if ($extension == "mp3") {                                              // Check if filetype is mp3
            exec('screen -dmS '.$screen_name.' mpg123 '. $music);                                          // Play mp3 in background with mpg123 
        }

        else {                                                                  // Else
            exec('screen -dmS '.$screen_name.' aplay '. $music);                // Play wav in background with aplay. 
        }
	header('Location: '.$_SERVER['PHP_SELF']);                              // Redirect to the page itself
}
if ($_GET["control"] == "stop") {                                               // Check URL
	exec('screen -p 0 -S '.$screen_name.' -X kill');                        // Kill the background wav playing
        header('Location: '.$_SERVER['PHP_SELF']);                              // Redirect to the page itself
}

echo "<h3>PHP WAV/MP3 player for Raspberry Pi by lululombard</h3>\n<br />\n";   // Header

        $list = shell_exec("screen -ls");                                       // List screens 
if (strpos($list, $screen_name) == FALSE) {                                     // Check if the backgrounding task is running
	echo '<a href="?control=play">Play</a>';                                // If no, show the play button
}
else {                                                                          // If yes
	echo '<a href="?control=stop">Stop</a>';                                // Show Stop button
}
?>
<?php
// Speak to arduino with PHP
// CC BY-NC-SA 2012 lululombard
// You need serial.sh with chmod 777 in /var/www to use this. 
// You need to disable terminal on /dev/ttyAMA0 : "nano /boot/cmdline.txt", and delete "console=ttyAMA0,115200 kgdboc=ttyAMA0,115200".  CTRL+X to save.
// Then, "nano /etc/inittab", go to the last line and add "#" in front of "2:23:respawn:/sbin/getty -L ttyAMA0 115200 vt100"
// Made to work with "domotique.ino", with ardunio connected to rx/tx of the GPIOs
// Not yet commented, as the name says, it's beta !

        $screen_name = "arduino";
        $user = "www-data";
        
        $list = shell_exec("ls /var/run/screen/S-".$user);
        if (strpos($list, $screen_name) == FALSE) {
                exec('screen -dmS arduino /dev/ttyAMA0 115200');
                sleep(1);
                exec('screen -S arduino -X height 1');
                exec('/var/www/serial.sh 2');
                header('Location: '.$_SERVER['PHP_SELF']);
        }
        elseif ($_GET['pin']) {
                $allowed = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l");
                if (in_array($_GET['pin'], $allowed)) {
                        exec('/var/www/serial.sh '.$_GET['pin']);
                        header('Location: '.$_SERVER['PHP_SELF']);
                }
                else {
                        echo "Caractere non pris en charge.<br />";
                }
        }
        else {
        exec('/var/www/serial.sh 1');
        exec('screen -S arduino -X hardcopy /var/www/status.txt');
        $serial = exec('tail -1 /var/www/status.txt');
        $status = explode(";", $serial);
       
        $on="ON";
        $off="OFF";
        $checked_pin = 0;
        foreach ($status as $actual_pin) {
                if($actual_pin == 1){$text[$checked_pin]=$off;}else{$text[$checked_pin]=$on;}
                $checked_pin++;
        }
        $pin = 2;
        $checked_pin = 0;
        $pins_order = array("a","b","c","d","e","f","g","h","i","j","k","l");
        foreach ($text as $actual_pin) {
                echo "PIN ".$pin.": ".$status[$checked_pin]." <a href=\"?pin=".$pins_order[$checked_pin]."\">". $actual_pin ."</a><br />";
                $checked_pin++;
                $pin++;
        }
        }
?>
<?php
        $screen_name = "arduino";
        $user = "www-data";
        $list = shell_exec("ls /var/run/screen/S-".$user);
        if (strpos($list, $screen_name) == FALSE) {
                exec('screen -dmS arduino /dev/ttyAMA0 115200');
                sleep(1);
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
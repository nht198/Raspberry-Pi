<?php

session_start();

switch ($_GET['i']) {
	case 'uptime':
		$uptime = explode(" ", shell_exec("cat /proc/uptime"));
		echo "Depuis ".secondsToReadableTime($uptime[0]) ;
		break;

	case 'stockage':
		exec('df -hT | grep -vE "tmpfs|rootfs|Filesystem"', $drives);
		for ($drive = 0; $drive < count($drives); $drive++)  {
			$drives[$drive] = preg_replace('!\s+!', ' ', $drives[$drive]);
			preg_match_all('/\S+/', $drives[$drive], $drivedetails);
		
			$a = 0;
			while (1) {
				if (strpos($drivedetails[0][5+$a], '%') != FALSE) { break; }
				$a++;
			}
			
			$img = "sd.png";

			echo '<div class="row">';
			echo '<span class="span2">';
			echo '<img src="pinfo/img/'.$img.'"> <b>'.$drivedetails[0][6+$a].'</b> ('.$drivedetails[0][1+$a].')';
			echo '</span>';
			echo '<span class="span2">';
			echo $drivedetails[0][4+$a].'B <span class="muted">/</span> '.$drivedetails[0][2+$a].'B<br>';
			echo '</span><span class="span3">';
									
			$bar = "";
			if ($drivedetails[0][5+$a] > 80) { $bar = "progress-danger"; }

			echo '<div class="progress '.$bar.'">';
			echo '<div class="bar" style="width: '.$drivedetails[0][5+$a].';">'.$drivedetails[0][5+$a].'</div>';
			echo '</div>';
			echo '</span>';
			echo '</div>';
		}	
		break;

	case 'refresh_sec':
		$cpu = 0;
		exec("ps -e -o pcpu", $out);
		foreach ($out as $use) { $cpu += $use; }

		$cpu_disp = $cpu; $bar = "";
		if ($cpu <= 7) { $cpu_disp = 10; } //Rends la progression moins précise, mais le numéro s'affiche correctement
		if ($cpu <= 15) { $cpu = round($cpu); } //Rends le numéro plus petit (supress. virgule) pour qu'il s'affiche correctement
		if ($cpu >= 80) { $bar = "progress-danger"; }

		echo '<div class="progress '.$bar.'">';
		echo '<div class="bar" style="width: '.$cpu_disp.'%;">'.$cpu.'% </div>';
		echo '</div>';
		
		echo '|';

		$cpu_temp = round(shell_exec("cat /sys/class/thermal/thermal_zone0/temp")/1000);
		$percent = round(($cpu_temp/80)*100);

		$bar = "";
		if ($cpu_temp >= 60) { $bar = "progress-danger"; }

		echo '<div class="progress '.$bar.'">';
		echo '<div class="bar" style="width: '.$percent.'%;">'.$percent.'% ('.$cpu_temp.'°C)</div>';
		echo '</div>';
		
		echo '|';

		$out = "";
		exec('free -mo', $out);
		preg_match_all('/\s+([0-9]+)/', $out[1], $matches);
		list($total, $used, $free, $shared, $buffers, $cached) = $matches[1];
		$percent = round(($used - $buffers - $cached) / $total * 100);

		$bar = "";
		if ($percent > 80) { $bar = "progress-danger"; }
		
		echo 'Utilisé : '.($used - $buffers - $cached).'MB <span class="muted">/</span> Libre : '.($free + $buffers + $cached).'MB <span class="muted">/</span> Total : '.$total.'MB';
		echo '<div class="progress '.$bar.'">';
		echo '<div class="bar" style="width: '.$percent.'%;">'.$percent.'%</div>';
		echo '</div>';

		echo '|';

		$string = shell_exec('/sbin/ifconfig eth0 | /bin/grep "RX bytes"');
   		$string = str_ireplace("RX bytes:", "", $string);
		$string = str_ireplace("TX bytes:", "", $string);
		$string = trim($string);
		$string = explode(" ", $string);

   		$rx_current = $string[0];
		$tx_current = $string[4];
		$rx_old = $_SESSION['rx'];
		$tx_old = $_SESSION['tx'];

		$_SESSION['rx'] = $rx_current;
		$_SESSION['tx'] = $tx_current;

		echo round(($rx_current-$rx_old)/1024,2);
		echo '|';
		echo round(($tx_current-$tx_old)/1024,2); 
		echo '|';
		echo '<i class="icon-circle-arrow-down"></i> : '.round(($rx_current-$rx_old)/1024,2).'kB/s <span class="muted">/</span> <i class="icon-circle-arrow-up"></i> : '.round(($tx_current-$tx_old)/1024,2).'kB/s<br>';
		
		break; // Doit être appelée toutes les secondes sinon la vitesse internet est fausse

}

function secondsToReadableTime($seconds){
	$y = floor($seconds/60/60/24/365);
	$d = floor($seconds/60/60/24) % 365;
	$h = floor(($seconds / 3600) % 24);
	$m = floor(($seconds / 60) % 60);

	$string = '';
	if($y > 0) { $yw = $y > 1 ? ' années ' : ' année '; $string .= $y . $yw; }
	if($d > 0) { $dw = $d > 1 ? ' jours ' : ' jour '; $string .= $d . $dw; }
	if($h > 0) { $hw = $h > 1 ? ' heures ' : ' heure '; $string .= $h . $hw; }
	if($m > 0) {  $mw = $m > 1 ? ' minutes ' : ' minute '; $string .= $m . $mw; }
	return preg_replace('/\s+/',' ',$string);
}

?>

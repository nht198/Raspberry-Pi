<?
if ($_GET['ctrl']=="start")
    exec('sudo screen -dmS webcam python3 /var/www/refresh.py');
if ($_GET['ctrl']=="stop")
    exec('sudo screen -p 0 -S webcam -X kill');
?>
<html>
<head>
<script type="text/javascript">
<!--
function refresh(){
    document.images["pic"].src="outfile.jpeg?<? echo md5_file('/var/www/outfile.jpeg'); ?>";
setTimeout('refresh()', 100);}

if(document.images)window.onload=refresh;

// -->
</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/
libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
var auto_refresh = setInterval(
function ()
{
$('#refresh').load('streaming.php').fadeIn("slow");
}, 1500);
</script>
</head> 

<body>
<img id="pic" width="800" height="600"/>
<div id="refresh">
<? exec('streamer -c /dev/video0 -b 16 -o /var/www/outfile.jpeg'); ?>
</div>
</body>
</html>
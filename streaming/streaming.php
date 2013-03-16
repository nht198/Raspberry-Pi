<html>
<head>
<script type="text/javascript">
<!--
function refresh(){
    document.images["pic"].src="outfile.jpeg?<? echo md5_file('/var/www/streaming/outfile.jpeg'); ?>";
setInterval('refresh()', 500);}

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
<center>
<img id="pic" height="100%"/>
<div id="refresh">
<? exec('streamer -c /dev/video0 -b 16 -o /var/www/streaming/outfile.jpeg'); ?>
</div>
</center>
</body>
</html>
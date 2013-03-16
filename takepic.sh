jour=$(date +%Y-%m-%d)
heure=$(date +%H:%M)
streamer -c /dev/video0 -b 16 -o /var/www/pic/Capture\ $jour\ $heure.jpeg

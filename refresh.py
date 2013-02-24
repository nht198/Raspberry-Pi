import os
while 1:
	os.system('streamer -c /dev/video0 -b 16 -o /var/www/outfile.jpeg')

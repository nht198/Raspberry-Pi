import os
import time
cmd1 = "streamer -c /dev/video0 -b 16 -o /var/www/camera/outfile"
ext = ".jpeg"
while 1:
    time.sleep(1)
    timestamp = time.time()
    print (timestamp)
    cmd = cmd1 + str(int(timestamp)) + ext
    os.system(cmd)
#!/bin/sh

### BEGIN INIT INFO
# Provides:             plaatsign
# Required-Start:       $remote_fs $syslog
# Required-Stop:        $remote_fs $syslog
# Default-Start:        2 3 4 5
# Default-Stop:
# Short-Description:    PlaatSign Digital Content Viewer
### END INIT INFO

do_start() {
 while [ 1 ]   
 do
    setterm -cursor off;
    clear
	 #fbi -d /dev/fb0 -T 2 -t 10 -noverbose -a /var/www/html/plaatsign/uploads/images/* -1
	 fbi -d /dev/fb0 -t 10 -noverbose -a /var/www/html/plaatsign/uploads/images/* -1;
	 
	 #pqiv -f -s -i -t -d 10 -n --watch-directories /var/www/html/plaatsign/uploads/images	 
	 
	 omxplayer /var/www/html/plaatsign/uploads/videos.*.mp4; | echo "";
  done
  setterm -cursor on;
}

case "$1" in
  start)
  do_start
  ;;
  stop|status)
  echo "Error: argument '$1' not supported" >&2
  ;;
  *)
  echo "Usage: plaatsign [start|stop|status]" >&2
  exit 3
  ;;  
esac


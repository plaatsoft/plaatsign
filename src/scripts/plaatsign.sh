#!/bin/sh

### BEGIN INIT INFO
# Provides:             plaatsign
# Required-Start:       $remote_fs $syslog
# Required-Stop:        $remote_fs $syslog
# Default-Start:        3 
# Default-Stop:
# Short-Description:    PlaatSign Digital Content Viewer
### END INIT INFO

do_start() {
 while [ 1 ]   
 do
    clear
	 fbi -t 10 -d /dev/fb0 -noverbose -a /var/www/html/plaatsign/uploads/images/* -1 >> /dev/null  
  done
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


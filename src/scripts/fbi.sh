#!/bin/bash

while [ 1 ] 
do
clear
fbi -t 5 -d /dev/fb0 -noverbose -a /var/www/html/plaatsign/uploads/* -1 >> /dev/null
done

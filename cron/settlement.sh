###
# EBENGKEL SETTLEMENT
##

#!/bin/sh

#set this path to root path
EBENGKEL_HOME='/var/www/ebengkel'

SP="-----------------------------------------------------------------------------"

dt=$(date +"%Y-%m-%d %H:%M:%S")
echo "$dt EBENGKEL SETTLEMENT"

dt=$(date +"%Y-%m-%d %H:%M:%S")
echo "$dt locating EBENGKEL_HOME : $EBENGKEL_HOME"
cd $EBENGKEL_HOME

#export path to assign php
export PATH=/opt/lampp/bin:$PATH

PS=$(php artisan settlement:daily 2>&1)
echo "$PS"

dt=$(date +"%Y-%m-%d %H:%M:%S")
echo "$dt $SP"


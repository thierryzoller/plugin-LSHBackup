#!/bin/bash
ME=`dirname ${BASH_SOURCE[0]}`
osascript -e "tell application \"Terminal\" to do script \"cd $ME; rm -f config.txt; clear; touch config.txt; ./rclone.bin config --config config.txt; open config.txt\""
osascript -e 'tell application "Terminal" to activate'

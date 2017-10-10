#!/bin/bash
echo disable plugin and press enter
read
rm external/rclone/rclone
rm external/rclone_windows/jeedom-rclone-configurator.zip
mv .git ../datatransfert_git
echo upload to market and press enter
read
mv ../datatransfert_git .git

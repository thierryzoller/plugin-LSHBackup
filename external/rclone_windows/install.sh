#!/bin/sh
cd `dirname $0`
VERSION=v1.38
rm -rf rclone-$VERSION-windows-386.zip rclone-$VERSION-windows-386 jeedom-rclone-configurator
wget https://downloads.rclone.org/rclone-$VERSION-windows-386.zip
unzip rclone-$VERSION-windows-386.zip
mkdir jeedom-rclone-configurator
mv rclone-$VERSION-windows-386/rclone.exe jeedom-rclone-configurator
cp rclone.cmd jeedom-rclone-configurator/configurator.cmd
zip -r jeedom-rclone-configurator jeedom-rclone-configurator
rm -rf rclone-$VERSION-windows-386.zip rclone-$VERSION-windows-386 jeedom-rclone-configurator
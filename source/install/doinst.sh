#!/bin/bash
BOOT="/boot/config/plugins/dwsnap"
DOCROOT="/usr/local/emhttp/plugins/dwsnap"

chmod +0755 $DOCROOT/scripts/*

if [ ! -d $BOOT/config ]; then
    mkdir $BOOT/config
fi

cp -nr $DOCROOT/defaults/snapraid.conf $BOOT/config/snapraid.conf

if [ ! -L /etc/snapraid.conf ]; then
    rm -f /etc/snapraid.conf
    ln -sf $BOOT/config/snapraid.conf /etc/snapraid.conf
fi

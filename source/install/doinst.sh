#!/bin/bash
BOOT="/boot/config/plugins/dwsnap"
DOCROOT="/usr/local/emhttp/plugins/dwsnap"

chmod +0755 $DOCROOT/scripts/*
chmod +0755 /usr/sbin/snaprunner

if [ ! -d $BOOT/config ]; then
    mkdir -p $BOOT/config
fi

if [ ! -d /var/log/snapraid ]; then
    mkdir -p /var/log/snapraid
fi

cp -nr $DOCROOT/defaults/snapraid.conf $BOOT/config/snapraid.conf

if [ ! -L /etc/snapraid.conf ]; then
    rm -f /etc/snapraid.conf
    ln -sf $BOOT/config/snapraid.conf /etc/snapraid.conf
fi

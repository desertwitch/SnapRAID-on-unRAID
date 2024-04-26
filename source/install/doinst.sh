#!/bin/bash
#
# Copyright Derek Macias (parts of code from NUT package)
# Copyright macester (parts of code from NUT package)
# Copyright gfjardim (parts of code from NUT package)
# Copyright SimonF (parts of code from NUT package)
# Copyright desertwitch
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License 2
# as published by the Free Software Foundation.
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
BOOT="/boot/config/plugins/dwsnap"
DOCROOT="/usr/local/emhttp/plugins/dwsnap"

chmod +0755 $DOCROOT/scripts/*
chmod +0755 $DOCROOT/event/*
chmod +0755 /usr/bin/snapraid-cron
chmod +0755 /usr/bin/snapraid-runner

if [ ! -d $BOOT/config ]; then
    mkdir -p $BOOT/config
fi

if ! mountpoint -q /var/lib/snapraid 2>/dev/null; then 
    rm -rf /var/lib/snapraid
    mkdir -p /var/lib/snapraid
    mount -t tmpfs -o size=30% tmpfs /var/lib/snapraid
fi

if [ ! -d /var/lib/snapraid/logs ]; then
    mkdir -p /var/lib/snapraid/logs
fi

if [ ! -f /var/lib/snapraid/logs/snaplog ] && [ -f $BOOT/config/snaplog ]; then
    mv -f $BOOT/config/snaplog /var/lib/snapraid/logs/snaplog
fi

cp -n $DOCROOT/default.cfg $BOOT/dwsnap.cfg
cp -n $DOCROOT/defaults/snapraid.conf $BOOT/config/snapraid.conf

if [ ! -L /etc/snapraid.conf ]; then
    rm -f /etc/snapraid.conf
    ln -sf $BOOT/config/snapraid.conf /etc/snapraid.conf
fi

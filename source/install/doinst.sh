#!/bin/bash
#
# Copyright Derek Macias (parts of code from NUT package)
# Copyright macester (parts of code from NUT package)
# Copyright gfjardim (parts of code from NUT package)
# Copyright SimonF (parts of code from NUT package)
# Copyright Lime Technology (any and all other parts of Unraid)
#
# Copyright desertwitch (as author and maintainer of this file)
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

chmod 755 $DOCROOT/scripts/*
chmod 755 $DOCROOT/event/*
chmod 755 /usr/bin/snapraid-cron
chmod 755 /usr/bin/snapraid-runner

if [ ! -d $BOOT/config ]; then
    mkdir -p $BOOT/config
fi

if ! mountpoint -q /var/lib/snapraid; then
    rm -rf /var/lib/snapraid
    mkdir -p /var/lib/snapraid
    if ! mount -t tmpfs -o size=40% tmpfs /var/lib/snapraid; then
        echo "[warning] Failed to create a RAM disk for SnapRAID, falling back to a regular folder." | logger -t "snapraid-install"
    fi
fi

chown root:root /var/lib/snapraid
chmod 755 /var/lib/snapraid

if [ ! -d /var/lib/snapraid/logs ]; then
    mkdir -p /var/lib/snapraid/logs
fi

for logfile in "$BOOT"/config/*-snaplog; do
    logname=$(basename "$logfile")
    if [ ! -f "/var/lib/snapraid/logs/${logname}" ]; then
        mv -f "$logfile" "/var/lib/snapraid/logs/${logname}"
    else
        rm -f "$logfile"
    fi
done

cp -n $DOCROOT/default.cfg $BOOT/dwsnap.cfg
cp -n $DOCROOT/defaults/primary.cfg $BOOT/config/primary.cfg
cp -n $DOCROOT/defaults/primary.conf $BOOT/config/primary.conf

# set up plugin-specific polling tasks
rm -f /etc/cron.daily/snapraid-poller >/dev/null 2>&1
ln -sf /usr/local/emhttp/plugins/dwsnap/scripts/poller /etc/cron.daily/snapraid-poller >/dev/null 2>&1
chmod +x /etc/cron.daily/snapraid-poller >/dev/null 2>&1

<?
/* Copyright Derek Macias (parts of code from NUT package)
 * Copyright macester (parts of code from NUT package)
 * Copyright gfjardim (parts of code from NUT package)
 * Copyright SimonF (parts of code from NUT package)
 * Copyright Dan Landon (parts of code from Web GUI)
 * Copyright Bergware International (parts of code from Web GUI)
 * Copyright Lime Technology (any and all other parts of Unraid)
 *
 * Copyright desertwitch (as author and maintainer of this file)
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 */
require_once '/usr/local/emhttp/plugins/dwsnap/include/dwsnap_helpers.php';

$dwsnap_cfg  = parse_ini_file("/boot/config/plugins/dwsnap/dwsnap.cfg");
$dwsnap_prio = trim(isset($dwsnap_cfg['PRIO']) ? htmlspecialchars($dwsnap_cfg['PRIO']) : 'disable');
$dwsnap_footer = trim(isset($dwsnap_cfg['FOOTER']) ? htmlspecialchars($dwsnap_cfg['FOOTER']) : 'disable');
$dwsnap_screenimg = trim(isset($dwsnap_cfg['SCREENIMG']) ? htmlspecialchars($dwsnap_cfg['SCREENIMG']) : 'enable');
$dwsnap_stoparray = trim(isset($dwsnap_cfg['STOPARRAY']) ? htmlspecialchars($dwsnap_cfg['STOPARRAY']) : 'enable');
$dwsnap_killtime = trim(isset($dwsnap_cfg['KILLTIME']) ? htmlspecialchars($dwsnap_cfg['KILLTIME']) : '30');
$dwsnap_cron = trim(isset($dwsnap_cfg['CRON']) ? htmlspecialchars($dwsnap_cfg['CRON']) : 'disable');
$dwsnap_cronhour = trim(isset($dwsnap_cfg['CRONHOUR']) ? htmlspecialchars($dwsnap_cfg['CRONHOUR']) : '1');
$dwsnap_crondow = trim(isset($dwsnap_cfg['CRONDOW']) ? htmlspecialchars($dwsnap_cfg['CRONDOW']) : '0');
$dwsnap_crondom = trim(isset($dwsnap_cfg['CRONDOM']) ? htmlspecialchars($dwsnap_cfg['CRONDOM']) : '1');
$dwsnap_startnotify = trim(isset($dwsnap_cfg['STARTNOTIFY']) ? htmlspecialchars($dwsnap_cfg['STARTNOTIFY']) : 'disable');
$dwsnap_finishnotify = trim(isset($dwsnap_cfg['FINISHNOTIFY']) ? htmlspecialchars($dwsnap_cfg['FINISHNOTIFY']) : 'enable');
$dwsnap_errornotify = trim(isset($dwsnap_cfg['ERRORNOTIFY']) ? htmlspecialchars($dwsnap_cfg['ERRORNOTIFY']) : 'enable');
$dwsnap_noprogress = trim(isset($dwsnap_cfg['NOPROGRESS']) ? htmlspecialchars($dwsnap_cfg['NOPROGRESS']) : 'enable');
$dwsnap_touch = trim(isset($dwsnap_cfg['TOUCH']) ? htmlspecialchars($dwsnap_cfg['TOUCH']) : 'enable');
$dwsnap_diff = trim(isset($dwsnap_cfg['DIFF']) ? htmlspecialchars($dwsnap_cfg['DIFF']) : 'enable');
$dwsnap_sync = trim(isset($dwsnap_cfg['SYNC']) ? htmlspecialchars($dwsnap_cfg['SYNC']) : 'enable');
$dwsnap_prehash = trim(isset($dwsnap_cfg['PREHASH']) ? htmlspecialchars($dwsnap_cfg['PREHASH']) : 'disable');
$dwsnap_forcezero = trim(isset($dwsnap_cfg['FORCEZERO']) ? htmlspecialchars($dwsnap_cfg['FORCEZERO']) : 'disable');
$dwsnap_sync_errors = trim(isset($dwsnap_cfg['SYNCERRORS']) ? htmlspecialchars($dwsnap_cfg['SYNCERRORS']) : '100');
$dwsnap_scrub = trim(isset($dwsnap_cfg['SCRUB']) ? htmlspecialchars($dwsnap_cfg['SCRUB']) : 'enable');
$dwsnap_scrubpercent = trim(isset($dwsnap_cfg['SCRUBPERCENT']) ? htmlspecialchars($dwsnap_cfg['SCRUBPERCENT']) : '5');
$dwsnap_scrubage = trim(isset($dwsnap_cfg['SCRUBAGE']) ? htmlspecialchars($dwsnap_cfg['SCRUBAGE']) : '10');
$dwsnap_scrubnew = trim(isset($dwsnap_cfg['SCRUBNEW']) ? htmlspecialchars($dwsnap_cfg['SCRUBNEW']) : 'disable');
$dwsnap_scrub_errors = trim(isset($dwsnap_cfg['SCRUBERRORS']) ? htmlspecialchars($dwsnap_cfg['SCRUBERRORS']) : '100');
$dwsnap_added = trim(isset($dwsnap_cfg['ADDED']) ? htmlspecialchars($dwsnap_cfg['ADDED']) : '-1');
$dwsnap_deleted = trim(isset($dwsnap_cfg['DELETED']) ? htmlspecialchars($dwsnap_cfg['DELETED']) : '-1');
$dwsnap_updated = trim(isset($dwsnap_cfg['UPDATED']) ? htmlspecialchars($dwsnap_cfg['UPDATED']) : '-1');
$dwsnap_moved = trim(isset($dwsnap_cfg['MOVED']) ? htmlspecialchars($dwsnap_cfg['MOVED']) : '-1');
$dwsnap_copied = trim(isset($dwsnap_cfg['COPIED']) ? htmlspecialchars($dwsnap_cfg['COPIED']) : '-1');
$dwsnap_restored = trim(isset($dwsnap_cfg['RESTORED']) ? htmlspecialchars($dwsnap_cfg['RESTORED']) : '-1');

$dwsnap_backend = trim(htmlspecialchars(shell_exec("find /var/log/packages/ -type f -iname 'snapraid-*' -printf '%f\n' 2>/dev/null") ?? "n/a"));

$dwsnap_laststart = trim(file_exists("/var/lib/snapraid/logs/laststart") ? htmlspecialchars(file_get_contents("/var/lib/snapraid/logs/laststart")) : "-");
$dwsnap_lastfinish = trim(file_exists("/var/lib/snapraid/logs/lastfinish") ? htmlspecialchars(file_get_contents("/var/lib/snapraid/logs/lastfinish")) : "-");
$dwsnap_lastsync = trim(file_exists("/boot/config/plugins/dwsnap/config/lastsync") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/lastsync")) : "-");
$dwsnap_lastscrub = trim(file_exists("/boot/config/plugins/dwsnap/config/lastscrub") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/lastscrub")) : "-");
$dwsnap_lastnodiff = trim(file_exists("/boot/config/plugins/dwsnap/config/lastnodiff") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/lastnodiff")) : "-");

$dwsnap_cfg_content = file_get_contents("/etc/snapraid.conf") ?? "n/a";
$dwsnap_parity_re = '/(.*?parity) (\/mnt\/.*?)\//m';
$dwsnap_data_re = '/data (.*?) (\/mnt\/.*?)\//m';

preg_match_all($dwsnap_parity_re, $dwsnap_cfg_content, $dwsnap_parity_disks, PREG_SET_ORDER);
preg_match_all($dwsnap_data_re, $dwsnap_cfg_content, $dwsnap_data_disks, PREG_SET_ORDER);

?>

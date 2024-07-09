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

class SnapraidArrayConfiguration {
    public $cfgname;
    public $cfg;
    public $prio;
    public $sync_expires;
    public $scrub_expires;
    public $rawreports;
    public $cron;
    public $cronhour;
    public $crondow;
    public $crondom;
    public $startnotify;
    public $finishnotify;
    public $errornotify;
    public $noprogress;
    public $touch;
    public $diff;
    public $sync;
    public $prehash;
    public $forcezero;
    public $sync_errors;
    public $scrub;
    public $scrubpercent;
    public $scrubage;
    public $scrubnew;
    public $scrub_errors;
    public $added;
    public $deleted;
    public $updated;
    public $moved;
    public $copied;
    public $restored;
    public $laststart;
    public $lastfinish;
    public $lastsync;
    public $lastscrub;
    public $lastnodiff;
    public $snapcfg;
    public $parity_disks = [];
    public $data_disks = [];
    public $parity_disks_raw = [];
    public $data_disks_raw = [];
    public $content_files_raw = [];

    public function __construct($cfg_name)
    {
        $this->cfgname = $cfg_name;

        $this->cfg = file_exists("/boot/config/plugins/dwsnap/config/$cfg_name.cfg") ? parse_ini_file("/boot/config/plugins/dwsnap/config/$cfg_name.cfg") : [];
        $this->snapcfg = file_exists("/boot/config/plugins/dwsnap/config/$cfg_name.conf") ? file_get_contents("/boot/config/plugins/dwsnap/config/$cfg_name.conf") : "-";

        $this->prio = trim(isset($this->cfg['PRIO']) ? htmlspecialchars($this->cfg['PRIO']) : 'disable');
        $this->sync_expires = trim(isset($this->cfg['SYNCEXPIRES']) ? htmlspecialchars($this->cfg['SYNCEXPIRES']) : '7');
        $this->scrub_expires = trim(isset($this->cfg['SCRUBEXPIRES']) ? htmlspecialchars($this->cfg['SCRUBEXPIRES']) : '7');
        $this->rawreports = trim(isset($this->cfg['RAWREPORTS']) ? htmlspecialchars($this->cfg['RAWREPORTS']) : 'disable');
        $this->cron = trim(isset($this->cfg['CRON']) ? htmlspecialchars($this->cfg['CRON']) : 'disable');
        $this->cronhour = trim(isset($this->cfg['CRONHOUR']) ? htmlspecialchars($this->cfg['CRONHOUR']) : '1');
        $this->crondow = trim(isset($this->cfg['CRONDOW']) ? htmlspecialchars($this->cfg['CRONDOW']) : '0');
        $this->crondom = trim(isset($this->cfg['CRONDOM']) ? htmlspecialchars($this->cfg['CRONDOM']) : '1');
        $this->startnotify = trim(isset($this->cfg['STARTNOTIFY']) ? htmlspecialchars($this->cfg['STARTNOTIFY']) : 'disable');
        $this->finishnotify = trim(isset($this->cfg['FINISHNOTIFY']) ? htmlspecialchars($this->cfg['FINISHNOTIFY']) : 'enable');
        $this->errornotify = trim(isset($this->cfg['ERRORNOTIFY']) ? htmlspecialchars($this->cfg['ERRORNOTIFY']) : 'enable');
        $this->noprogress = trim(isset($this->cfg['NOPROGRESS']) ? htmlspecialchars($this->cfg['NOPROGRESS']) : 'enable');
        $this->touch = trim(isset($this->cfg['TOUCH']) ? htmlspecialchars($this->cfg['TOUCH']) : 'enable');
        $this->diff = trim(isset($this->cfg['DIFF']) ? htmlspecialchars($this->cfg['DIFF']) : 'enable');
        $this->sync = trim(isset($this->cfg['SYNC']) ? htmlspecialchars($this->cfg['SYNC']) : 'enable');
        $this->prehash = trim(isset($this->cfg['PREHASH']) ? htmlspecialchars($this->cfg['PREHASH']) : 'disable');
        $this->forcezero = trim(isset($this->cfg['FORCEZERO']) ? htmlspecialchars($this->cfg['FORCEZERO']) : 'disable');
        $this->sync_errors = trim(isset($this->cfg['SYNCERRORS']) ? htmlspecialchars($this->cfg['SYNCERRORS']) : '100');
        $this->scrub = trim(isset($this->cfg['SCRUB']) ? htmlspecialchars($this->cfg['SCRUB']) : 'enable');
        $this->scrubpercent = trim(isset($this->cfg['SCRUBPERCENT']) ? htmlspecialchars($this->cfg['SCRUBPERCENT']) : '5');
        $this->scrubage = trim(isset($this->cfg['SCRUBAGE']) ? htmlspecialchars($this->cfg['SCRUBAGE']) : '10');
        $this->scrubnew = trim(isset($this->cfg['SCRUBNEW']) ? htmlspecialchars($this->cfg['SCRUBNEW']) : 'disable');
        $this->scrub_errors = trim(isset($this->cfg['SCRUBERRORS']) ? htmlspecialchars($this->cfg['SCRUBERRORS']) : '100');
        $this->added = trim(isset($this->cfg['ADDED']) ? htmlspecialchars($this->cfg['ADDED']) : '-1');
        $this->deleted = trim(isset($this->cfg['DELETED']) ? htmlspecialchars($this->cfg['DELETED']) : '-1');
        $this->updated = trim(isset($this->cfg['UPDATED']) ? htmlspecialchars($this->cfg['UPDATED']) : '-1');
        $this->moved = trim(isset($this->cfg['MOVED']) ? htmlspecialchars($this->cfg['MOVED']) : '-1');
        $this->copied = trim(isset($this->cfg['COPIED']) ? htmlspecialchars($this->cfg['COPIED']) : '-1');
        $this->restored = trim(isset($this->cfg['RESTORED']) ? htmlspecialchars($this->cfg['RESTORED']) : '-1');

        $this->laststart = trim(file_exists("/var/lib/snapraid/logs/$cfg_name-laststart") ? htmlspecialchars(file_get_contents("/var/lib/snapraid/logs/$cfg_name-laststart")) : "-");
        $this->lastfinish = trim(file_exists("/var/lib/snapraid/logs/$cfg_name-lastfinish") ? htmlspecialchars(file_get_contents("/var/lib/snapraid/logs/$cfg_name-lastfinish")) : "-");
        $this->lastsync = trim(file_exists("/boot/config/plugins/dwsnap/config/$cfg_name-lastsync") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/$cfg_name-lastsync")) : "-");
        $this->lastscrub = trim(file_exists("/boot/config/plugins/dwsnap/config/$cfg_name-lastscrub") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/$cfg_name-lastscrub")) : "-");
        $this->lastnodiff = trim(file_exists("/boot/config/plugins/dwsnap/config/$cfg_name-lastnodiff") ? htmlspecialchars(file_get_contents("/boot/config/plugins/dwsnap/config/$cfg_name-lastnodiff")) : "-");        
    
        preg_match_all('/(.*?parity) (\/mnt\/((addons|disks|remotes|rootshare)\/)?.*?)\//m', $this->snapcfg, $this->parity_disks, PREG_SET_ORDER);
        preg_match_all('/data (.*?) (\/mnt\/((addons|disks|remotes|rootshare)\/)?.*?)\//m', $this->snapcfg, $this->data_disks, PREG_SET_ORDER);

        preg_match_all('/(.*?parity) (.*?)$/m', $this->snapcfg, $this->parity_disks_raw, PREG_PATTERN_ORDER);
        preg_match_all('/data (.*?) (.*?)$/m', $this->snapcfg, $this->data_disks_raw, PREG_PATTERN_ORDER);
        preg_match_all('/content (.*?)$/m', $this->snapcfg, $this->content_files_raw, PREG_PATTERN_ORDER);
    }

    public function getFooterHTML($snap_tip_class) {
        try {
            $snap_footer_html = "";
            $snap_array_name = strtoupper($this->cfgname);
            
            $snap_config_name = $this->cfgname;
            $snap_running = htmlspecialchars(trim(shell_exec( "if pgrep -f \"^(/usr/bin/ionice -c [0-9] )?/usr/bin/snapraid -c /boot/config/plugins/dwsnap/config/$snap_config_name.conf\" >/dev/null 2>&1 || pgrep -f \"^(/bin/bash )?/usr/bin/snapraid-cron $snap_config_name\" >/dev/null 2>&1 || pgrep -f \"^(/bin/bash )?/usr/bin/snapraid-runner $snap_config_name\" >/dev/null 2>&1; then echo YES; else echo NO; fi" ) ?? "-"));
            
            if($snap_running === "YES") { return "<a href='/Settings/dwsnapOps?snapr=".$this->cfgname."' style='cursor:pointer;color:inherit;text-decoration:none;'><span class='$snap_tip_class' title='$snap_array_name: Array Operation in Progress'><i class='fa fa-cog fa-spin'></i></span></a>"; }
    
            $snap_ramdisk_util = htmlspecialchars(trim(shell_exec("df --output=pcent /var/lib/snapraid 2>/dev/null | tr -dc '0-9' 2>/dev/null") ?? "-"));
            if(!empty($this->parity_disks) && !empty($this->data_disks) && !empty($this->parity_disks_raw[2]) && !empty($this->data_disks_raw[2])) {
                if(count($this->parity_disks_raw[2]) === count($this->parity_disks) && count($this->data_disks_raw[2]) === count($this->data_disks)) {
                    $snap_all_disks_available = true;
                    foreach ($this->parity_disks as $snap_parity_disk){
                        $snap_disk_fs = htmlspecialchars(trim(shell_exec("cat /etc/mtab 2>/dev/null | grep '" . $snap_parity_disk[2] . " ' 2>/dev/null | awk '{print $3}' 2>/dev/null") ?? "-"));
                        if($snap_disk_fs == "-") { $snap_all_disks_available = false; }
                    }
                    foreach ($this->data_disks as $snap_data_disk){
                        $snap_disk_fs = htmlspecialchars(trim(shell_exec("cat /etc/mtab 2>/dev/null | grep '" . $snap_data_disk[2] . " ' 2>/dev/null | awk '{print $3}' 2>/dev/null") ?? "-"));
                        if($snap_disk_fs == "-") { $snap_all_disks_available = false; }
                    }
                    if($snap_all_disks_available) {
                        if($this->lastsync !== "-" && $this->lastscrub !== "-") {
                            $snap_lastsync_ago = dwsnap_time_ago($this->lastsync, $this->sync_expires);
                            $snap_lastscrub_ago = dwsnap_time_ago($this->lastscrub, $this->scrub_expires);
                            if(file_exists("/boot/config/plugins/dwsnap/config/".$this->cfgname."-syncneeded")) {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                }
                            }
                            elseif($this->lastnodiff !== "-") {
                                $t_now = time();
                                $t_lastsync = strtotime($this->lastsync);
                                $t_lastnodiff = strtotime($this->lastnodiff);
                                $t_lastsync_diff = abs($t_now - $t_lastsync);
                                $t_lastnodiff_diff = abs($t_now - $t_lastnodiff);
                                $snap_lastnodiff_ago = dwsnap_time_ago($this->lastnodiff, $this->sync_expires);
                                if($t_lastnodiff_diff < $t_lastsync_diff) {
                                    if (strpos($snap_lastnodiff_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                        if($snap_ramdisk_util > 90) {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                        } else {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-clock-o orange-text'></i></span>";
                                        }
                                    } else {
                                        if($snap_ramdisk_util > 90) {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                        } else {   
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-check green-text'></i></span>";
                                        }
                                    }
                                } else {
                                    if (strpos($snap_lastsync_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                        if($snap_ramdisk_util > 90) {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                        } else {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-clock-o orange-text'></i></span>";
                                        }
                                    } else {
                                        if($snap_ramdisk_util > 90) {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                        } else {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-check green-text'></i></span>";
                                        }
                                    }
                                }
                            } else {
                                if(file_exists("/boot/config/plugins/dwsnap/config/".$this->cfgname."-syncneeded")) {
                                    if($snap_ramdisk_util > 90) {
                                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                    } else {
                                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                    }
                                } else {
                                    if (strpos($snap_lastsync_ago, "orange-text") !== false || strpos($snap_lastscrub_ago, "orange-text") !== false) {
                                        if($snap_ramdisk_util > 90) {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                        } else {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-clock-o orange-text'></i></span>";
                                        }
                                    } else {
                                        if($snap_ramdisk_util > 90) {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                        } else {
                                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-check green-text'></i></span>";
                                        }
                                    }
                                }
                            }
                        } elseif ($this->lastsync !== "-" && $this->lastscrub == "-") {
                            $snap_lastsync_ago = dwsnap_time_ago($this->lastsync, $this->sync_expires);
                            if(file_exists("/boot/config/plugins/dwsnap/config/".$this->cfgname."-syncneeded")) {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                }
                            } else {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: ".strip_tags($snap_lastsync_ago)." / Last Scrub: Never'><i class='fa fa-clock-o orange-text'></i></span>";
                                }
                            }
                        } elseif ($this->lastsync == "-" && $this->lastscrub !== "-") {
                            $snap_lastscrub_ago = dwsnap_time_ago($this->lastscrub, $this->scrub_expires);
                            if(file_exists("/boot/config/plugins/dwsnap/config/".$this->cfgname."-syncneeded")) {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                }
                            } else {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: Never / Last Scrub: ".strip_tags($snap_lastscrub_ago)."'><i class='fa fa-clock-o orange-text'></i></span>";
                                }
                            }
                        } else {
                            if(file_exists("/boot/config/plugins/dwsnap/config/".$this->cfgname."-syncneeded")) {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Data Differences (Not in Sync) / Last Sync: Never / Last Scrub: Never'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Data Differences (Not in Sync) / Last Sync: Never / Last Scrub: Never'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                }
                            } else {
                                if($snap_ramdisk_util > 90) {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / RAM Disk > 90% / Last Sync: Never / Last Scrub: Never'><i class='fa fa-exclamation-triangle red-text'></i></span>";
                                } else {
                                    $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: All disks are online and mounted / Last Sync: Never / Last Scrub: Never'><i class='fa fa-clock-o orange-text'></i></span>";
                                }
                            }
                        }
                    } else {
                        if($snap_ramdisk_util > 90) {
                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: At least one disk is not online and/or mounted / RAM Disk > 90%'><i class='fa fa-times red-text'></i></span>";
                        } else {
                            $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: At least one disk is not online and/or mounted'><i class='fa fa-times red-text'></i></span>";
                        }
                    }
                } else {
                    if($snap_ramdisk_util > 90) {
                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: Malformed configuration - mount not inside /mnt or missing trailing slashes? / RAM Disk > 90%'><i class='fa fa-times red-text'></i></span>";
                    } else {
                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: Malformed configuration - mount not inside /mnt or missing trailing slashes?'><i class='fa fa-times red-text'></i></span>";
                    }
                }
            } else {
                if((!empty($this->parity_disks_raw[2]) && empty($this->parity_disks)) || (empty($this->parity_disks_raw[2]) && !empty($this->parity_disks))) {
                    if($snap_ramdisk_util > 90) {
                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: No parity disks parseable - not inside /mnt or malformed declaration? / RAM Disk > 90%'><i class='fa fa-times red-text'></i></span>";
                    } else {
                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: No parity disks parseable - not inside /mnt or malformed declaration?'><i class='fa fa-times red-text'></i></span>";
                    }
                } elseif((!empty($this->data_disks_raw[2]) && empty($this->data_disks)) || (empty($this->data_disks_raw[2]) && !empty($this->data_disks))) {
                    if($snap_ramdisk_util > 90) {
                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: No data disks parseable - not inside /mnt or missing trailing slashes? / RAM Disk > 90%'><i class='fa fa-times red-text'></i></span>";
                    } else {
                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: No data disks parseable - not inside /mnt or missing trailing slashes?'><i class='fa fa-times red-text'></i></span>";
                    }            
                } else {
                    if($snap_ramdisk_util > 90) {
                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: No parity and/or data disks are configured / RAM Disk > 90%'><i class='fa fa-times red-text'></i></span>";
                    } else {
                        $snap_footer_html = "<span class='$snap_tip_class' title='$snap_array_name: No parity and/or data disks are configured'><i class='fa fa-times red-text'></i></span>";
                    }
                }
            }
            $snap_footer_html = "<a href='/Settings/dwsnapOps?snapr=".$this->cfgname."' style='cursor:pointer;color:inherit;text-decoration:none;'>" . $snap_footer_html . "</a>";
            return $snap_footer_html;
        } catch (Throwable $e) { // For PHP 7
            return "";
        } catch (Exception $e) { // For PHP 5
            return "";
        }
    }
}

$dwsnap_selected_array = "primary";
$dwsnap_selected_array_missing = "no";

if(!empty($_GET['snapr']) && $_GET['snapr'] !== "primary") {
    if(file_exists("/boot/config/plugins/dwsnap/config/".$_GET['snapr'].".cfg") && file_exists("/boot/config/plugins/dwsnap/config/".$_GET['snapr'].".conf")) {
        $dwsnap_selected_array = $_GET['snapr'];
    } else {
        $dwsnap_selected_array_missing = "yes";
    }
}

$dwsnap_cfg = parse_ini_file("/boot/config/plugins/dwsnap/dwsnap.cfg");
$dwsnap_active_cfg = new SnapraidArrayConfiguration($dwsnap_selected_array);

$dwsnap_footer = trim(isset($dwsnap_cfg['FOOTER']) ? htmlspecialchars($dwsnap_cfg['FOOTER']) : 'disable');
$dwsnap_dashboards = trim(isset($dwsnap_cfg['DASHBOARDS']) ? htmlspecialchars($dwsnap_cfg['DASHBOARDS']) : 'disable');
$dwsnap_screenimg = trim(isset($dwsnap_cfg['SCREENIMG']) ? htmlspecialchars($dwsnap_cfg['SCREENIMG']) : 'enable');
$dwsnap_stoparray = trim(isset($dwsnap_cfg['STOPARRAY']) ? htmlspecialchars($dwsnap_cfg['STOPARRAY']) : 'enable');
$dwsnap_killtime = trim(isset($dwsnap_cfg['KILLTIME']) ? htmlspecialchars($dwsnap_cfg['KILLTIME']) : '30');

$dwsnap_backend = htmlspecialchars(trim(shell_exec("find /var/log/packages/ -type f -iname 'snapraid-*' -printf '%f\n' 2>/dev/null") ?? "n/a"));

?>

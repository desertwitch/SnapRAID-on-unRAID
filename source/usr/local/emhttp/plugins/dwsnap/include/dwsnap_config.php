<?
$dwsnap_cfg  = parse_ini_file("/boot/config/plugins/dwsnap/dwsnap.cfg");
$dwsnap_prio = trim(isset($dwsnap_cfg['PRIO']) ? htmlspecialchars($dwsnap_cfg['PRIO']) : 'disable');
$dwsnap_cron = trim(isset($dwsnap_cfg['CRON']) ? htmlspecialchars($dwsnap_cfg['CRON']) : 'disable');
$dwsnap_cronhour = trim(isset($dwsnap_cfg['CRONHOUR']) ? htmlspecialchars($dwsnap_cfg['CRONHOUR']) : '1');
$dwsnap_crondow = trim(isset($dwsnap_cfg['CRONDOW']) ? htmlspecialchars($dwsnap_cfg['CRONDOW']) : '0');
$dwsnap_crondom = trim(isset($dwsnap_cfg['CRONDOM']) ? htmlspecialchars($dwsnap_cfg['CRONDOM']) : '1');
$dwsnap_notify = trim(isset($dwsnap_cfg['NOTIFY']) ? htmlspecialchars($dwsnap_cfg['NOTIFY']) : 'enable');
$dwsnap_touch = trim(isset($dwsnap_cfg['TOUCH']) ? htmlspecialchars($dwsnap_cfg['TOUCH']) : 'enable');
$dwsnap_diff = trim(isset($dwsnap_cfg['DIFF']) ? htmlspecialchars($dwsnap_cfg['DIFF']) : 'enable');
$dwsnap_sync = trim(isset($dwsnap_cfg['SYNC']) ? htmlspecialchars($dwsnap_cfg['SYNC']) : 'enable');
$dwsnap_prehash = trim(isset($dwsnap_cfg['PREHASH']) ? htmlspecialchars($dwsnap_cfg['PREHASH']) : 'enable');
$dwsnap_scrub = trim(isset($dwsnap_cfg['SCRUB']) ? htmlspecialchars($dwsnap_cfg['SCRUB']) : 'enable');
$dwsnap_added = trim(isset($dwsnap_cfg['ADDED']) ? htmlspecialchars($dwsnap_cfg['ADDED']) : '0');
$dwsnap_deleted = trim(isset($dwsnap_cfg['DELETED']) ? htmlspecialchars($dwsnap_cfg['DELETED']) : '0');
$dwsnap_updated = trim(isset($dwsnap_cfg['UPDATED']) ? htmlspecialchars($dwsnap_cfg['UPDATED']) : '0');
$dwsnap_moved = trim(isset($dwsnap_cfg['MOVED']) ? htmlspecialchars($dwsnap_cfg['MOVED']) : '0');
$dwsnap_copied = trim(isset($dwsnap_cfg['COPIED']) ? htmlspecialchars($dwsnap_cfg['COPIED']) : '0');
$dwsnap_restored = trim(isset($dwsnap_cfg['RESTORED']) ? htmlspecialchars($dwsnap_cfg['RESTORED']) : '0');

$snapraid_backend = trim(shell_exec("find /var/log/packages/ -type f -iname 'snapraid-*' -printf '%f\n' 2> /dev/null"));
$snapraid_laststart = (file_exists("/var/log/snapraid/laststart") ? (file_get_contents("/var/log/snapraid/laststart") ?? "n/a") : "n/a");
$snapraid_lastfinish = (file_exists("/var/log/snapraid/lastfinish") ? (file_get_contents("/var/log/snapraid/lastfinish") ?? "n/a") : "n/a");

function snap_hour_options($time){
    $options = '';
        for($i = 0; $i <= 23; $i++){
            $options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $options .= ' selected';

            $options .= '>'.$i.'</option>';
        }
    return $options;
}

function snap_dom_options($time){
    $options = '';
        for($i = 1; $i <= 31; $i++){
            $options .= '<option value="'.$i.'"';
            if(intval($time) === $i)
                $options .= ' selected';

            $options .= '>'.$i.'</option>';
        }
    return $options;
}
?>

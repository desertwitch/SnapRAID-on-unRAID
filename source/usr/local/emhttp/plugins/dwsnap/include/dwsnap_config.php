<?
$dwsnap_cfg  = parse_ini_file("/boot/config/plugins/dwsnap/dwsnap.cfg");
$dwsnap_prio = trim(isset($dwsnap_cfg['PRIO']) ? htmlspecialchars($dwsnap_cfg['PRIO']) : 'disable');

$snapraid_backend = trim(shell_exec("find /var/log/packages/ -type f -iname 'snapraid-*' -printf '%f\n' 2> /dev/null"));
$snapraid_laststart = (file_exists("/var/log/snapraid/laststart") ? (file_get_contents("/var/log/snapraid/laststart") ?? "n/a") : "n/a");
$snapraid_lastfinish = (file_exists("/var/log/snapraid/lastfinish") ? (file_get_contents("/var/log/snapraid/lastfinish") ?? "n/a") : "n/a");
?>

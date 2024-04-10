<?
$snap_running = trim(htmlspecialchars(shell_exec( "if pgrep -x snapraid >/dev/null 2>&1 || pgrep -x snapraid-cron >/dev/null 2>&1 || pgrep -x snapraid-runner >/dev/null 2>&1; then echo YES; else echo NO; fi" )));
echo("RUNNING:".$snap_running);
?>

<?
$snapraid_running = trim(htmlspecialchars(shell_exec( "if pgrep -x snapraid >/dev/null 2>&1; then echo YES; else echo NO; fi" )));
echo("RUNNING:".$snapraid_running);
?>
<?
$snapraid_running = (intval(trim(htmlspecialchars(shell_exec( "if pgrep -x snaprunner >/dev/null 2>&1; then echo 1; else echo 0; fi" )))) === 1 );
echo("RUNNING:".$snapraid_running);
?>

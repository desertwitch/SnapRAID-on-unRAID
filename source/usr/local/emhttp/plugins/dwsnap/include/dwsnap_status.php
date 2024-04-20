<?
/* Copyright Derek Macias (parts of code from NUT package)
 * Copyright macester (parts of code from NUT package)
 * Copyright gfjardim (parts of code from NUT package)
 * Copyright SimonF (parts of code from NUT package)
 * Copyright desertwitch
 *
 * Copyright Dan Landon
 * Copyright Bergware International
 * Copyright Lime Technology
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 */
$snap_running = trim(htmlspecialchars(shell_exec( "if pgrep -x snapraid >/dev/null 2>&1 || pgrep -x snapraid-cron >/dev/null 2>&1 || pgrep -x snapraid-runner >/dev/null 2>&1; then echo YES; else echo NO; fi" ) ?? "-"));
echo("RUNNING:".$snap_running);
?>

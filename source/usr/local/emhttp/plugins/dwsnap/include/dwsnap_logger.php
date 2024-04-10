<?
if(file_exists("/var/log/snapraid/snaplog")) {
    $snap_log = file_get_contents("/var/log/snapraid/snaplog");
    if(!empty($snap_log)) {
        echo("<pre class='snaplog'>".$snap_log."</pre>");
    } else {
        echo("<pre class='snaplog'></pre>");
    }
} else {
    echo("<pre class='snaplog'></pre>");
}
?>

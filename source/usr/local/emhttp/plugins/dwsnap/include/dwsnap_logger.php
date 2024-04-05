<?php
if(file_exists("/var/log/snapraid/snaplog")) {
    $snaplog = file_get_contents("/var/log/snapraid/snaplog");
    if(!empty($snaplog)) {
        echo("<pre class='snaplog'>".$snaplog."</pre>");
    } else {
        echo("<pre class='snaplog'></pre>");
    }
} else {
    echo("<pre class='snaplog'></pre>");
}
?>

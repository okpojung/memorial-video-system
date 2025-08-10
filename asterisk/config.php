<?php
    date_default_timezone_set('Asia/Seoul');
    $now = date('Y-m-d H:i:s');

    //*************************************************
    //  MVS API
    //*************************************************
    define("MVS_GET_USER_INFORM", "/var/lib/asterisk/agi-bin/mvs/log/MVS_GET_USER_INFORM.log");
    define("MVS_GET_VIDEO_INFORM", "/var/lib/asterisk/agi-bin/mvs/log/MVS_GET_VIDEO_INFORM.log");

    define("MVS_GET_USER_INFORM_SQL", "/var/lib/asterisk/agi-bin/mvs/log/MVS_GET_USER_INFORM_SQL.log");

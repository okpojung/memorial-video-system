#!/usr/bin/php -q
<?php

$file_server_path = realpath(__FILE__);
$server_path = str_replace(basename(__FILE__), "", $file_server_path);
$agi_bin_path = str_replace("/mvs", "", $server_path);

include_once($server_path . "/dbconfig.php");
include_once($server_path . "/db_func.php");
include_once($server_path . "/func.php");
include_once($server_path . "/config.php");

include_once($server_path . "/phpagi.php");
include_once($server_path . "/vendor/autoload.php");

$log_tag = '';
$log_file = MVS_GET_VIDEO_INFORM;
$log_file_sql = MVS_GET_USER_INFORM_SQL;


global $db_mvs_users, $db_mvs_customer_video;

try {
    $agi = new AGI();
    $result_code = "9999";

    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if (mysqli_connect_error()) {
        $result_code = "8888";
        echo $mysqli->connect_error;
        throw new Exception("db connect error");
    }

    //$value['code'] = '0000';
    //$result_code = "1000";

    // throw new Exception("완료");

    baro_write_log('-------- AGI GET_VIDEO_IN_FORM VARIABLE INFO -----', $log_file, array('LOG START TEST'=>'-----------------'));

    $play = $agi->request['agi_arg_1'];
    $caller = $agi->request['agi_callerid'];

    baro_write_log('-------- AGI GET_VIDEO_IN_FORM VARIABLE INFO -----', $log_file, array('$caller'=>$caller, '$play' =>$play));


//    $play = '1';
//    $caller = '01037635613';
    //*************************************************
    //  AGI 결과코드
    //
    //
    // $RESULT = "1000"     |  'OK
    // $RESULT = "9999"     |  'undefined error
    //*************************************************

    $users_select_where = array('tel' => $caller);

    $result_code = "1000";
    $url = 'http://192.168.94.101:8100/api/video';
    $fields = array(
        'tel' => $caller,
        'play' => $play,
    );
    $url = $url . '?' . http_build_query($fields, '', '&');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    // $agi->set_variable("COUNT", $count);
    $result_code = "1000";
    throw new Exception("완료");

} catch (Exception $e) {
    $agi->set_variable("RESULT", $result_code);
}
?>

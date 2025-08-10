#!/usr/bin/php -q
<?php
//ini_set('error_reporting', 0);

$file_server_path = realpath(__FILE__);
$server_path = str_replace(basename(__FILE__), "", $file_server_path);
$agi_bin_path   = str_replace("/mvs","",$server_path);

include_once($server_path . "/dbconfig.php");
include_once($server_path . "/db_func.php");
include_once($server_path . "/func.php");
include_once($server_path . "/config.php");

include_once($server_path . "/phpagi.php");
include_once($server_path . "/vendor/autoload.php");

$log_tag        = '';
$log_file  = MVS_GET_USER_INFORM;
$log_file_sql   = MVS_GET_USER_INFORM_SQL;


global $db_mvs_users, $db_mvs_customer_video;

try {
    $agi = new AGI();
    $result_code = "9999";

    $mysqli = new mysqli($dbhost, $dbuser, $dbpass,$dbname);

    if (mysqli_connect_error()) {
        $result_code = "8888";
        echo $mysqli->connect_error;
        throw new Exception("db connect error");
    }

    //$value['code'] = '0000';
    //$result_code = "1000";

    // throw new Exception("완료");


    $m_TdnNumber       = $agi->get_variable("m_TdnNumber");
    $m_mvs_070         = $m_TdnNumber['data'];

    $caller 		  = $agi->request['agi_callerid'];

    //*************************************************
    //  AGI 결과코드
    //
    //
    // $RESULT = "1000"     |  'OK
    // $RESULT = "1001"     |  'OK(인증필요)
    // $RESULT = "1005"     |  '등록된 영상 정보 없음
    // $RESULT = "1010"     |  '등록된 사용자 아님
    // $RESULT = "1012"     |  '해당 단말은 고장 수리중
    // $RESULT = "1014"     |  '070 번호는 서비스 준비중
    // $RESULT = "8999"     |  'ARS번호에 대한 정보 없음
    // $RESULT = "9999"     |  'undefined error
    //*************************************************


    baro_write_log('-------- AGI GET VARIABLE INFO -----', $log_file, array('mvs_070'=>$m_mvs_070, 'auth_tel' =>$caller));

    $users_select_where = array('tel'=>$caller);

    $count = 0;
    $db_mvs_users_row        = get_select($db_mvs_users,'',$users_select_where);
    $db_mvs_users            = $db_mvs_users_row->fetch_array(MYSQLI_ASSOC);
    $id                      = $db_mvs_users['id'];
    $result_code = "1000";

    if($db_mvs_users_row->num_rows < 1 )
    {
        $result_code = "1010";
        throw new Exception("등록된 사용자 아님");
    }

    $customer_video_select_where = array('customer_id' =>$id);
    $db_mvs_customer_video_row        = get_select($db_mvs_customer_video,'',$customer_video_select_where);

    if($db_mvs_customer_video_row->num_rows < 1 )
    {
        $result_code = "1005";
        throw new Exception("등록된 영상 없음");
    }


    if($db_mvs_customer_video_row->num_rows == 1 )
    {
        $url = 'http://192.168.94.101:8100/api/video';
        $fields = array(
            'tel' => $caller,
//            'call' => $m_mvs_070,
        );
        $url = $url.'?'.http_build_query($fields, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
    }
    else {
        $result_code = "1016";
        $count = $db_mvs_customer_video_row->num_rows;
        $url = 'http://192.168.94.101:8100/api/video';
        $fields = array(
            'tel' => $caller,
            'play' => '',
        );
        $url = $url.'?'.http_build_query($fields, '', '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        // $agi->set_variable("COUNT", $count);
        throw new Exception("등록된 영상 여러개");
    }
    $value['code'] = '0000';
    $result_code = "1000";
    throw new Exception("완료");

} catch (Exception $e) {

    $agi->set_variable("RESULT", $result_code);
    $agi->set_variable("COUNT", $count);
//    baro_write_log('----- AGI EXCEPTION -----', $log_file, array($e->getMessage(), "AGI_RETURN_CODE(RESULT): " . $result_code));
}
?>

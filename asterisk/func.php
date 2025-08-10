<?php
    function baro_write_log2($mode, $file, $arr1, $arr2=array()) {
        echo getcwd();
        $new_arr = array();
        if(count($arr2) > 0 ) {
            $new_arr = array_merge($arr1, $arr2);
        }else {
            $new_arr = $arr1;
        }

        $new_arr['write_date'] = date('Y-m-d H:i:s');
        $fp = fopen($file, "a+");
        ob_start();
        echo $new_arr['write_date'].'
    ';
        echo $mode.'
    ';
        print_r($new_arr);
        $msg = ob_get_contents();
        ob_end_clean();
        fwrite($fp, $msg);
        fclose($fp);
    }

    //암호화
    function encrypt($str, $key)
    {

        $key = hex2binold($key);

        //암호화 모듈을 오픈합니다
        $td = mcrypt_module_open("rijndael-128", "", "cbc", "fedcba9876543210");

        //암호화 처리를 초기화합니다.
        mcrypt_generic_init($td, $key, CIPHER_IV);

        //데이터를 암호화합니다
        $encrypted = mcrypt_generic($td, $str);

        //암호화 장치를 종료합니다.
        mcrypt_generic_deinit($td);
        //모델을 닫습니다.
        mcrypt_module_close($td);
        return bin2hex($encrypted);

    }

    //복호화
    function decrypt($code, $key)
    {

        $key = hex2binold($key);
        $code = hex2binold($code);

        $td = mcrypt_module_open("rijndael-128", "", "cbc", "fedcba9876543210");

        mcrypt_generic_init($td, $key, CIPHER_IV);

        $decrypted = mdecrypt_generic($td, $code);

        mcrypt_generic_deinit($td);

        mcrypt_module_close($td);

        return utf8_encode(trim($decrypted));

    }

    function hex2binold($hexdata)
    {
        $bindata = "";
        for ($i = 0; $i < strlen($hexdata); $i += 2) {
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    }

    function tnt_send($dest_phone,$msg_body){

            if(mb_strlen($msg_body, 'euc-kr') >= 90){
                $msg_type = 'LMS';
                $send_opt = array(
                    'dest_phone' => $dest_phone
                , 'send_phone' => '16889915'
                , 'msg_body' => $msg_body
                , 'subject' => '바로서비스 문자발송'
    //            , 'send_time' => date("Y-m-d H:i:s")
                );
            } else {
                $msg_type = 'SMS';
                $send_opt = array(
                    'dest_phone' => $dest_phone
                , 'send_phone' => '16889915'
                , 'msg_body' => $msg_body
    //            , 'send_time' => date("Y-m-d H:i:s")
                );
            }


            $res = curl_tnt_send($send_opt,$msg_type);
            $object = json_decode($res, true);

            return $object;
    }

    function curl_tnt_send($send_opt,$msg_type) {
        $post_field_string = http_build_query($send_opt, '', '&');

        $ch = curl_init();

        $url = "https://api2.msgagent.com/api/webshot/send/general/".$msg_type."/barosvc";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_field_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;

    }

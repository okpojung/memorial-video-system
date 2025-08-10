#!/usr/bin/php -q
<?php
        require 'phpagi.php';

        $agi = new AGI();

        $callee = $agi->request['agi_dnid'];

		$agi->say_digits(3165);

        switch($callee){
                case '07076833165' : $RESULT = 3100; break;     // succ
                case '07076833166' : $RESULT = 3100; break;     // succ
                default            : $RESULT = 3101; break;     // fail
        }

        $agi->set_variable("RESULT", $RESULT);
?>

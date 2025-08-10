<?php

namespace Inok\phpagi;

class AGI_Others
{
  const AST_CONFIG_DIR = '/etc/asterisk/';
  const AST_SPOOL_DIR = '/var/spool/asterisk/';
  const AST_TMP_DIR = self::AST_SPOOL_DIR . '/tmp/';
  const DEFAULT_PHPAGI_CONFIG = self::AST_CONFIG_DIR . '/phpagi.conf';

  const AST_DIGIT_ANY = '0123456789#*';

  const AGIRES_OK = 200;

  const AST_STATE_DOWN = 0;
  const AST_STATE_RESERVED = 1;
  const AST_STATE_OFFHOOK = 2;
  const AST_STATE_DIALING = 3;
  const AST_STATE_RING = 4;
  const AST_STATE_RINGING = 5;
  const AST_STATE_UP = 6;
  const AST_STATE_BUSY = 7;
  const AST_STATE_DIALING_OFFHOOK = 8;
  const AST_STATE_PRERING = 9;

  const AUDIO_FILENO = 3; // STDERR_FILENO + 1

  public static $phpagi_error_handler_email = null;

  /**
   * error handler for phpagi.
   *
   * @param integer $level PHP error level
   * @param string $message error message
   * @param string $file path to file
   * @param integer $line line number of error
   * @param array $context variables in the current scope
   */
  public static function phpagi_error_handler(int $level, string $message, string $file, int $line, array $context) {
    if (ini_get('error_reporting') == 0) {
      return; // this happens with an @
    }

    @syslog(LOG_WARNING, $file . '[' . $line . ']: ' . $message);

    if (function_exists('mail') && !is_null(self::$phpagi_error_handler_email)) { // generate email debugging information
      // decode error level
      switch ($level) {
        case E_WARNING:
        case E_USER_WARNING:
          $level = "Warning";
          break;
        case E_NOTICE:
        case E_USER_NOTICE:
          $level = "Notice";
          break;
        case E_USER_ERROR:
          $level = "Error";
          break;
      }

      // build message
      $basefile = basename($file);
      $subject = "$basefile/$line/$level: $message";
      $message = "$level: $message in $file on line $line\n\n";

      // figure out who we are
      if (function_exists('socket_create')) {
        $addr = null;
        $port = 80;
        $socket = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        @socket_connect($socket, '64.0.0.0', $port);
        @socket_getsockname($socket, $addr, $port);
        @socket_close($socket);
        $message .= "\n\nIP Address: $addr\n";
      }

      // include variables
      $message .= "\n\nContext:\n" . print_r($context, true);
      $message .= "\n\nGLOBALS:\n" . print_r($GLOBALS, true);
      $message .= "\n\nBacktrace:\n" . print_r(debug_backtrace(), true);

      // include code fragment
      if (file_exists($file)) {
        $message .= "\n\n$file:\n";
        $code = @file($file);
        for ($i = max(0, $line - 10); $i < min($line + 10, count($code)); $i++) {
          $message .= ($i + 1) . "\t$code[$i]";
        }
      }

      // make sure message is fully readable (convert unprintable chars to hex representation)
      $ret = '';
      for ($i = 0; $i < strlen($message); $i++) {
        $c = ord($message[$i]);
        if ($c == 10 || $c == 13 || $c == 9) {
          $ret .= $message[$i];
        } elseif ($c < 16) {
          $ret .= '\x0' . dechex($c);
        } elseif ($c < 32 || $c > 127) {
          $ret .= '\x' . dechex($c);
        } else {
          $ret .= $message[$i];
        }
      }
      $message = $ret;

      // send the mail if less than 5 errors
      static $mailcount = 0;
      if ($mailcount < 5) {
        @mail(self::$phpagi_error_handler_email, $subject, $message);
      }
      $mailcount++;
    }
  }
}

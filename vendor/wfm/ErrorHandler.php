<?php

namespace wfm;

class ErrorHandler
{
  public function __construct()
  {
    if (DEBUG) {
      error_reporting(-1);
    } else {
      error_reporting(0);
    }
    set_exception_handler([$this, 'exceptionHandler']);
    set_error_handler([$this, 'errorHandler']);

    ob_start();
    register_shutdown_function([$this, 'fatalErrorHandler']);
  }


  public function errorHandler($errno, $errstr, $errfile, $errline)
  {
    $this->logErrors($errstr, $errfile, $errline);
    $this->displayError($errno, $errstr, $errfile, $errline);
  }

  public function fatalErrorHandler()
  {
    $error = error_get_last();
    if (!empty($error) && $error['type'] & (E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR)) {
      $this->logErrors($error['message'], $error['file'], $error['line']);
      ob_end_clean();
      $this->displayError($error['type'], $error['message'], $error['file'], $error['line']);
    } else {
      ob_end_flush();
    }
  }

  public function exceptionHandler(\Throwable $e)
  {
    $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
    $this->displayError('Исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
  }

  public function logErrors($message = '', $file = '', $line = '')
  {
    file_put_contents(
      LOGS . '/errors.log',
      '[' . date('Y-m-d H:i:s') . " ] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n", FILE_APPEND);
//    error_log("[" . date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} |  Строка: {$line}");
  }

  public function displayError($errno, $errstr, $errfile, $errline, $response = 500)
  {
    if ($response == 0) {
      $response = 404;
    }
    http_response_code($response);
    if ($response == 404 && !DEBUG) {
      require WWW . '/errors/404.php';
      exit();
    }
    if (DEBUG) {
      require WWW . '/errors/development.php';
    } else {
      require WWW . '/errors/production.php';
    }
    exit();
  }
}

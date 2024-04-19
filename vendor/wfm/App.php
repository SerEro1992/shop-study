<?php

namespace wfm;

class App
{
  public static $app;

  /**
   * @throws \Exception
   */
  public function __construct()
  {
    $query = trim(urldecode($_SERVER['QUERY_STRING']), '/');
    new ErrorHandler();
    session_start();
    self::$app = Registry::getInstance();
    $this->getParams();
    Router::dispatch($query);
  }

  protected function getParams()
  {
    $params = require CONFIG . '/params.php';
    if (!empty($params)) {
      foreach ($params as $key => $value) {
        self::$app->setProperty($key, $value);
      }
    }
  }


}
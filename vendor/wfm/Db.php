<?php

namespace wfm;

use RedBeanPHP\R;

class Db
{
  use TSingleton;

  private function __construct()
  {
    $db = require CONFIG . '/config_db.php';
    R::setup($db['dsn'], $db['username'], $db['password']);
    if (!R::testConnection()) {
      throw new \Exception('Нет соединения с БД', 500);
    }
    R::freeze(true);
    if (DEBUG) {
      R::debug(true, 3);
    }

    R::ext('xdispense', function ($type) {
      return R::getRedBean()->dispense($type);
    });
  }
}
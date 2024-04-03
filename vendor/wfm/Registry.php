<?php

namespace wfm;

class Registry
{
  use TSingleton;
  protected static array $properties=[];

  public function setProperty($name, $value)
  {
    static::$properties[$name]= $value;
  }

  public function getProperty($name)
  {
    return static::$properties[$name] ?? null;
  }

  public function getProperties():array
  {
    return self::$properties;
  }


}
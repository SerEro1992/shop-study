<?php

namespace wfm;

use RedBeanPHP\R;
use Valitron\Validator;

abstract class Model
{
  public array $attributes = [];
  public array $errors = [];
  public array $rules = [];
  public array $labels = [];

  public function __construct()
  {
    Db::getInstance();
  }

  public function load($data)
  {
    foreach ($this->attributes as $name => $value) {
      if (isset($data[$name])) {
        $this->attributes[$name] = $data[$name];
      }
    }
  }

  public function validate($data): bool
  {
    #переопределяем файлы перевода для валидатора
    Validator::langDir(APP . '/languages/validator/lang');
    Validator::lang(App::$app->getProperty('language')['code']);

    #создаем валидатор
    $validator = new Validator($data);

    #применяем правила
    $validator->rules($this->rules);

    #применяем метки
    $validator->labels($this->getLabels());

    if ($validator->validate()) {
      return true;
    } else {
      #получаем ошибки валидации
      $this->errors = $validator->errors();
      return false;
    }

  }

  public function getErrors()
  {
    $errors = '<ul>';
    foreach ($this->errors as $error) {
      foreach ($error as $item) {
        $errors .= "<li>{$item}</li>";
      }
    }
    $errors .= '</ul>';
    $_SESSION['errors'] = $errors;
  }

  public function getLabels(): array
  {
    $labels = [];
    foreach ($this->labels as $key => $value) {
      $labels[$key] = ___($value);
    }
    return $labels;
  }

  public function save($table): int|string
  {
    $tbl = R::dispense($table);
    foreach ($this->attributes as $name => $value) {
      if (!empty($value)) {
        $tbl->{$name} = $value;
      }
    }
    return R::store($tbl);
  }


}
<?php

namespace app\widgets\language;

use RedBeanPHP\R;
use wfm\App;

class Language
{
  protected $tpl;
  protected $languages;
  protected $language;

  public function __construct()
  {
    $this->tpl = __DIR__ . '/lang_tpl.php';
    $this->run();
  }

   protected function run()
  {
    $this->languages = App::$app->getProperty('languages');
    $this->language = App::$app->getProperty('language');

    echo $this->getHtml();
  }

  public static function getLanguages(): array
  {
    # возвращает из базы все языки в массиве
    return R::getAssoc("SELECT code, title, base , id FROM language ORDER BY base DESC");

  }
  public static function getLanguage($languages)
  {
    #получаем текущий язык из маршрута
    $lang = App::$app->getProperty('lang');

    # проверяем существует ли такой язык
    if ($lang && array_key_exists($lang, $languages)) {
      $key = $lang;
    } elseif (!$lang) {
      $key = key($languages);
    } else {
      $lang = h($lang);
      throw  new \Exception("Язык {$lang} не определен", 404);
    }

    # получаем информацию о языке
    $lang_info = $languages[$key];
    $lang_info['code'] = $key;
    return $lang_info;

  }

  protected function getHtml(): string
  {
    ob_start();
    require_once $this->tpl;
    return ob_get_clean();
  }

}
<?php

namespace app\controllers;

use app\models\Cart;
use wfm\App;

class LanguageController extends AppController
{
  public function changeAction()
  {
    $lang = get('lang', "s");
    if ($lang) {
      if (array_key_exists($lang, App::$app->getProperty('languages'))) {
        # отрезаем базовый url
        $url = trim(str_replace(PATH, '', $_SERVER['HTTP_REFERER']), '/');

        # развбиваем url на части 1-я часть возможный бывший язык
        $url_parts = explode('/', $url, 2);

        # ищем первую часть (бывший язык) в массиве языков
        if (array_key_exists($url_parts[0], App::$app->getProperty('languages'))) {
          # присваиваем первую часть (бывший язык) новому языку если он не базовый
          if ($lang != App::$app->getProperty('language')['code']) {
            $url_parts[0] = $lang;
          } else {
            # если базовый то удалим из url
            array_shift($url_parts);
          }
        } else {
          # присваиваем первой части новый язык, если он не базовый
          if ($lang != App::$app->getProperty('language')['code']) {
            array_unshift($url_parts, $lang);
          }
        }

        Cart::translate_cart(App::$app->getProperty('languages')[$lang]);

        $url = PATH . '/' . implode('/', $url_parts);
        redirect($url);
      }
    }
    redirect();
  }
}
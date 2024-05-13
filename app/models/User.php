<?php

namespace app\models;

use app\models\AppModel;

use RedBeanPHP\R;

class User extends AppModel
{
  #безопасные данные (принимает только необходимые поля)
  public array $attributes = [
    'email' => '',
    'password' => '',
    'name' => '',
    'address' => '',
  ];

  #правила валидации
  public array $rules = [
    'required' => [
      'email',
      'password',
      'name',
      'address'
    ],
    'email' => [
      'email',

    ],
    'lengthMin' => [
      ['password', 6]]
  ];

  #метки для валидатки
  public array $labels = [
    'email' => 'tpl_signup_email_input',
    'password' => 'tpl_signup_password_input',
    'name' => 'tpl_signup_name_input',
    'address' => 'tpl_signup_address_input',
  ];

  public static function checkAuth(): bool
  {
    return isset($_SESSION['user']);
  }

  public function checkUnique($text_error = ''): bool
  {
    $user = R::findOne('user', 'email = ?', [$this->attributes['email']]);
    if ($user) {
      $this->errors['unique'][] = $text_error ?: ___('user_signup_error_email_unique');
      return false;
    }
    return true;
  }

  public function login($is_admin = false): bool
  {
    $email = post('email');
    $password = post('password');
    if ($email && $password) {
      if ($is_admin) {
        $user = R::findOne('user', 'email = ? AND role = ?', [$email, 'admin']);
      } else {
        $user = R::findOne('user', 'email = ?', [$email]);
      }

      if ($user) {
        if (password_verify($password, $user->password)) {
          foreach ($user as $key => $value) {
            if ($key != 'password') {
              $_SESSION['user'][$key] = $value;
            }
          }
          return true;
        }
      }
    }
    return false;
  }


}
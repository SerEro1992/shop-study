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
      ['password', 6]],
    'optional' => [
      'email',
      'password',
    ]
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

  public function getCountOrders($user_id): int
  {
    return R::count('orders', 'user_id = ?', [$user_id]);
  }

  public function getUserOrders($start, $perpage, $user_id): array
  {
    return R::getAll('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC LIMIT ?, ?', [$user_id, $start, $perpage]);
  }

  public function getUserOrder($id): array
  {
    return R::getAll('SELECT o.*, op.* FROM orders o JOIN order_product op ON o.id = op.order_id WHERE o.id = ?', [$id]);
  }

  public function getCountFiles(): int
  {
    return R::count('order_download', 'user_id = ? AND status = 1', [$_SESSION['user']['id']]);
  }

  public function getUserFiles($start, $perpage, $lang): array
  {
    return R::getAll("SELECT od.*, d.* , dd.* FROM order_download od JOIN download d ON d.id=od.download_id JOIN download_description dd ON d.id = dd.download_id WHERE od.user_id = ? AND od.status=1 AND dd.language_id = ? LIMIT $start, $perpage", [$_SESSION['user']['id'], $lang['id']]);
  }

  public function getUserFile($id, $lang): array
  {
    return R::getRow("SELECT od.*, d.* , dd.* FROM order_download od JOIN download d ON d.id=od.download_id JOIN download_description dd ON d.id = dd.download_id WHERE od.user_id = ? AND od.status=1 AND od.download_id = ? AND dd.language_id = ?", [$_SESSION['user']['id'], $id, $lang['id']]);
  }


}
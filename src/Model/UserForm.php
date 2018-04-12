<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 11.04.18
 * Time: 10:37
 */

namespace App\Model;

class UserForm
{
  const ATTR_EMAIL = 'email';
  const ATTR_ACCESS_LEVEL = 'accessLevel';
  const ACCESS_LVL_LIST = [
    User::ACCESS_LVL_ADMIN => 'Admin'
  ];
}
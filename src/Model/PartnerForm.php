<?php
/**
 * Created by PhpStorm.
 * User: hendrik
 * Date: 06.04.18
 * Time: 09:44
 */

namespace App\Model;

class PartnerForm
{
  const ATTR_NAME = 'name';
  const ATTR_IDENTIFIER = 'identifier';
  const ATTR_STATUS = 'status';
  const ATTR_COMMISSION = 'commission';
  const ATTR_FEATURED = 'featured';
  const ATTR_EMAIL = 'email';
  const ATTR_API_ENABLED = 'apiEnabled';
  const ATTR_API_LIVE_URL = 'apiLiveUrl';
  const ATTR_API_TEST_URL = 'apiTestUrl';
  const ATTR_REMOTE_USERNAME = 'remoteUsername';
  const ATTR_REMOTE_PASSWORD = 'remotePassword';
  const ATTR_LOCAL_USERNAME = 'localUsername';
  const ATTR_LOCAL_PASSWORD = 'localPassword';
  const ATTR_AUTHORIZATION_TYPE = 'authorizationType';
  const ATTR_LOGO_URL = 'logoUrl';
  const ATTR_WEBSITE = 'website';

  const AUTHORIZATION_BASIC = 'Basic';
  const AUTHORIZATION_LIST = [
    self::AUTHORIZATION_BASIC => self::AUTHORIZATION_BASIC
  ];
}
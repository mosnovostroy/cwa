<?php
namespace frontend\components;

class GoogleOAuth2Service extends \nodge\eauth\services\GoogleOAuth2Service
{

  protected $scopes = [
      self::SCOPE_USERINFO_PROFILE,
      self::SCOPE_EMAIL,
  ];

	protected function fetchAttributes()
	{
		$info = $this->makeSignedRequest('https://www.googleapis.com/oauth2/v1/userinfo');

		$this->attributes['id'] = $info['id'];
		$this->attributes['name'] = $info['name'];

		if (!empty($info['link'])) {
			$this->attributes['url'] = $info['link'];
		}

    // Моя кастомизация:
    if (!empty($info['email']))
			$this->attributes['email'] = $info['email'];
    if (!empty($info['picture']))
			$this->attributes['avatar'] = $info['picture'];
	}

}

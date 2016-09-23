<?php
/**
 * Кастомизация класса MailruOAuth2Service
 */

namespace frontend\components;

class MailruOAuth2Service extends \nodge\eauth\services\MailruOAuth2Service
{
	protected function fetchAttributes()
	{
		$tokenData = $this->getAccessTokenData();

		$info = $this->makeSignedRequest('/', [
			'query' => [
				'uids' => $tokenData['params']['x_mailru_vid'],
				'method' => 'users.getInfo',
				'app_id' => $this->clientId,
			],
		]);

		$info = $info[0];

		$this->attributes['id'] = $info['uid'];
		$this->attributes['name'] = $info['first_name'] . ' ' . $info['last_name'];
		$this->attributes['url'] = $info['link'];

    // Моя кастомизация:
		$this->attributes['email'] = $info['email'];
		$this->attributes['avatar'] = $info['pic_190'];

		return true;
	}
}

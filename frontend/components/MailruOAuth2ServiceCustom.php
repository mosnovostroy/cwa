<?php
/**
 * Customization of MailruOAuth2Service class file.
 */

namespace frontend\components;

use nodge\eauth\services\MailruOAuth2Service;

/**
 * Mail.Ru provider class.
 */
class MailruOAuth2ServiceCustom extends MailruOAuth2Service
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
		$this->attributes['pic_190'] = $info['pic_190'];

		return true;
	}
}

<?php
namespace Destiny\Twitch;

use Destiny\Common\Exception;
use Destiny\Common\Service;
use Destiny\Common\Config;
use Destiny\Common\MimeType;
use Destiny\Common\CurlBrowser;
use Destiny\Common\Utils\String;
use Destiny\Common\Utils\Date;

class TwitchApiService extends Service {
	
	/**
	 * Stored when the broadcaster logs in, used to retrieve subscription
	 *
	 * @var string
	 */
	protected $token = '';
	protected static $instance = null;

	/**
	 * Singleton
	 *
	 * @return TwitchApiService
	 */
	public static function instance() {
		return parent::instance ();
	}

	/**
	 * Get the broadcasters latest token
	 *
	 * @return string
	 */
	private function getBroadcasterToken() {
		if (empty ( $this->token )) {
			$this->token = trim ( file_get_contents ( Config::$a ['cache'] ['path'] . 'BROADCASTERTOKEN.tmp' ) );
		}
		return $this->token;
	}

	/**
	 *
	 * @param array $options
	 * @return ApiConsumer
	 */
	public function getPastBroadcasts(array $options = array()) {
		return new CurlBrowser ( array_merge ( array (
			'timeout' => 25,
			'url' => new String ( 'https://api.twitch.tv/kraken/channels/{user}/videos?broadcasts=true&limit={limit}', array (
				'user' => Config::$a ['twitch'] ['user'],
				'limit' => 4 
			) ),
			'contentType' => MimeType::JSON 
		), $options ) );
	}

	/**
	 *
	 * @return ApiConsumer
	 */
	public function getStreamInfo(array $options = array()) {
		return new CurlBrowser ( array_merge ( array (
			'url' => new String ( 'https://api.twitch.tv/kraken/streams/{user}/', array (
				'user' => Config::$a ['twitch'] ['user'] 
			) ),
			'contentType' => MimeType::JSON,
			'onfetch' => function ($json) {
				if (isset ( $json ['status'] ) && $json ['status'] == 503) {
					throw new Exception ( 'Stream api down' );
				}
				if (is_object ( $json ) && isset ( $json ['stream'] ) && $json ['stream'] != null) {
					$json ['stream'] ['channel'] ['updated_at'] = Date::getDateTime ( $json ['stream'] ['channel'] ['updated_at'] )->format ( Date::FORMAT );
				}
				// Last broadcast if the stream is offline
				// Called via static method, because we are in a closure
				$channel = self::instance ()->getChannel ()->getResponse ();
				$json ['lastbroadcast'] = Date::getDateTime ( $channel ['updated_at'] )->format ( Date::FORMAT );
				$json ['status'] = $channel ['status'];
				$json ['game'] = $channel ['game'];
				
				// Just some clean up
				if (isset ( $json ['_links'] )) {
					unset ( $json ['_links'] );
				}
				return $json;
			} 
		), $options ) );
	}

	/**
	 *
	 * @param array $options
	 * @return ApiConsumer
	 */
	public function getChannel(array $options = array()) {
		return new CurlBrowser ( array_merge ( array (
			'url' => new String ( 'https://api.twitch.tv/kraken/channels/{user}', array (
				'user' => Config::$a ['twitch'] ['user'] 
			) ),
			'contentType' => MimeType::JSON 
		), $options ) );
	}

	/**
	 * Get channel subscription info from Twitch
	 *
	 * @param string $channel
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function getChannelSubscriptions($channel, $limit, $offset) {
		$token = $this->getBroadcasterToken ();
		if (! empty ( $token )) {
			$curlBrowser = new CurlBrowser ( array (
				'url' => new String ( 'https://api.twitch.tv/kraken/channels/{channel}/subscriptions?limit={limit}&offset={offset}&oauth_token={oauth_token}', array (
					'channel' => urlencode ( $channel ),
					'limit' => intval ( $limit ),
					'offset' => intval ( $offset ),
					'oauth_token' => urlencode ( $token ) 
				) ),
				'contentType' => MimeType::JSON 
			) );
			return $curlBrowser->getResponse ();
		}
		return null;
	}

}
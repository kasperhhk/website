<?php 
namespace Destiny\Controllers;

use Destiny\Common\Application;
use Destiny\Common\HttpEntity;
use Destiny\Common\Utils\Http;
use Destiny\Common\MimeType;
use Destiny\Common\Annotation\Controller;
use Destiny\Common\Annotation\Route;

/**
 * @Controller
 */
class ApiController {

	/**
	 * @Route ("/youtube")
	 *
	 * @param array $params
	 */
	public function youtube(array $params) {
		$app = Application::instance ();
		$playlist = $app->getCacheDriver ()->fetch ( 'youtubeplaylist' );
		$response = new HttpEntity ( Http::STATUS_OK, json_encode ( $playlist ) );
		$response->addHeader ( Http::HEADER_CACHE_CONTROL, 'private' );
		$response->addHeader ( Http::HEADER_PRAGMA, 'public' );
		$response->addHeader ( Http::HEADER_CONTENTTYPE, MimeType::JSON );
		return $response;
	}

	/**
	 * @Route ("/twitter")
	 *
	 * @param array $params
	 */
	public function twitter(array $params) {
		$app = Application::instance ();
		$tweets = $app->getCacheDriver ()->fetch ( 'twitter' );
		$response = new HttpEntity ( Http::STATUS_OK, json_encode ( $tweets ) );
		$response->addHeader ( Http::HEADER_CACHE_CONTROL, 'private' );
		$response->addHeader ( Http::HEADER_PRAGMA, 'public' );
		$response->addHeader ( Http::HEADER_CONTENTTYPE, MimeType::JSON );
		return $response;
	}

	/**
	 * @Route ("/summoners")
	 *
	 * @param array $params
	 */
	public function summoners(array $params) {
		$app = Application::instance ();
		$summoners = $app->getCacheDriver ()->fetch ( 'summoners' );
		$response = new HttpEntity ( Http::STATUS_OK, json_encode ( $summoners ) );
		$response->addHeader ( Http::HEADER_CACHE_CONTROL, 'private' );
		$response->addHeader ( Http::HEADER_PRAGMA, 'public' );
		$response->addHeader ( Http::HEADER_CONTENTTYPE, MimeType::JSON );
		return $response;
	}

	/**
	 * @Route ("/stream")
	 *
	 * @param array $params
	 */
	public function stream(array $params) {
		$app = Application::instance ();
		$info = $app->getCacheDriver ()->fetch ( 'streaminfo' );
		$response = new HttpEntity ( Http::STATUS_OK, json_encode ( $info ) );
		$response->addHeader ( Http::HEADER_CACHE_CONTROL, 'private' );
		$response->addHeader ( Http::HEADER_PRAGMA, 'public' );
		$response->addHeader ( Http::HEADER_CONTENTTYPE, MimeType::JSON );
		return $response;
	}

	/**
	 * @Route ("/lastfm")
	 *
	 * @param array $params
	 */
	public function lastfm(array $params) {
		$app = Application::instance ();
		$tracks = $app->getCacheDriver ()->fetch ( 'recenttracks' );
		$response = new HttpEntity ( Http::STATUS_OK, json_encode ( $tracks ) );
		$response->addHeader ( Http::HEADER_CACHE_CONTROL, 'private' );
		$response->addHeader ( Http::HEADER_PRAGMA, 'public' );
		$response->addHeader ( Http::HEADER_CONTENTTYPE, MimeType::JSON );
		return $response;
	}

	/**
	 * @Route ("/broadcasts")
	 *
	 * @param array $params
	 */
	public function broadcasts(array $params) {
		$app = Application::instance ();
		$broadcasts = $app->getCacheDriver ()->fetch ( 'pastbroadcasts' );
		$response = new HttpEntity ( Http::STATUS_OK, json_encode ( $broadcasts ) );
		$response->addHeader ( Http::HEADER_CACHE_CONTROL, 'private' );
		$response->addHeader ( Http::HEADER_PRAGMA, 'public' );
		$response->addHeader ( Http::HEADER_CONTENTTYPE, MimeType::JSON );
		return $response;
	}
}
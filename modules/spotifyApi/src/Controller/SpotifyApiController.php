<?php

namespace Drupal\spotifyApi\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Exception\GuzzleException;
use Drupal\spotifyApi\Controller\AuthorizationApiSpotify as Authorization;

/**
 * Class SpotifyApiController
 * @package Drupal\spotifyApi\Controller
 */
class SpotifyApiController extends ControllerBase
{
  /**
   * @var mixed
   */
  protected $dataAuth;
  /**
   * @var \GuzzleHttp\Client
   */
  private $httpClient;

  /**
   * SpotifyApiController constructor.
   */
  public function __construct()
  {
    $token = new Authorization();
    try {
      $token->setAuthorizationApi ();
    } catch (GuzzleException $e) {
      return \Drupal::logger ('authorization token api')->error ($e);
    }
    $this->dataAuth = $token->getAuthorizationApi ();
    $this->httpClient = \Drupal::httpClient ();
  }

  /**
   * method the new releases spotify
   */
  protected function newReleases()
  {
    try {
      $httpRequest = $this->httpClient->request ('GET', 'https://api.spotify.com/v1/browse/new-releases', [
        'headers' => [
          'Authorization' => $this->dataAuth->token_type . ' ' . $this->dataAuth->access_token
        ]
      ]);
      $newReleases = json_decode ($httpRequest->getBody ());
    } catch (GuzzleException $e) {
      return \Drupal::logger ('new releases api spotify')->error ($e);
    }
    $buildView['releases_page'] = [
      '#theme' => 'new_releases_spotify_page',
      '#new_releases' => $newReleases
    ];

    return $buildView;
  }

  /**
   * method details artists
   */
  protected function artist()
  {

  }

}

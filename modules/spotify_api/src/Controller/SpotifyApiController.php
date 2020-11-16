<?php
/**
 * @file
 * Contains \Drupal\spotify_api\Controller\SpotifyApiController.
 */

namespace Drupal\spotify_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use GuzzleHttp\Exception\GuzzleException;
use Drupal\spotify_api\Controller\AuthorizationApiSpotify as Authorization;

/**
 * Class SpotifyApiController
 * @package Drupal\spotify_api\Controller
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
  public function newReleases()
  {
    try {
      $httpRequest = $this->httpClient->request ('GET', 'https://api.spotify.com/v1/browse/new-releases', [
        'headers' => [
          'Authorization' => $this->dataAuth->token_type . ' ' . $this->dataAuth->access_token
        ]
      ]);
      $new_releases = json_decode ($httpRequest->getBody ()->getContents ());
    } catch (GuzzleException $e) {
      return \Drupal::logger ('New releases api spotify')->error ($e);
    }
    $buildView['releases_page'] = [
      '#theme' => 'new_releases_spotify_page',
      '#new_releases' => $new_releases
    ];
    return $buildView;
  }

  /**
   * method details artists and top artists
   * @param $id
   */
  public function artist($id)
  {
    try {
      $httpRequestArtist = $this->httpClient->request ('GET', 'https://api.spotify.com/v1/artists/' . $id, [
        'headers' => [
          'Authorization' => $this->dataAuth->token_type . ' ' . $this->dataAuth->access_token
        ]
      ]);
      $artist = json_decode ($httpRequestArtist->getBody ()->getContents ());
    } catch (GuzzleException $e) {
      return \Drupal::logger ('Artist api spotify')->error ($e);
    }

    try {
      $httpRequestTracks = $this->httpClient->request ('GET', 'https://api.spotify.com/v1/artists/' . $id . '/top-tracks?country=ES', [
        'headers' => [
          'Authorization' => $this->dataAuth->token_type . ' ' . $this->dataAuth->access_token
        ]
      ]);
      $tracks = json_decode ($httpRequestTracks->getBody ()->getContents ());
    } catch (GuzzleException $e) {
      return \Drupal::logger ('Tracks api spotify')->error ($e);
    }

    $buildView['artist_page'] = [
      '#theme' => 'artist_spotify_page',
      '#artist' => $artist,
      '#tracks' => $tracks,
    ];
    return $buildView;
  }

}

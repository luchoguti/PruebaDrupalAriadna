<?php
/**
 * @file
 * Contains \Drupal\spotify_api\Controller\AuthorizationApiSpotify.
 */

namespace Drupal\spotify_api\Controller;


use GuzzleHttp\Exception\GuzzleException;

class AuthorizationApiSpotify
{
  private $dataApi;
  private $authorizationApi;
  public function __construct()
  {
    $this->dataApi = \Drupal::httpClient ();
  }

  /**
   * @throws GuzzleException
   */
  public function setAuthorizationApi()
  {
    $dataAuthorization = $this->dataApi->request ('POST','https://accounts.spotify.com/api/token',[
      'form_params' => [
        'grant_type' => 'client_credentials',
        'client_id' => '5c60d8d7b2334df18324627228944c82',
        'client_secret' => 'fef0860c04654fd5ab5927427f66ad39'
      ]
    ]);
    $this->authorizationApi = json_decode($dataAuthorization->getBody ()->getContents ());
  }

  /**
   * @return mixed
   */
  public function getAuthorizationApi()
  {
    return $this->authorizationApi;
  }

}

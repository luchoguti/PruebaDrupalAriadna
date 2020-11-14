<?php


namespace Drupal\spotifyApi\Controller;


use GuzzleHttp\Exception\GuzzleException;

class AuthorizationAdiSpotify
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
        'client_secret' => 'b1e161c0fac0443cafd7ac6c090dc29b'
      ]
    ]);
    $this->authorizationApi = json_encode ($dataAuthorization->getBody ());
  }

  /**
   * @return mixed
   */
  public function getAuthorizationApi()
  {
    return $this->authorizationApi;
  }

}

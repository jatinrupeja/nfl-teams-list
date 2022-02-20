<?php

namespace Drupal\nfl_teams_list;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Http\ClientFactory;
use Drupal\Core\Language\LanguageManagerInterface;

/**
 * NFL Teams Client service.
 */
class NflTeamsClientService {

  const BASE_URI = 'http://delivery.chalk247.com/';

  /**
   * The http client factory.
   *
   * @var Drupal\Core\Http\ClientFactory
   */
  protected $client;

  /**
   * Config factory.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Language Manager.
   *
   * @var Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * The cache default service.
   *
   * @var Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheDefault;

  /**
   * The api key, from configuration.
   *
   * @var string
   */
  protected $apiKey;

  /**
   * The cache id.
   *
   * @var string
   */
  protected $cid;

  /**
   * NflTeamsClientService constructor.
   *
   * @param \Drupal\Core\Http\ClientFactory $http_client_factory
   *   The client factory.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The config factory.
   * @param Drupal\Core\Cache\CacheBackendInterface $cache_default
   *   The cache.
   */
  public function __construct(
    ClientFactory $http_client_factory,
    ConfigFactoryInterface $config_factory,
    LanguageManagerInterface $language_manager,
    CacheBackendInterface $cache_default) {

    $this->client = $http_client_factory->fromOptions([
      'base_uri' => self::BASE_URI,
    ]);

    // Get API Key from configs.
    $this->configFactory = $config_factory;
    $this->apiKey = $this->configFactory->get('nfl_teams_list.settings')
      ->get('api_key');

    // https://api.drupal.org/api/drupal/core!core.api.php/group/cache/8.2.x
    $this->cacheDefault = $cache_default;
    $this->languageManager = $language_manager;
    $this->cid = 'nfl_teams_list:' . $this->languageManager
      ->getCurrentLanguage()
      ->getId();

  }

  /**
   * Get the NFL teams.
   *
   * @return array
   *   NFL Teams.
   */
  public function nflTeams() {

    $data = NULL;

    // Get the data from cache else hit the API.
    if ($cache = $this->cacheDefault->get($this->cid)) {
      $data = $cache->data;
    }
    else {
      $response = $this->client->get('team_list/NFL.JSON', [
        'query' => [
          'api_key' => $this->apiKey,
        ],
      ]);
      $data = Json::decode($response->getBody());
      $this->cacheDefault->set($this->cid, $data);
    }

    return $data['results']['data']['team'];
  }

  /**
   * Get the cache id.
   *
   * @return string
   *   The cache id.
   */
  public function getCid() {
    return $this->cid;
  }

}

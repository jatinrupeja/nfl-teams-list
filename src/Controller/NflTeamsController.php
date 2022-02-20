<?php

namespace Drupal\nfl_teams_list\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\nfl_teams_list\NflTeamsClientService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines NflTeamsController class.
 */
class NflTeamsController extends ControllerBase {

  /**
   * Module configuration.
   *
   * @var Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * The NFL Teams client Service.
   *
   * @var Drupal\nfl_teams\NflTeamsClientService
   */
  protected $nflTeamsClientService;

  /**
   * Constructor for NflTeamsController class.
   *
   * @param \Drupal\nfl_teams_list\NflTeamsClientService $nfl_teams_client
   *   The nfl service.
   */
  public function __construct(NflTeamsClientService $nfl_teams_client) {
    $this->nflTeamsClient = $nfl_teams_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('nfl_teams_client')
    );
  }

  /**
   * Render the theme with teams data.
   *
   * @return array
   *   Return Theme array.
   */
  public function content() {

    $teams = $this->nflTeamsClient->nflTeams();

    $nfc = [];
    $afc = [];
    foreach ($teams as $key => $value) {
      if ($value['conference'] == 'National Football Conference') {
        $nfc[] = $value;
      }
      if ($value['conference'] == 'American Football Conference') {
        $afc[] = $value;
      }
    }

    return [
      '#theme' => 'nfl_teams',
      '#nfc' => $nfc,
      '#afc' => $afc,
    ];
  }

}

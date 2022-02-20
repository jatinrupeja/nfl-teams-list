<?php

namespace Drupal\nfl_teams_list\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Creates a 'NFL Teams' Block.
 *
 * @Block(
 *   id = "nfl_teams_list_block",
 *   admin_label = @Translation("NFL Teams block"),
 * )
 */
class NflTeamsListBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    // Get the Teams data from 'nfl_teams_client' service.
    $service = \Drupal::service('nfl_teams_client');
    $teams = $service->nflTeams();

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

    $renderable = [
      '#theme' => 'nfl_teams',
      '#nfc' => $nfc,
      '#afc' => $afc,
    ];

    return $renderable;
  }

}

(function ($, Drupal) {
  Drupal.behaviors.nfl_teams_sorter = {
    attach: function (context, settings) {
      $('#nfc').tablesorter();
      $('#afc').tablesorter();
    }
  };
})(jQuery, Drupal);

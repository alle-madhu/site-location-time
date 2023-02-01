<?php

namespace Drupal\site_location_and_time\Service;

use DateTime;
use DateTimeZone;
use Drupal;

/**
 * Class GetTimezoneCurrentTime.
 */
class ServiceToGetTimezoneCurrentTime {

  function getTimezoneCurrentTime(){

    $config = Drupal::config('site_location_and_time.locationandtimezone');
    $timezone = $config->get('timezone');
    $datetime = new DateTime('now', new DateTimeZone($timezone));
    $current_time = $datetime->format('d M Y - H:i a');

    return $current_time;

  }

}

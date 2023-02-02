<?php

namespace Drupal\site_location_and_time\Service;

use DateTime;
use DateTimeZone;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class GetTimezoneCurrentTime.
 */
class ServiceToGetTimezoneCurrentTime  {

  /**
   * Summary of config_factory
   * @var mixed
   */
  protected $config_factory;

  /**
   * Summary of __construct
   * @param ContainerInterface $config_factory
   */
  public function __construct($config_factory) {
    $this->config_factory = $config_factory;
  }

  /**
   * Summary of getTimezoneCurrentTime
   * @return string
   */
  function getTimezoneCurrentTime(){

    $config = $this->config_factory->get('site_location_and_time.locationandtimezone');

    $timezone = $config->get('timezone');
    $datetime = new DateTime('now', new DateTimeZone($timezone));
    $current_time = $datetime->format('d M Y - H:i a');
    return $current_time;

  }


}

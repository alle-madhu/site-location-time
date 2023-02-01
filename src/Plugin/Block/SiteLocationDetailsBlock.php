<?php

namespace Drupal\site_location_and_time\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\Renderer;
use Drupal\site_location_and_time\Service\ServiceToGetTimezoneCurrentTime as TimezoneCurrent;

/**
 * Provides a 'SiteLocationDetailsBlock' block.
 *
 * @Block(
 *  id = "get_timezone_current_time",
 *  admin_label = @Translation("Get timezone current time"),
 * )
 */
class SiteLocationDetailsBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * Summary of timeZoneService
   * @var mixed
   */
  protected $timeZoneService;

  /**
   * Summary of configFactory
   * @var mixed
   */
  protected $configFactory;

  /**
   * Summary of render
   * @var mixed
   */
  protected $renderer;

  /**
   * Constructs an EventCountdownBlock object.
   *
   * @param array $configuration
   *   The block configuration.
   * @param string $plugin_id
   *   The ID of the plugin.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param TimezoneCurrent $timeZoneService
   *   The date calculator.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A config factory instance.
   * @param \Drupal\Core\Render\Renderer $renderer
   *   A config factory instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    $TimezoneCurrent,
    ConfigFactoryInterface $config_factory,
    Renderer $renderer
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->timeZoneService = $TimezoneCurrent;
    $this->configFactory = $config_factory;
    $this->renderer = $renderer;
  }

  /**
   * Creates an instance of the plugin.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The container to pull out services used in the plugin.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @return static
   *   Returns an instance of this plugin.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('site_location_and_time.current_time_based_on_timezone'),
      $container->get('config.factory'),
      $container->get('renderer'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {

    $build = [];
    $config_form = $this->configFactory->get('site_location_and_time.locationandtimezone');

    $build = [
      '#theme' => 'site_location_details',
      '#cache' => [
        'contexts' => [
          'url',        //if url changes cache get invalidated
        ],
        'max-age' => 60 //set it for 1 minute
      ],
      '#country' => $config_form->get('country'),
      '#city' => $config_form->get('city'),
      '#currentTime' => $this->timeZoneService->getTimezoneCurrentTime()
    ];
    //adding config_form object as dependency so
    //when this object get updated cache of the blocj will get invalidated
    $this->renderer->addCacheableDependency($build, $config_form);
    return $build;
  }
}

<?php


namespace Drupal\agenda\Plugin\Block;


use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Http\RequestStack;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventBlock
 *
 * @Block(
 *   id = "c_event_block",
 *   admin_label = @Translation("Event Block")
 * )
 *
 * @package Drupal\agenda\Plugin\Block
 */
class EventBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  protected $loggerFactory;


  public function __construct(
    array $configuration,
    $plugin_id, $plugin_definition,
    Request $request,
    EntityTypeManagerInterface $entityTypeManager,
    LoggerChannelFactoryInterface $loggerChannelFactory
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->request           = $request;
    $this->entityTypeManager = $entityTypeManager;
    $this->loggerFactory     = $loggerChannelFactory;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /** @var RequestStack $request */
    $request = $container->get('request_stack');

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = $container->get('entity_type.manager');

    /** @var LoggerChannelFactoryInterface $logger */
    $logger = $container->get('logger.factory');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $request->getCurrentRequest(),
      $entityTypeManager,
      $logger
    );
  }


  /**
   * @inheritDoc
   */
  public function build() {

    /** @var EntityTypeInterface $node */
    $node = $this->request->attributes->get('node');

    $viewRender = [];

    try {
      /** @var \Drupal\Core\Entity\Query\QueryInterface $query */
      $query = $this->entityTypeManager->getStorage('node')->getQuery();

      $result = $query->condition('field_event_type', $node->get('field_event_type')->target_id)
        ->condition('nid', $node->id(), '<>')
        ->accessCheck()
        ->execute();

      $result = array_values($result);

      $sideEvent = $this->entityTypeManager->getStorage('node')
        ->load($result[array_rand($result, 1)]);

      $viewRender = $this->entityTypeManager->getViewBuilder('node')
        ->view($sideEvent, 'teaser');
    }
    catch (InvalidPluginDefinitionException $e) {
      $this->loggerFactory->get('Plugin')->error($e->getMessage());
    }
    catch (PluginNotFoundException $e) {
      $this->loggerFactory->get('Plugin')->error($e->getMessage());
    }

    return $viewRender;
  }

  /**
   * {@inheritDoc}
   */
  public function getCacheContexts() {
    return ['url.path'];
  }

}

<?php


namespace Drupal\agenda\Plugin\QueueWorker;


use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Queue\QueueWorkerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Updates a feed's items.
 *
 * @QueueWorker(
 *   id = "agenda_event_unpublish",
 *   title = @Translation("Unpublish passed event"),
 *   cron = {"time" = 60}
 * )
 */
class UnpublishEvent extends QueueWorkerBase implements ContainerFactoryPluginInterface {

  protected $entityTypeManager;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @inheritDoc
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id, $plugin_definition) {

    /** @var EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = $container->get('entity_type.manager');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $entityTypeManager
    );
  }

  /**
   * @inheritDoc
   */
  public function processItem($data) {
    /** @var \Drupal\Core\Entity\EntityInterface $node */
    $node = $this->entityTypeManager->getStorage('node')->load($data);

    if (!$node) return;

    $node->set('status', 1);
    $node->save();

  }


}

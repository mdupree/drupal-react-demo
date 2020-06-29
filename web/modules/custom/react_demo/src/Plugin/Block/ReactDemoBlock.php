<?php

namespace Drupal\react_demo\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block for react demo.
 *
 * @Block(
 *   id = "react_demo_block",
 *   admin_label = @Translation("React Demo Block"),
 * )
 */
class ReactDemoBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build['#markup'] = '<div id="react-demo-bind"></div>';


    $build['#attached']['library'] = [
      'react_demo/react',
      'react_demo/custom',
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    return $form;
  }

}

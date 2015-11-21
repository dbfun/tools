<?php

/**
 * @package    PApplication
 *
 * @copyright
 */

 
class PApplication {

  /**
   * Init common classes
   *
   */

  public function init() 
  {
    require(__DIR__.'/core/factory.php');
    PFactory::init();
  }
}
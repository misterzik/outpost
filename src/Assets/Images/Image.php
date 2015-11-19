<?php

/**
 * @package Outpost
 * @author Pixo <info@pixotech.com>
 * @copyright 2015, Pixo
 * @license http://opensource.org/licenses/NCSA NCSA
 */

namespace Outpost\Assets\Images;

use Outpost\Assets\Asset;

abstract class Image extends Asset implements ImageInterface {

  protected $alt;

  public static function makeKey() {
    $args = func_get_args();
    return md5(serialize($args));
  }

  public function __construct($alt = '') {
    $this->alt = '';
  }

  public function getAlt() {
    return $this->alt;
  }
}
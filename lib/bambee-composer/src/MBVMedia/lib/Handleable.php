<?php
/**
 * Created by PhpStorm.
 * User: hterhoeven
 * Date: 01.03.2016
 * Time: 11:43
 */

namespace MBVMedia\Lib;


interface Handleable {
    public function handleShortcode(array $args = array(), $content = '');
}
<?php

namespace App\Controllers\AbstractControllers;

abstract class AbstractController extends \App\Controllers\BaseController
{
    abstract function create();

    abstract function read();

    abstract function update();

    abstract function delete();
}

<?php

namespace MiniBox\Container\Exception;

use MiniBox\Exception\NotFoundException;
use Psr\Container\NotFoundExceptionInterface;

class ServiceNotFoundException extends NotFoundException implements NotFoundExceptionInterface{}
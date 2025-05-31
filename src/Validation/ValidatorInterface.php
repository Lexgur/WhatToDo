<?php

declare(strict_types=1);

namespace Edgaras\WhatToDo\Validation;

interface ValidatorInterface
{
    public function validate(mixed $input):bool;
}
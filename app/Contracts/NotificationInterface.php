<?php

namespace App\Contracts;

interface NotificationInterface
{
    public function send(string $message): void;
}

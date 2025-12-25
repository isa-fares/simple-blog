<?php
use App\Services\Notifications;

abstract class BaseNotification implements BaseNotification
{
    protected $
    protected $notification;
    public function __construct(string $to)
    {
        $this->to = $to;
    }
    public function log(string $message): void
        {
        Log::info($message);
    }
}

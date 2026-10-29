<?php

require_once __DIR__ . "/../Models/Notification.php";

class NotificationController
{

    private $notification;

    public function __construct()
    {
        $this->notification = new Notification();
    }


    // Return all notifications
    public function index()
    {
        return $this->notification->all();
    }


    // Count unread notifications
    public function unreadCount()
    {
        return $this->notification->unreadCount();
    }


    // Mark all notifications as read
    public function markAllRead()
    {
        return $this->notification->markAllRead();
    }
public function generate()
{
    return $this->notification->generateNotifications();
}
}
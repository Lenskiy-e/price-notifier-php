<?php


namespace App\QueueHandlers;


use App\Services\MailerService;

class MessageHandler implements QueueHandler
{
    /**
     * @var MailerService
     */
    private $mailer;
    
    /**
     * MessageHandler constructor.
     * @param MailerService $mailer
     */
    public function __construct(MailerService $mailer)
    {
        $this->mailer = $mailer;
    }
    
    public function __invoke()
    {
        $this->mailer->send();
    }
}
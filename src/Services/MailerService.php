<?php
declare(strict_types=1);

namespace App\Services;

use Swift_Mailer;
use Swift_Message;

class MailerService
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    
    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
    
    /**
     * @param string $mail_to
     * @param string $subject
     * @param string $body
     * @return int
     */
    public function send(string $mail_to, string $subject, string $body): int
    {
        $message = (new Swift_Message())
            ->setSubject($subject)
            ->setFrom( getenv('mail_from') )
            ->setTo($mail_to)
            ->setContentType("text/html")
            ->setBody($body);
        return $this->mailer->send($message);
    }
}
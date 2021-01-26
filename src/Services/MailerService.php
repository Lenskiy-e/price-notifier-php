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
    
    private $mail_to;
    private $subject;
    private $body;
    
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
    public function send(): int
    {
        $message = (new Swift_Message())
            ->setSubject($this->subject)
            ->setFrom( getenv('mail_from') )
            ->setTo($this->mail_to)
            ->setContentType("text/html")
            ->setBody($this->body);
        return $this->mailer->send($message);
    }
    
    /**
     * @param mixed $mail_to
     */
    public function setMailTo($mail_to): void
    {
        $this->mail_to = $mail_to;
    }
    
    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }
    
    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }
    
    
}
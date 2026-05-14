<?php

namespace App\EventListener;

use App\Entity\Comment;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::postPersist, entity: Comment::class)]
class CommentNotificationListener
{
    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail
    ){}

    public function postPersist(Comment $comment): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@guestbook.pl')
            ->to($this->adminEmail)
            ->subject("Nowy komentarz dodany")
            ->htmlTemplate('emails/comment_notification.html.twig')
            ->context(['comment' => $comment]);

        $this->mailer->send($email);
    }
}
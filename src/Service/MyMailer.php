<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use App\Security\EmailVerifier;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * @var MailerInterface
 */
class MyMailer 
{
    
    private $mailer ;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($user, $token)
    {
        $email = (new TemplatedEmail())
        ->from('portfolio.rostand@gmail.com')
        //->from(new Address('zakina.rostand@gmail.com', 'noreply - portfolio'))
        ->to($user->getEmail())
        //->to('zakina.annouche@gmail.com')
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Inscription portfolio')
        ->text('confirmez votre inscription ')
        //->html('<p>See Twig integration for better HTML integration!</p>');
        ->htmlTemplate('register/emailConfirmation.html.twig')
        ->context([
            'token'=>$token,
            'user' => $user
        ]);
    
        $this->mailer->send($email);
    }
}
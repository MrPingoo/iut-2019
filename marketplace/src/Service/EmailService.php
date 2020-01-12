<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;

class EmailService
{
    protected $mailer;
    private $templating;
    private $em;

    public function __construct(\Swift_Mailer $mailer,\Twig_Environment $templating,EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->em = $em;
    }

    public function sendRegistrationMail($subject, $from, $to, $args) {
        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->renderRegistrationTemplate($args),
                'text/html'
            )
        ;

        $this->mailer->send($message);
    }

    public function renderRegistrationTemplate($args)
    {
        return $this->templating->render(
            'emails/registration.html.twig',
            $args
        );
    }
}
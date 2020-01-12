<?php


namespace App\Controller;


use App\Service\EmailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Request $request, EmailService $emailService)
    {
        $emailService->sendRegistrationMail('Welcome', 'noreply@localhost', $this->getUser()->getEmail(), ['user' => $this->getUser()]);
        return $this->render('default/index.html.twig', [
            'vars' => ['Hello World', 'Hello IUT']
        ]);
    }
}

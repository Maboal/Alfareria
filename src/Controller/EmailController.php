<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    
    #[Route('/sendEmail', name: 'api_alfareria_sendEmail', methods: ['POST'])]
    public function sendEmail(Request $request, MailerInterface $mailer)
    {
        $name = $request->request->get('name');
        $emailInput = $request->request->get('email');
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');
        
        $email = (new Email())
            ->from(new Address($emailInput, $name))
            ->to('alfareriaartesana@gmail.com')
            ->subject($subject)
            ->text($message);

        $mailer->send($email);

        return $this->json(['success' => true]);
    }
}

<?php

namespace App\Common\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="redirect_home")
     */
    public function redirectHome(): Response
    {
        return $this->redirectToRoute('app.swagger_ui');
    }
}

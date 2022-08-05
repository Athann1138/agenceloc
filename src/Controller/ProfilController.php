<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function profil()
    {
        return $this->render('profil/Profil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
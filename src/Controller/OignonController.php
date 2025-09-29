<?php

namespace App\Controller;

use App\Repository\OignonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/oignon', name: 'oignon_')]
final class OignonController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function liste(OignonRepository $oignonRepository): Response
    {
        $oignons = $oignonRepository->findAll();
        return $this->render('oignon/liste_oignon.html.twig', [
            'oignons' => $oignons,
        ]);
    }
}

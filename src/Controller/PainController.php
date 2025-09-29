<?php

namespace App\Controller;

use App\Repository\PainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pain', name: 'pain_')]
final class PainController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function liste(PainRepository $painRepository): Response
    {
        $pains = $painRepository->findAll();
        return $this->render('pain/liste_pain.html.twig', [
            'pains' => $pains,
        ]);

    }
}

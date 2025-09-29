<?php

namespace App\Controller;

use App\Entity\Burger;
use App\Repository\SauceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sauce', name: 'sauce_')]
final class SauceController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function liste(SauceRepository $sauceRepository): Response
    {
        $sauces = $sauceRepository->findAll();
        return $this->render('sauce/liste_sauce.html.twig', [
            'sauces' => $sauces,
        ]);

    }
}

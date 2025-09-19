<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Burger;


class BurgerController extends AbstractController
{
    private static array $burgers = [
        [
            'nom' => 'Cheeseburger',
            'description' => 'Un burger classique avec du fromage fondant.',
            'prix' => 5.99
        ],
        [
            'nom' => 'Hamburger',
            'description' => 'Un burger simple avec steak et garnitures.',
            'prix' => 4.99
        ],
        [
            'nom' => 'Big Mac',
            'description' => 'Le célèbre Big Mac avec sa sauce spéciale.',
            'prix' => 6.99
        ]
    ];

    #[Route('/burger', name: 'liste_burger')]
    public function liste(): Response
    {
        $burgers = self::$burgers;
        return $this->render('liste_burger.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/burger/{id}', name: 'burger_show')]
    public function show(int $id): Response
    {
        $burgers = self::$burgers;
        if (!isset($burgers[$id])) {
           return $this->render('burger_show.html.twig', [
                'burger' => null,
            ]);
        }
        return $this->render('burger_show.html.twig', [
            'burger' => $burgers[$id],
        ]);
    }

    #[Route('/burger/create', name: 'burger_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $burger = new Burger();
        $burger->setName('Burger classique');

        $entityManager->persist($burger);
        $entityManager->flush();

        return new Response('Burger créé avec succès !');
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Burger;
use App\Repository\BurgerRepository;
use Symfony\Component\HttpFoundation\Request;

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

    #[Route('/burger/{id}', name: 'burger_read')]
    public function read(BurgerRepository $burgerRepository, int $id): Response
    {
        $burger = $burgerRepository->find($id);
        if (!$burger) {
            throw $this->createNotFoundException('Burger non trouvé');
        }
        return $this->render('burger/show.html.twig', [
            'burger' => $burger,
        ]);
    }

    #[Route('/burger/{id}/update', name: 'burger_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, BurgerRepository $burgerRepository, int $id): Response
    {
        $burger = $burgerRepository->find($id);
        if (!$burger) {
            throw $this->createNotFoundException('Burger non trouvé');
        }
        $burger->setName('Burger modifié');
        $entityManager->flush();
        return new Response('Burger modifié avec succès !');
    }

    #[Route('/burger/{id}/delete', name: 'burger_delete')]
    public function delete(EntityManagerInterface $entityManager, BurgerRepository $burgerRepository, int $id): Response
    {
        $burger = $burgerRepository->find($id);
        if (!$burger) {
            throw $this->createNotFoundException('Burger non trouvé');
        }
        $entityManager->remove($burger);
        $entityManager->flush();
        return new Response('Burger supprimé avec succès !');
    }

    #[Route('/burgers', name: 'burger_index')]
    public function index(BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findAll();
        return $this->render('burger/index.html.twig', [
            'burgers' => $burgers,
        ]);
    }
}
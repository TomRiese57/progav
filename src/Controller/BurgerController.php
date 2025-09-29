<?php 

namespace App\Controller;

use App\Entity\Burger;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/burger', name: 'burger_')]
class BurgerController extends AbstractController
{
    #[Route('/liste', name: 'liste')]
    public function liste(BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findAll();

        return $this->render('burger/liste_burger.html.twig', [
            'burgers' => $burgers,
        ]);
    }

    #[Route('/burger/show/{id}', name: 'detail')]
    public function detail(int $id, BurgerRepository $burgerRepository): Response
    {
        $burger = $burgerRepository->find($id);

        if (!$burger) {
            throw $this->createNotFoundException("Burger avec l'id $id introuvable.");
        }

        return $this->render('burger/detail.html.twig', [
            'burger' => $burger,
        ]);
    }

    #[Route('/creation', name: 'creation')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $burger = new Burger();
        $burger->setNom('Krabby Patty');
        $burger->setPrix(4.99);
    
        // Persister et sauvegarder le nouveau burger
        $entityManager->persist($burger);
        $entityManager->flush();
    
        return new Response('Burger créé avec succès !');
    }
}
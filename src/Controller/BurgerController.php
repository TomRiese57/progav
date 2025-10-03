<?php 

namespace App\Controller;

use App\Entity\Burger;
use App\Form\BurgerType;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/show/{id}', name: 'detail')]
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

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $burger = new Burger();
        $form = $this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($burger);
            $em->flush();
    
            $this->addFlash('success', 'Burger créé !');
            return $this->redirectToRoute('burger_liste');
        }
    
        return $this->render('burger/create_form.html.twig', [
            'burger' => $burger,
            'form' => $form->createView()
        ]);
    }

    #[Route('/creation', name: 'creation')]
    public function creation(EntityManagerInterface $entityManager): Response
    {
        $burger = new Burger();
        $burger->setNom('Krabby Patty');
        $burger->setPrix(4.99);
    
        // Persister et sauvegarder le nouveau burger
        $entityManager->persist($burger);
        $entityManager->flush();
    
        return new Response('Burger créé avec succès !');
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(int $id, EntityManagerInterface $entityManager, BurgerRepository $burgerRepository): Response
    {
        $burger = $burgerRepository->find($id);
    
        if (!$burger) {
            throw $this->createNotFoundException("Burger avec l'id $id introuvable.");
        }
    
        $burger->setPrix(5.99); // Mettre à jour le prix du burger
    
        // Sauvegarder les modifications
        $entityManager->flush();
    
        return new Response('Burger mis à jour avec succès !');
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(int $id, EntityManagerInterface $entityManager, BurgerRepository $burgerRepository): Response
    {
        $burger = $burgerRepository->find($id);
    
        if (!$burger) {
            throw $this->createNotFoundException("Burger avec l'id $id introuvable.");
        }
    
        // Supprimer le burger
        $entityManager->remove($burger);
        $entityManager->flush();
    
        return new Response('Burger supprimé avec succès !');
    }

    #[Route('/by/{ingredient}', name: 'by_ingredient')]
    public function burgersByIngredient(string $ingredient, BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findBurgersWithIngredient($ingredient);

        return $this->render('burger/by_ingredient.html.twig', [
            'burgers' => $burgers,
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/top/{limit}', name: 'by_price')]
    public function topBurgers(int $limit, BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findTopXBurgers($limit);

        return $this->render('burger/by_price.html.twig', [
            'burgers' => $burgers,
            'limit' => $limit,
        ]);
    }

    #[Route('/without/{ingredient}', name: 'without_ingredient')]
    public function burgersWithoutIngredient(string $ingredient, BurgerRepository $burgerRepository, EntityManagerInterface $entityManager): Response
    {

        $burgers = $burgerRepository->findBurgersWithoutIngredient($ingredient);

        return $this->render('burger/without_ingredient.html.twig', [
            'burgers' => $burgers,
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/min_ingredients/{min}', name: 'min_ingredients')]
    public function burgersWithMinimumIngredients(int $min, BurgerRepository $burgerRepository): Response
    {
        $burgers = $burgerRepository->findBurgersWithMinimumIngredients($min);

        return $this->render('burger/min_ingredients.html.twig', [
            'burgers' => $burgers,
            'min' => $min,
        ]);
    }
}
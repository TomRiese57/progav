<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sauce;
use App\Repository\SauceRepository;
use Symfony\Component\HttpFoundation\Request;

final class SauceController extends AbstractController
{
    #[Route('/sauce', name: 'app_sauce')]
    public function index(SauceRepository $sauceRepository): Response
    {
        $sauces = $sauceRepository->findAll();
        return $this->render('sauce/index.html.twig', [
            'sauces' => $sauces,
        ]);
    }

    #[Route('/sauce/create/{name}', name: 'sauce_create')]
    public function create(EntityManagerInterface $entityManager, string $name): Response
    {
        $sauce = new Sauce();
        $sauce->setName($name);

        $entityManager->persist($sauce);
        $entityManager->flush();

        return new Response('Sauce créée avec succès !');
    }

    #[Route('/sauce/{id}', name: 'sauce_read')]
    public function read(SauceRepository $sauceRepository, int $id): Response
    {
        $sauce = $sauceRepository->find($id);
        if (!$sauce) {
            throw $this->createNotFoundException('Sauce non trouvée');
        }

        return $this->render('sauce/show.html.twig', [
            'sauce' => $sauce,
        ]);
    }

    #[Route('/sauce/{id}/update/{name}', name: 'sauce_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, SauceRepository $sauceRepository, int $id, string $name): Response
    {
        $sauce = $sauceRepository->find($id);
        if (!$sauce) {
            throw $this->createNotFoundException('Sauce non trouvée');
        }
        $sauce->setName($name);
        $entityManager->flush();
        return new Response('Sauce modifiée avec succès !');
    }

    #[Route('/sauce/{id}/delete', name: 'sauce_delete')]
    public function delete(EntityManagerInterface $entityManager, SauceRepository $sauceRepository, int $id): Response
    {
        $sauce = $sauceRepository->find($id);
        if (!$sauce) {
            throw $this->createNotFoundException('Sauce non trouvée');
        }
        $entityManager->remove($sauce);
        $entityManager->flush();
        return new Response('Sauce supprimée avec succès !');
    }

    #[Route('/sauces', name: 'sauce_index')]
    public function list(SauceRepository $sauceRepository): Response
    {
        $sauces = $sauceRepository->findAll();
        return $this->render('sauce/index.html.twig', [
            'sauces' => $sauces,
        ]);
    }
}

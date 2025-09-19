<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pain;
use App\Repository\PainRepository;
use Symfony\Component\HttpFoundation\Request;

final class PainController extends AbstractController
{
    #[Route('/pain', name: 'app_pain')]
    public function index(): Response
    {
        return $this->render('pain/index.html.twig', [
            'controller_name' => 'PainController',
        ]);
    }

    #[Route('/pain/create', name: 'pain_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $pain = new Pain();
        $pain->setName('Pain classique');

        $entityManager->persist($pain);
        $entityManager->flush();

        return new Response('Pain créé avec succès !');
    }

    #[Route('/pain/{id}', name: 'pain_read')]
    public function read(PainRepository $painRepository, int $id): Response
    {
        $pain = $painRepository->find($id);
        if (!$pain) {
            throw $this->createNotFoundException('Pain non trouvé');
        }

        return $this->render('pain/show.html.twig', [
            'pain' => $pain,
        ]);
    }

    #[Route('/pain/{id}/update', name: 'pain_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, PainRepository $painRepository, int $id): Response
    {
        $pain = $painRepository->find($id);
        if (!$pain) {
            throw $this->createNotFoundException('Pain non trouvé');
        }
        $pain->setName('Pain modifié');
        $entityManager->flush();
        return new Response('Pain modifié avec succès !');
    }

    #[Route('/pain/{id}/delete', name: 'pain_delete')]
    public function delete(EntityManagerInterface $entityManager, PainRepository $painRepository, int $id): Response
    {
        $pain = $painRepository->find($id);
        if (!$pain) {
            throw $this->createNotFoundException('Pain non trouvé');
        }
        $entityManager->remove($pain);
        $entityManager->flush();
        return new Response('Pain supprimé avec succès !');
    }
}

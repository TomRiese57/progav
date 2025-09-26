<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Oignon;
use App\Repository\OignonRepository;
use Symfony\Component\HttpFoundation\Request;

final class OignonController extends AbstractController
{
    #[Route('/oignon', name: 'app_oignon')]
    public function index(OignonRepository $oignonRepository): Response
    {
        $oignons = $oignonRepository->findAll();
        return $this->render('oignon/index.html.twig', [
            'oignons' => $oignons,
        ]);
    }

    #[Route('/oignon/create/{name}', name: 'oignon_create')]
    public function create(EntityManagerInterface $entityManager, string $name): Response
    {
        $oignon = new Oignon();
        $oignon->setName($name);

        $entityManager->persist($oignon);
        $entityManager->flush();

        return new Response('Oignon créé avec succès !');
    }

    #[Route('/oignon/{id}', name: 'oignon_read')]
    public function read(OignonRepository $oignonRepository, int $id): Response
    {
        $oignon = $oignonRepository->find($id);
        if (!$oignon) {
            throw $this->createNotFoundException('Oignon non trouvé');
        }

        return $this->render('oignon/show.html.twig', [
            'oignon' => $oignon,
        ]);
    }

    #[Route('/oignon/{id}/update/{name}', name: 'oignon_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, OignonRepository $oignonRepository, int $id, string $name): Response
    {
        $oignon = $oignonRepository->find($id);
        if (!$oignon) {
            throw $this->createNotFoundException('Oignon non trouvé');
        }
        $oignon->setName($name);
        $entityManager->flush();
        return new Response('Oignon modifié avec succès !');
    }

    #[Route('/oignon/{id}/delete', name: 'oignon_delete')]
    public function delete(EntityManagerInterface $entityManager, OignonRepository $oignonRepository, int $id): Response
    {
        $oignon = $oignonRepository->find($id);
        if (!$oignon) {
            throw $this->createNotFoundException('Oignon non trouvé');
        }
        $entityManager->remove($oignon);
        $entityManager->flush();
        return new Response('Oignon supprimé avec succès !');
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;

final class CommentaireController extends AbstractController
{
    #[Route('/commentaire', name: 'app_commentaire')]
    public function index(): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'controller_name' => 'CommentaireController',
        ]);
    }

    #[Route('/commentaire/create', name: 'commentaire_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $commentaire->setName('Commentaire classique');

        $entityManager->persist($commentaire);
        $entityManager->flush();

        return new Response('Commentaire créé avec succès !');
    }

    #[Route('/commentaire/{id}', name: 'commentaire_read')]
    public function read(CommentaireRepository $commentaireRepository, int $id): Response
    {
        $commentaire = $commentaireRepository->find($id);
        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé');
        }

        return $this->render('commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    #[Route('/commentaire/{id}/update', name: 'commentaire_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository, int $id): Response
    {
        $commentaire = $commentaireRepository->find($id);
        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé');
        }
        $commentaire->setName('Commentaire modifié');
        $entityManager->flush();
        return new Response('Commentaire modifié avec succès !');
    }

    #[Route('/commentaire/{id}/delete', name: 'commentaire_delete')]
    public function delete(EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository, int $id): Response
    {
        $commentaire = $commentaireRepository->find($id);
        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé');
        }
        $entityManager->remove($commentaire);
        $entityManager->flush();
        return new Response('Commentaire supprimé avec succès !');
    }
}
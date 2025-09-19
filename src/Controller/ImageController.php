<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;

final class ImageController extends AbstractController
{
    #[Route('/image', name: 'app_image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig', [
            'controller_name' => 'ImageController',
        ]);
    }

    #[Route('/image/create', name: 'image_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $image = new Image();
        $image->setName('Image classique');

        $entityManager->persist($image);
        $entityManager->flush();

        return new Response('Image créée avec succès !');
    }

    #[Route('/image/{id}', name: 'image_read')]
    public function read(ImageRepository $imageRepository, int $id): Response
    {
        $image = $imageRepository->find($id);
        if (!$image) {
            throw $this->createNotFoundException('Image non trouvée');
        }

        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/image/{id}/update', name: 'image_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, ImageRepository $imageRepository, int $id): Response
    {
        $image = $imageRepository->find($id);
        if (!$image) {
            throw $this->createNotFoundException('Image non trouvée');
        }
        $image->setName('Image modifiée');
        $entityManager->flush();
        return new Response('Image modifiée avec succès !');
    }

    #[Route('/image/{id}/delete', name: 'image_delete')]
    public function delete(EntityManagerInterface $entityManager, ImageRepository $imageRepository, int $id): Response
    {
        $image = $imageRepository->find($id);
        if (!$image) {
            throw $this->createNotFoundException('Image non trouvée');
        }
        $entityManager->remove($image);
        $entityManager->flush();
        return new Response('Image supprimée avec succès !');
    }
}

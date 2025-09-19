<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;

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
}

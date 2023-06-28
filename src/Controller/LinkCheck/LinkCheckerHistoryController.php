<?php

namespace App\Controller\LinkCheck;

use App\Entity\LinkCheck\LinkCheckHistory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkCheckerHistoryController extends AbstractController
{
    #[Route('/link/history', name: 'link_history_show')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        //reikia pagination
        $links = $entityManager->getRepository(LinkCheckHistory::class)->findAll();

        return $this->render('linkCheck/show.html.twig', ['links' => $links]);

    }

}
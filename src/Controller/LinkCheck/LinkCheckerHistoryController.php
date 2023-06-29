<?php

namespace App\Controller\LinkCheck;

use App\Entity\LinkCheck\LinkCheckHistory;
use App\Entity\LinkCheck\LinkCheckHistoryRedirects;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkCheckerHistoryController extends AbstractController
{
    #[Route('/link/history', name: 'link_history_show')]
    public function show(EntityManagerInterface $entityManager): Response
    {
        $linkCheckRepository = $entityManager->getRepository(LinkCheckHistory::class);
        $linkHistoryData = $linkCheckRepository->getLinkCheckData();
        $redirectToCheck = $this->generateUrl('link_check');
        return $this->render('linkCheck/show.html.twig', ['linkHistory' => $linkHistoryData,
            'redirect_to_check' => $redirectToCheck]);

    }

}
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
        //need pagination
        //$linkCheckRepository = $entityManager->getRepository(LinkCheckHistoryRedirects::class);
        //$linkHistoryData_raw = $linkCheckRepository->getLinkCheckData();

        /*
        $linkHistoryData = [];
        //indexing received sql data
        foreach ($linkHistoryData_raw as $data){
            if(!isset($linkHistoryData[$data['id']])){
                $linkHistoryData[$data['id']]['keywords'] = explode(':',$data['keywords']);
                $linkHistoryData[$data['id']]['keyword_occurrences'] = explode(':',$data['keyword_occurrences']);
                //to not occupy RAM
                unset($data['keywords'],$data['keyword_occurrences']);
            }
            $linkHistoryData[$data['id']]['redirects'][$data['id_redirects']] = $data;
        }*/
        $linkCheckRepository = $entityManager->getRepository(LinkCheckHistory::class);
        $linkHistoryData = $linkCheckRepository->getLinkCheckData();
        $redirectToCheck = $this->generateUrl('link_check');
        return $this->render('linkCheck/show.html.twig', ['linkHistory' => $linkHistoryData,
            'redirect_to_check' => $redirectToCheck]);

    }

}
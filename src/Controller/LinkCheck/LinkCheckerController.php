<?php

namespace App\Controller\LinkCheck;

use App\Entity\LinkCheck\LinkCheckHistory;
use App\Form\LinkCheck\LinkCheckHistoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LinkCheckerController extends AbstractController
{

    private array $keywords = [];
    private array $redirects = [];
    private array $errors = [];

    public function createLinkHistory(EntityManagerInterface $entityManager): Response
    {
        $product = new LinkCheckHistory();
        $product->setUrl('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    #[Route('/link/check', name: 'link_check')]
    public function check(Request $request, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(LinkCheckHistoryType::class);

        $form->handleRequest($request);

        $dataToRender['form'] = $form->createView();
        $dataToRender['request_data'] = false;

        if ($form->isSubmitted() && $form->isValid()) {
            //Get submitted form data
            $formData = $form->getData();

            $dataToRender['request_data'] = $this->processRequest($formData,$validator);
        }
        $dataToRender['redirect_to_history'] = $this->generateUrl('link_history_show');
        $dataToRender['errors'] = $this->errors;

        return $this->render('linkCheck/check_form.html.twig', $dataToRender);
    }

    /**
     * Makes entered url accessible
     * @param string $url
     * @param ValidatorInterface $validator
     * @return string
     */
    private function validateUrl(string $url, ValidatorInterface $validator): string
    {
        $errors = $validator->validate($url, [
            new \Symfony\Component\Validator\Constraints\Url(),
        ]);

        if (count($errors) === 0) {
            return $url;
        }

        $parsedUrl = parse_url($url);
        if (!isset($parsedUrl['scheme'])) {
            $url = 'https://' . $url;
        }

        return $url;
    }


    /**
     * @param $formData
     * @param $validator
     * @return array of data from response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    private function processRequest($formData,$validator):array {
        //I doubt other types will ever be required
        //$requestType = $formData['type'] ? 'GET' : 'POST';
        $requestType = 'GET';
        $maxRedirects = $formData['max_redirects'];
        $url = $formData['url'];
        $url = $this->validateUrl($url,$validator);
        $result = [];

        $httpClient = HttpClient::create();

        $statusCode = $this->checkUrlResponses($url,$httpClient,$requestType,$maxRedirects,$formData['key_words']);

        $result['status'] = $statusCode;
        $result['redirects'] = count($this->redirects);
        $result['keywords'] = $this->keywords;

        return $result;
    }

    private function proccessKeywords(string $rawKeyWords, string $responseContent): void {
        //force array
        if(str_contains($rawKeyWords,PHP_EOL)){
            $keywords = explode(PHP_EOL,$rawKeyWords);
        } else {
            $keywords[] = $rawKeyWords;
        }

        $result = [];
        //get all words
        foreach ($keywords as $keyword) {
            $occurrences = substr_count($responseContent,$keyword);
            if (isset($this->keywords[$keyword])){
                $this->keywords[$keyword] += $occurrences;
            } else {
                $this->keywords[$keyword] = $occurrences;
            }

        }
    }

    private function checkUrlResponses($url,$httpClient,$requestType,$maxRedirects,$formKeywords){
        $statusCode = 0;
        for ($i = 0; $i < $maxRedirects; $i++) {
            $response = $httpClient->request($requestType, $url, [
                'max_redirects' => 0, // Disable automatic redirects
                'http_version' => '1.1', // Enable HTTP/1.1 for redirect responses
            ]);

            $statusCode = $response->getStatusCode();
            $redirectUrl = $response->getInfo('redirect_url');

            if ($statusCode >= 300 && $statusCode < 400) {
                $this->redirects[] = $redirectUrl;
                $url = $redirectUrl;
            } else {
                try {
                    $content = $response->getContent();

                    // Check if the response content contains a specific keyword
                    $this->proccessKeywords($formKeywords,$content);
                } catch (\Exception $e){
                    error_log($e->getMessage());
                    $this->errors[] = $e->getMessage();
                }

                break;
            }
        }
        return $statusCode;
    }

}
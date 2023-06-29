<?php

namespace App\Tests\Controller;

use App\Entity\LinkCheck\LinkCheckHistory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LinkCheckTest extends WebTestCase
{
    /**
     * Test if accessable
     * @return void
     */
    public function testLinkCheckUrl(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/link/check');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'URL Check');
    }

    /**
     * Test if accessable
     * @return void
     */
    public function testLinkHistoryUrl(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/link/history');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'URL History');
    }

    /**
     * Test a LinkCheckHistoryType form submition and response
     * @return void
     */
    public function testFormSubmission()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/link/check');

        $desiredUrl = 'https://google.com';
        $desiredMaxRedirects = 5;
        $form = $crawler->selectButton('Check URL')->form();
        $form['link_check_history[url]'] = $desiredUrl;
        $form['link_check_history[max_redirects]'] = $desiredMaxRedirects;

        // Submit the form
        $client->submit($form);

        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $linkCheckHistoryRepository = $entityManager->getRepository(LinkCheckHistory::class);
        $linkCheckHistory = $linkCheckHistoryRepository->findOneBy(['url' => $desiredUrl]);

        $this->assertNotNull($linkCheckHistory);
        $this->assertEquals($desiredUrl, $linkCheckHistory->getUrl());
    }

}

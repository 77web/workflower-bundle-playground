<?php


namespace App\Tests\Functional\Controller\PullRequestControllerTest;


use App\Entity\PullRequest;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class CreateTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtureFiles([]);
    }

    public function test()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/create-pr');
        $this->assertTrue($client->getResponse()->isOk());
        $form = $crawler->selectButton('Create PullRequest')->form();
        $form['pull_request[title]'] = 'dummy-title';
        $client->submit($form);

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());

        $em = $client->getContainer()->get('doctrine')->getManager();
        $pr1 = $em->find(PullRequest::class, 1);
        self::assertNotNull($pr1);
        self::assertNotNull($pr1->getWorkflow());
    }

    public function test_invalid()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/create-pr');
        $form = $crawler->selectButton('Create PullRequest')->form();
        $form['pull_request[title]'] = '';
        $client->submit($form);

        $response = $client->getResponse();
        $this->assertFalse($response->isRedirection());
    }
}

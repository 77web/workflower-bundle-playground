<?php


namespace App\Tests\Functional\Controller\PullRequestControllerTest;


use App\Entity\PullRequest;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use PHPMentors\Workflower\Workflow\Workflow;

class FixTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function setUp()
    {
        $this->loadFixtureFiles([]);

        $client = static::createClient();
        $crawler = $client->request('GET', '/create-pr');
        $this->assertTrue($client->getResponse()->isOk());
        $form = $crawler->selectButton('Create PullRequest')->form();
        $form['pull_request[title]'] = 'dummy-title';
        $client->submit($form);
        $crawler = $client->request('GET', '/pull/1');
        $form = $crawler->selectButton('Review disapproved')->form();
        $client->submitForm('review_pull_request[review_disapproved]', $form->getValues());

        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    public function test()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pull/1/fix');
        $form = $crawler->selectButton('Fix PullRequest')->form();
        $form['pull_request[title]'] = 'fixed';

        $client->submit($form);
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection(), $response->getStatusCode());

        $req1 = $this->em->find(PullRequest::class, 1);
        $this->assertEquals('fixed', $req1->getTitle());
        $this->assertEquals('review-pr', $req1->getWorkflow()->getCurrentFlowObject()->getId());
    }

    public function test_notfound()
    {
        $client = static::createClient();
        $client->request('POST', '/pull/99/fix');

        $response = $client->getResponse();
        $this->assertTrue($response->isNotFound(), $response->getStatusCode());
    }
}

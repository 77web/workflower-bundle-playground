<?php


namespace App\Tests\Functional\Controller\PullRequestControllerTest;


use App\Entity\PullRequest;
use Doctrine\ORM\EntityManagerInterface;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use PHPMentors\Workflower\Workflow\Workflow;

class ReviewTest extends WebTestCase
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

        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    public function test_approve()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pull/1');
        $form = $crawler->selectButton('Review approved')->form();
        $client->submitForm('review_pull_request[review_approved]', $form->getValues());

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection(), $response->getStatusCode());

        $req1 = $this->em->find(PullRequest::class, 1);
        $this->assertTrue($req1->isApproved());
        $this->assertTrue($req1->isMerged());
    }

    public function test_disapprove()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pull/1');
        $form = $crawler->selectButton('Review disapproved')->form();
        $client->submitForm('review_pull_request[review_disapproved]', $form->getValues());

        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection(), $response->getStatusCode());

        $req1 = $this->em->find(PullRequest::class, 1);
        $this->assertFalse($req1->isApproved());
    }

    public function test_notfound()
    {
        $client = static::createClient();
        $client->request('POST', '/pull_req/99/review');

        $response = $client->getResponse();
        $this->assertTrue($response->isNotFound(), $response->getStatusCode());
    }
}

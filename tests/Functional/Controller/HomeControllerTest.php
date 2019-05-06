<?php


namespace App\Tests\Functional\Controller;


use Liip\FunctionalTestBundle\Test\WebTestCase;
use PHPMentors\Workflower\Workflow\Workflow;

class HomeControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtureFiles([
            __DIR__.'/../Resources/fixtures/home_index.yaml',
        ]);

        $container = $this->getContainer();
        $dummySerializedWorkflow = $container->get('phpmentors_workflower.base64_php_workflow_serializer')->serialize(new Workflow('dummy', 'dummy'));
        $conn = $container->get('doctrine')->getConnection();
        $conn->query(sprintf('update pull_request set serialized_workflow = "%s"', $dummySerializedWorkflow));
    }

    public function test_index()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isOk());
        $this->assertEquals(2, $crawler->filter('#pull-req-list li')->count());
    }

    public function test_show()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pull/1');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk(), $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('h2:contains("req1")')->count());
    }

    public function test_show_notfound()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/pull/99');

        $response = $client->getResponse();
        $this->assertTrue($response->isNotFound(), $response->getContent());
    }
}

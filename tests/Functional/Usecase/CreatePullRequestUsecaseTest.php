<?php


namespace App\Tests\Functional\Usecase;


use App\Entity\PullRequest;
use App\Usecase\CreatePullRequestUsecase;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class CreatePullRequestUsecaseTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtureFiles([]);
    }

    public function test()
    {
        $container = $this->getContainer();
        $usecase = $container->get(CreatePullRequestUsecase::class);
        $pullRequest = new PullRequest();
        $pullRequest->setTitle('dummy');

        $usecase->run($pullRequest);

        $em = $container->get('doctrine')->getManager();
        $em->clear();
        $req1 = $em->find(PullRequest::class, 1);
        self::assertNotNull($req1);
        self::assertNotNull($req1->getWorkflow());
    }
}

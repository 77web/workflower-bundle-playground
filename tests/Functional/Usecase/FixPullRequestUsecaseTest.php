<?php


namespace App\Tests\Functional\Usecase;


use App\Entity\PullRequest;
use App\Usecase\CreatePullRequestUsecase;
use App\Usecase\FixPullRequestUsecase;
use App\Usecase\ReviewPullRequestUsecase;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class FixPullRequestUsecaseTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtureFiles([]);
    }

    public function test()
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine')->getManager();

        $pullRequest = new PullRequest();
        $pullRequest->setTitle('dummy');

        $createUsecase = $container->get(CreatePullRequestUsecase::class);
        $createUsecase->run($pullRequest);

        $reviewUsecase = $container->get(ReviewPullRequestUsecase::class);
        $reviewUsecase->run($pullRequest);
        $req1 = $em->find(PullRequest::class, 1);
        self::assertNotNull($req1);
        self::assertEquals('fix-pr', $req1->getWorkflow()->getCurrentFlowObject()->getId(), 'go to fix-pr');


        $usecase = $container->get(FixPullRequestUsecase::class);
        $usecase->run($pullRequest);

        $em->clear();
        $req1 = $em->find(PullRequest::class, 1);
        self::assertNotNull($req1);
        self::assertEquals('review-pr', $req1->getWorkflow()->getCurrentFlowObject()->getId(), 'taken back to review-pr');
    }
}

<?php


namespace App\Tests\Functional\Usecase;


use App\Entity\PullRequest;
use App\Usecase\CreatePullRequestUsecase;
use App\Usecase\ReviewPullRequestUsecase;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class ReviewPullRequestUsecaseTest extends WebTestCase
{
    public function setUp()
    {
        $this->loadFixtureFiles([]);
    }

    public function test_not_approved()
    {
        $container = $this->getContainer();
        $pullRequest = new PullRequest();
        $pullRequest->setTitle('dummy');
        $pullRequest->setApproved(false);

        $createUsecase = $container->get(CreatePullRequestUsecase::class);
        $createUsecase->run($pullRequest);

        $reviewUsecase = $container->get(ReviewPullRequestUsecase::class);
        $reviewUsecase->run($pullRequest);

        $em = $container->get('doctrine')->getManager();
        $em->clear();
        /** @var PullRequest $req1 */
        $req1 = $em->find(PullRequest::class, 1);
        self::assertNotNull($req1);
        self::assertEquals('fix-pr', $req1->getWorkflow()->getCurrentFlowObject()->getId());
    }

    public function test_approved()
    {
        $container = $this->getContainer();
        $pullRequest = new PullRequest();
        $pullRequest->setTitle('dummy');
        $pullRequest->setApproved(true);

        $createUsecase = $container->get(CreatePullRequestUsecase::class);
        $createUsecase->run($pullRequest);

        $reviewUsecase = $container->get(ReviewPullRequestUsecase::class);
        $reviewUsecase->run($pullRequest);

        $em = $container->get('doctrine')->getManager();
        $em->clear();
        /** @var PullRequest $req1 */
        $req1 = $em->find(PullRequest::class, 1);
        self::assertNotNull($req1);
        self::assertTrue($req1->isMerged());
    }
}

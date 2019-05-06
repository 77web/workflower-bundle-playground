<?php


namespace App\OperationRunner;


use App\Entity\PullRequest;
use App\Participant\Reviewer;
use Doctrine\ORM\EntityManagerInterface;
use PHPMentors\Workflower\Workflow\Operation\OperationalInterface;
use PHPMentors\Workflower\Workflow\Operation\OperationRunnerInterface;
use PHPMentors\Workflower\Workflow\Participant\ParticipantInterface;
use PHPMentors\Workflower\Workflow\Workflow;
use Psr\Log\LoggerInterface;

class MergePullRequestOperationRunner implements OperationRunnerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }


    public function provideParticipant(OperationalInterface $operational, Workflow $workflow)
    {
        return new Reviewer();
    }

    public function run(OperationalInterface $operational, Workflow $workflow)
    {
        $processData = $workflow->getProcessData();
        /** @var PullRequest $pullRequest */
        $pullRequest = $processData['data'];
        $pullRequest->setMerged(true);
        $this->em->persist($pullRequest);
        $this->em->flush();

        $message = sprintf('#%d merged!', $processData['id']);
        $this->logger->info($message);
    }

}

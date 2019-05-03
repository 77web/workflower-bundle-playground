<?php


namespace App\OperationRunner;


use App\Participant\Reviewer;
use PHPMentors\Workflower\Workflow\Operation\OperationalInterface;
use PHPMentors\Workflower\Workflow\Operation\OperationRunnerInterface;
use PHPMentors\Workflower\Workflow\Participant\ParticipantInterface;
use PHPMentors\Workflower\Workflow\Workflow;
use Psr\Log\LoggerInterface;

class MergePullRequestOperationRunner implements OperationRunnerInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function provideParticipant(OperationalInterface $operational, Workflow $workflow)
    {
        return new Reviewer();
    }

    public function run(OperationalInterface $operational, Workflow $workflow)
    {
        $processData = $workflow->getProcessData();
        $message = sprintf('#%d merged!', $processData['id']);

        $this->logger->info($message);
    }

}

<?php


namespace App\Usecase;


use App\Entity\PullRequest;
use App\Participant\Reviewer;
use Doctrine\ORM\EntityManagerInterface;
use PHPMentors\Workflower\Process\Process;
use PHPMentors\Workflower\Process\ProcessAwareInterface;
use PHPMentors\Workflower\Process\WorkItemContext;

class ReviewPullRequestUsecase implements ProcessAwareInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Process
     */
    private $process;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    public function run(PullRequest $pullRequest)
    {
        $reviewer = new Reviewer();

        $workItem = new WorkItemContext($reviewer);
        $workItem->setProcessContext($pullRequest);
        $workItem->setActivityId($pullRequest->getWorkflow()->getCurrentFlowObject()->getId());
        $this->process->allocateWorkItem($workItem);
        $this->process->startWorkItem($workItem);
        $this->process->completeWorkItem($workItem);

        $this->em->persist($pullRequest);
        $this->em->flush();
    }
}

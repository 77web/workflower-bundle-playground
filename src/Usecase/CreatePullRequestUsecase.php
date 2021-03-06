<?php


namespace App\Usecase;


use App\Entity\PullRequest;
use App\Participant\Dev;
use Doctrine\ORM\EntityManagerInterface;
use PHPMentors\Workflower\Process\EventContext;
use PHPMentors\Workflower\Process\Process;
use PHPMentors\Workflower\Process\ProcessAwareInterface;
use PHPMentors\Workflower\Process\WorkflowAwareInterface;
use PHPMentors\Workflower\Process\WorkItemContext;
use PHPMentors\Workflower\Workflow\Event\StartEvent;
use PHPMentors\Workflower\Workflow\Workflow;
use PHPMentors\Workflower\Workflow\WorkflowRepositoryInterface;

class CreatePullRequestUsecase implements ProcessAwareInterface
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
        $dev = new Dev();

        // start
        $eventContext = new EventContext('StartEvent_1', $pullRequest);
        // $pullRequest->setWorkflow() will be called automatically
        $this->process->start($eventContext);

        // "create-pr"
        $workItem = new WorkItemContext($dev);
        $workItem->setProcessContext($pullRequest);
        $workItem->setActivityId($pullRequest->getWorkflow()->getCurrentFlowObject()->getId());
        $this->process->allocateWorkItem($workItem);
        $this->process->startWorkItem($workItem);
        $this->process->completeWorkItem($workItem);

        $this->em->persist($pullRequest);
        $this->em->flush();
    }
}

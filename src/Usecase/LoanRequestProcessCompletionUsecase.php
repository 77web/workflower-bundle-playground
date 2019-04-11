<?php


namespace App\Usecase;

use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\Workflower\Process\Process;
use PHPMentors\Workflower\Process\ProcessAwareInterface;
use PHPMentors\Workflower\Process\WorkItemContextInterface;

class LoanRequestProcessCompletionUsecase implements ProcessAwareInterface
{
    /**
     * @var Process
     */
    private $process;

    /**
     * {@inheritdoc}
     */
    public function setProcess(Process $process)
    {
        $this->process = $process;
    }

    // ...

    /**
     * {@inheritdoc}
     */
    public function run(EntityInterface $entity)
    {
        assert($entity instanceof WorkItemContextInterface);

        $this->process->completeWorkItem($entity);
    }
}

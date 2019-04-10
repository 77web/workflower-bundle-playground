<?php


namespace App\Workflow;


use PHPMentors\Workflower\Persistence\WorkflowSerializableInterface;
use PHPMentors\Workflower\Process\ProcessContextInterface;
use PHPMentors\Workflower\Workflow\Workflow;

class LoanRequestProcess implements ProcessContextInterface, WorkflowSerializableInterface
{
    /**
     * @var Workflow
     */
    private $workflow;

    /**
     * @var string
     *
     * @Column(type="blob", name="serialized_workflow")
     */
    private $serializedWorkflow;

    /**
     * @var string
     */
    private $foo = 'test_foo';

    /**
     * @var string
     */
    private $bar = 'test_bar';

    /**
     * {@inheritdoc}
     */
    public function getProcessData(): array
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function setWorkflow(Workflow $workflow)
    {
        $this->workflow = $workflow;
    }

    /**
     * {@inheritdoc}
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * {@inheritdoc}
     */
    public function setSerializedWorkflow($workflow)
    {
        $this->serializedWorkflow = $workflow;
    }

    /**
     * {@inheritdoc}
     */
    public function getSerializedWorkflow()
    {
        if (is_resource($this->serializedWorkflow)) {
            return stream_get_contents($this->serializedWorkflow, -1, 0);
        } else {
            return $this->serializedWorkflow;
        }
    }
}

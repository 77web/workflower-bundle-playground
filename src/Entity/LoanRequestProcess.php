<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHPMentors\Workflower\Persistence\WorkflowSerializableInterface;
use PHPMentors\Workflower\Process\ProcessContextInterface;
use PHPMentors\Workflower\Workflow\Workflow;

/**
 * Class LoanRequestProcess
 *
 * @ORM\Entity()
 * @ORM\Table()
 * @package App\Workflow
 */
class LoanRequestProcess implements ProcessContextInterface, WorkflowSerializableInterface
{
    /**
     * @var integer
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Workflow
     */
    private $workflow;

    /**
     * @var string
     *
     * @ORM\Column(type="blob", name="serialized_workflow")
     */
    private $serializedWorkflow;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcessData(): array
    {
        return [
            'name' => $this->name,
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

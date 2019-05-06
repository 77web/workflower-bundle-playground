<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PHPMentors\Workflower\Persistence\WorkflowSerializableInterface;
use PHPMentors\Workflower\Process\ProcessContextInterface;
use PHPMentors\Workflower\Workflow\Workflow;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PullRequest
 *
 * @ORM\Entity(repositoryClass="App\Repository\PullRequestRepository")
 * @ORM\Table
 * @package App\Entity
 */
class PullRequest implements ProcessContextInterface, WorkflowSerializableInterface
{
    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $approved = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $merged = false;

    /**
     * @var Workflow
     */
    private $workflow;

    /**
     * @var string
     *
     * @ORM\Column(type="blob")
     */
    private $serializedWorkflow;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return PullRequest
     */
    public function setTitle(string $title): PullRequest
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMerged(): bool
    {
        return $this->merged;
    }

    /**
     * @param bool $merged
     * @return PullRequest
     */
    public function setMerged(bool $merged): PullRequest
    {
        $this->merged = $merged;

        return $this;
    }

    public function getProcessData()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'approved' => $this->approved,
        ];
    }

    public function getWorkflow()
    {
        return $this->workflow;
    }

    public function setWorkflow(Workflow $workflow)
    {
        $this->workflow = $workflow;
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

    /**
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->approved;
    }

    /**
     * @param bool $approved
     * @return PullRequest
     */
    public function setApproved(bool $approved): PullRequest
    {
        $this->approved = $approved;

        return $this;
    }
}

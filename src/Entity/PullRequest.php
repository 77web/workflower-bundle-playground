<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class PullRequest
 *
 * @ORM\Entity()
 * @package App\Entity
 */
class PullRequest
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
     */
    private $title;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $merged = false;

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
    public function getTitle(): string
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
}

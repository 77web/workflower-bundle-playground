<?php

namespace App\Controller;

use App\Repository\PullRequestRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="home_")
 */
class HomeController extends AbstractController
{
    /**
     * @var PullRequestRepository
     */
    private $repository;

    /**
     * @param PullRequestRepository $repository
     */
    public function __construct(PullRequestRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="index")
     * @Template()
     */
    public function index()
    {
        return [
            'pull_reqs' => $this->repository->findBy([], ['id' => 'desc']),
        ];
    }
}

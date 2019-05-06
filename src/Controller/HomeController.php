<?php

namespace App\Controller;

use App\Entity\PullRequest;
use App\Repository\PullRequestRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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

    /**
     * @Route("/pull/{id}", name="pull_req_show")
     * @ParamConverter(name="pullRequest", class="App\Entity\PullRequest")
     * @Template()
     * @param PullRequest $pullRequest
     * @return array
     */
    public function show(PullRequest $pullRequest)
    {
        return [
            'pull_req' => $pullRequest,
        ];
    }
}

<?php


namespace App\Controller;


use App\Entity\PullRequest;
use App\Form\PullRequestType;
use App\Form\ReviewPullRequestType;
use App\Usecase\CreatePullRequestUsecase;
use App\Usecase\FixPullRequestUsecase;
use App\Usecase\ReviewPullRequestUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class PullRequestController
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var CreatePullRequestUsecase
     */
    private $createPullRequestUsecase;

    /**
     * @var ReviewPullRequestUsecase
     */
    private $reviewPullRequestUsecase;

    /**
     * @var FixPullRequestUsecase
     */
    private $fixPullRequestUsecase;

    /**
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param CreatePullRequestUsecase $createPullRequestUsecase
     * @param ReviewPullRequestUsecase $reviewPullRequestUsecase
     * @param FixPullRequestUsecase $fixPullRequestUsecase
     */
    public function __construct(FormFactoryInterface $formFactory, RouterInterface $router, SessionInterface $session, CreatePullRequestUsecase $createPullRequestUsecase, ReviewPullRequestUsecase $reviewPullRequestUsecase, FixPullRequestUsecase $fixPullRequestUsecase)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->session = $session;
        $this->createPullRequestUsecase = $createPullRequestUsecase;
        $this->reviewPullRequestUsecase = $reviewPullRequestUsecase;
        $this->fixPullRequestUsecase = $fixPullRequestUsecase;
    }

    /**
     * @Route("/create-pr", name="create_pr")
     * @Template()
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->formFactory->create(PullRequestType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pullRequest = $form->getData();

            $this->createPullRequestUsecase->run($pullRequest);

            return new RedirectResponse('/');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * @Method("POST")
     * @Route("/pull/{id}/review", name="pull_req_review")
     * @ParamConverter(name="pullRequest", class="App\Entity\PullRequest")
     * @param Request $request
     * @param PullRequest $pullRequest
     * @return RedirectResponse
     */
    public function reviewAction(Request $request, PullRequest $pullRequest)
    {
        $form = $this->formFactory->create(ReviewPullRequestType::class, $pullRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->reviewPullRequestUsecase->run($pullRequest);

            $this->session->getFlashbag()->set('notice', 'Review sent.');
        }

        return new RedirectResponse($this->router->generate("home_pull_req_show", ['id' => $pullRequest->getId()]));
    }

    /**
     * @Route("/pull/{id}/fix", name="pull_req_fix")
     * @ParamConverter(name="pullRequest", class="App\Entity\PullRequest")
     * @Template
     * @param Request $request
     * @param PullRequest $pullRequest
     * @return array|RedirectResponse
     */
    public function fixAction(Request $request, PullRequest $pullRequest)
    {
        $form = $this->formFactory->create(PullRequestType::class, $pullRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->fixPullRequestUsecase->run($pullRequest);

            return new RedirectResponse($this->router->generate("home_pull_req_show", ['id' => $pullRequest->getId()]));
        }

        return [
            'pull_req' => $pullRequest,
            'form' => $form->createView(),
        ];
    }
}

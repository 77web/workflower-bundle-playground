<?php


namespace App\Controller;


use App\Form\PullRequestType;
use App\Usecase\CreatePullRequestUsecase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PullRequestController
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var CreatePullRequestUsecase
     */
    private $createPullRequestUsecase;

    /**
     * @param FormFactoryInterface $formFactory
     * @param CreatePullRequestUsecase $createPullRequestUsecase
     */
    public function __construct(FormFactoryInterface $formFactory, CreatePullRequestUsecase $createPullRequestUsecase)
    {
        $this->formFactory = $formFactory;
        $this->createPullRequestUsecase = $createPullRequestUsecase;
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
}

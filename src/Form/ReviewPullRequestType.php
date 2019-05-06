<?php


namespace App\Form;


use App\Entity\PullRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewPullRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('review_approved', SubmitType::class)
            ->add('review_disapproved', SubmitType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
                $approved = $event->getForm()->get('review_approved')->isClicked();
                $event->getData()->setApproved($approved);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => PullRequest::class,
            ])
        ;
    }

}

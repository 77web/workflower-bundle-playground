<?php


namespace App\Tests\Form;


use App\Entity\PullRequest;
use App\Form\ReviewPullRequestType;
use Symfony\Component\Form\Test\TypeTestCase;

class ReviewPullRequestTypeTest extends TypeTestCase
{
    public function test_approved()
    {
        $pullReq = new PullRequest();
        $form = $this->factory->create(ReviewPullRequestType::class, $pullReq);
        $form->submit([
            'review_approved' => '1',
        ]);

        $this->assertTrue($form->isValid());
        $this->assertTrue($pullReq->isApproved());
    }

    public function test_disapproved()
    {
        $pullReq = new PullRequest();
        $form = $this->factory->create(ReviewPullRequestType::class, $pullReq);
        $form->submit([
            'review_disapproved' => '1',
        ]);

        $this->assertTrue($form->isValid());
        $this->assertFalse($pullReq->isApproved());
    }
}

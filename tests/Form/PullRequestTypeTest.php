<?php


namespace App\Tests\Form;


use App\Entity\PullRequest;
use App\Form\PullRequestType;
use Symfony\Component\Form\Test\TypeTestCase;

class PullRequestTypeTest extends TypeTestCase
{
    public function test()
    {
        $form = $this->factory->create(PullRequestType::class);
        $form->submit([
            'title' => 'dummy',
        ]);

        $this->assertTrue($form->isValid());

        $obj = $form->getData();
        $this->assertInstanceOf(PullRequest::class, $obj);
        $this->assertEquals('dummy', $obj->getTitle());
    }
}

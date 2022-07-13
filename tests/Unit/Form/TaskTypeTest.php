<?php 
namespace App\Tests\Unit\Form;

use App\Form\TaskType;
use App\Entity\Task;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidDataTaskForm(): void
    {
        $formData = [
            'title' => 'test',
            'content' => 'test2',
        ];

        $model = new Task();
        $form = $this->factory->create(TaskType::class, $model);

        $expected = new Task();
        $expected->setTitle($formData['title']);
        $expected->setContent($formData['content']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected->getTitle(), $model->getTitle());

        $this->assertEquals($expected->getContent(), $model->getContent());
    }

    public function testCustomFormViewTaskForm(): void
    {
        $formData = new Task();

        $formData->setTitle('test du jour');
        $formData->setContent('content test');

        $view = $this->factory->create(TaskType::class, $formData)->createView();

        $this->assertSame('test du jour', $view->children['title']->vars['data']);
        
        $this->assertSame('content test', $view->children['content']->vars['data']);
    }
}
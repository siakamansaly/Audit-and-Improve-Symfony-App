<?php 
namespace App\Tests\Unit\Form;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class UserTypeTest extends TypeTestCase
{
    /**
     * @return array<mixed>
     */
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidDataUserForm(): void
    {
        $formData = [
            'username' => 'toto',
            'password' => ['first' => 'password', 'second' => 'password'],
            'email' => 'toto@toto.fr',
            'roles' => ['ROLE_ADMIN'],
        ];

        $model = new User();
        $form = $this->factory->create(UserType::class, $model);

        $expected = new User();
        $expected->setUsername($formData['username']);
        $expected->setPassword($formData['password']['first']);
        $expected->setEmail($formData['email']);
        $expected->setRoles($formData['roles']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected->getUsername(), $model->getUsername());

        $this->assertEquals($expected->getPassword(), $model->getPassword());

        $this->assertEquals($expected->getEmail(), $model->getEmail());

        $this->assertEquals($expected->getRoles(), $model->getRoles());
    }

    public function testCustomFormViewUserForm(): void
    {
        $Data = [
            'username' => 'toto',
            'password' => ['first' => 'password', 'second' => 'password'],
            'email' => 'toto@toto.fr',
            'roles' => ['ROLE_USER'],
        ];

        $formData = new User();

        $formData->setUsername($Data['username']);
        $formData->setPassword($Data['password']['first']);
        $formData->setEmail($Data['email']);
        $formData->setRoles($Data['roles']);

        $view = $this->factory->create(UserType::class, $formData)->createView();

        $this->assertSame('toto', $view->children['username']->vars['data']);
        
        $this->assertSame('toto@toto.fr', $view->children['email']->vars['data']);

        $this->assertSame('password', $view->children['password']->vars['data']);

        $this->assertSame('ROLE_USER', $view->children['roles']->vars['data'][0]);
    }
}
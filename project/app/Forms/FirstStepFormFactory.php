<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model;
use App\Model\UserFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;
use Nette\Http\Session;

final class FirstStepFormFactory
{
	use Nette\SmartObject;

	private FormFactory $factory;

	private Model\UserFacade $userFacade;

    private Session $session;

    private Explorer $database;


    public function __construct(
        FormFactory $factory,
        UserFacade $userFacade,
        Session $session,
        Explorer $database
    )
	{
		$this->factory = $factory;
		$this->userFacade = $userFacade;
        $this->session = $session;
        $this->database = $database;
	}


	public function firstStep(callable $onSuccess): Form
	{
		$form = $this->factory->create();
		$form->addText('username', 'Pick a username:')
			->setRequired('Please pick a username.');

		$form->addEmail('email', 'Your e-mail:')
			->setRequired('Please enter your e-mail.');

		$form->addPassword('password', 'Create a password:')
			->setOption('description', sprintf('at least %d characters', $this->userFacade::PasswordMinLength))
			->setRequired('Please create a password.')
			->addRule($form::MIN_LENGTH, null, $this->userFacade::PasswordMinLength);

		$form->addSubmit('send', 'Next step');

        $form->onValidate[] = [$this, 'validate'];

		$form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {

            $section = $this->session->getSection('singUp');
            $section->set('username', $data->username);
            $section->set('email', $data->email);
            $section->set('password', $data->password);

            $onSuccess();
		};

		return $form;
	}

    public function validate(Form $form, \stdClass $data)
    {
        $isUsernameExist = $this->database->query('SELECT * FROM users WHERE', [
            'username' => $data->username,
        ])->fetch();

        if ($isUsernameExist) {
            $form['username']->addError('Username is already taken.');
        }

        $isEmailExist = $this->database->query('SELECT * FROM users WHERE', [
           'email' => $data->email,
        ])->fetch();

        if ($isEmailExist) {
            $form['email']->addError('Email is already taken.');
        }
    }
}

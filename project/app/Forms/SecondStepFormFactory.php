<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model;
use Nette;
use Nette\Application\UI\Form;
use Nette\Http\Session;


final class SecondStepFormFactory
{
	use Nette\SmartObject;

	private FormFactory $factory;

	private Model\UserFacade $userFacade;

    private Session $session;

	public function __construct(
        FormFactory $factory,
        Model\UserFacade $userFacade,
        Session $session
    )
	{
		$this->factory = $factory;
		$this->userFacade = $userFacade;
        $this->session = $session;
	}


	public function secondStep(callable $onSuccess): Form
	{
		$form = $this->factory->create();

        $form->addInteger('age', 'Age')
            ->addRule($form::RANGE, 'at least %d and no more than %d', [18, 80])
            ->setRequired('Please pick a username.');

        $form->addSelect('gender', 'Gender', [1 => 'male', 2 => 'female'])
            ->setRequired('Please pick a Gender.');

        $form->addSelect('genderSearch', 'Gender Search', [1 => 'male', 2 => 'female'])
            ->setRequired('Please pick a Gender Search.');

        $form->addTextArea('profileInfo', 'Profile info')
            ->addRule($form::MAX_LENGTH, 'Max length is 128 symbols', [128]);

		$form->addSubmit('send', 'Next step');

		$form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {

            $section = $this->session->getSection('singUp');

            $section->set('age', $data->age);
            $section->set('gender', $data->gender);
            $section->set('genderSearch', $data->genderSearch);
            $section->set('profileInfo', $data->profileInfo);

            $onSuccess();
		};

		return $form;
	}
}

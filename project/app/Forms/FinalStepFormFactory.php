<?php

declare(strict_types=1);

namespace App\Forms;

use App\Model\CityFacade;
use App\Model\CountryFacade;
use App\Model\UserFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Http\Session;

final class FinalStepFormFactory
{
	use Nette\SmartObject;

	private FormFactory $factory;

	private CountryFacade $countryFacade;

    private CityFacade $cityFacade;

    private UserFacade $userFacade;

    private Session $session;

	public function __construct(
        FormFactory      $factory,
        CountryFacade    $countryFacade,
        CityFacade       $cityFacade,
        UserFacade       $userFacade,
        Session          $session
    )
	{
		$this->factory = $factory;
		$this->countryFacade = $countryFacade;
        $this->cityFacade = $cityFacade;
        $this->userFacade = $userFacade;
        $this->session = $session;
	}


	public function finalStep(callable $onSuccess): Form
	{
		$form = $this->factory->create();

        $country = $form->addSelect('country', 'Country', $this->countryFacade->getCounties())
            ->setOption('description', 'Please select country and city on the next step');


        $city = $form->addSelect('city', 'City:');

        //todo: implement via Ajax request
        $form->onAnchor[] = fn() =>
            $city->setItems($country->getValue()
            ? $this->cityFacade->getCities($country->getValue())
            : []);

        $form->addSubmit('send', 'Next step');

		$form->onSuccess[] = function (Form $form, \stdClass $data) use ($onSuccess): void {
            $section = $this->session->getSection('singUp');
            $section->set('countryId', $data->country);
            $section->set('cityId', $data->city);

            $this->userFacade->add($section);

			$onSuccess();
		};

		return $form;
	}
}

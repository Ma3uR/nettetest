<?php

declare(strict_types=1);

namespace App\Module\Admin\Presenters;

use App\Forms;
use Nette;
use Nette\Application\UI\Form;


final class SignPresenter extends Nette\Application\UI\Presenter
{
	/** @persistent */
	public string $backlink = '';

	private Forms\SignInFormFactory $signInFactory;

	private Forms\FirstStepFormFactory $firstStep;

	private Forms\SecondStepFormFactory $secondStep;

    private Forms\FinalStepFormFactory $finalStep;

    private Nette\Database\Explorer $database;

	public function __construct(
        Forms\SignInFormFactory    $signInFactory,
        Forms\FirstStepFormFactory $firstStep,
        Forms\SecondStepFormFactory $secondStep,
        Forms\FinalStepFormFactory $finalStep,
        Nette\Database\Explorer $database
    )
	{
		$this->signInFactory = $signInFactory;
		$this->firstStep = $firstStep;
		$this->secondStep = $secondStep;
        $this->finalStep = $finalStep;
        $this->database = $database;
	}


	/**
	 * Sign-in form factory.
	 */
	protected function createComponentSignInForm(): Form
	{
		return $this->signInFactory->create(function (): void {
			$this->restoreRequest($this->backlink);
			$this->redirect('Profile:show');
		});
	}


	/**
	 * Sign-up form factory.
	 */
	protected function createComponentFirstStepForm(): Form
	{
		return $this->firstStep->firstStep(function (): void {
			$this->redirect('Sign:secondStep');
		});
	}

    protected function createComponentSecondStepForm(): Form
    {
        return $this->secondStep->secondStep(function (): void {
            $this->redirect('Sign:finalStep');
        });
    }

    protected function createComponentFinalStepForm(): Form
    {
        return $this->finalStep->finalStep(function (): void {
            $this->redirect('Dashboard:');
        });
    }


	public function actionOut(): void
	{
		$this->getUser()->logout();
	}
}

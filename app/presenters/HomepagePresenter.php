<?php

namespace App\Presenters;

use Nette;
use App\Model;
use App\Forms;


class HomepagePresenter extends BasePresenter
{

	/** @var Forms\NewInsuranceFormFactory @inject */
	public $newInsuranceFormFactory;

	public function __construct(Nette\Database\Context $database) 
    {
		parent::__construct($database);
    }

	public function renderDefault()
	{
		$this->template->insurances = $this->database->table("insurance_contracts");
	}

	
	protected function createComponentNewInsuranceForm()
	{
		$form =  $this->newInsuranceFormFactory->create(function () {});
		return $form;
	}

}

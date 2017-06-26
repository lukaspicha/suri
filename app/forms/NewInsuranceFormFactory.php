<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use App\Model;


class NewInsuranceFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	private $database;

	private $insuranceModel;



	public function __construct(FormFactory $factory, Nette\Database\Context $db, Model\InsuranceModel $insModel)
	{
		$this->factory = $factory;
		$this->database = $db;
		$this->insuranceModel = $insModel;
	}


	/**
	 * @return Form
	 * Vytvoření formuláře pro novou pojistku
	 */
	public function create(callable $onSuccess)
	{
		$form = $this->factory->create();
		
		$form->addText("name", "Jméno a příjmení:")
			->setRequired("Prosím, zadejte Vaše jméno a příjmení.")
			->setAttribute("placeholder", "jméno a příjmení");
		$form->addText('zip_code', "PSČ:")
			->setRequired("Prosím, zadejte PSČ.")
			->addRule(Form::PATTERN, 'PSČ musí mít 5 číslic', '([0-9]\s*){5}')
			->setAttribute('placeholder', "poštovní směrovací číslo");

		$form->addEmail('mail', "E-mail")
			->setRequired("Prosím, zadejte Vaší e-mailovou adresu.")
			->setAttribute('placeholder', "e-mail");

		$form->addText("bday_date", "Datum narození:")
			->setRequired("Prosím, zadejte Váš datum narození.")
    		->setAttribute("placeholder", "dd.mm.rrrr")
    		->addRule(Form::PATTERN, "Datum musí být ve formátu dd.mm.rrrr", "(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(19|20)\d\d");

    	$carTypesRS = $this->database->table("car_types");
    	$types = [];
    	foreach ($carTypesRS as $row) {
    		$types[$row->id] = $row->name;
    	}
    	$form->addSelect("car_types", "Typ vozidla:", $types)
    		->setRequired("Prosím, vyberte typ vozidla")
    		->setPrompt("Typ vozidla");

    	$form->addText("engine_capacity", "Objem válců (cm3):")
    		->setOption("id", "engine")
    		->setAttribute("placeholder", "objem válců")
    		->setRequired("Prosím, zadejte objem válců vozidla.")
    		->addRule(Form::INTEGER, 'Objem musí být celé kladné číslo.')
    		->addRule(Form::RANGE, 'Objem musí být celé kladné číslo (1 - 100000 cm3).', [1, 100000]);

    	$form->addText("engine_power", "Výkon vozidla (kW):")
    		->setAttribute("placeholder", "výkon vozidla")
    		->setRequired("Prosím, zadejte  výkon vozidla.")
    		->addRule(Form::INTEGER, 'Výkon musí být celé kladné číslo.')
    		->addRule(Form::RANGE, 'Výkon musí být celé kladné číslo (1 - 1000 kW).', [1, 1000]);

    	$form->addText("weight", "Hmotnost vozidla (kg):")
    		->setAttribute("placeholder", "hmotnost vozidla")
    		->setRequired("Prosím, zadejte  hmotnost vozidla.")
    		->addRule(Form::INTEGER, 'Hmotnost musí být celé kladné číslo.')
    		->addRule(Form::RANGE, 'Hmotnost musí být celé kladné číslo (1 - 20000 kg).', [1, 20000]);
		$form->addSubmit("send", "Odeslat")
			->onClick[] = [$this, "send"];

		$form->onValidate[] = [$this, "validate"];
		return $form;
	}

	/**
	* Před odesláním se validuje psč a věk klienta
	* Pokud nejsou splněy požadavky formulář se neodešle
	*/
	public function validate($form)
	{
		$values = $form->getValues();

		if($this->getAge($values->bday_date) < 18)
			$form->addError("Musíte být starší 18-ti let.");

		if(!$this->isZipCodeValid($form->parent->context->parameters["wwwDir"]."/files/psc-mest-cr.csv", $values->zip_code))
			$form->addError("Neplatné PSČ.");
	}

	public function send($button)
	{
		$form = $button->getForm();
		$values = $form->getValues();

		//přidání atributu věk
		$values["age"] = $this->getAge($values->bday_date);

		//Přidání klienta do DB
		try
		{
			$client = $this->database->table("clients")->insert([
				"name" => $values->name, 
            	"zip_code" => $values->zip_code, 
            	"mail" => $values->mail, 
            	"bday_date" => date_create_from_format('d.m.Y', $values->bday_date)
        	]);	
		}
		catch(\PDOEXception $ex)
        {
        	$form->parent->flashMessage($ex->getMessage(), "ui red message");
			$form->parent->redirect("this");	
        }
		// Přidání auta do DB
        try
        {
			$car = $this->database->table("cars")->insert([
				"car_types_id" => $values->car_types, 
            	"engine_capacity" => $values->engine_capacity,
           	 	"engine_power" => $values->engine_power, 
            	"weight" => $values->weight
        	]);
        }
        catch(\PDOEXception $ex)
        {
        	$form->parent->flashMessage($ex->getMessage(), "ui red message");
			$form->parent->redirect("this");	
        }
            
        $basePrice = 1000;

        // Přidání nové smlouvy
        try
        {
        	$this->insuranceModel->newOrder($values, $client, $car, $basePrice);
        	$form->parent->flashMessage("Návrh na smlouvu byl vytvořen.", "ui green message");
        	$form->parent->redirect("default");
        }
        catch(\PDOEXception $ex)
        {
        	$form->parent->flashMessage($ex->getMessage(), "ui red message");
			$form->parent->redirect("this");	
        }
	}

	/*
    *	Výpočet věku pojistíka (na den přesně)
    *	userBDayString - datum narození klienta (string)
    * 	Vrátí věk
	*/
	private function getAge($userBDayString)
	{
		$userBDay = date_create_from_format('d.m.Y', $userBDayString);
		$today = date_create_from_format("Y-m-d", date("Y-m-d"));
		$interval = date_diff($userBDay, $today);
		return $interval->y;
	}

	/*
	* file - csv soubor s psč 
	* zip_code - hledané psč
	* V daném csv souboru najde zadané psč
	* TRUE - psč nalezeno, FALSE - nenalezeno, chyba při práci se souborem
	*/
	private function isZipCodeValid($file, $zip_code)
	{
		$handle = fopen('nette.safe://'.$file, 'r');
		if ($handle) {
			$firstLine = fgets($handle); // první řádek jsou názvy sloupců
    		while(($line = fgets($handle)) !== false) {
        		$values = explode(";", $line);
        		if(substr($values[1], 0, 5) == $zip_code)
        			return TRUE;
    		}
    		fclose($handle);
    		return FALSE;
		} 
		else {
			dump("Soubor se nepodařilo načíst");
			return FALSE;
		} 
	}

}

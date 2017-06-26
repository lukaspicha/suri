<?php

namespace App\Presenters;

use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $database;
	
	public function __construct(Nette\Database\Context $db)
	{
		$this->database = $db;
	}
}

<?php
namespace App\Model;
use Nette;
class BaseModel 
{
	
	/**
	* @var \Nette\Database\Context
	*/
	protected $database;
	protected $tableName;
	
	public function __construct(Nette\Database\Context $database, $tableName)
	{
		$this->database = $database;
		$this->tableName = $tableName;
	}
	public function getTable() 
	{
		return $this->database->table($this->tableName);
	}
	public function query($queryString)
	{
		return $this->database->query($queryString);
	}
	public function addRow($values) 
    {
        return $this->database->table($this->tableName)->insert($values);
    }
    
    public function getRow($id)
    {
    	return $this->getTable()->get($id);
    }
    
    public function deleteRow($id) 
    {
        $this->getRow($id)->delete();
    }
}
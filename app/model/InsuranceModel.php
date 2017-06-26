<?php
namespace App\Model;
use Nette;

class InsuranceModel extends BaseModel
{
	
	public function __construct(Nette\Database\Context $database) 
    {
    	$this->tableName = "insurance_contracts";
		parent::__construct($database, $this->tableName);
    }

    /**
     *  values - hodnoty z fouláře
     *  client - pojistík, databázový řádek
     *  car - pojištěné auto, databázový řádek
     *  basePrice - základní cena pojištění
     * 
     */
    public function newOrder($values, $client, $car, $basePrice)
    {
        // uložení výpočtu pojistného včetně základní ceny
        $calculation = [
            "zip" => $this->saleForZip($values->zip_code, $basePrice, 0.1, -1),
            "twenty-six-younger" => $this->overChargeFor26Less($values->age, $basePrice, 0.2, 1),
            "thirty-five-older" => $this->saleForAge35More($values->age, $basePrice, 0.1, -1),
            "engine" => $this->overChargeForEngine($values->engine_capacity, $values->engine_power, $basePrice, 0.3, 1),
            "weight" => $this->overChargeForWeight($values->weight, $basePrice, 0.2, 1),
            "base-price" => 1000
        ];

        // Výpočet celkové ceny, k základní ceně se přičtou (odečtou) všechny parametry, nutno odečíst basePrice, která je součástí uloženého výpočtu ceny
        $totalPrice = $basePrice + array_sum(array_values($calculation)) - $calculation["base-price"];
        
        // Přidání nové smlouvy do databáze
        $this->addRow([
            "clients_id" => $client->id,
            "cars_id" => $car->id,
            "total_price" => $totalPrice,
            "calculation" => serialize($calculation),
        ]);
    }

   
    /*
    *   Platí pro všechny fce:
    *   zip_code, age, engine_capacity, engine_power, weight - jsou vstupní hodnoty
    *   basePrice - cena ze které se počítá sleva / příplatek
    *   percent - hodnota v procentech, která udáva slevu / příplatek
    *   surcharge - "1" pro příplatek, "-1" pro slevu
    *   Popis výpočtu spočítá se částka $basePrice * $percent, pokud se jedná o slevu, vynásobí se -1, pokud o příplatek vynásobí se 1
    *   jednotlivé fce vrací slevu nebo příplatek, NULL když na tenhle atribut nelze slevu uplatnit
    */

    /*
    * Sleva pro mimo pražské občany
    */
    private function saleForZip($zip_code, $basePrice, $percent, $surcharge)
    {
        if($zip_code[0] != 1)
            return ($basePrice * $percent) * $surcharge;
    }

    /*
    * Příplatek pro mladší 26 let
    */
    private function overChargeFor26Less($age, $basePrice, $percent, $surcharge)
    {
        if($age < 26)
             return ($basePrice * $percent) * $surcharge;
    }

    /*
    * Sleva pro 35+
    */
    private function saleForAge35More($age, $basePrice, $percent, $surcharge)
    {
        if($age >= 35)
            return ($basePrice * $percent) * $surcharge;
    }

    /*
    * Příplatek pro objem nad 1600 cm3 nebo výkon 100 kW
    */
    private function overChargeForEngine($engine_capacity, $engine_power, $basePrice, $percent, $surcharge)
    {
        if($engine_capacity >= 1600 || $engine_power >= 100)
            return ($basePrice * $percent) * $surcharge;
    }

    /*
    * Příplatek pro vozidla těžší 3500 kg
    */
    private function overChargeForWeight($weight, $basePrice, $percent, $surcharge)
    {
        if($weight >= 3500)
            return ($basePrice * $percent) * $surcharge;
    }

}
<?php
class Seed implements JsonSerializable
{
    private $_db;

    private $_Id;
    private $_Name;
    private $_Description;
    private $_Price;
    private $_Growtime;
    private $_Available;

    public function __construct(Database $database, $SeedId)
    {
        $this->_db = $database;

        //Check if seed exists, throw exception when not
        if(!$this->_db->exists("SELECT id FROM seed WHERE id = ?", array($SeedId)))
            throw new Exception("Could not finish Seed constructor: SeedId(".$SeedId.") does not exist");

        //Set seed id
        $this->_Id = $SeedId;

        //Load seed details
        $Seed = $this->_db->getRecord("SELECT name, description, price, growtime, available FROM seed WHERE id = ?", array($this->_Id));

        //Set seed details
        $this->_Name = $Seed["name"];
        $this->_Description = $Seed["description"];
        $this->_Price = $Seed["price"];
        $this->_Growtime = $Seed["growtime"];
        $this->_Available = $Seed["available"];
    }

    public function getId()
    {
        return $this->_Id;
    }

    public function getName()
    {
        return $this->_Name;
    }

    public function getDescription()
    {
        return $this->_Description;
    }

    public function getPrice()
    {
        return $this->_Price;
    }

    public function getGrowtime()
    {
        return $this->_Growtime;
    }

    public function getAvailable()
    {
        return $this->_Available;
    }

    public function jsonSerialize()
    {
        return array(
            "name" => $this->_Name,
            "description" => $this->_Description,
            "price" => $this->_Price,
            "growtime" => $this->_Growtime,
            "available" => $this->_Available
        );
    }
}
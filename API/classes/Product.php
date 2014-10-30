<?php
class Product implements JsonSerializable
{
    private $_db;

    private $_Id;
    private $_Name;
    private $_Description;
    private $_Growtime;

    public function __construct(Database $database, $ProductId)
    {
        $this->_db = $database;

        //Check if product exists, throw exception when not
        if(!$this->_db->exists("SELECT id FROM product WHERE id = ?", array($ProductId)))
            throw new Exception("Could not finish Product constructor: ProductId(".$ProductId.") does not exist");

        $this->_Id = $ProductId; //Set product id
        $Product = $this->_db->getRecord("SELECT name, description, growtime FROM product WHERE id = ? LIMIT 1", array($this->_Id)); //Load product details

        //Set product details
        $this->_Name = $Product["name"];
        $this->_Description = $Product["description"];
        $this->_Growtime = $Product["growtime"];
    }

    public function jsonSerialize()
    {
        return array(
            "id" => $this->_Id,
            "name" => $this->_Name,
            "description" => $this->_Description,
            "growtime" => $this->_Growtime
        );
    }
}
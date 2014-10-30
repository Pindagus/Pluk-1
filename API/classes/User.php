<?php
class User implements JsonSerializable
{
    private $_db;

    private $_Id;
    private $_Username;
    private $_Password;

    private $_Fields;
    private $_Seeds; //Seeds user has
    private $_Products;

    public function __construct(Database $database, $UserId)
    {
        $this->_db = $database;

        //Check if user exists, throw exception when not
        if(!$this->_db->exists("SELECT id FROM user WHERE id = ?", array($UserId)))
            throw new Exception("Could not finish User constructor: UserId(".$UserId.") does not exist");

        $this->_Id = $UserId; //Set user id
        $User = $this->_db->getRecord("SELECT username, password FROM user WHERE id = ? LIMIT 1", array($this->_Id)); //Load user details

        //Set user details
        $this->_Username = $User["username"];
        $this->_Password = $User["password"];

        //Fields
        $this->_Fields = array(); //Set user fields array
        $Fields = $this->_db->getRecords("SELECT id FROM field WHERE user_id = ?", array($this->_Id)); //Load user fields

        foreach($Fields as $Field) //Set user fields
        {
            array_push($this->_Fields, new Field($this->_db, $Field["id"]));
        }

        //Seeds
        $this->_Seeds = array(); //Set user seeds array
        $Seeds = $this->_db->getRecords("SELECT seed_id, amount FROM user_seeds WHERE user_id = ?", array($this->_Id)); //Load user seeds

        foreach($Seeds as $Seed) //Set user seeds
        {
            $seed = new Seed($this->_db, $Seed["seed_id"]); //Create seed

            for($i = 0; $i < $Seed["amount"]; $i++) //Push seed multiple times to array to get the right amount
            {
                array_push($this->_Seeds, $seed);
            }
        }

        //Products (vegetables)
        $this->_Products = array(); //Set user products array
        $Products = $this->_db->getRecords("SELECT product_id, amount FROM user_products WHERE user_id = ?", array($this->_Id)); //Load user products

        foreach($Products as $Product)
        {
            $product = new Product($this->_db, $Product["product_id"]); //Create product

            for($i = 0; $i < $Product["amount"]; $i++) //Push product multiple times to array to get the right amount
            {
                array_push($this->_Products, $product);
            }
        }
    }

    public function getId()
    {
        return $this->_Id;
    }

    public function getUsername()
    {
        return $this->_Username;
    }

    public function getPassword()
    {
        return $this->_Password;
    }

    public function getSeeds()
    {
        return $this->_Seeds;
    }

    public function jsonSerialize()
    {
        return array(
            "id" => (int) $this->_Id,
            "username" => $this->_Username,
            "password" => $this->_Password,
            "fields" => $this->_Fields,
            "seeds" => $this->_Seeds,
            "products" => $this->_Products
        );
    }
}
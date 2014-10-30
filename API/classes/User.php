<?php
class User implements JsonSerializable
{
    private $_db;

    private $_Id;
    private $_Username;
    private $_Password;

    private $_Seeds; //Seeds user has
    //TODO: Add $_Products (Vegetables user has)

    public function __construct(Database $database, $UserId)
    {
        $this->_db = $database;

        //Check if user exists, throw exception when not
        if(!$this->_db->exists("SELECT id FROM user WHERE id = ?", array($UserId)))
            throw new Exception("Could not finish User constructor: UserId(".$UserId.") does not exist");

        //Set user id
        $this->_Id = $UserId;

        //Load user details
        $User = $this->_db->getRecord("SELECT username, password FROM user WHERE id = ? LIMIT 1", array($this->_Id));

        //Set user details
        $this->_Username = $User["username"];
        $this->_Password = $User["password"];

        //Set user seeds array
        $this->_Seeds = array();

        //Load user seeds
        $Seeds = $this->_db->getRecords("SELECT seed_id, amount FROM user_seeds WHERE user_id = ?", array($this->_Id));

        //Set user seeds
        foreach($Seeds as $Seed)
        {
            $seed = new Seed($this->_db, $Seed["seed_id"]);

            for($i = 0; $i < $Seed["amount"]; $i++)
            {
                array_push($this->_Seeds, $seed);
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
            "username" => $this->_Username,
            "password" => $this->_Password,
            "seeds" => $this->_Seeds
        );
    }
}
<?php
class Field implements JsonSerializable
{
    private $_db;

    private $_Id;
    private $_Watertime;

    //TODO: Add plagues and plants

    public function __construct(Database $database, $FieldId)
    {
        $this->_db = $database;

        //Check if field exists, throw exception when not
        if(!$this->_db->exists("SELECT id FROM field WHERE id = ?", array($FieldId)))
            throw new Exception("Could not finish Field constructor: FieldId(".$FieldId.") does not exist");

        //Set field id
        $this->_Id = $FieldId;

        //Load field details
        $Field = $this->_db->getRecord("SELECT watertime FROM field WHERE id = ? LIMIT 1", array($this->_Id));

        //Set field details
        $this->_Watertime = $Field["watertime"];
    }

    public function jsonSerialize()
    {
        return array(
            "id" => (int) $this->_Id,
            "watertime" => $this->_Watertime
        );
    }
}
<?php
class Database
{
    protected $_db;
    protected static $_instance;

    public static function singleton(PDO $pdo)
    {
        // Check the instance
        if (Database::$_instance)
            return Database::$_instance;

        return Database::$_instance = new Database($pdo);
    }

    public function __construct(PDO $pdo)
    {
        $this->_db = $pdo;
    }

    private function executeSQL($SQL, array $values) {
        $query = $this->_db->prepare($SQL);
        $query->execute($values);
        return $query;
    }

    public function getRecord($SQL, array $values) {
        return $this->executeSQL($SQL, $values)->fetch();
    }

    public function getRecords($SQL, array $values) {
        return $this->executeSQL($SQL, $values)->fetchAll();
    }

    public function execute($SQL, array $values) {
        return $this->executeSQL($SQL, $values);
    }

    public function exists($SQL, array $values)
    {
        $data = $this->getRecords($SQL, $values);

        if(count($data) > 0)
            return true;
        else
            return false;
    }

    public function lastInsertedID() {
        return $this->_db->lastInsertId();
    }
} 
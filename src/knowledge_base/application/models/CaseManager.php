<?php


class CaseManager
{
    private $conn;

    public function __construct()
    {
        require_once 'application/controller/home.php';
        try {
            $this->conn = DbManager::connect();
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    /**
     * Method that returns the cases
     * @return array cases stored in the db
     */
    public function getCases(){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("SELECT * FROM cases");
            $prepared_query->execute();
            return $prepared_query->fetchAll();

        } catch (PDOException $ex) {
        }
    }
}


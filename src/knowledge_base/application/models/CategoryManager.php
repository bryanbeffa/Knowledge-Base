<?php


class CategoryManager
{
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = DbManager::connect();
        } catch (PDOException $ex) {
            throw $ex;
        }
    }

    /**
     * Method that returns the categories
     * @return array categories stored in the db
     */
    public function getCategories(){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("SELECT * FROM categories");
            $prepared_query->execute();
            return $prepared_query->fetchAll();

        } catch (PDOException $ex) {
        }
    }

}
<?php


class CategoryManager
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

    public function addCategory($category_name){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("INSERT INTO categories(name) VALUES (:category_name)");
            $prepared_query->bindParam(':category_name', $category_name, PDO::PARAM_STR);
            $prepared_query->execute();

            return true;
        } catch (PDOException $ex) {
            Home::setErrorMsg("La categoria esiste già");
            return false;
        }
    }
}
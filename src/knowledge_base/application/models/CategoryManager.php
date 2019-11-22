<?php


class CategoryManager
{
    /**
     * @var attribute that defines the database connection
     */
    private $conn;

    public function __construct()
    {
        require_once 'application/models/MessageManager.php';
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

    /**
     * Method that returns if there is a category with the defined id
     * @param $id category id
     * @return bool if there is at leas 1 result
     */
    public function checkCategoryId($id)
    {
        try {
            $prepared_query = $this->conn->prepare("SELECT * FROM CATEGORIES WHERE id = :id");
            $prepared_query->bindParam(":id", $id, PDO::PARAM_INT);
            $prepared_query->execute();
            $res = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

            return sizeof($res) > 0;

        } catch (PDOException $ex) {
        }
    }

    /**
     * Method that tries to add a new category.
     * @param $category_name name of the new category
     * @return bool if the operation is successful
     */
    public function addCategory($category_name){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("INSERT INTO categories(name) VALUES (:category_name)");
            $prepared_query->bindParam(':category_name', $category_name, PDO::PARAM_STR);
            $prepared_query->execute();

            return true;
        } catch (PDOException $ex) {
            MessageManager::setErrorMsg("La categoria esiste già");
            return false;
        }
    }

    /**
     * Method that returns the category using its id
     * @param $id id of the category
     * @return mixed category desired
     */
    public function getCategoryById($id){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("SELECT name FROM categories WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            $res = $prepared_query->fetch();
            return $res[0];

        } catch (PDOException $ex) {
        }
    }

    /**
     * Method that tries to delete the desired category
     * @param $id id of the category to delete
     * @return bool if the operation was successful
     */
    public function deleteCategory($id){
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;
        } catch (PDOException $ex) {
            echo $ex;
            MessageManager::setErrorMsg("Impossibile eliminare questa categoria");
            return false;
        }
    }
}
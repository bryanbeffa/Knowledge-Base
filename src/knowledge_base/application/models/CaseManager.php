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
    public function getCases()
    {
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("SELECT * FROM cases");
            $prepared_query->execute();
            return $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
        }
    }

    /**
     * Method that tries to add a new case
     */
    public function addCase(DocCase $case)
    {
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("INSERT INTO cases(title, created_by, description, category_id, variant) 
                VALUES (:title, :created_by, :description, :category_id, :variant)");

            //get values
            $title = $case->getTitle();
            $description = $case->getDescription();
            $category_id = $case->getCategory();
            $variant = $case->getVariant();

            //bind params
            $prepared_query->bindParam(':title', $title, PDO::PARAM_STR);
            $prepared_query->bindParam(':created_by', $_SESSION['id'], PDO::PARAM_INT);
            $prepared_query->bindParam(':description', $description, PDO::PARAM_STR);
            $prepared_query->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':variant', $variant, PDO::PARAM_INT);

            $prepared_query->execute();

            return true;
        } catch (PDOException $ex) {
            echo $ex;
            Home::setErrorMsg("Impossibile creare il caso");
            return false;
        }


    }

    /**
     * Method that sets to 1 the deleted attribute
     * @param $id case to hide.
     */
    public function setDeletedCase($id)
    {
        try {
            //prepare query
            $prepared_query = $this->conn->prepare("UPDATE CASES SET deleted = 1 WHERE ID = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

        } catch (PDOException $ex) {
        }
    }
}


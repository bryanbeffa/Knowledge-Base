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
            $sql = "SELECT * FROM cases WHERE DELETED = 0 LIMIT 1";

            //prepare query
            if(isset($_SESSION['order_results'])){

                if(intval($_SESSION['order_results']) == 0){

                    //order by date
                    $sql = "SELECT * FROM cases WHERE DELETED = 0 order by created_at desc";
                } else if(intval($_SESSION['order_results'])== 1){

                    //order by times
                    $sql = "SELECT * FROM cases WHERE DELETED = 0";
                }
            }

            $prepared_query = $this->conn->prepare($sql);
            $prepared_query->execute();
            return $prepared_query->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $ex) {
            echo $ex;
        }
    }

    /**
     * Method that tries to add a new case
     */
    public function addCase(DocCase $case)
    {
        try {

            $sql = "INSERT INTO cases(title, created_by, description, category_id, variant) 
                VALUES (:title, :created_by, :description, :category_id, :variant);";

            //get values
            $title = $case->getTitle();
            $description = $case->getDescription();
            $category_id = $case->getCategory();
            $variant = $case->getVariant();

            //if the variant is not null, insert row in rappresentations table
            if ($variant != null) {
                $sql .= "INSERT INTO rappresentations (id_case) VALUES (:id_case);";
            }

            //prepare query
            $prepared_query = $this->conn->prepare($sql);

            //bind params first statement
            $prepared_query->bindParam(':title', $title, PDO::PARAM_STR);
            $prepared_query->bindParam(':created_by', $_SESSION['id'], PDO::PARAM_INT);
            $prepared_query->bindParam(':description', $description, PDO::PARAM_STR);
            $prepared_query->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':variant', $variant, PDO::PARAM_INT);


            //bind params second statement -> if there is a variant
            if ($variant != null) {
                $prepared_query->bindParam(':id_case', $variant, PDO::PARAM_INT);
            }

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

            //get cases that have the variant id of the deleted case
            $variant_query = $this->conn->prepare("SELECT * FROM CASES WHERE variant = :id");
            $variant_query->bindParam(':id', $id, PDO::PARAM_INT);
            $variant_query->execute();

            $results = $variant_query->fetchAll(PDO::FETCH_ASSOC);

            //set null variant field
            foreach ($results as $result){
                $prepared_query = $this->conn->prepare("UPDATE CASES set variant = null where id = :id");
                $prepared_query->bindParam(':id', $result['id'], PDO::PARAM_INT);
                $prepared_query->execute();
            }

            //prepare query
            $prepared_query = $this->conn->prepare("UPDATE CASES SET deleted = 1 WHERE ID = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

        } catch (PDOException $ex) {
        }
    }

    /**
     * Method that return the times of the case.
     * @param $id of the case
     */
    public function getTimes($id)
    {
        try {
            //get which cases have this variant case
            $prepared_query = $this->conn->prepare("SELECT * FROM cases WHERE variant = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            $variant_case_results = $prepared_query->fetchAll(PDO::FETCH_ASSOC);

            //get not deleted case
            $times = 0;
            foreach ($variant_case_results as $variant_case_result){

                $prepared_query = $this->conn->prepare("SELECT  count(*) FROM cases WHERE id = :id AND deleted = 0");
                $prepared_query->bindParam(':id', $variant_case_results['id'], PDO::PARAM_INT);
                $prepared_query->execute();
                $res = $prepared_query->fetch();

                if(intval($res[0]) == 1){
                    $times++;
                }
            }

            return $times;

        } catch (PDOException $ex) {
        }
    }
}


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
            $sql = "SELECT * FROM cases WHERE DELETED = 0";

            //filter variables
            $text_filter = "";
            $date_filter = "";
            $category_filter = "";

            //prepare query
            if (isset($_SESSION['order_results'])) {

                //set session variables value
                (isset($_SESSION['text_filter'])) ? $text_filter = $_SESSION['text_filter'] : $text_filter = "";
                (isset($_SESSION['date_filter'])) ? $date_filter = $_SESSION['date_filter'] : $date_filter = "";

                //if value == 0 -> all categories
                (isset($_SESSION['category_filter']) && intval($_SESSION['category_filter']) != 0) ? $category_filter = $_SESSION['category_filter'] : $category_filter = "";

                $id = $text_filter;
                $text_filter = "%" . $text_filter . "%";
                $date_filter = "%" . $date_filter . "%";
                $category_filter = "%" . $category_filter . "%";

                //variable that sets if include or not null category id
                $include_null_category_id = (($_SESSION['category_filter']) == 0) ? " OR category_id IS NULL) " : ") ";

                if (intval($_SESSION['order_results']) == 0) {

                    //order by date
                    $sql = "SELECT * FROM cases WHERE DELETED = 0 AND 
                            (description LIKE :text_filter 
                                OR id LIKE :id 
                                OR title LIKE :text_filter 
                                OR variant LIKE :id) 
                                AND (created_at LIKE :date_filter) 
                                AND (category_id LIKE :category_id" . $include_null_category_id . "
                                order by created_at desc";

                } else if (intval($_SESSION['order_results']) == 1) {

                    //order by times
                    $sql = "SELECT * FROM cases WHERE DELETED = 0";
                }
            }

            $prepared_query = $this->conn->prepare($sql);

            //bind params
            $prepared_query->bindParam(':text_filter', $text_filter, PDO::PARAM_STR);
            $prepared_query->bindParam(':id', $id, PDO::PARAM_STR);
            $prepared_query->bindParam(':date_filter', $date_filter, PDO::PARAM_STR);
            $prepared_query->bindParam(':category_id', $category_filter, PDO::PARAM_STR);

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

            $sql = "INSERT INTO cases(title, created_by, description, category_id, variant) 
                VALUES (:title, :created_by, :description, :category_id, :variant);";

            //get values
            $title = $case->getTitle();
            $description = $case->getDescription();
            $category_id = $case->getCategory();
            $variant = $case->getVariant();

            //if the variant is not null, insert row in representation table
            if ($variant != null) {
                $sql .= "INSERT INTO representation (id_case, id_variant) VALUES (:id_case, :variant);";
            }

            //prepare query
            $prepared_query = $this->conn->prepare($sql);

            //bind params first statement
            $prepared_query->bindParam(':title', $title, PDO::PARAM_STR);
            $prepared_query->bindParam(':created_by', $_SESSION['id'], PDO::PARAM_INT);
            $prepared_query->bindParam(':description', $description, PDO::PARAM_STR);
            $prepared_query->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':variant', $variant, PDO::PARAM_INT);

            $prepared_query->execute();

            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that sets to 1 the deleted attribute
     * @param $id case to hide.
     * @return bool if the case is been deleted
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
            foreach ($results as $result) {
                $prepared_query = $this->conn->prepare("UPDATE CASES set variant = null where id = :id");
                $prepared_query->bindParam(':id', $result['id'], PDO::PARAM_INT);
                $prepared_query->execute();
            }

            //prepare query
            $prepared_query = $this->conn->prepare("UPDATE CASES SET deleted = 1 WHERE ID = :id");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            return true;

        } catch (PDOException $ex) {
            return false;
        }
    }

    /**
     * Method that return the times of the case.
     * @param $id of the case
     * @return int times of representation
     */
    public function getTimes($id)
    {
        try {
            //get times
            $prepared_query = $this->conn->prepare("SELECT count(*) FROM cases WHERE variant = :id and deleted = 0");
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->execute();

            $res = $prepared_query->fetch();

            return intval($res[0]);

        } catch (PDOException $ex) {
        }
    }


    public function modifyCase(DocCase $case, $id)
    {
        try {

            $sql = "UPDATE cases SET title = :title, description = :description, category_id = :category_id, variant = :variant WHERE ID = :id;";

            //get values
            $title = $case->getTitle();
            $description = $case->getDescription();
            $category_id = $case->getCategory();
            $variant = $case->getVariant();

            //if the variant is not null, insert row in representation table
            if ($variant != null) {
                $sql .= "INSERT INTO representation (id_case, id_variant) VALUES (:id, :variant);";
                echo $variant;
            }

            //prepare query
            $prepared_query = $this->conn->prepare($sql);

            //bind params first statement
            $prepared_query->bindParam(':title', $title, PDO::PARAM_STR);
            $prepared_query->bindParam(':id', $id, PDO::PARAM_INT);
            $prepared_query->bindParam(':description', $description, PDO::PARAM_STR);
            $prepared_query->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $prepared_query->bindParam(':variant', $variant, PDO::PARAM_INT);

            $prepared_query->execute();

            return true;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            return false;
        }
    }
}


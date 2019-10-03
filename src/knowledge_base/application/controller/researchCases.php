<?php


class ResearchCases
{

    private $user_manager;
    private $validator;
    private $category_manager;
    private $case_manager;

    public function __construct()
    {
        require_once 'application/models/DbManager.php';
        require_once 'application/models/UserManager.php';
        require_once 'application/models/CategoryManager.php';
        require_once 'application/models/CaseManager.php';
        require_once 'application/models/DocCase.php';
        require_once 'application/models/Validator.php';
        require_once 'application/controller/dbErrorPage.php';

        try {
            $this->user_manager = new UserManager();
            $this->validator = new Validator();
            $this->category_manager = new CategoryManager();
            $this->case_manager = new CaseManager();
        } catch (PDOException $exception) {
        }
    }

    public function showCases()
    {
        //check if the db is connected
        if (isset($this->user_manager)) {
            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                require_once 'application/views/templates/head.php';

                if (isset($_POST['order_results']) && intval($_POST['order_results']) == 1) {

                    $_SESSION['order_results'] = 1;
                } else {
                    //set date order
                    $_SESSION['order_results'] = 0;
                }

                $is_admin = "";

                //check if the user is an admin
                if (UserManager::isAdminUser($_SESSION['email'])) {
                    require_once 'application/views/templates/admin_header.php';
                    $is_admin = 1;
                } else {
                    require_once 'application/views/templates/user_header.php';
                    $is_admin = 0;
                }

                //get categories
                $categories = $this->category_manager->getCategories();

                //get cases
                $cases = $this->case_manager->getCases();

                $cases_times = array();
                foreach ($cases as $case) {
                    //create associative array, id case as key
                    $key = $case['id'];
                    $cases_times["$key"] = $this->case_manager->getTimes($case['id']);
                    $cases_categories["$key"] = $this->category_manager->getCategoryById($case['category_id']);
                }

                require_once 'application/views/users/ricerca_casi.php';

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }

        } else {
            DbErrorPage::noDatabaseConnection();
        }
    }

    /**
     * Method that deletes the specified case.
     * @param $id
     */
    public
    function deleteCase($id)
    {
        //check if the db is connected
        if (isset($this->user_manager)) {


            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                //check if the user is an admin
                if (UserManager::isAdminUser($_SESSION['email'])) {

                    $this->case_manager->setDeletedCase($id);

                    //redirect research cases page
                    header("Location: " . URL . "researchCases/showCases");

                } else {
                    $this->researchCases();
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbErrorPage::noDatabaseConnection();
        }
    }

    /**
     * Add new case
     */
    public
    function addCase()
    {
        //check if the db is connected
        if (isset($this->user_manager)) {

            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                //set sessions variable
                (isset($_POST['new_case_title'])) ? $_SESSION['new_case_title'] = $_POST['new_case_title'] : "";
                (isset($_POST['new_case_description'])) ? $_SESSION['new_case_description'] = $_POST['new_case_description'] : "";

                //check if the variables are initialized
                if (isset($_POST['new_case_title']) && isset($_POST['new_case_category']) && isset($_POST['new_case_description'])) {

                    //test input
                    $title = $this->testInput($_POST['new_case_title']);
                    $category = $this->testInput($_POST['new_case_category']);
                    $description = $this->testInput($_POST['new_case_description']);

                    //check if data are valid
                    if ($this->validator->validateTextInput($title, 1, 50)) {
                        if ($this->validator->validateTextInput($description, 1, 65535)) {

                            $variant = null;
                            if (isset($_POST['new_case_variant'])) {

                                //null -> 0
                                if (intval($_POST['new_case_variant']) != 0) {
                                    $variant = $this->testInput($_POST['new_case_variant']);
                                }
                            }

                            //creare DocCase object
                            $case = new DocCase($title, $category, $variant, $description);

                            if (!$this->case_manager->addCase($case)) {
                                $this->printError();
                                header("Location: " . URL . "researchCases/showCases");
                            } else {
                                //unset session variable
                                unset($_SESSION['new_case_title']);
                                unset($_SESSION['new_case_description']);

                                //redirect research cases page
                                header("Location: " . URL . "researchCases/showCases");
                            }

                            exit();

                        } else {
                            self::$error_msg = "La descrizione deve contenere del testo";
                        }

                    } else {
                        self::$error_msg = "Il titolo non deve contenere da 1 a 50 caratteri";
                    }

                    $this->printError();
                    header("Location: " . URL . "researchCases/showCases");

                } else {
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbErrorPage::noDatabaseConnection();
        }
    }

    function testInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * Add new category.
     */
    public
    function addCategory()
    {
        //check if the db is connected
        if (isset($this->user_manager)) {

            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                //check if the user is an admin
                if (UserManager::isAdminUser($_SESSION['email'])) {

                    //check if the variables are not null
                    if (isset($_POST['new_category']) && !empty($_POST['new_category'])) {

                        $new_category = $this->testInput($_POST['new_category']);

                        if ($this->validator->validateTextInput($new_category, 1, 50)) {
                            //add new category
                            if ($this->category_manager->addCategory($new_category)) {
                                //redirect research cases page
                                header("Location: " . URL . "researchCases/showCases");
                            } else {
                                header("Location: " . URL . "researchCases/showCases");
                                Home::printError();
                            }
                        } else {
                            Home::setErrorMsg("Inserire da 1 a 50 caratteri");
                            header("Location: " . URL . "researchCases/showCases");
                            Home::printError();
                        }
                    }

                } else {
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbErrorPage::noDatabaseConnection();
        }
    }
}
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
        require_once 'application/controller/dbError.php';

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

                    //order by most represent
                    $_SESSION['order_results'] = 1;
                } else if (isset($_POST['order_results']) && intval($_POST['order_results']) == 2) {

                    //order by less recent
                    $_SESSION['order_results'] = 2;
                } else {
                    //order by most recent
                    $_SESSION['order_results'] = 0;
                }

                //set session variables
                (isset($_POST['text_filter'])) ? $_SESSION['text_filter'] = $this->testInput($_POST['text_filter']) : $_SESSION['text_filter'] = '';
                (isset($_POST['date_filter'])) ? $_SESSION['date_filter'] = $this->testInput($_POST['date_filter']) : $_SESSION['date_filter'] = '';
                (isset($_POST['category_filter'])) ? $_SESSION['category_filter'] = intval($this->testInput($_POST['category_filter'])) : $_SESSION['category_filter'] = '';

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

                //used to print variant during the modification of a case if filters are actived
                $all_cases = $this->case_manager->getAllCases();

                $cases_times = array();
                foreach ($cases as $case) {
                    //create associative array, id case as key
                    $key = $case['id'];
                    $cases_times["$key"] = $this->case_manager->getTimes($case['id']);
                    $cases_categories["$key"] = $this->category_manager->getCategoryById($case['category_id']);
                }

                //if success variable is set print the message
                if (isset($_SESSION['success'])) {
                    Home::setSuccessMsg($_SESSION['success']);
                    Home::printSuccessMsg();
                    unset($_SESSION['success']);
                }

                require_once 'application/views/users/ricerca_casi.php';

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }

        } else {
            DbError::noDatabaseConnection();
        }
    }

    /**
     * Method that deletes the specified case.
     * @param $id
     */
    public
    function deleteCase()
    {
        //check if the db is connected
        if (isset($this->user_manager)) {

            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                //check if the user is an admin
                if (UserManager::isAdminUser($_SESSION['email'])) {

                    if (isset($_POST['caseToDeleteId'])) {

                        $id = $this->testInput($_POST['caseToDeleteId']);
                        $id = intval($id);

                        //try to delete the case
                        if ($this->case_manager->setDeletedCase($id)) {

                            //show success alert
                            $_SESSION['success'] = "Il caso è stato eliminato con successo";

                            //redirect to showCases function
                            header("Location: " . URL . "researchCases/showCases");
                        } else {
                            Home::setErrorMsg("Impossibile eliminare il caso");
                            Home::printErrorMsg();
                            $this->showCases();
                        }
                    } else {
                        //redirect to showCases function
                        header("Location: " . URL . "researchCases/showCases");
                    }
                } else {
                    //redirect to login page
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbError::noDatabaseConnection();
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

                                    //check if the variant is valid
                                    if(!$this->case_manager->checkCaseId($variant)) {
                                        $variant = null;
                                    }
                                }
                            }

                            if($this->category_manager->checkCategoryId($category)) {
                                //create DocCase object
                                $case = new DocCase($title, $category, $variant, $description);

                                if ($this->case_manager->addCase($case)) {

                                    //unset session variables
                                    unset($_SESSION['new_case_title']);
                                    unset($_SESSION['new_case_description']);
                                    unset($_SESSION['new_case_category']);

                                    //show success alert
                                    $_SESSION['success'] = "Il caso è stato creato con successo";

                                    //redirect to showCases function
                                    header("Location: " . URL . "researchCases/showCases");

                                } else {
                                    Home::setErrorMsg("Impossibile creare il caso. Riprova più tardi");
                                    Home::printErrorMsg();
                                    $this->showCases();
                                }
                            } else {
                                Home::setErrorMsg("La categoria inserita non è valida");
                                Home::printErrorMsg();
                                $this->showCases();
                            }

                            exit();
                        } else {
                            Home::setErrorMsg("La descrizione deve contenere del testo");
                        }

                    } else {
                        Home::setErrorMsg("Il titolo non deve contenere da 1 a 50 caratteri");
                    }

                    Home::printErrorMsg();
                    $this->showCases();

                } else {
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbError::noDatabaseConnection();
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

                                //show success alert
                                $_SESSION['success'] = "La categoria è stata aggiunta";

                                //redirect to showCases function
                                header("Location: " . URL . "researchCases/showCases");

                            } else {
                                $this->showCases();
                                Home::printErrorMsg();
                            }
                        } else {
                            Home::setErrorMsg("Inserire da 1 a 50 caratteri");
                            Home::printErrorMsg();
                            $this->showCases();
                        }
                    } else {
                        //redirect to login page
                        header("Location: " . URL . "researchCases/showCases");
                    }


                } else {
                    //redirect to login page
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbError::noDatabaseConnection();
        }
    }

    /**
     * Delete category.
     */
    public function deleteCategory()
    {
        //check if the db is connected
        if (isset($this->user_manager)) {

            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                //check if the user is an admin
                if (UserManager::isAdminUser($_SESSION['email'])) {

                    //check post variable
                    if (isset($_POST['delete_category_id']) && !empty($this->testInput($_POST['delete_category_id']))) {

                        $category_id = $this->testInput($_POST['delete_category_id']);

                        //delete category
                        $this->category_manager->deleteCategory($category_id);

                        //show success alert
                        $_SESSION['success'] = "La categoria è stata eliminata con successo";

                        header("Location: " . URL . "researchCases/showCases");

                    } else {
                        Home::setErrorMsg("Impossibile eliminare la categoria");
                        Home::printErrorMsg();
                        $this->showCases();
                    }

                } else {
                    //redirect to login page
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbError::noDatabaseConnection();
        }
    }

    public function modifyCase()
    {
        //check if the db is connected
        if (isset($this->user_manager)) {

            //check if the uses is logged
            if ($this->user_manager->isUserLogged()) {

                //check if the user is an admin and the case id to modify is set
                if (UserManager::isAdminUser($_SESSION['email']) && isset($_POST['modify_case_id'])) {

                    //check if the variables are initialized
                    if (isset($_POST['modify_case_title']) && isset($_POST['modify_case_category']) && isset($_POST['modify_case_description'])) {

                        //test input
                        $title = $this->testInput($_POST['modify_case_title']);
                        $category = $this->testInput($_POST['modify_case_category']);
                        $description = $this->testInput($_POST['modify_case_description']);
                        $id = $this->testInput($_POST['modify_case_id']);

                        //check if data are valid
                        if ($this->validator->validateTextInput($title, 1, 50)) {
                            if ($this->validator->validateTextInput($description, 1, 65535)) {

                                $variant = null;
                                if (isset($_POST['modify_case_variant'])) {

                                    //null -> 0
                                    if (intval($_POST['modify_case_variant']) != 0) {

                                        $variant = $this->testInput($_POST['modify_case_variant']);

                                        //check if the variant is valid
                                        if(!$this->case_manager->checkCaseId($variant)) {
                                            $variant = null;
                                        }
                                    }
                                }

                                if($this->category_manager->checkCategoryId($category)){

                                    //create DocCase object
                                    $case = new DocCase($title, $category, $variant, $description);

                                    if ($this->case_manager->modifyCase($case, $id)) {

                                        //show success alert
                                        $_SESSION['success'] = "Il caso è stato modificato con successo";

                                        //redirect to showCases function
                                        header("Location: " . URL . "researchCases/showCases");

                                    } else {
                                        Home::setErrorMsg("Impossibile modificare il caso. Riprova più tardi");
                                        Home::printErrorMsg();
                                        $this->showCases();
                                    }
                                } else {
                                    Home::setErrorMsg("La categoria inserita non è valida");
                                    Home::printErrorMsg();
                                    $this->showCases();
                                }

                                exit();
                            } else {
                                Home::setErrorMsg("La descrizione deve contenere del testo");
                            }

                        } else {
                            Home::setErrorMsg("Il titolo non deve contenere da 1 a 50 caratteri");
                        }

                        Home::printErrorMsg();
                        $this->showCases();

                    } else {
                        header("Location: " . URL . "researchCases/showCases");
                    }

                } else {
                    //redirect to login page
                    header("Location: " . URL . "researchCases/showCases");
                }

            } else {
                //redirect to login page
                header("Location: " . URL . "home/index");
            }
        } else {
            DbError::noDatabaseConnection();
        }
    }
}
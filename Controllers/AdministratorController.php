<?php
    namespace Controllers;

    use DAO\AdministratorDAO as AdministratorDAO;
    use Helpers\SessionHelper as SessionHelper;
    use Helpers\MessageHelper as MessageHelper;
    use Models\Administrator as Administrator;

    class AdministratorController
    {
        private $administratorDAO;

        public function __construct(){
            $this->administratorDAO = new AdministratorDAO();
        }

        public function AddView($message = ""){
            if((new SessionHelper)->isAdmin()) {
                require_once(VIEWS_PATH."add-administrator.php");
            } else 
                (new HomeController())->Index();
        }

        public function ListView(){
            if((new SessionHelper)->isAdmin()) {
                $administratorList = $this->administratorDAO->getAll();

                require_once(VIEWS_PATH."administrator-list.php");
            } else 
                (new HomeController())->Index();
        }

        public function Add($email, $password, $checkPassword){
            if((new SessionHelper)->isAdmin()) {
                $administratorList = $this->administratorDAO->GetAll();

                if(strcmp($checkPassword, $password) != 0) {
                    $message = MessageHelper::PASSWORD_DONT_MATCH;
                } else {
                    foreach($administratorList as $eachadministrator) {
                        if($eachadministrator->getEmail() == $email){
                            $administrator = $eachadministrator;
                        }
                    }
        
                    if(!isset($administrator)){ 
                        $administrator = new Administrator();
                        $administrator->setEmail($email);
                        $administrator->setPassword($password);
                        
                        $this->administratorDAO->Add($administrator);
                        $message = "Admin registered!";
                    }else{
                        $message = MessageHelper::ADMINISTRATOR_EXISTS;
                    }
                }
                $this->AddView($message);
            } else 
                (new HomeController())->Index();
        }

        public function Remove($removeId){
            if((new SessionHelper)->isAdmin()) {
                $this->administratorDAO->DeleteById($removeId);
                $this->ListView();
            } else 
                (new HomeController())->Index();
        }


        public function ModifyView($modifyId){
            if((new SessionHelper)->isAdmin()) {
                $administrator = $this->administratorDAO->FindById($modifyId);

                require_once(VIEWS_PATH."modify-administrator.php");
            } else 
                (new HomeController())->Index();
        }
    }
?> 
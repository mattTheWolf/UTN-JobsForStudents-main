<?php
    namespace Controllers;

use DAO\UserDAO;
use Helpers\MessageHelper;
use Helpers\SessionHelper;
use Models\CompanyUser;

class CompanyUserController
    {
        private $userDAO;

        public function __construct(){
            $this->userDAO = new UserDAO();
        }

        public function AddView($message = ""){
            if((new SessionHelper)->isAdmin()) {
                require_once(VIEWS_PATH."add-companyUser.php");
            } else 
                (new HomeController())->Index();
        }

        public function ListView(){
            if((new SessionHelper)->isAdmin()) {
                $companyUserList = $this->userDAO->GetAllCompanyUsers();

                require_once(VIEWS_PATH."companyUser-list.php");
            } else 
                (new HomeController())->Index();
        }

        public function Add($email, $password, $checkPassword){
            $message = "";
            if((new SessionHelper)->isAdmin()) {
                $companyUserList = $this->userDAO->GetAll();

                if(strcmp($checkPassword, $password) != 0) {
                    $message = MessageHelper::PASSWORD_DONT_MATCH;
                } else {
                    foreach($companyUserList as $eachcompanyUser) {
                        if($eachcompanyUser->getEmail() == $email){
                            $companyUser = $eachcompanyUser;
                        }
                    }
        
                    if(!isset($companyUser)){ 
                        $companyUser = new CompanyUser();
                        $companyUser->setEmail($email);
                        $companyUser->setPassword($password);
                        
                        $this->userDAO->AddCompanyUser($companyUser);
                        $message = "Company user registered!";
                    }else{
                        $message = MessageHelper::COMPANY_USER_EXISTS;
                    }
                }
                
                $this->AddView($message);
            } else 
                (new HomeController())->Index($message);
        }
    }
?> 
<?php
    namespace Helpers;
    use Models\Administrator as Administrator;
use Models\CompanyUser;

class SessionHelper {
        public function sessionRestart() {
            session_destroy();
            session_start();
        }

        public function isAdmin() {
            $isAdmin = false;
            if($_SESSION["currentUser"] instanceof Administrator) {
                $isAdmin = true;
            }
            return $isAdmin;
        }

        public function isCompany(){
            $isCompany = false;
            if($_SESSION["currentUser"] instanceof CompanyUser) {
                $isCompany = true;
            }
            return $isCompany;
        }

        public function setCurrentUser($loginUser) {
            $_SESSION['currentUser'] = $loginUser;
        }

        public function getCurrentUser(){
            return $_SESSION['currentUser'];
        }
    }
?>
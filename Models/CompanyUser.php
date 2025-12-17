<?php   namespace Models;

    use Models\User;

    class CompanyUser extends User{
       private $company;

        public function getCompany(){
            return $this->company;
        }

        public function setCompany($company){
            $this->company = $company;
        }
    }
?>
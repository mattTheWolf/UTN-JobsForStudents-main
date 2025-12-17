<?php
    namespace Controllers;

    use DAO\APICareerDAO as APICareerDAO;
    use Models\Career as Career;

    class CareerController {
        private $careerDAO;

        public function __construct(){
            $this->careerDAO = new APICareerDAO();
        }

        //DELETES THE LIST AND FILLS WITH THE API DATA
        public function UpdateFromAPI() {
            $this->careerDAO->LoadFromAPI();
        }
    }
?>  
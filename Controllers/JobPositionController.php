<?php
    namespace Controllers;

    use DAO\APIJobPositionDAO as APIJobPositionDAO;
    use Models\JobPosition as JobPosition;

    class JobPositionController {
        private $jobPositionDAO;

        public function __construct(){
            $this->jobPositionDAO = new APIJobPositionDAO();
        }

        //DELETES THE LIST AND FILLS WITH THE API DATA
        public function UpdateFromAPI() {
            $this->jobPositionDAO->LoadFromAPI();
        }
    }
?>  
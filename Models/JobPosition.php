<?php
    namespace Models;

    class jobPosition {
        private $jobPositionId;
        private $career;
        private $description;

        //CONSTRUCTOR
        // public function __construct($careerId, $description) { 
        //     $this->careerId = $careerId;
        //     $this->description = $description;
        // }

        //GETTERS & SETTERS
        public function getJobPositionId() {
            return $this->jobPositionId;
        }

        public function setJobPositionId($jobPositionId) {
            $this->jobPositionId = $jobPositionId;
        }

        public function getCareer() {
            return $this->career;
        }

        public function setCareer($career) {
            $this->career = $career;
        }

        public function getDescription() {
           return $this->description;
        }

        public function setDescription($description) {
            $this->description = $description;
        }

        //TO STRING METHOD
        public function __toString() {
            return  "<br>ID: ".$this->jobPositionId.
                    "<br>Career: ".$this->career.
                    "<br>Description: ".$this->description;
        }
    }
?>
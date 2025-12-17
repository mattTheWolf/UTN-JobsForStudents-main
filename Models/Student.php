<?php   namespace Models;

    use Models\User;

    class Student extends User{
        private $firstName;
        private $lastName;
        private $phoneNumber;
        private $gender;
        private $dNI;
        private $birthDate;
        private $career;
        private $fileNumber;
        private $appointment;

        //GET/SET        
        public function getFirstName(){
            return $this->firstName;
        }

        public function setFirstName($firstName){
            $this->firstName = $firstName;
        }
        
        public function getLastName(){
            return $this->lastName;
        }

        public function setLastName($lastName){
            $this->lastName = $lastName;
        }
        
        public function getPhoneNumber(){
            return $this->phoneNumber;
        }

        public function setPhoneNumber($phoneNumber){
            $this->phoneNumber = $phoneNumber;
        }

        public function getGender(){
            return $this->gender;
        }

        public function setGender($gender){
            $this->gender = $gender;
        }

        public function getDNI(){
            return $this->dNI;
        }

        public function setDNI($dNI){
            $this->dNI = $dNI;
        }

        public function getBirthDate(){
            return $this->birthDate;
        }

        public function setBirthDate($birthDate){
            $this->birthDate = $birthDate;
        }

        public function getCareer(){
            return $this->career;
        }

        public function setCareer($career){
            $this->career = $career;
        }

        public function getFileNumber(){
            return $this->fileNumber;
        }
    
        public function setFileNumber($fileNumber){
                $this->fileNumber = $fileNumber;
        }

        public function getAppointment(){
            return $this->appointment;
        }

        public function setAppointment($appointment){
            $this->appointment = $appointment;
        }
    
        //toString
        public function __tostring(){
            return "<br>ID: ".parent::getUserId().
                   "<br>DNI: ".$this->dNI.
                   "<br>Full name: ".$this->firstName." ".$this->lastName.
                   "<br>Born in ".$this->birthDate.
                   "<br>Gender: ".$this->gender.
                   "<br>Phone: ".$this->phoneNumber.
                   "<br>Career: ".$this->career.
                   "<br>File number: ".$this->fileNumber.
                   "<br>Appointment<br>---------------------".$this->appointment;
        }
    };
?>
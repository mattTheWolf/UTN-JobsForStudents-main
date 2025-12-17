<?php
    namespace Models;

    class JobOffer {

        private $jobOfferId;
        private $title;
        private $publishedDate;
        private $finishDate;
        private $task;
        private $skills;
        private $active;
        private $remote;
        private $salary;
        private $jobPosition; //relation one to one
        private $dedication;
        private $company; //relation one to one
        private $administrator;
       
        //GETTERS & SETTERS
        public function getJobOfferId() {
            return $this->jobOfferId;
        }

        public function setJobOfferId($jobOfferId) {
            $this->jobOfferId = $jobOfferId;
        }

        public function getTitle() {
            return $this->title;
        }

        public function setTitle($title) {
            $this->title = $title;
        }

        public function getPublishedDate() {
            return $this->publishedDate;
        }

        public function setPublishedDate($publishedDate) {
            $this->publishedDate = $publishedDate;
        }

        public function getFinishDate() {
            return $this->finishDate;
        }
 
        public function setFinishDate($finishDate) {
            $this->finishDate = $finishDate;
        }

        public function getTask() {
            return $this->task;
        }

        public function setTask($task) {
            $this->task = $task;
        }

        public function getSkills() {
            return $this->skills;
        }

        public function setSkills($skills) {
            $this->skills = $skills;
        }

        public function getActive() {
            return $this->active;
        }

        public function setActive($active) {
            $this->active = $active;
        }

        public function getRemote() {
            return $this->remote;
        }

        public function setRemote($remote) {
            $this->remote = $remote;;
        }

        public function getSalary() {
            return $this->salary;
        }

        public function setSalary($salary) {
            $this->salary = $salary;
        }

        public function getJobPosition() {
            return $this->jobPosition;
        }

        public function setJobPosition($jobPosition) {
            $this->jobPosition = $jobPosition;
        }

        public function getDedication()
        {
            return $this->dedication;
        }

        public function setDedication($dedication)
        {
            $this->dedication = $dedication;
        }

        public function getCompany()
        {
            return $this->company;
        }

        public function setCompany($company)
        {
            $this->company = $company;
        }

        public function getAdministrator()
        {
            return $this->administrator;
        }

        public function setAdministrator($administrator)
        {
            $this->administrator = $administrator;
        }

        //TO STRING METHOD
        public function __toString() {
            return  "<br>Title: ".$this->title.
                    "<br>Published: ".$this->publishedDate.
                    "<br>Finished Date: ".$this->finishDate.
                    "<br>Task: ".$this->task.
                    "<br>Skills: ".$this->skills.
                    "<br>Active: ".$this->active.
                    "<br>Remote: ".$this->remote.
                    "<br>Salary: ".$this->salary.
                    "<br>Job Position: ".
                    "<br>-------------------------------".$this->jobPosition;
        }
    }
    
?>
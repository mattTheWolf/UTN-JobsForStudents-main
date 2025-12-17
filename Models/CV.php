<?php
    namespace Models;

    class CV
    {
        private $name;
        private $user;
        
        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;
        }        

        public function getUser()
        {
            return $this->user;
        }

        public function setUser($user)
        {
            $this->user = $user;
        }
    }
?>
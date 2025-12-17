<?php namespace Models;
    abstract class Dedication{
        const FULLTIME = "Full-Time";
        const PARTTIME = "Part-Time";
        const CASUAL = "Casual";

        public static function GetAll(){
            return array(Dedication::FULLTIME, Dedication::PARTTIME, Dedication::CASUAL);
        }
    };
?>
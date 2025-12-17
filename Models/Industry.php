<?php namespace Models;
    abstract class Industry{
        const TEXTIL = "Textil";
        const AEROSPACE = "Aerospace";
        const TRANSPORT = "Transport";
        const IT = "IT";
        const COMPUTER = "Computer";
        const TELECOMMUNICATION = "Telecomunication";
        const AGRICULTURE = "Agriculture";
        const CONSTRUCTION = "Construction";
        const EDUCATION = "Education";
        const PHARMACEUTICAL = "Pharmaceutical";
        const FOOD = "Food";
        const HEALTHCARE = "Health care";
        const HOSPITALITY = "Hospitality";
        const ENTERTAINMENT = "Entertainment";
        const NEWSMEDIA = "News Media";
        const ENERGY = "Energy";
        const MANUFACTURING = "Manufacturing";
        const MUSIC = "Music";
        const MINING = "Mining";
        const WWW = "Worldwide web";
        const ELECTRONICS = "Electronics";

        public static function GetAll() {
            return array(Industry::TEXTIL, Industry::AEROSPACE, Industry::TRANSPORT, Industry::IT, Industry::COMPUTER, 
                        Industry::TELECOMMUNICATION, Industry::AGRICULTURE, Industry::CONSTRUCTION, Industry::EDUCATION, 
                        Industry::PHARMACEUTICAL, Industry::FOOD, Industry::HEALTHCARE, Industry::HOSPITALITY, 
                        Industry::ENTERTAINMENT, Industry::NEWSMEDIA, Industry::ENERGY, Industry::MANUFACTURING, 
                        Industry::MUSIC, Industry::MINING,Industry::WWW, Industry::ELECTRONICS);
        }
    };
?>
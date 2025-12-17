<?php namespace DAO;

class UTNAPIStudentDAO{

        public function loadFromAPI(){
            $apiList = array();
            $apiCareers = (new APICareerDAO)->getAll();

            //CURL
            $url = curl_init();
            //Sets URL
            curl_setopt($url, CURLOPT_URL, 'https://utn-students-api2.herokuapp.com/api/Student');
            //Sets Header key
            curl_setopt($url, CURLOPT_HTTPHEADER, array('x-api-key:4f3bceed-50ba-4461-a910-518598664c08'));
            curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($url);
            $toJson = json_decode($response);

            foreach($toJson as $eachStudent){
                foreach($apiCareers as $eachCareer){
                    if($eachStudent->careerId == $eachCareer->getCareerId())
                        $eachStudent->careerId = $eachCareer->getDescription();
                }
                array_push($apiList, $eachStudent);
            }
            return $apiList;
        }
    }
?>
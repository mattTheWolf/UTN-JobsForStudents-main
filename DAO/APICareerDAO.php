<?php
    namespace DAO;

    use Models\Career as Career;
    use DAO\ICareerDAO as ICareerDAO;

    class APICareerDAO implements ICareerDAO{

        private $careerList;

    
        public function LoadFromAPI() {
            $this->careerList = array();

            //CURL
            $url = curl_init();
            //Sets URL
            curl_setopt($url, CURLOPT_URL, 'https://utn-students-api2.herokuapp.com/api/career');
            //Sets Header key
            curl_setopt($url, CURLOPT_HTTPHEADER, array('x-api-key:4f3bceed-50ba-4461-a910-518598664c08'));
            curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($url);
            $toJson = json_decode($response);

            foreach($toJson as $career) {
                
                $newcareer = new career();

                $newcareer->setcareerId($career->careerId);
                $newcareer->setDescription($career->description);
                $newcareer->setActive($career->active);

                array_push($this->careerList, $newcareer);
            }

        }

        public function GetAll(){
            $this->LoadFromAPI();
            return $this->careerList;
        }

        public function FindById($careerId)
        {
            $careerSearch = null;

            foreach($this->careerList as $career){
                if($career->getCareerId() == $careerId){
                    $careerSearch = $career;
                }
            }

               return $careerSearch;
        }
}

?>
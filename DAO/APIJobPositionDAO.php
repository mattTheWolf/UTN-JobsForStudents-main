<?php
    namespace DAO;

    use \Exception as Exception;
    use Models\JobPosition as JobPosition;
    use Models\Career as Career;
    use DAO\IJobPositionDAO as IJobPositionDAO;
    use DAO\CareerDAO as CareerDAO;

    class APIJobPositionDAO implements IJobPositionDAO {
        private $jobPositionList;

        public function GetAll(){
            $this->LoadFromAPI();
            return $this->jobPositionList;
        }

        public function FindById($jobPositionId) {
            $jobPositionList = $this->GetAll();
            
            $jobPositionSearch = null;

            foreach($jobPositionList as $jobPosition){
                if($jobPosition->getJobPositionId() == $jobPositionId){
                    $jobPositionSearch = $jobPosition;
                }
            }

               return $jobPositionSearch;
        }

        public function LoadFromAPI() {
            $this->jobPositionList = array();
            $careerDAO = new APICareerDAO();
            $apiCareers = $careerDAO->GetAll();

            //CURL
            $url = curl_init();
            //Sets URL
            curl_setopt($url, CURLOPT_URL, 'https://utn-students-api2.herokuapp.com/api/jobPosition');
            //Sets Header key
            curl_setopt($url, CURLOPT_HTTPHEADER, array('x-api-key:4f3bceed-50ba-4461-a910-518598664c08'));
            curl_setopt($url, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($url);
            $toJson = json_decode($response);
            
            foreach($toJson as $jobPosition) {
                $newJobPosition = new JobPosition();
                foreach($apiCareers as $career) {
                    if($jobPosition->careerId == $career->getCareerId()){
                        $newCareer = new Career();

                        $newCareer->setCareerId($career->getCareerId());
                        $newCareer->setDescription($career->getDescription());
                        $newCareer->setActive($career->getActive());

                        $newJobPosition->setCareer($newCareer);
                    }
                }

                $newJobPosition->setJobPositionId($jobPosition->jobPositionId);
                $newJobPosition->setDescription($jobPosition->description);

                array_push($this->jobPositionList, $newJobPosition);
            }
        }
    }
?>
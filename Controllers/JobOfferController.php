<?php
    namespace Controllers;

    use DAO\JobOfferDAO as JobOfferDAO;
    use DAO\APIJobPositionDAO as APIJobPositionDAO;
    use DAO\CompanyDAO as CompanyDAO;
    use DAO\APICareerDAO as APICareerDAO;
    use Models\JobOffer as JobOffer;
    use Models\Dedication as Dedication;
    use Models\AdministratorDAO as AdministratorDAO;
    use Models\Administrator as Administrator;
    use Helpers\SessionHelper as SessionHelper;
    use Helpers\MessageHelper as MessageHelper;
    use Controllers\CompanyController as CompanyController;

    class JobOfferController {
        private $jobOfferDAO;

        public function __construct() {
            $this->jobOfferDAO = new JobOfferDAO();
        }

        public function ShowAddView($careerId = "", $message = "") {
            if((new SessionHelper)->isAdmin() || (new SessionHelper)->isCompany()) {
                $careerDAO = new APICareerDAO();
                $careerList = $careerDAO->GetAll();
    
                $jobPositionDAO = new APIJobPositionDAO();
                $allJobPositionList = $jobPositionDAO->GetAll();
                $jobPositionList = [];
    
                if($careerId) {
                    foreach($allJobPositionList as $jobPosition) {
                        if($jobPosition->getCareer()->getCareerId() == $careerId){
                            array_push($jobPositionList, $jobPosition);
                        }
                    }
                }
                $dedicationList = Dedication::GetAll();
    
                $companyDAO = new CompanyDAO();
                $companyList = $companyDAO->GetAll();
    
                if($companyList){
                    $admin = (new SessionHelper)->getCurrentUser();
                    require_once(VIEWS_PATH."add-jobOffer.php");
                }else{
                    $message = MessageHelper::NO_COMPANY;
                    (new CompanyController())->ShowAddView($message);
                }
            } else 
                (new HomeController())->Index();
        }

        public function ShowListView($message = ""){
            $jobOfferList = $this->jobOfferDAO->GetAll();

            if(!$jobOfferList) {
                $jobOfferList = new JobOffer();
            }else{
                if((new SessionHelper)->isCompany()){
                    $filteredList = array();
                    foreach($jobOfferList as $eachOffer){
                        if($eachOffer->getCompany()->getCompanyId() == (new SessionHelper)->getCurrentUser()->getCompany()->getCompanyId())
                            array_push($filteredList, $eachOffer);
                    }

                    $jobOfferList = $filteredList;
                }
            }

            //$admin = (new SessionHelper)->getCurrentUser();
            $isAdmin = (new SessionHelper)->isAdmin() || (new SessionHelper)->isCompany();

            require_once(VIEWS_PATH."jobOffer-list.php");
        }

        public function Add($jobPositionId, $companyId, $title, $publishedDate, $finishDate, $task, $skills, $active, $remote, $salary, $dedication, $administratorId) {
            $message = MessageHelper::SUCCESSFULLY_CREATED;
            if((new SessionHelper)->isAdmin() || (new SessionHelper)->isCompany()) {
                $jobOffer = new JobOffer();
                
                if($this->checkDates($publishedDate, $finishDate)){

                    $jobPositionDAO = new APIJobPositionDAO();
                    $companyDAO = new CompanyDAO();

                    $jobOffer->setJobPosition($jobPositionDAO->FindById($jobPositionId));
                    $jobOffer->setCompany($companyDAO->FindById($companyId));
                    $jobOffer->setTitle($title);
                    $jobOffer->setPublishedDate($publishedDate);
                    $jobOffer->setFinishDate($finishDate);
                    $jobOffer->setTask($task);
                    $jobOffer->setSkills($skills);
                    $jobOffer->setActive($active);
                    $jobOffer->setRemote($remote);
                    $jobOffer->setSalary($salary);
                    $jobOffer->setDedication($dedication);
                    $jobOffer->setAdministrator($administratorId);

                    $jobOfferList = $this->jobOfferDAO->Add($jobOffer);
                } else {
                    $message = MessageHelper::INVALID_DATE;
                }
                $this->ShowAddView("", $message);
            } else 
                (new HomeController())->Index();
        }

        public function Remove($removeId){
            if((new SessionHelper)->isAdmin() || (new SessionHelper)->isCompany()) {
                $this->jobOfferDAO->DeleteById($removeId);  
                $this->ShowListView();
            } else 
                (new HomeController())->Index();
        }

        public function ModifyView($modifyId){
            if((new SessionHelper)->isAdmin() || (new SessionHelper)->isCompany()) {
                $jobOffer = $this->jobOfferDAO->FindById($modifyId);
                $dedicationList = Dedication::GetAll();
                
                $jobPositionDAO = new APIJobPositionDAO();
                $jobPositionList = $jobPositionDAO->GetAll();
                $careerId = $jobOffer->getJobPosition()->getCareer()->getCareerId();
                
                $companyDAO = new CompanyDAO();
                $companyList = $companyDAO->GetAll();

                $admin = (new SessionHelper)->getCurrentUser() || (new SessionHelper)->isCompany();

                $user = (new SessionHelper)->getCurrentUser();
                require_once(VIEWS_PATH."modify-jobOffer.php");
            } else 
                (new HomeController())->Index();
        }

        public function ModifyAJobOffer($jobOfferId, $title, $publishedDate, $finishDate, $task, $skills, $active, $remote, $salary, $jobPositionId, $dedication, $companyId, $administratorId = ""){
            if((new SessionHelper)->isAdmin() || (new SessionHelper)->isCompany()) {
                if($this->checkDates($publishedDate, $finishDate)){

                    $jobOffer = new JobOffer();
                    $jobPositionDAO = new APIJobPositionDAO();
                    $companyDAO = new CompanyDAO();

                    $jobOffer->setJobOfferId($jobOfferId);
                    $jobOffer->setJobPosition($jobPositionDAO->FindById($jobPositionId));
                    $jobOffer->setCompany($companyDAO->FindById($companyId));
                    $jobOffer->setTitle($title);
                    $jobOffer->setPublishedDate($publishedDate);
                    $jobOffer->setFinishDate($finishDate);
                    $jobOffer->setTask($task);
                    $jobOffer->setSkills($skills);
                    $jobOffer->setActive($active);
                    $jobOffer->setRemote($remote);
                    $jobOffer->setSalary($salary);
                    $jobOffer->setDedication($dedication);
                    $jobOffer->setAdministrator($administratorId);

                    $jobOfferList = $this->jobOfferDAO->Modify($jobOffer);

                    $this->jobOfferDAO->Modify($jobOffer);
                } else {
                    $message = MessageHelper::INVALID_DATE;
                }
                
                $this->ShowListView();
            } else 
                (new HomeController())->Index();
        }

        public function ViewDetail($jobOfferId) {
            $jobOffer = $this->jobOfferDAO->FindById($jobOfferId);

            $isAdmin = (new SessionHelper)->isAdmin() || (new SessionHelper)->isCompany();

            require_once(VIEWS_PATH."jobOffer-viewDetail.php");
        }

        //Validation of the dates (finishedDate can't be earlier than publishedDate)
        private function checkDates($publishedDate, $finishDate){
            $validDate = true;
            
            if($publishedDate > $finishDate || $publishedDate < date("Y-m-d"))
                $validDate = false;

            return $validDate;
        }

        public function JobOffersFromCompany($companyId){
            $isAdmin = (new SessionHelper)->isAdmin() || (new SessionHelper)->isCompany();

            $jobOfferList = $this->jobOfferDAO->getJobOffersOfCompany((new CompanyDAO)->FindById($companyId));

            if(!$jobOfferList)
                    $jobOfferList = new JobOffer();
            
            require_once(VIEWS_PATH."jobOffer-list.php");
    
        }
    }
?>
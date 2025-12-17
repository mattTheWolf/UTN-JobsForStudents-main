<?php
    namespace Controllers;

    use DAO\JobOfferDAO as JobOfferDAO;
    use DAO\APIJobPositionDAO as APIJobPositionDAO;
    use DAO\APICareerDAO as APICareerDAO;
    use DAO\AppointmentDAO;
    use DAO\StudentDAO;
    use Helpers\SessionHelper as SessionHelper;
    use Models\Administrator as Administrator;
    use Models\JobOffer as JobOffer;

    class HomeController
    {
        private $jobOfferDAO;
        private $jobPositionDAO;
        private $careerDAO;

        public function __construct(){
            $this->jobOfferDAO = new JobOfferDAO();
            $this->jobPositionDAO = new APIJobPositionDAO();
            $this->careerDAO = new APICareerDAO();
        }

        public function Index($message = ""){
            $getAll = $this->jobOfferDAO->GetAll();
            $jobOfferList = array();
            $isCompany = (new SessionHelper())->isCompany();

            //Bring active ones to jobOfferList
            if($getAll)
                foreach($getAll as $eachJobOffer)
                    if($eachJobOffer->getActive()){
                        if($isCompany && (new SessionHelper())->getCurrentUser()->getCompany()){
                            if($eachJobOffer->getCompany()->getCompanyId() == (new SessionHelper())->getCurrentUser()->getCompany()->getCompanyId())
                            array_push($jobOfferList, $eachJobOffer);
                        }else{
                            array_push($jobOfferList, $eachJobOffer);
                        }
                    }

            //For each jobboffer...
            foreach($jobOfferList as $eachJobOffer)
                //If caducated...
                if(date('c') > $eachJobOffer->getFinishDate()){
                    $offerAppointments = (new AppointmentDAO)->getAppointmentsOfJobOffer($eachJobOffer);
                    //For each appointment of that caducated offer...
                    foreach($offerAppointments as $eachAppointment){
                        $appliedStudents = (new StudentController)->getStudentsByAppointment($eachAppointment);
                        //For each applied student for an appointment of that caducated offer.
                        foreach($appliedStudents as $eachAppliedStudent){
                            //Send the email
                            (new EmailController)->sendEmail($eachAppliedStudent->getEmail(), "From the company ".$eachJobOffer->getCompany()->getName()." - Thank you!", "The applying for the offer <b>".$eachJobOffer->getTitle()."</b> from <b>".$eachJobOffer->getCompany()->getName()."</b> has ended.<br>Thanks for your apply!");
                            //And a copy to us to see if it works
                            //(new EmailController)->sendEmail("mlcuenca91@gmail.com", "From the company ".$eachJobOffer->getCompany()->getName()." - Thank you!", "<b>".$eachAppliedStudent->getEmail()."</b><br><br>The applying for the offer <b>".$eachJobOffer->getTitle()."</b> from <b>".$eachJobOffer->getCompany()->getName()."</b> has ended.<br>Thanks for your apply!");
                            (new EmailController)->sendEmail("carlosmercado--@hotmail.com", "From the company ".$eachJobOffer->getCompany()->getName()." - Thank you!", "<b>".$eachAppliedStudent->getEmail()."</b><br><br>The applying for the offer <b>".$eachJobOffer->getTitle()."</b> from <b>".$eachJobOffer->getCompany()->getName()."</b> has ended.<br>Thanks for your apply!");
                        }
                    }                   
                }
            

            //And set them inactive
            $this->jobOfferDAO->setInactiveFinishedOffers();

            //Bring the new active ones to jobOfferList once again
            $getAll = $this->jobOfferDAO->GetAll();
            $jobOfferList = array();
            $i = 0;
            if($getAll)
                foreach($getAll as $eachJobOffer)
                    if($isCompany && (new SessionHelper())->getCurrentUser()->getCompany()){
                        if($eachJobOffer->getActive()){
                            if(($isCompany && $eachJobOffer->getCompany()->getCompanyId() == (new SessionHelper())->getCurrentUser()->getCompany()->getCompanyId()) || !$isCompany){
                                array_push($jobOfferList, $eachJobOffer);
                                $i++;
                            }
                        }
                    }else{
                        array_push($jobOfferList, $eachJobOffer);
                    }
                    
            $jobPositionList = $this->jobPositionDAO->GetAll();
            $careerList = $this->careerDAO->GetAll();

            $isAdmin = (new SessionHelper())->isAdmin() || (new SessionHelper)->isCompany();  

            require_once(VIEWS_PATH."home.php");
        }   

        public function Filters($careerId, $jobPositionSearch = "") {
            $isAdmin = (new SessionHelper())->isAdmin();
            $isCompany = (new SessionHelper())->isCompany();

            $jobOfferList = $this->jobOfferDAO->GetAll();

            $jobPositionList = $this->jobPositionDAO->GetAll();

            $careerList = $this->careerDAO->GetAll();

            $newJOList = []; 

            if($jobOfferList){
                foreach($jobOfferList as $jobOffer){
                    if((strpos(strtolower($jobOffer->getJobPosition()->getDescription()), strtolower($jobPositionSearch)) !== false && $jobPositionSearch != "") || $jobOffer->getJobPosition()->getCareer()->getCareerId() == $careerId)
                        if(($isCompany && $jobOffer->getCompany()->getCompanyId() == (new SessionHelper())->getCurrentUser()->getCompany()->getCompanyId()) || !$isCompany)
                            array_push($newJOList, $jobOffer);
                }
                $i = count($newJOList);
            }else
                $i = 0;  

            if($newJOList) { 
                $jobOfferList = $newJOList;
            } else {
                $jobOfferList = null;
            }

            $message = "";
            
            require_once(VIEWS_PATH."home.php");
        }
    }
?>
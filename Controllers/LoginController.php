<?php
    namespace Controllers;

    use Models\Administrator as Administrator;
    use Models\Student as Student;
    use Helpers\SessionHelper as SessionHelper;
    use DAO\AppointmentDAO;
    use DAO\APICareerDAO;
    use DAO\CompanyDAO;
    use DAO\UserDAO;
    use DAO\UTNAPIStudentDAO;
    use Models\CompanyUser;

    class LoginController{
        private $userDAO;
        private $UTNAPIDAO;
        private $careerDAO;
        private $companyDAO;

        public function __construct(){
            $this->UTNAPIDAO = new UTNAPIStudentDAO();
            $this->userDAO = new UserDAO();
            $this->careerDAO = new APICareerDAO();
            $this->companyDAO = new CompanyDAO();
        }

        public function LogInView($message = ""){
            session_destroy();
            require_once(VIEWS_PATH."login.php");
        }

        public function LogIn($email, $userPassword){
            (new SessionHelper())->sessionRestart();

            $loginUser = null;

            //Login
            foreach($this->userDAO->getAll() as $eachUser)
                if($eachUser->getEmail() == $email && $eachUser->getPassword() == $userPassword){
                    $active = null;
                    switch($eachUser->getRole()){
                        case("company"):
                            $user = new CompanyUser();
                            $company = ($this->companyDAO)->getCompanyByCompanyUserId($eachUser->getUserId());
                            if($company)
                                $user->setCompany($company);
                            $active = true;
                            break;
                        case("admin"):
                            $user = new Administrator();
                            $active = true;
                            break;
                        case("student"):
                            $user = new Student();

                                foreach($this->UTNAPIDAO->loadFromAPI() as $eachUTNStudent)
                                    if($eachUser->getEmail() == $eachUTNStudent->email){
                                        if($eachUTNStudent->active == true){
                                            $user->setFirstName($eachUTNStudent->firstName);
                                            $user->setLastName($eachUTNStudent->lastName);
                                            $user->setPhoneNumber($eachUTNStudent->phoneNumber);
                                            $user->setGender($eachUTNStudent->gender);
                                            $user->setDNI($eachUTNStudent->dni);
                                            $user->setBirthDate($eachUTNStudent->birthDate);
                                            $user->setCareer($eachUTNStudent->careerId);
                                            $user->setFileNumber($eachUTNStudent->fileNumber);
                                        
                                            $appointmentList = (new AppointmentDAO)->getAll();

                                            if($appointmentList)
                                                foreach($appointmentList as $eachAppointment){
                                                    if($eachAppointment->getStudent()->getUserId() == $eachUser->getUserId())
                                                        $user->setAppointment($eachAppointment);
                                                }
                                                    
                                            $active = true;
                                        }
                                        else{
                                            $this->LogInView("That student is not active!");
                                            $active = false;
                                        }
                                    }
                            break;
                    }

                    if($active == true){
                        $user->setUserId($eachUser->getUserId());
                        $user->setEmail($eachUser->getEmail());
                        $user->setPassword($eachUser->getPassword());
                        $user->setRole($eachUser->getRole());
    
                        $loginUser = $user;
                    }
                }

            if($loginUser == null)
                $this->LogInView("Those login credentials doesn't exist in our database!");
            else{
                (new SessionHelper())->setCurrentUser($loginUser);
                $this->careerDAO->LoadFromAPI();
                header("location: ".FRONT_ROOT."Home/Index");
            }
        }
    }
?> 
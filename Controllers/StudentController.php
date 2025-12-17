<?php
    namespace Controllers;

    use DAO\StudentDAO as StudentDAO;
    use Models\AcademicStatus as AcademicStatus;
    use Models\Career;
    use Models\Student as Student;
    use Models\Administrator as Administrator;
    use Helpers\SessionHelper as SessionHelper;
    use Helpers\MessageHelper as MessageHelper;
    use Controllers\LoginController as LoginController;
    use DAO\UserDAO;
    use DAO\UTNAPIStudentDAO;
    use DAO\AppointmentDAO;

class StudentController{
        private $UTNAPIDAO;
        private $studentDAO;

        public function __construct(){
            $this->studentDAO = new StudentDAO();
            $this->UTNAPIDAO = new UTNAPIStudentDAO();
        }
        
        public function RegisterView($message = ""){
            if(isset($_SESSION['currentUser']))
                session_destroy();
            require_once(VIEWS_PATH."add-student.php");
        }

        public function Add($dNI, $fileNumber, $email, $password, $password2){
            $newStudent = NULL;
            $studentList = $this->studentDAO->getAll();

            //If already registered
            $found = false;
            if($studentList){
                foreach($studentList as $eachStudent){
                    if($eachStudent->getEmail() == $email){
                        $message = "You´re already registered!";
                        $found = true;
                    }
                }
            }

            //If not registered
            if(!$found){
                if($password != $password2){
                    $message = "The input passwords don't match!";
                }else{
                    $UTNAPILIST = $this->UTNAPIDAO->loadFromAPI();

                    //Check if UTN student exists..
                    foreach($UTNAPILIST as $eachUTNStudent){
                        if($eachUTNStudent->dni == $dNI &&
                           $eachUTNStudent->fileNumber == $fileNumber &&
                           $eachUTNStudent->email == $email){
                                $newStudent = $eachUTNStudent;
                           }
                    }
    
                    //If UTN student exists
                    if($newStudent){
                        //..but is not active
                        if($newStudent->active == false)
                            $message = "Can´t register!, that student is not active in UTN!";
                        //..and if is active
                        else{
                            $newStudent = new Student();
                            $newStudent->setEmail($email);
                            $newStudent->setPassword($password);
            
                            $this->studentDAO->add($newStudent);
                            $message = MessageHelper::REGISTER_COMPLETE;
                            header("location:".FRONT_ROOT."Login/LoginView?message=".$message);
                        }
                    //If UTN student does not exist
                    }else{
                        $message = "That information doesn´t match with any UTN student!";
                    }
                }
            }
            $this->RegisterView($message);
        }

        public function ListView(){
            if((new SessionHelper())->isAdmin() || (new SessionHelper)->isCompany()) {
            $studentList = $this->studentDAO->getAll();
            $newStudentList = array();

            if($studentList != null){
                $UTNAPILIST = $this->UTNAPIDAO->loadFromAPI();
                foreach($studentList as $eachStudent){
                    foreach($UTNAPILIST as $eachUTNStudent){
                        if($eachStudent->getEmail() == $eachUTNStudent->email){
                            $this->APIStudentToStudent($eachUTNStudent, $eachStudent);
                            array_push($newStudentList, $eachStudent);
                        }
                    }
                }
    
                $studentList = $newStudentList;
            }else
                $studentList = new Student();

            require_once(VIEWS_PATH."student-list.php");
            } else {
                header("location: ".FRONT_ROOT."Home/Index");
            }
        }

        private function getStudentById($id){
            $studentList = $this->studentDAO->getAll();

            $student = null;

            foreach($studentList as $eachStudent){
                if($eachStudent->getUserId() == $id)
                    $student = $eachStudent;
            }

            return $student;
        }

        public function ViewStudentDetails($studentId){
            $UTNAPILIST = $this->UTNAPIDAO->loadFromAPI();
            $studentList = $this->studentDAO->getAll();

            $student = new Student();

            foreach($studentList as $eachStudent){
                foreach($UTNAPILIST as $eachUTNStudent){
                    if($studentId == $eachStudent->getUserId() && $eachUTNStudent->email == $eachStudent->getEmail()){
                        $this->APIStudentToStudent($eachUTNStudent, $student);
                        $student->setUserId($eachStudent->getUserId());
                    }
                }
            }

            $userCV = (new AppointmentDAO)->getCVByUserId($student->getUserId());

            require_once(VIEWS_PATH."student-profile.php");
        }

        public function ProfileView($email){
            $UTNAPILIST = $this->UTNAPIDAO->loadFromAPI();
            $studentList = $this->studentDAO->getAll();

            $student = new Student();

            foreach($studentList as $eachStudent){
                foreach($UTNAPILIST as $eachUTNStudent){
                    if($email == $eachUTNStudent->email && $eachUTNStudent->email == $eachStudent->getEmail()){
                        $this->APIStudentToStudent($eachUTNStudent, $student);
                        $student->setUserId($eachStudent->getUserId());
                    }
                }
            }

            $userCV = (new AppointmentDAO)->getCVByUserId($student->getUserId());

            require_once(VIEWS_PATH."student-profile.php");
        }

        public function getStudentsByAppointment($appointment){
            $studentList = $this->studentDAO->getAll();
            $appliedStudents = array();

            foreach($studentList as $eachStudent){
                if($eachStudent->getUserId() == $appointment->getStudent()->getUserId())
                    array_push($appliedStudents, $eachStudent);
            }

            return $appliedStudents;
        }

        public function APIStudentToStudent($from, Student $to){
            $to->setCareer($from->careerId);
            $to->setFirstName($from->firstName);
            $to->setLastName($from->lastName);
            $to->setDNI($from->dni);
            $to->setFileNumber($from->fileNumber);
            $to->setPhoneNumber($from->phoneNumber);
            $to->setGender($from->gender);
            $to->setEmail($from->email);
            $to->setBirthDate($from->birthDate);
        }
    }
?> 
<?php
    namespace DAO;

    use DAO\IAppointmentDAO as IAppointmentDAO;
    use Models\Appointment as Appointment;
    use \Exception as Exception;
    use DAO\Connection as Connection;
    use Models\CV;
    use Models\JobOffer;

class AppointmentDAO implements IAppointmentDAO{

        private $connection;
        private $tableName = "appointments";

        public function Add(Appointment $appointment) {
            $this->setInactive($appointment->getStudent()->getUserId());
            try
            {
                $query = "INSERT INTO ".$this->tableName." (studentId, jobOfferId, cv, dateAppointment, referenceURL, comments, active) 
                    VALUES (:studentId, :jobOfferId, :cv, :dateAppointment, :referenceURL, :comments, :active);";

                $parameters["studentId"] = $appointment->getStudent()->getUserId();
                $parameters["jobOfferId"] = $appointment->getJobOffer()->getJobOfferId();    
                $parameters["cv"] = $appointment->getCv();
                $parameters["dateAppointment"] = $appointment->getDateAppointment();
                $parameters["referenceURL"] = $appointment->getReferenceURL();
                $parameters["comments"] = $appointment->getComments();
                $parameters["active"] = $appointment->getActive();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        private function setInactive($studentId) {
            try
            {
                $query = "UPDATE ".$this->tableName." SET active = 0 WHERE studentId =:studentId";

                $parameters["studentId"] = $studentId;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function addCV($cv){
            try
            {
                $query = "INSERT INTO cvs (name, studentId) VALUES (:name, :studentId);";

                $parameters["name"] = $cv->getName();
                $parameters["studentId"] = $cv->getUser()->getUserId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex){
                throw $ex;
            }
        }

        public function updateCV($cv){
            try
            {
                $query = "UPDATE cvs set name=:name WHERE studentId =:studentId;";

                $parameters["name"] = $cv->getName();
                $parameters["studentId"] = $cv->getUser()->getUserId();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex){
                throw $ex;
            }
        }

        public function getCVByUserId($studentId){
            try
            {
                $query = "SELECT * FROM ".'cvs WHERE (studentId = :studentId);';

                $this->connection = Connection::GetInstance();
                
                $parameters["studentId"] = $studentId;

                $result = $this->connection->Execute($query, $parameters);

                if($result){ 
                    foreach($result as $eachResult) {
                        $cv = new CV();
                        $cv->setName($eachResult["name"]);
                        $cv->setUser((new StudentDAO)->FindById($eachResult["studentId"]));                    
                    }
                    return $cv;
                }
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function GetAll()
        {
            try
            {
                $appointmentList = array();

                $query = "SELECT * FROM ".$this->tableName;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                    $appointment = new Appointment();
                    
                    $appointment->setStudent((new StudentDAO)->FindById($row["studentId"]));
                    $appointment->setJobOffer((new JobOfferDAO)->FindById($row["jobOfferId"]));
                    $appointment->setCv($row["cv"]);
                    $appointment->setDateAppointment($row["dateAppointment"]);
                    $appointment->setReferenceURL($row["referenceURL"]);
                    $appointment->setComments($row["comments"]);
                    $appointment->setActive($row["active"]);
        
                    array_push($appointmentList, $appointment);
                }
                
                return $appointmentList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function GetHistoryById($studentId)
        {
            try
            {
                $appointmentList = array();

                $query = "SELECT * FROM ".$this->tableName." WHERE studentd = :studentId";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                    $appointment = new Appointment();
                    
                    $appointment->setStudent((new StudentDAO)->FindById($row["studentId"]));
                    $appointment->setJobOffer((new JobOfferDAO)->FindById($row["jobOfferId"]));
                    $appointment->setCv($row["cv"]);
                    $appointment->setDateAppointment($row["dateAppointment"]);
                    $appointment->setReferenceURL($row["referenceURL"]);
                    $appointment->setComments($row["comments"]);
                    $appointment->setActive($row["active"]);
        
                    array_push($appointmentList, $appointment);
                }
                
                return $appointmentList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function FindById($studentId, $jobOfferId){
            try{
                $query = "SELECT * FROM ".$this->tableName." WHERE studentId =:studentId AND jobOfferId =:jobOfferId;";

                $parameters["studentId"] = $studentId;
                $parameters["jobOfferId"] = $jobOfferId;
                
                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query, $parameters)[0];
                if($resultSet){
                    $appointment = new Appointment();
                    
                    $appointment->setStudent((new StudentDAO)->FindById($resultSet["studentId"]));
                    $appointment->setJobOffer((new JobOfferDAO)->FindById($resultSet["jobOfferId"]));
                    $appointment->setCv($resultSet["cv"]);
                    $appointment->setDateAppointment($resultSet["dateAppointment"]);
                    $appointment->setReferenceURL($resultSet["referenceURL"]);
                    $appointment->setComments($resultSet["comments"]);
                    $appointment->setActive($resultSet["active"]);

                    return $appointment;
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }

        public function CancelApplyById($studentId, $jobOfferId){
            try{
                $query = "UPDATE ".$this->tableName." SET active = 0 WHERE studentId = :studentId; AND jobOfferId = :jobOfferId";

                $parameters["studentId"] = $studentId;
                $parameters["jobOfferId"] = $jobOfferId;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }catch(Exception $ex){
                throw $ex;
            }
        }

        public function HistoryById($studentId)//<------------------ ver si lo utilizamos
        {
            try
            {
                $query = "SELECT * FROM ".$this->tableName.' WHERE (studentId = :studentId);';

                $this->connection = Connection::GetInstance();
                
                $parameters["studentId"] = $studentId;

                $result = $this->connection->Execute($query, $parameters);

                if($result){ 
                    $historyList = array(); 
                    foreach($result as $eachResult) {
                        $appointment = new Appointment();
                        $appointment->setStudent((new StudentDAO)->FindById($eachResult["studentId"]));
                        $appointment->setJobOffer((new JobOfferDAO)->FindById($eachResult["jobOfferId"]));
                        $appointment->setCv($eachResult["cv"]);
                        $appointment->setDateAppointment($eachResult["dateAppointment"]);
                        $appointment->setReferenceURL($eachResult["referenceURL"]);  
                        $appointment->setActive($eachResult["active"]);  
                        
                        array_push($historyList, $appointment);                     
                    }
                return $historyList;
                }
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }
    
        public function getAppointmentsOfJobOffer($jobOffer){
            try
            {
                $query = "SELECT * FROM ".$this->tableName.' WHERE jobOfferId = :jobOfferId;';

                $this->connection = Connection::GetInstance();
                
                $parameters["jobOfferId"] = $jobOffer->getJobOfferId();

                $result = $this->connection->Execute($query, $parameters);

                $companyDAO = new CompanyDAO();           
                $jobPositionDAO = new APIJobPositionDAO();

                $appointmentList = array();

                if($result) 
                    foreach($result as $eachResult) {
                        $appointment = new Appointment();
                
                        $appointment->setStudent((new StudentDAO)->FindById($eachResult["studentId"]));
                        $appointment->setJobOffer((new JobOfferDAO)->FindById($eachResult["jobOfferId"]));
                        $appointment->setCv($eachResult["cv"]);
                        $appointment->setDateAppointment($eachResult["dateAppointment"]);
                        $appointment->setReferenceURL($eachResult["referenceURL"]);
                        $appointment->setComments($eachResult["comments"]);
                        $appointment->setActive($eachResult["active"]);
                        
                        array_push($appointmentList, $appointment);
                    }

                return $appointmentList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

    }
?>
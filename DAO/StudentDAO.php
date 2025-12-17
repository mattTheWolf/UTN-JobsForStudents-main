<?php namespace DAO;

    use Controllers\StudentController as StudentController;
    use \Exception as Exception;
    use DAO\IStudentDAO as IStudentDAO;
    use Models\Student as Student;    
    use DAO\Connection as Connection;
    use DAO\APICareerDAO as APICareerDAO;

    class StudentDAO implements IStudentDAO{
        private $connection;
        private $tableName = "users";
        private $careerDAO;

        public function __construct() {
             $this->careerDAO = new APICareerDAO();
        }

        public function add(Student $student){
            try{
                $query = "INSERT INTO ".$this->tableName." (email, password, roleId) 
                VALUES (:email, :password, 2);";

                $parameters["email"] = $student->getEmail();
                $parameters["password"] = $student->getPassword();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }catch(Exception $ex){
                throw $ex;
            }
        }

        public function getAll(){
            try{
                $companyList = array();

                $query = "SELECT * FROM ".$this->tableName." WHERE roleId = 2";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                if($resultSet){
                    foreach ($resultSet as $row){                
                        $student = new Student();
                        $student->setUserId($row["userId"]);
                        $student->setEmail($row["email"]);
                        $student->setPassword($row["password"]);
            
                        array_push($companyList, $student);
                    }
                    return $companyList;
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }

        public function deleteById($studentId){
            try{
                $query = "DELETE FROM ".$this->tableName." WHERE userId = :userId; AND roleId = 2";

                $parameters["userId"] = $studentId;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }catch(Exception $ex){
                throw $ex;
            }
        }

        public function FindById($studentId){
            try{
                $query = "SELECT * FROM ".$this->tableName.' WHERE userId = :userId AND roleId = 2;';

                $this->connection = Connection::GetInstance();
                
                $parameters["userId"] = $studentId;

                $result = $this->connection->Execute($query, $parameters)[0];

                $apiList = (new UTNAPIStudentDAO)->loadFromAPI();

                if($result){
                    $student = new Student();
                    $student->setUserId($result["userId"]);
                    $student->setEmail($result["email"]);
                    $student->setPassword($result["password"]);

                    foreach($apiList as $eachUTN)
                        if($student->getEmail() == $eachUTN->email)
                            (new StudentController)->APIStudentToStudent($eachUTN, $student);
                    
                    return $student;
                }
            }catch(Exception $ex){
                throw $ex;
            }
        }

        public function modifyById($studentId, $password, $email){
            try{
                $query = "UPDATE ".$this->tableName." SET password=:password, email=:email
                WHERE userId=:userId AND roleId = 2;";

                $parameters["userId"] = $studentId;
                $parameters["email"] = $email;
                $parameters["password"] = $password;
    
                $this->connection = Connection::GetInstance();
    
                $this->connection->ExecuteNonQuery($query, $parameters);
            }catch(Exception $ex){
                throw $ex;
            }
        }
    }
?>
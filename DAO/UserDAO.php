<?php
    namespace DAO;

    //use DAO\IUserDAO as IUserDAO;
    use DAO\RoleDAO;
    use DAO\IUserDAO;
    use Models\User;
    use Models\Student;
    use Models\Administrator as Administrator;
    use \Exception as Exception;
    use DAO\Connection as Connection;
use Models\CompanyUser;

class UserDAO implements IUserDAO{

        private $connection;
        private $tableName = "users";

        public function AddAdmin(Administrator $administrator) {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (email, password, roleId) 
                    VALUES (:email, :password, 1);";

                $parameters["email"] = $administrator->getEmail();
                $parameters["password"] = $administrator->getPassword();
             
                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function AddStudent(Student $student){
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

        public function AddCompanyUser(CompanyUser $companyUser){
            try
            {
                $query = "INSERT INTO ".$this->tableName." (email, password, roleId) 
                    VALUES (:email, :password, 3);";

                $parameters["email"] = $companyUser->getEmail();
                $parameters["password"] = $companyUser->getPassword();
             
                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        //To login
        public function getAll(){
            try
            {
                $userList = array();

                $query = "SELECT * FROM ".$this->tableName.";";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                    $administrator = new User();
                    
                    $administrator->setUserId($row["userId"]);
                    $administrator->setEmail($row["email"]);
                    $administrator->setPassword($row["password"]);
                    $administrator->setRole((new RoleDAO)->getRoleById($row["roleId"]));
        
                    array_push($userList, $administrator);
                }
                
                return $userList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }
 
        //to list, not to LOG IN
        public function GetAllAdmins(){
            try
            {
                $administratorList = array();

                $query = "SELECT * FROM ".$this->tableName." WHERE roleId = 1";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                    $administrator = new administrator();
                    
                    $administrator->setUserId($row["userId"]);
                    $administrator->setEmail($row["email"]);
                    $administrator->setPassword($row["password"]);
        
                    array_push($administratorList, $administrator);
                }
                
                return $administratorList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        //to list, not to LOG IN
        public function GetAllStudents(){
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

        //to list, not to LOG IN
        public function GetAllCompanyUsers(){
            try
            {
                $administratorList = array();
        
                $query = "SELECT * FROM ".$this->tableName." WHERE roleId = 3";
        
                $this->connection = Connection::GetInstance();
        
                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)
                {                
                    $administrator = new administrator();
                    
                    $administrator->setUserId($row["userId"]);
                    $administrator->setEmail($row["email"]);
                    $administrator->setPassword($row["password"]);
        
                    array_push($administratorList, $administrator);
                }
                
                return $administratorList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function FindById($userId){
            try
            {
                $query = "SELECT * FROM ".$this->tableName.' WHERE userId = :userId;';

                $this->connection = Connection::GetInstance();
                
                $parameters["userId"] = $userId;

                $result = $this->connection->Execute($query, $parameters)[0];

                if($result) {
                    if($result["roleId"] == 2)  
                        $user = new Student();
                    else  
                        $user = new Administrator();

                    $user->setUserId($result["userId"]);
                    $user->setEmail($result["email"]);
                    $user->setPassword($result["password"]);
                    $user->setRole((new RoleDAO)->getRoleById($result["roleId"]));

                    return $user;
                }
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }        
    }
?>


<?php
    namespace DAO;

    use DAO\IRoleDAO as IRoleDAO;
    use Models\Student;
    use Models\Administrator as Administrator;
    use \Exception as Exception;
    use DAO\Connection as Connection;

class RoleDAO implements IRoleDAO{

        private $connection;
        private $tableName = "roles";

        public function Add($userRole) {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (userRole) 
                    VALUES (:userRole);";

                $parameters["userRole"] = $userRole;
             
                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function GetAll(){
            try
            {
                $rolesList = array();

                $query = "SELECT * FROM ".$this->tableName.";";

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                foreach ($resultSet as $row)                      
                    array_push($rolesList, $row);

                return $rolesList;
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }     

        public function getRoleById($roleId){
            try{
                $query = "SELECT * FROM ".$this->tableName.' WHERE roleId = :roleId;';

                $this->connection = Connection::GetInstance();
                
                $parameters["roleId"] = $roleId;

                $result = $this->connection->Execute($query, $parameters)[0];

                if($result)
                    return $result['userRole'];
                
            }catch(Exception $ex){
                throw $ex;
            }
        }
    }
?>
<?php
    namespace DAO;

    use DAO\IAdministratorDAO as IAdministratorDAO;
    use Models\Administrator as Administrator;
    use \Exception as Exception;
    use DAO\Connection as Connection;

class AdministratorDAO implements IAdministratorDAO{

        private $connection;
        private $tableName = "users";

        public function Add(Administrator $administrator) {
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

        public function GetAll()
        {
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

        public function DeleteById($administratorId)
        {
            try
            {
                $query = "DELETE FROM ".$this->tableName." WHERE userId = :userId AND WHERE roleId = 1;";

                $parameters["userId"] = $administratorId;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function Modify($administrator)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." SET email=:email, password=:password
                WHERE userId=:userId AND roleId = 1;";

                $parameters["userId"] = $administrator->getAdministratorId();
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

        public function FindById($administratorId)
        {
            try
            {
                $query = "SELECT * FROM ".$this->tableName.' WHERE userId = :userId AND roleId = 1;';

                $this->connection = Connection::GetInstance();
                
                $parameters["userId"] = $administratorId;

                $result = $this->connection->Execute($query, $parameters)[0];

                if($result) {
                    $administrator = new administrator();
                    $administrator->setUserId($result["userId"]);
                    $administrator->setEmail($result["email"]);
                    $administrator->setPassword($result["password"]);
                
                    return $administrator;
                }
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }        
    }
?>
<?php
    namespace DAO;

    use DAO\ICVDAO as ICVDAO;
    use DAO\QueryType as QueryType;
    use Exception;
    use Models\CV as CV;

    class CVDAO implements ICVDAO
    {
        public function addCV(CV $cv){
            try
            {
                $query = "CALL cv_add(?);";
                
                $parameters["name"] = $cv->getName();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters, QueryType::StoredProcedure);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }
    }
?>
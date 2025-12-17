<?php
    namespace DAO;

    use \Exception as Exception;
    use DAO\ICompanyDAO as ICompanyDAO;
    use Models\Company as Company;    
    use DAO\Connection as Connection;

    class CompanyDAO implements ICompanyDAO
    {
        private $connection;
        private $tableName = "companies";

        public function Add(Company $company)
        {
            try
            {
                $query = "INSERT INTO ".$this->tableName." (name, cuit, description, website, street, number_street, aboutUs, active, industry, companyUserId) 
                    VALUES (:name, :cuit, :description, :website, :street, :number_street, :aboutUs, :active, :industry, :companyUserId);";

                $parameters["name"] = $company->getName();
                $parameters["cuit"] = $company->getCuit();
                $parameters["description"] = $company->getDescription();
                $parameters["website"] = $company->getWebsite();
                $parameters["street"] = $company->getStreet();
                $parameters["number_street"] = $company->getNumber();
                $parameters["aboutUs"] = $company->getAboutUs();
                $parameters["active"] = $company->getActive();
                $parameters["industry"] = $company->getIndustry();
                $parameters["companyUserId"] = $company->getCompanyUser()->getUserId();

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
                $companyList = array();

                $query = "SELECT * FROM ".$this->tableName;

                $this->connection = Connection::GetInstance();

                $resultSet = $this->connection->Execute($query);
                
                if($resultSet){
                    foreach ($resultSet as $row)
                    {                
                        $company = new Company();
                        $company->setCompanyId($row["companyId"]);
                        $company->setName($row["name"]);
                        $company->setCuit($row["cuit"]);
                        $company->setDescription($row["description"]);
                        $company->setWebsite($row["website"]);
                        $company->setStreet($row["street"]);
                        $company->setNumber($row["number_street"]);
                        $company->setAboutUs($row["aboutUs"]);
                        $company->setActive($row["active"]);
                        $company->setIndustry($row["industry"]);
                        $company->setCompanyUser((new UserDAO)->FindById($row["companyUserId"]));
            
                        array_push($companyList, $company);
                    }
                    return $companyList;
                }
                
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function DeleteById($companyId)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." SET active = 0 WHERE companyId = :companyId;";

                $parameters["companyId"] = $companyId;

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function FindById($companyId)
        {
            try
            {
                $query = "SELECT * FROM ".$this->tableName.' WHERE (companyId = :companyId);';

                $this->connection = Connection::GetInstance();
                
                $parameters["companyId"] = $companyId;

                $result = $this->connection->Execute($query, $parameters)[0];

                if($result) {
                    $company = new Company();
                    $company->setCompanyId($result["companyId"]);
                    $company->setName($result["name"]);
                    $company->setCuit($result["cuit"]);
                    $company->setDescription($result["description"]);
                    $company->setWebsite($result["website"]);
                    $company->setStreet($result["street"]);
                    $company->setNumber($result["number_street"]);
                    $company->setAboutUs($result["aboutUs"]);
                    $company->setActive($result["active"]);
                    $company->setIndustry($result["industry"]);
                    $company->setCompanyUser((new UserDAO)->FindById($result["companyUserId"]));
                
                    return $company;
                }
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }

        public function getCompanyByCompanyUserId($companyUserId)
        {
            try
            {
                $query = "SELECT * FROM ".$this->tableName.' WHERE (companyUserId = :companyUserId);';

                $this->connection = Connection::GetInstance();
                
                $parameters["companyUserId"] = $companyUserId;

                $result = $this->connection->Execute($query, $parameters)[0];

                if($result) {
                    $company = new Company();
                    $company->setCompanyId($result["companyId"]);
                    $company->setName($result["name"]);
                    $company->setCuit($result["cuit"]);
                    $company->setDescription($result["description"]);
                    $company->setWebsite($result["website"]);
                    $company->setStreet($result["street"]);
                    $company->setNumber($result["number_street"]);
                    $company->setAboutUs($result["aboutUs"]);
                    $company->setActive($result["active"]);
                    $company->setIndustry($result["industry"]);
                
                    return $company;
                }
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }


        public function ModifyById($addingCompany)
        {
            try
            {
                $query = "UPDATE ".$this->tableName." SET name=:name, cuit=:cuit, description=:description, website=:website, 
                street=:street, number_street=:number_street, aboutUs=:aboutUs, active=:active, industry=:industry
                WHERE companyId=:companyId;";

                $parameters["companyId"] = $addingCompany->getCompanyId();
                $parameters["name"] = $addingCompany->getName();
                $parameters["cuit"] = $addingCompany->getCuit();
                $parameters["description"] = $addingCompany->getDescription();
                $parameters["website"] = $addingCompany->getWebsite();
                $parameters["street"] = $addingCompany->getStreet();
                $parameters["number_street"] = $addingCompany->getNumber();
                $parameters["aboutUs"] = $addingCompany->getAboutUs();
                $parameters["active"] = $addingCompany->getActive() == true ? 1 : 0;
                $parameters["industry"] = $addingCompany->getIndustry();

                $this->connection = Connection::GetInstance();

                $this->connection->ExecuteNonQuery($query, $parameters);
            }
            catch(Exception $ex)
            {
                throw $ex;
            }
        }
    }
?>
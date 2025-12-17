<?php
    namespace DAO;

    use Models\Administrator as Administrator;

    interface IAdministratorDAO
    {
        function add(Administrator $Administrator);
        function getAll();
        function deleteById($name);
    }
?>
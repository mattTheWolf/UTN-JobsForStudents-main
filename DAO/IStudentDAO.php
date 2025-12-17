<?php
    namespace DAO;

    use Models\Student as Student;

    interface IStudentDAO{
        function add(Student $student);
        function getAll();
        function deleteById($name);
    }
?>
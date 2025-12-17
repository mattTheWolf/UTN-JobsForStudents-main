<?php
    namespace DAO;

    use Models\CV as CV;

    interface ICVDAO{
        function addCV(CV $cv);
    }
?>
<?php
    namespace DAO;

    use Models\JobOffer as JobOffer;

    interface IJobOfferDAO
    {
        function Add(JobOffer $jobOffer);
        function GetAll();
        function DeleteById($jobOfferId);
        function FindById($jobOfferId);
        function Modify($jobOffer);
    }
?>
<?php
    namespace DAO;

    use Models\Appointment as Appointment;

    interface IAppointmentDAO
    {
        public function Add(Appointment $appointment);
        public function GetAll();
        public function CancelApplyById($studentId, $jobOfferId);
    }
?>
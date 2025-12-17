<?php
    namespace Controllers;

    use Plugins\FPDF as FPDF;

    class PDFController{
        private $pdf;

        public function __construct(){
            ob_clean();
            $this->pdf = new FPDF();
            $this->pdf->SetFont('Helvetica','B',20);
            $this->pdf->SetTextColor(50,60,100);
            $this->pdf->AddPage('P');
            $this->pdf->SetDisplayMode('default');
        }
        
        public function Download($jobOffer, $appointmentList){
            
            $this->pdf->SetTitle($jobOffer->getTitle()." from ".$jobOffer->getCompany()->getName()." Applicants");

            //display the title with a border around it
            $this->pdf->SetXY(50,20);
            $this->pdf->SetDrawColor(50,60,100);
            $this->pdf->Cell(100,10,$jobOffer->getTitle()." from ".$jobOffer->getCompany()->getName(),1,0,'C',0);

            $Y = 1;
            foreach($appointmentList as $eachAppointment){
                //Set x and y position for the main text, reduce font size and write content
                $this->pdf->SetXY (10,30+10*$Y++);
                $this->pdf->SetFontSize(13);
                $this->pdf->Write(5,"DNI: ".$eachAppointment->getStudent()->getDNI()." | Name: ".
                                    $eachAppointment->getStudent()->getFirstName()." ".
                                    $eachAppointment->getStudent()->getLastName()." | Email: ".
                                    $eachAppointment->getStudent()->getEmail());
            }

            
            ob_start();
            $this->pdf->Output($jobOffer->getTitle()." from ".$jobOffer->getCompany()->getName()." Applicants.pdf",'D');
        }
    }
?>
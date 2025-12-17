<?php namespace Models;

class Appointment{

  private $jobOffer;
  private $student;
  private $cv; 
  private $dateAppointment;
  private $referenceURL;
  private $comments;
  private $active;


  public function getDateAppointment()
  {
    return $this->dateAppointment;
  }

  public function setDateAppointment($dateAppointment)
  {
    $this->dateAppointment = $dateAppointment;

    return $this;
  }

  public function getReferenceURL()
  {
    return $this->referenceURL;
  }

  public function setReferenceURL($referenceURL)
  {
    $this->referenceURL = $referenceURL;

    return $this;
  }

  public function getCV()
  {
    return $this->cv;
  }

  public function setCV($cv)
  {
    $this->cv = $cv;

    return $this;
  }

  public function getJobOffer()
  {
    return $this->jobOffer;
  }

  public function setJobOffer($jobOffer)
  {
    $this->jobOffer = $jobOffer;

    return $this;
  }
 
  public function getStudent()
  {
    return $this->student;
  }

  public function setStudent($student)
  {
    $this->student = $student;

    return $this;
  }

  public function getComments(){
    return $this->comments;
  }
  
  public function setComments($comments){
    $this->comments = $comments;
  }

  public function getActive(){
    return $this->active;
  }

  public function setActive($active){
    $this->active = $active;
  }

  public function __tostring(){
    return "<br>CV: ".$this->cv.
           "<br>Student: ".$this->student.
           "<br>Date appointment: ".$this->dateAppointment.
           "<br>Comments: ".$this->comments.
           "<br>Reference url: ".$this->referenceURL;
  }
}
?>
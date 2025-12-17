<?php namespace Models;

class Career{

    private $careerId;
    private $description;
    private $active = true;

    /*Public function __construct($careerId, $title, $description){
        $this->careerId= $careerId;
        $this->title= $title;
        $this->description = $description;
    }*/

    public function getCareerId()
    {
        return $this->careerId;
    }

    public function setCareerId($careerId)
    {
        $this->careerId = $careerId;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    public function __tostring(){
        return "<br>Career ID: ".$this->careerId.
                "<br>Description: ".$this->description.
               "<br>Active: ".$this->active;
    }
}

<?php
namespace App\Core;

class Task {
    protected $id;
    protected $description;
    protected $completed = false;

    public function __construct($id, $desc)
    {
        $this->id = $id;
        $this->description = $desc;
    }

    public function complete()
    {
        $this->completed = true;
    }

    public function isComplete()
    {
        return $this->completed;
    }

    public function getDesc()
    {
        return $this->description;
    }

    public function getID()
    {
        return $this->id;
    }
}

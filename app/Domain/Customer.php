<?php namespace App\Domain;

class Customer
{
    public $id, $name, $email;

    /**
     * @param int    $id
     * @param string $name
     * @param string $email
     */
    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }
}

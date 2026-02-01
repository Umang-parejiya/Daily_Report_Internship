<?php

// inherits properties and methods of another class.

class Employee {    // Base Class (Employee)

    public $name;
    protected $salary;

    public function __construct($name, $salary) {
        $this->name = $name;
        $this->salary = $salary;
    }

    public function getDetails() {
        return "Name: $this->name, Salary: $this->salary";
    }
}

// Child Class (Manager)
class Manager extends Employee {

    public $department;

    public function __construct($name, $salary, $department) {
        parent::__construct($name, $salary);
        $this->department = $department;
    }

    // Method overriding
    public function getDetails() {
        return "Name: $this->name, Salary: $this->salary, Department: $this->department";
    }
}


$mgr = new Manager("Rohit", 80000, "IT");

echo $mgr->getDetails();











?>
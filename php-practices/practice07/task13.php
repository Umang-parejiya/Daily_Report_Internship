<?php

//Namespace is like a folder for classes.
// It prevents class name conflicts.

namespace App\Payment;

class Service {
    public function process() {
        echo "Processing payment...";
    }
}
namespace App\Notification;

class Service {
    public function send() {
        echo "Sending notification...";
    }
}

// Two classes have same name: servise

namespace School\Staff;

class Person {
    public function info() {
        echo "I am a Teacher";
    }
}

namespace School\Members;

class Person {
    public function info() {
        echo "I am a Student";
    }
}
?>
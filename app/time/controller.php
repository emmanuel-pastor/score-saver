<?php
class timeController {
    public function get() {
        $day = date("j");
        $month = date("n");
        $year = date("Y");
        $hour = date("G");
        $minute = date("i");
        $second = date("s");
        $json = '{
            "day":"'. $day .'",
            "month":"'. $month .'",
            "year":"'. $year .'",
            "hour":"'. $hour .'",
            "minute":"'. $minute .'",
            "second":"'. $second .'"
        }';
        echo $json;
    }
}

$action = $_GET['action'];

switch ($action) {
    case "get":
        $controller = new timeController();
        $controller->get();
        break;
    default:
        require dirname(__FILE__) . "/../../public/404.php";
        break;
}
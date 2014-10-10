<?php
$mcuaddress = "192.168.1.1";

$auth = array('login' => "username", 'pass' => "userpassword");

$place_list = array(
    1 => "blank_conference_1",
    2 => "blank_conference_2"
);

$endpoints = array(
    "office_big" => array("name" => "office_big", "override_name"=>"Office: big", "address"=>"192.168.1.10"),
    "office_small" => array("name"=> "office_small", "override_name"=>"Office: small", "address"=>"192.168.1.20"),
    "region_nyc" => array("name"=> "region_nyc", "override_name"=>"Office: NYC", "address"=>"10.10.10.10")
);
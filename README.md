cisco_telepresence_mcu_api_client
=================================

Cisco TelePresence MCU API client written in PHP


## install

install XML-RPC library for PHP5

`sudo apt-get install php5-xmlrpc`

download sources from GitHub

`wget https://github.com/vanzhiganov/cisco_telepresence_mcu_api_client/archive/master.zip`

unpacking

`unzip master`

`cd cisco_telepresence_mcu_api_client-master/`

copy config file from example file

`cp config.origin.php config.php`


## configuration

Enter Cisco TelePresence IP address in $mcuaddress variable

`$mcuaddress = "192.168.1.1";`

Enter Login/Password in $auth variable

`$auth = array('login' => "username", 'pass' => "userpassword");`

Specify conference names for each meeting room

```
$place_list = array(
    1 => "blank_conference_1",
    2 => "blank_conference_2"
);
```

Specify all Endpoints in this list. For correct working you need associate `name` in Cisco TelePresence Admin Panel

```
$endpoints = array(
    "office_big" => array("name" => "office_big", "override_name"=>"Office: big", "address"=>"192.168.1.10"),
    "office_small" => array("name"=> "office_small", "override_name"=>"Office: small", "address"=>"192.168.1.20"),
    "region_nyc" => array("name"=> "region_nyc", "override_name"=>"Office: NYC", "address"=>"10.10.10.10")
);
```
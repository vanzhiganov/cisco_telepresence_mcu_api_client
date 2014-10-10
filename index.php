<?php
include 'config.php';

function qqq($method, $array, $debug = false) {
    global $mcuaddress;

    $request = xmlrpc_encode_request($method, $array);
    $context = stream_context_create(array('http' => array(
        'method' => "POST",
        'header' => "Content-Type: text/xml",
        'content' => $request
    )));

    if ($debug) {
        print("<pre>".$request."</pre>");
        print "<hr/>";
    }

    $file = file_get_contents("http://{$mcuaddress}/RPC2", false, $context);
    $response = xmlrpc_decode($file);

    if ($debug) {
        print("<pre>".$response."</pre>");
        print "<hr/>";
    }

    if ($response && xmlrpc_is_fault($response)) {
        trigger_error("xmlrpc: {$response['faultString']} ({$response['faultCode']})");
    }
    return $response;
}

$place = (isset($_GET['place']) ? (int) $_GET['place'] : 0);
$action = (isset($_GET['action']) ? $_GET['action'] : null);
$name = (isset($_GET['name']) ? $_GET['name'] : null);
?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/foundation.css" />
    <script src="js/vendor/modernizr.js"></script>
    <script src="js/foundation/foundation.alert.js"></script>
    <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</head>
<body>
    <div class="row">
        <div class="large-12 medium-12 columns">
        <?php if (!$place): ?>
            <a href="?place=1" class="button expand">Переговорная большая</a>
            <a href="?place=1" class="button expand">Переговорная малая</a>
        <?php else: ?>
            <a href="#" class="button expand" style="background-color: grey;">Назад</a>
        <?php endif; ?>
        </div>
    </div>
    <?php if ($place > 0): ?>
        <div class="row">
            <div class="large-6 medium-6 columns">
                <a href="?place=<?php echo $place; ?>&action=conference.create" class="button expand alert">Начать конференцию</a>
            </div>
            <div class="large-6 medium-6 columns">
                <a href="?place=<?php echo $place; ?>&action=conference.destroy" class="button expand alert">Закончить конференцию</a>
            </div>
        </div>

        <div class="row">
            <div class="large-12 medium-12 columns">
                <?php if ($action == "conference.create"): ?>
                    <?php $result = qqq("conference.destroy", array ( "authenticationUser"=>$auth['login'], "authenticationPassword"=>$auth['pass'], "conferenceName" => $place_list[$place])); ?>
                    <?php $result = qqq("conference.create", array ( "authenticationUser"=>$auth['login'], "authenticationPassword"=>$auth['pass'], "conferenceName" => $place_list[$place])); ?>
                    <?php if ($result['status'] == 'operation successful'): ?>
                        <div data-alert class="alert-box success">
                            Конференция создана! Добавьте участников.
                            <a href="#" class="close">&times;</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($action == "conference.destroy"): ?>
                    <?php $result = qqq("conference.destroy", array ( "authenticationUser"=>$auth['login'], "authenticationPassword"=>$auth['pass'], "conferenceName" => $place_list[$place])); ?>
                    <div data-alert class="alert-box success">
                        Конференция завершена.
                        <a href="#" class="close">&times;</a>
                    </div>
                <?php endif; ?>
                <?php if ($action == "participant.add"): ?>
                    <?php // $result = qqq("participant.add", array ("authenticationUser"=>$auth['login'], "authenticationPassword"=>$auth['pass'], "conferenceName" => $place_list[$place], "participantName" => "office_small", "address" => "172.16.2.80")); ?>
                    <?php $result = qqq("participant.add", array ("authenticationUser"=>$auth['login'], "authenticationPassword"=>$auth['pass'], "conferenceName" => $place_list[$place], "participantName" => $name, "address" => $endpoints[$name]['address'])); ?>
                    <?php if ($result['status'] == 'operation successful'): ?>
                        <div data-alert class="alert-box success">
                            Участник добавлен
                            <a href="#" class="close">&times;</a>
                        </div>
                    <?php else: ?>
                        <div data-alert class="alert-box warning">
                            Что-то пошло не так. Зовите админа.
                            <a href="#" class="close">&times;</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if ($action == "participant.disconnect"): ?>
                    <?php // $result = qqq("participant.add", array ("authenticationUser"=>$auth['login'], "authenticationPassword"=>$auth['pass'], "conferenceName" => $place_list[$place], "participantName" => "office_small", "address" => "172.16.2.80")); ?>
                    <?php $result = qqq("participant.disconnect", array ("authenticationUser"=>$auth['login'], "authenticationPassword"=>$auth['pass'], "conferenceName" => $place_list[$place], "participantName" => $name, "address" => $endpoints[$name]['address'])); ?>
                    <?php echo json_encode($result); ?>
                    <?php if ($result['status'] == 'operation successful'): ?>
                        <div data-alert class="alert-box">
                            Участник отключен
                            <a href="#" class="close">&times;</a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="large-12 medium-12 columns">
                <hr/>
            </div>
        </div>
        <?php /* $result = qqq("conference.status", array ( "authenticationUser"=>$auth['login'], "authenticationPassword"=>$auth['pass'], "conferenceName" => $place_list[$place])); ?>
    <!-- table border="1">
    <?php foreach($result as $parameter => $val) { ?>
        <tr>
            <td><?php echo $parameter; ?></td>
            <td><?php print_r($val); ?></td>
        </tr>
    <?php }; */ ?>
    <!/table!>
        <div class="row">
            <div class="large-12 medium-12 columns">
                <h3>Участники конференции</h3>
            </div>
        </div>
        <div class="row">
            <table width="100%">
                <?php foreach($endpoints as $id => $ep): ?>
                <tr>
                    <td class="large-8 medium-8">
                        <a href="?place=<?php echo $place; ?>&action=participant.add&name=<?php echo $id;?>" class="button expand success"><?php echo $ep['override_name'];?></a>
                    </td>
                    <td class="large-4 medium-4">
                        <a href="?place=<?php echo $place; ?>&action=participant.disconnect&name=<?php echo $id;?>" class="button expand alert">Отключить</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

        </div>
    <?php endif; ?>
</body>
</html>
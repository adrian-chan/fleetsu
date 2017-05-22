<?php

    require_once ('bootstrap.php');

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    $app = new Slim\App($config);

    $app->get('/', function () {

    });

    //ROUTE FOR BACKEND API CALL
    $app->get('/device-status', function (Request $request, Response $response) {

        // Load Data Source
        $dataSource = __DIR__ . ('/datasource/device.csv');
        $data = helpers::getFromCsv($dataSource);

        // Process the status for each device
        $withStatus = array_map(function ($row) {

            // init checking parameters
            $checkDate = new DateTime($row['Last Reported DateTime']);
            $currDate  = new DateTime();

            // 24 hour range
            $tsRange = 60 * 60 * 24;

            // process the status accordingly
            $row['Status'] = (abs($currDate->getTimestamp() - $checkDate->getTimestamp()) <= $tsRange ) ? 'OK' : 'OFFLINE';

            return $row;

         }, $data);

        $response->getBody()->write(json_encode($withStatus));
        $newResponse = $response->withHeader(
           'Content-type',
           'application/json'
        );

        return $newResponse;
    });

    //ROUTE FOR FOR FRONTEND
    $app->get('device-report', function () {
            
    });

    $app->run();
?>
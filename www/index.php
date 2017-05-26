<?php
    require_once ('bootstrap.php');

    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    $app = new Slim\App($config);

    $app->get('/', function () {
            echo "Fleetsu Assignment";
        });

    $container = $app->getContainer();

    // Register Twig View helper
    $container['view'] = function ($c) {
        $view = new \Slim\Views\Twig(__DIR__ . '/views');

        // Instantiate and add Slim specific extension
        $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
        $view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

        return $view;
    };

    //ROUTE FOR BACKEND API CALL
    $app->get('/device-status', function (Request $request, Response $response) {

            // Load Data Source
            $dataSource = __DIR__ . ('/datasource/demo.csv');
            $data = helpers::getFromCsv($dataSource);

            // Process the status for each device
            $withStatus = array_map(function ($row) {

                    // init checking parameters
                    $checkDate = new DateTime($row['Last Reported DateTime']);
                    $currDate  = new DateTime();

                    // 24 hour range = 86400
                    $tsRange = 60 * 60 * 24;
                    $tsDiff = $currDate->getTimestamp() - $checkDate->getTimestamp();

                    // add to statuses to array for current row
                    $row['Status'] = ($tsDiff <= $tsRange && $tsDiff >= 0 ) ? 'OK' : 'OFFLINE';
                    $row['tsDiff'] = $tsDiff;

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
    $app->get('/device-report', function (Request $request, Response $response) {

            $current = new DateTime();

            $data["current"] = $current->format("Y-m-d h:m");

           return $this->view->render($response, 'device.html', $data);
        });

    $app->run();
?>
<?php

declare(strict_types=1);

use Slim\App;

return function (App $app) {
    $app->add(new Tuupola\Middleware\JwtAuthentication([
        "relaxed" => ["localhost", "example.com"],
        "secret" => '12345000',
        "algorithm" => ["HS256"],
        "attribute" => "jwt",
        "error" => function ($response, $arguments) {
            $data["status"] = "error";
            $data["message"] = $arguments["message"];

            $response->getBody()->write(
                json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            );

            return $response->withHeader("Content-Type", "application/json");
        },
    ]));
};

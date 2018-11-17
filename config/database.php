<?php

return [
    "database" => [
        "driver" => getenv("DB_DRIVER") ? : "sqlite",
        "host" => getenv("DB_HOST") ? : "http://localhost",
        "port" => getenv("DB_PORT") ? : "",
        "database" => getenv("DB_NAME") ? sqliteName(getenv("DB_NAME")) : "",
        "name" => getenv("DB_NAME") ? : "",
        "username" => getenv("DB_USERNAME") ? : "root",
        "password" => getenv("DB_PASSWORD") ? : "",
    ]
];
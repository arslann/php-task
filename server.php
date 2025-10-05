<?php


if(!isset($_COOKIE['auth_token'])) {
    fetchAuthToken();
} else {
    fetchData();
}

function fetchAuthToken() {
    $loginCurl = curl_init();
    curl_setopt_array($loginCurl, [
        CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            "username" => "365",
            "password" => "1"
        ]),
        CURLOPT_HTTPHEADER => [
            "Authorization: Basic QVBJX0V4cGxvcmVyOjEyMzQ1NmlzQUxhbWVQYXNz",
            "Content-Type: application/json"
        ],
    ]);
    $loginResponse = curl_exec($loginCurl);
    curl_close($loginCurl);
    $loginData = json_decode($loginResponse, true);

    // var_dump($loginData);

    $accessToken = $loginData['oauth']['access_token'] ?? null;

    echo $accessToken;

    if(isset($accessToken)) {
        setcookie('auth_token', $accessToken, time() + 1200);
    }
}

function fetchData() {
    $tasksCurl = curl_init();
    curl_setopt_array($tasksCurl, [
        CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/v1/tasks/select",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer {$_COOKIE['auth_token']}",
            "Content-Type: application/json"
        ],
    ]);
    $tasksResponse = curl_exec($tasksCurl);
    curl_close($tasksCurl);

    $tasks = json_decode($tasksResponse, true);

    print_r($tasks);
}

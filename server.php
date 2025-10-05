<?php


if(!isset($_COOKIE['auth_token'])) {
    fetchAuthToken();
} else {
    fetchData($_COOKIE['auth_token']);
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

    // echo $accessToken;

    if(isset($accessToken)) {
        setcookie('auth_token', $accessToken, time() + 1200);
        fetchData($accessToken);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Failed to get auth token']);
    }
}

function fetchData($token) {
    $tasksCurl = curl_init();
    curl_setopt_array($tasksCurl, [
        CURLOPT_URL => "https://api.baubuddy.de/dev/index.php/v1/tasks/select",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ],
    ]);
    $tasksResponse = curl_exec($tasksCurl);

    $httpCode = curl_getinfo($tasksCurl, CURLINFO_HTTP_CODE);
    curl_close($tasksCurl);

    $tasks = json_decode($tasksResponse, true);

    if($httpCode == 200) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true,'data' => $tasks, 'message'=> 'Success']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error while fetching data']);
    }

}

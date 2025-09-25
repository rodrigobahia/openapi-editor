<?php
function getBlankOpenAPITemplate($projectName) {
    return [
        "openapi" => "3.0.0",
        "info" => [
            "title" => $projectName,
            "description" => "API documentation for " . $projectName,
            "version" => "1.0.0",
            "contact" => [
                "name" => "API Support",
                "email" => "support@example.com"
            ]
        ],
        "servers" => [
            [
                "url" => "https://api.example.com/v1",
                "description" => "Production server"
            ]
        ],
        "security" => [],
        "tags" => [],
        "paths" => new stdClass(),
        "components" => [
            "schemas" => new stdClass(),
            "securitySchemes" => new stdClass()
        ]
    ];
}

function saveOpenAPIFile($filename, $data) {
    $filePath = __DIR__ . '/../files/' . $filename . '.json';
    return file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function loadOpenAPIFile($filename) {
    $filePath = __DIR__ . '/../files/' . $filename;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        return json_decode($content, true);
    }
    return null;
}

function getOpenAPIFiles() {
    $filesDir = __DIR__ . '/../files/';
    $files = [];
    
    if (is_dir($filesDir)) {
        $scanFiles = scandir($filesDir);
        foreach ($scanFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                $files[] = $file;
            }
        }
    }
    
    return $files;
}

function deleteOpenAPIFile($filename) {
    $filePath = __DIR__ . '/../files/' . $filename;
    if (file_exists($filePath)) {
        return unlink($filePath);
    }
    return false;
}
?>
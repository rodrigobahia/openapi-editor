<?php
require_once 'assets/translate.php';
require_once 'assets/openapi-helper.php';

if (!isset($_GET['file']) || !isset($_GET['format'])) {
    header('Location: index.php');
    exit;
}

$filename = $_GET['file'];
$format = $_GET['format'];
$openApiData = loadOpenAPIFile($filename);

if (!$openApiData) {
    header('Location: index.php?error=file_not_found');
    exit;
}

$baseFilename = pathinfo($filename, PATHINFO_FILENAME);

if ($format === 'json') {
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="' . $baseFilename . '.json"');
    echo json_encode($openApiData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
} elseif ($format === 'yaml') {
    // Conversão simples para YAML (você pode usar uma biblioteca mais robusta como symfony/yaml)
    header('Content-Type: application/x-yaml');
    header('Content-Disposition: attachment; filename="' . $baseFilename . '.yaml"');
    
    function arrayToYaml($array, $indent = 0) {
        $yaml = '';
        $indentStr = str_repeat('  ', $indent);
        
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (array_keys($value) === range(0, count($value) - 1)) {
                    // Array numérico
                    $yaml .= $indentStr . $key . ":\n";
                    foreach ($value as $item) {
                        if (is_array($item)) {
                            $yaml .= $indentStr . "- \n" . arrayToYaml($item, $indent + 1);
                        } else {
                            $yaml .= $indentStr . "- " . (is_string($item) ? '"' . addslashes($item) . '"' : $item) . "\n";
                        }
                    }
                } else {
                    // Array associativo
                    $yaml .= $indentStr . $key . ":\n" . arrayToYaml($value, $indent + 1);
                }
            } else {
                $yaml .= $indentStr . $key . ": " . (is_string($value) ? '"' . addslashes($value) . '"' : $value) . "\n";
            }
        }
        
        return $yaml;
    }
    
    echo arrayToYaml($openApiData);
    exit;
}

header('Location: index.php');
?>
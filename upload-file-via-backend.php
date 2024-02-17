<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Specify the path to your project root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Read and decode the incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);

$filename = $data['filename']; // The filename sent from the frontend
$fileContent = base64_decode($data['fileContent']); // Decoding the base64 content
$mimeType = $data['mimeType']; // MIME type of the file


// Create an S3Client
$s3Client = new S3Client([
    'version'     => 'latest',
    'region'      => 'blr1',
    'endpoint' => 'https://blr1.digitaloceanspaces.com',
    'use_path_style_endpoint' => false, // Configures to use subdomain/virtual calling format.
    'credentials' => [
        'key'    => $_ENV['AWS_ACCESS_KEY_ID'] ?? getenv('AWS_ACCESS_KEY_ID'),
        'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? getenv('AWS_SECRET_ACCESS_KEY'),
    ],
]);
$bucketName = 'drmahima'; // The bucket name

// Generating a unique prefix using the current timestamp and a random number
$uniquePrefix = time() . rand(1000, 9999) . '_';
$key = $uniquePrefix . $filename; // Constructing the key with a unique prefix


try {
    $result = $s3Client->putObject([
        'Bucket'     => $bucketName,
        'Key'        => $key,
        'Body'       => $fileContent,
        'ContentType'=> $mimeType,
        'ACL'        => 'public-read',
    ]);

    // Return the URL of the uploaded file as JSON
    header('Content-Type: application/json');
    echo json_encode(['url' => $result['ObjectURL']]);

} catch (AwsException $e) {
    // Return error message as JSON
    http_response_code(500); // Internal Server Error
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Error uploading file: ' . $e->getMessage()]);
}
?>

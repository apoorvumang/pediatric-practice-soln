<?php
// require('connect.php');
// include('header_db_link.php');

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Specify the path to your project root
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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

$bucketName = 'drmahima'; // Replace with your actual bucket name

// Assume you receive the original file extension from the frontend
$originalFileName = $_GET['filename'] ?? ''; // or use POST if you prefer
$fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

// Ensure the file extension is safe (e.g., not executing PHP files etc.)
// $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf']; // Add more as needed
// if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
//     header('HTTP/1.1 400 Bad Request');
//     echo json_encode(['error' => 'Invalid file extension.']);
//     exit;
// }

// Generate a unique file name or key with the original extension
$key = uniqid() . '.' . $fileExtension;

try {
    // Generate a pre-signed URL for PUT operations
    $cmd = $s3Client->getCommand('PutObject', [
        'Bucket' => $bucketName,
        'Key'    => $key,
        'ACL'    => 'public-read', // or use 'private' depending on your needs
    ]);

    // The URL will be valid for 20 minutes
    $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');

    // Get the actual pre-signed URL
    $presignedUrl = (string)$request->getUri();

    // Return the URL in a JSON response
    header('Content-Type: application/json');
    echo json_encode(['url' => $presignedUrl, 'key' => $key]);
} catch (AwsException $e) {
    // Output error message if something goes wrong
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => $e->getMessage()]);
}

?>
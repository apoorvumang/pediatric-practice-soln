<?php

require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Configure the S3 client
$s3Client = new S3Client([
    'version'     => 'latest',
    'region'      => 'blr1',
    'endpoint' => 'https://blr1.digitaloceanspaces.com',
    'use_path_style_endpoint' => false, // Configures to use subdomain/virtual calling format.
    'credentials' => [
        'key'    => 'DO00ACXR4T2BDNJPFMYJ',
        'secret' => 'tS8XQqnP0P7OQyAQg6vNr9UnBf90EkooLYNE+Jzotw0',
    ],
]);


// The bucket name and file path on S3
$bucketName = 'drmahima';
// Generating a unique prefix using the current timestamp and a random number
$uniquePrefix = time() . rand(1000, 9999) . '_';
$key = $uniquePrefix . basename('images/email_32.png'); // This will use the filename as the key in S3 with a unique prefix

// Path to the file to upload
$filePath = 'images/email_32.png';

try {
    $result = $s3Client->putObject([
        'Bucket'     => $bucketName,
        'Key'        => $key,
        'SourceFile' => $filePath,
        'ACL'        => 'public-read', // Make the file publicly accessible
    ]);
    

    echo "File uploaded successfully. Object URL is: <a href='" . $result['ObjectURL'] . "' target='_blank'>" . $result['ObjectURL'] . "</a>" . PHP_EOL;

} catch (AwsException $e) {
    // Output error message if something goes wrong
    echo "Error uploading file: " . $e->getMessage() . PHP_EOL;
}


?>
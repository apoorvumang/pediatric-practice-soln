<?php
/**
 * GitHub Webhook Deployment Script
 *
 * Automatically pulls latest code when a push to master is detected.
 * Secured via GitHub's HMAC-SHA256 webhook signature verification.
 *
 * Setup:
 * 1. Add DEPLOY_SECRET=<your-secret> to .env
 * 2. Add a webhook in GitHub repo settings:
 *    - URL: https://drmahima.com/deploy.php
 *    - Content type: application/json
 *    - Secret: same value as DEPLOY_SECRET
 *    - Events: Just the push event
 */

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$secret = $_ENV['DEPLOY_SECRET'] ?? '';
if (empty($secret)) {
    http_response_code(500);
    exit('Deploy secret not configured');
}

$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents('php://input');

if (empty($signature) || empty($payload)) {
    http_response_code(403);
    exit('Missing signature or payload');
}

$expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);
if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    exit('Invalid signature');
}

$data = json_decode($payload, true);

$ref = $data['ref'] ?? '';
if ($ref !== 'refs/heads/master') {
    http_response_code(200);
    exit('Not master branch, skipping');
}

$log = '/tmp/deploy-' . date('Y-m-d_H-i-s') . '.log';

$commands = [
    'cd ' . __DIR__,
    'git fetch origin master 2>&1',
    'git reset --hard origin/master 2>&1',
    'composer install --no-dev --no-interaction 2>&1',
];

$output = '';
foreach ($commands as $command) {
    $output .= "$ {$command}\n";
    $output .= shell_exec($command) . "\n";
}

file_put_contents($log, $output);

http_response_code(200);
echo "Deployed successfully\n";

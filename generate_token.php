<?php
// Generate a token for the first user (admin)
require_once __DIR__ . '/vendor/autoload.php';

use App\Models\ApiToken;

$tokenModel = new ApiToken();
echo "Generated Token for userID 1: " . $tokenModel->generate(1) . "\n";

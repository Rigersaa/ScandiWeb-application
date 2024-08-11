<?php
require 'vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;

// Load database configuration
$config = include('config/database.php');

/** @var PDO $pdo */
$pdo = new PDO($config['dsn'], $config['username'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Include GraphQL type definitions and resolvers
require 'src/GraphQL/Types.php';
require 'src/GraphQL/Queries.php';
require 'src/GraphQL/Mutations.php';

// Define schema
$schema = new Schema([
    'query' => $queryType,
    'mutation' => $mutationType,
]);

// Handle GraphQL requests
$input = file_get_contents('php://input');
$data = json_decode($input, true);
$query = $data['query'];
$variables = $data['variables'] ?? [];

$result = GraphQL::executeQuery($schema, $query, null, null, $variables);
$output = $result->toArray();
header('Content-Type: application/json');
echo json_encode($output);

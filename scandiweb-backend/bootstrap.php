<?php
require 'vendor/autoload.php';

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

// Database configuration
$config = require 'config/database.php';

try {
    $pdo = new PDO($config['dsn'], $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error mode
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Define GraphQL types
$categoryType = new ObjectType([
    'name' => 'Category',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::int())],
        'name' => ['type' => Type::string()],
    ],
]);

$productType = new ObjectType([
    'name' => 'Product',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::int())],
        'name' => ['type' => Type::string()],
        'category' => ['type' => $categoryType],
        'price' => ['type' => Type::float()],
    ],
]);

$orderType = new ObjectType([
    'name' => 'Order',
    'fields' => [
        'id' => ['type' => Type::nonNull(Type::int())],
        'productId' => ['type' => Type::nonNull(Type::int())],
        'quantity' => ['type' => Type::nonNull(Type::int())],
        'createdAt' => ['type' => Type::string()],
    ],
]);

$orderInputType = new ObjectType([
    'name' => 'OrderInput',
    'fields' => [
        'productId' => ['type' => Type::nonNull(Type::int())],
        'quantity' => ['type' => Type::nonNull(Type::int())],
    ],
]);

$productInputType = new ObjectType([
    'name' => 'ProductInput',
    'fields' => [
        'name' => ['type' => Type::nonNull(Type::string())],
        'categoryId' => ['type' => Type::nonNull(Type::int())],
        'price' => ['type' => Type::nonNull(Type::float())],
    ],
]);

// Define GraphQL query and mutation
require 'src/GraphQL/Queries.php';
require 'src/GraphQL/Mutations.php';

$schema = new Schema([
    'query' => $queryType,
    'mutation' => $mutationType,
]);

// Handle GraphQL requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $query = $data['query'];
    $variables = $data['variables'] ?? [];

    try {
        $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
        $output = $result->toArray();
    } catch (\Exception $e) {
        $output = ['errors' => [$e->getMessage()]];
    }

    header('Content-Type: application/json');
    echo json_encode($output);
} else {
    echo "Please send a POST request with GraphQL query.";
}

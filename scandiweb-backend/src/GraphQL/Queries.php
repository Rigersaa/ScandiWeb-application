<?php
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

global $pdo; // Ensure $pdo is globally available

$queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'categories' => [
            'type' => Type::listOf($categoryType),
            'resolve' => function($root, $args) {
                global $pdo;
                $stmt = $pdo->query('SELECT * FROM categories');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        ],
        'products' => [
            'type' => Type::listOf($productType),
            'resolve' => function($root, $args) {
                global $pdo;
                $stmt = $pdo->query('SELECT * FROM products');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        ],
        'orders' => [
            'type' => Type::listOf($orderType),
            'resolve' => function($root, $args) {
                global $pdo;
                $stmt = $pdo->query('SELECT * FROM orders');
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        ]
    ]
]);

<?php
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

global $pdo; // Ensure $pdo is globally available

$mutationType = new ObjectType([
    'name' => 'Mutation',
    'fields' => [
        'createOrder' => [
            'type' => $orderType,
            'args' => [
                'input' => Type::nonNull($orderInputType),
            ],
            'resolve' => function($root, $args) {
                global $pdo;
                $input = $args['input'];
                $stmt = $pdo->prepare('INSERT INTO orders (product_id, quantity) VALUES (:productId, :quantity)');
                $stmt->execute([
                    ':productId' => $input['productId'],
                    ':quantity' => $input['quantity']
                ]);

                return [
                    'id' => $pdo->lastInsertId(),
                    'productId' => $input['productId'],
                    'quantity' => $input['quantity'],
                    'createdAt' => date('Y-m-d H:i:s')
                ];
            }
        ],
        'createProduct' => [
            'type' => $productType,
            'args' => [
                'input' => Type::nonNull($productInputType),
            ],
            'resolve' => function($root, $args) {
                global $pdo;
                $input = $args['input'];
                $stmt = $pdo->prepare('INSERT INTO products (name, category_id, price) VALUES (:name, :categoryId, :price)');
                $stmt->execute([
                    ':name' => $input['name'],
                    ':categoryId' => $input['categoryId'],
                    ':price' => $input['price']
                ]);

                return [
                    'id' => $pdo->lastInsertId(),
                    'name' => $input['name'],
                    'attributes' => []
                ];
            }
        ]
    ]
]);

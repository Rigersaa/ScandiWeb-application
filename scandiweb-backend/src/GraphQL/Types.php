<?php
// src/GraphQL/Types.php
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\InputObjectType;

// Define Attribute Type
$attributeType = new ObjectType([
    'name' => 'Attribute',
    'fields' => [
        'id' => Type::int(),
        'value' => Type::string(),
        'type' => Type::string(),
    ],
]);

// Define Category Type
$categoryType = new ObjectType([
    'name' => 'Category',
    'fields' => [
        'id' => Type::int(),
        'name' => Type::string(),
        'type' => Type::string(),
    ],
]);

// Define Product Type
$productType = new ObjectType([
    'name' => 'Product',
    'fields' => [
        'id' => Type::int(),
        'name' => Type::string(),
        'attributes' => Type::listOf($attributeType),
    ],
]);

// Define Order Type
$orderType = new ObjectType([
    'name' => 'Order',
    'fields' => [
        'id' => Type::int(),
        'productId' => Type::int(),
        'quantity' => Type::int(),
        'createdAt' => Type::string(),
    ],
]);

// Define Input Types for Mutations
$productInputType = new InputObjectType([
    'name' => 'ProductInput',
    'fields' => [
        'name' => Type::nonNull(Type::string()),
        'categoryId' => Type::nonNull(Type::int()),
        'price' => Type::nonNull(Type::float()),
    ],
]);

$orderInputType = new InputObjectType([
    'name' => 'OrderInput',
    'fields' => [
        'productId' => Type::nonNull(Type::int()),
        'quantity' => Type::nonNull(Type::int()),
    ],
]);

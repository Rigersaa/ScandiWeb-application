<?php

$servername = "localhost";
$username = "root";
$password = "new_password"; 
$dbname = "scandiweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read data from JSON file
$jsonData = file_get_contents('data.json');
$data = json_decode($jsonData, true);

// Insert categories
if (isset($data['data']['categories'])) {
    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
    foreach ($data['data']['categories'] as $category) {
        $stmt->bind_param("s", $category['name']);
        $stmt->execute();
    }
    $stmt->close();
}

// Insert products
if (isset($data['data']['products'])) {
    $stmt = $conn->prepare("INSERT INTO products (id, name, price, category_id, description, brand) VALUES (?, ?, ?, (SELECT id FROM categories WHERE name = ?), ?, ?)");
    foreach ($data['data']['products'] as $product) {
        $price = $product['prices'][0]['amount'] ?? 0;
        $stmt->bind_param("ssdsss", $product['id'], $product['name'], $price, $product['category'], $product['description'], $product['brand']);
        $stmt->execute();

        // Insert attributes
        if (isset($product['attributes'])) {
            $productId = $product['id'];
            foreach ($product['attributes'] as $attributeSet) {
                $stmtAttr = $conn->prepare("INSERT INTO attributes (product_id, name, value) VALUES (?, ?, ?)");
                foreach ($attributeSet['items'] as $item) {
                    $stmtAttr->bind_param("sss", $productId, $attributeSet['name'], $item['value']);
                    $stmtAttr->execute();
                }
                $stmtAttr->close();
            }
        }
    }
    $stmt->close();
}

// Close connection
$conn->close();

echo "Database populated successfully!";
?>

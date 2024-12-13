<?php

require_once __DIR__ . '/../database/db.php';

class CartModel {
    public static function addToCart($session_id, $product_id, $quantity) {
        global $pdo;
        $query = $pdo->prepare("INSERT INTO cart (session_id, product_id, quantity) VALUES (?, ?, ?)");
        $query->execute([$session_id, $product_id, $quantity]);
    }

    public static function getCartItems($session_id) {
        global $pdo;
        $query = $pdo->prepare("
            SELECT cart.id, cart.product_id, cart.quantity, products.nome, products.descricao, products.preco, products.discount_price, products.desconto
            FROM cart
            JOIN products ON cart.product_id = products.id
            WHERE cart.session_id = ?
        ");
        $query->execute([$session_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
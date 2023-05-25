<?php
class ProductGateway {

private PDO $conn;
public function __construct(Database $database){
    // db connection
$this->conn = $database->getConnection(); 
}

// get all product
public function getAll():array{
$sql = "SELECT * FROM  product";
$stmt = $this->conn->query($sql);

return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
// create product product
public function create($data):string{
    $sql = 'INSERT INTO product (name, size, is_available) VALUES (:name, :size, :is_available)';
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":name" , $data["name"], PDO::PARAM_STR);
    $stmt->bindValue(":size", $data["size"], PDO::PARAM_INT);
    $stmt->bindValue(":is_available", (bool) $data["is_available"] ?? false, PDO::PARAM_BOOL);
    $stmt->execute();
    return $this->conn->lastInsertId();
}
// get single product
public function findById($id){
    $sql = "SELECT * FROM product WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
   
    return $data;
}

// update product
public function updateProduct($data, $newdata){
    $sql = 'UPDATE product SET name = :name, size = :size, is_available = :is_available WHERE id = :id';
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":name" , $newdata["name"]?? $data["name"], PDO::PARAM_STR);
    $stmt->bindValue(":size" , $newdata["size"]?? $data["size"], PDO::PARAM_STR);
    $stmt->bindValue(":is_available" , $newdata["is_available"]?? $data["is_available"], PDO::PARAM_STR);
    $stmt->bindValue(":id" , $newdata["id"]?? $data["id"], PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount();
}
public function deleteById($id){
    $sql = "DELETE FROM product WHERE id = :id";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    
   
    return $stmt->rowCount();
}

}
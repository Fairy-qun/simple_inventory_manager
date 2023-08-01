<?php
$warehouse_code = $_POST['warehouse_code'];
$material_code = $_POST['material_code'];

// echo $warehouse_code;
// echo $material_code;

if (empty($warehouse_code) && empty($material_code)) {
  echo "noValue";
} else if (empty($warehouse_code) || empty($material_code)) {
  try {
    include("../pdo/pdo.php");
    $sql = "select a.id,a.warehouse_code,a.material_code,a.quantity,b.warehouse_name,c.material_name from (inventory a left join warehouse b on a.warehouse_code = b.warehouse_code) left join material c on a.material_code=c.material_code where a.warehouse_code=? or a.material_code=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $warehouse_code);
    $stmt->bindParam(2, $material_code);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
} else {
  try {
    include("../pdo/pdo.php");
    $sql = "select a.id,a.warehouse_code,a.material_code,a.quantity,b.warehouse_name,c.material_name from (inventory a left join warehouse b on a.warehouse_code = b.warehouse_code) left join material c on a.material_code=c.material_code where a.warehouse_code=? and a.material_code=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $warehouse_code);
    $stmt->bindParam(2, $material_code);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

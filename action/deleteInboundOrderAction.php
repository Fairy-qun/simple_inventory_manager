<?php
$inbound_order_code = isset($_POST['inbound_order_code']) ? $_POST['inbound_order_code'] : '';
$warehouse_code = isset($_POST['warehouse_code']) ? $_POST['warehouse_code'] : '';
$material_code = isset($_POST['material_code']) ? $_POST['material_code'] : '';
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';

if (empty($inbound_order_code) || empty($warehouse_code) || empty($material_code) || empty($quantity)) {
  echo 'noValue';
} else {
  // echo $inbound_order_code . '---' . $warehouse_code . '---' . $material_code;

  try {
    include("../pdo/pdo.php");
    // 1.开启事务之前要将自动提交关闭
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    // 2.开启事务
    $pdo->beginTransaction();

    $sql = "delete from inbound_order where inbound_order_code = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $inbound_order_code);
    $stmt->execute();

    $sql = "delete from inbound_detail where inbound_order_code = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $inbound_order_code);
    $stmt->execute();

    $sql = "select quantity from inventory where warehouse_code=? and material_code=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $warehouse_code);
    $stmt->bindParam(2, $material_code);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($data) > 0) {
      foreach ($data as $v) {
        // 如果当前数量与库存数量相同，直接删除库存信息
        if ((int)$quantity == (int)$v['QUANTITY']) {
          $sql = "delete from inventory where warehouse_code=? and material_code=?";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(1, $warehouse_code);
          $stmt->bindParam(2, $material_code);
          $stmt->execute();
        } else {
          // 如果当前数量与库存数量不同，则库存数量更新操作
          $new_quantity = (int)$v['QUANTITY'] - (int)$quantity;
          $sql = "update inventory set quantity=? where warehouse_code=? and material_code=?";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(1, $new_quantity);
          $stmt->bindParam(2, $warehouse_code);
          $stmt->bindParam(3, $material_code);
          $stmt->execute();
        }
      }
    }
    // 都成功执行后关闭事务
    $pdo->commit();
    echo "success";
  } catch (PDOException $e) {
    echo $e->getMessage();
    // 事务失败时进行回滚操作
    $pdo->rollBack();
  }
  // 不管成功与否，都需要在结束时将自动提交打开
  $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
}

<?php
$state;
$arr = isset($_POST['data']) ? $_POST['data'] : '';
$inbound_order_code = isset($_POST['inbound_order_code']) ? trim($_POST['inbound_order_code']) : '';
$warehouse_code = isset($_POST['warehouse_code']) ? trim($_POST['warehouse_code']) : '';
$inbound_time = isset($_POST['inbound_time']) ? trim($_POST['inbound_time']) : '';
$operator = isset($_POST['operator']) ? trim($_POST['operator']) : '';
$remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';

$create_time = date("Y-m-d H:i:s");

if (empty($inbound_order_code) || empty($warehouse_code) || empty($inbound_time || empty($operator)) || empty($arr)) {
  $state = 'noValue';
  echo $state;
} else {

  try {
    include("../pdo/pdo.php");
    // 关闭自动提交
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    // 开启事务
    $pdo->beginTransaction();

    $material_code;
    $quantity;
    if (is_array($arr)) {
      foreach ($arr as $v) {
        $material_code = $v['material_code'];
        $quantity = $v['quantity'];
      }
    }

    // 1.首先需要查看库存中是否有该物料，库存是否充足
    $sql2 = "select * from inventory where warehouse_code=? and material_code=?";
    $stmt = $pdo->prepare($sql2);
    $stmt->bindParam(1, $warehouse_code);
    $stmt->bindParam(2, $material_code);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($data) > 0) {
      // 有数据，修改
      foreach ($data as $v) {
        $old_quantity = (int)$v['QUANTITY'];
        if ($old_quantity < $quantity) {
          echo 'understock';
        } else {

          // 出库详情
          $sql1 = "insert into outbound_detail(outbound_order_code,material_code,quantity) values(?,?,?)";
          $stem = $pdo->prepare($sql1);
          $stem->bindParam(1, $inbound_order_code);
          $stem->bindParam(2, $material_code);
          $stem->bindParam(3, $quantity);
          $stem->execute();

          // 对库存进行相应减少操作
          $sql3 = "update inventory set quantity = :old_quantity - :new_quantity  where warehouse_code=:warehouse_code and material_code=:material_code";
          $stmt = $pdo->prepare($sql3);
          $stmt->bindParam(':old_quantity', $old_quantity);
          $stmt->bindParam(':new_quantity', $quantity);
          $stmt->bindParam(':warehouse_code', $warehouse_code);
          $stmt->bindParam(':material_code', $material_code);
          $stmt->execute();

          // 出库单数据
          $sql = "insert into outbound_order(outbound_order_code,warehouse_code,outbound_time,operator,create_time,remark) values(?,?,to_date(to_char(?),'yyyy-MM-dd hh24:mi:ss'),?,to_date(to_char(?),'yyyy-MM-dd hh24:mi:ss'),?)";

          $stmt = $pdo->prepare($sql);

          $stmt->bindParam(1, $inbound_order_code);
          $stmt->bindParam(2, $warehouse_code);
          $stmt->bindParam(3, $inbound_time);
          $stmt->bindParam(4, $operator);
          $stmt->bindParam(5, $create_time);
          $stmt->bindParam(6, $remark);

          $stmt->execute();

          $state = 'success';
          echo $state;
        }
      }
    } else {
      // 无数据，库存不足
      echo 'understock';
    }
    $pdo->commit();
  } catch (PDOException $e) {
    echo $e->getMessage();
    // 事务失败时进行回滚操作
    $pdo->rollBack();
  }
  // 不管成功与否，都需要在结束时将自动提交打开
  $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
}

<?php
$arr = isset($_POST['data']) ? $_POST['data'] : '';
$inbound_order_code = isset($_POST['inbound_order_code']) ? trim($_POST['inbound_order_code']) : '';
$warehouse_code = isset($_POST['warehouse_code']) ? trim($_POST['warehouse_code']) : '';
$inbound_time = isset($_POST['inbound_time']) ? trim($_POST['inbound_time']) : '';
$operator = isset($_POST['operator']) ? trim($_POST['operator']) : '';
$remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';

$create_time = date("Y-m-d H:i:s");

if (empty($inbound_order_code) || empty($warehouse_code) || empty($inbound_time || empty($operator))) {
  echo 'noValue';
} else {

  try {
    include("../pdo/pdo.php");

    // 关闭自动提交
    $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
    // 开启事务
    $pdo->beginTransaction();

    $sql = "update inbound_order set inbound_order_code=?,warehouse_code=?,inbound_time=to_date(to_char(?),'yyyy-MM-dd hh24:mi:ss'),operator=?,create_time=to_date(to_char(?),'yyyy-MM-dd hh24:mi:ss'),remark=? where inbound_order_code=?";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(1, $inbound_order_code);
    $stmt->bindParam(2, $warehouse_code);
    $stmt->bindParam(3, $inbound_time);
    $stmt->bindParam(4, $operator);
    $stmt->bindParam(5, $create_time);
    $stmt->bindParam(6, $remark);
    $stmt->bindParam(7, $inbound_order_code);

    $stmt->execute();
    $material_code;
    $quantity;
    if (is_array($arr)) {
      foreach ($arr as $v) {
        $material_code = $v['material_code'];
        $quantity = $v['quantity'];
        $sql1 = "update inbound_detail set inbound_order_code=?,material_code=?,quantity=? where inbound_order_code=?";
        $stem = $pdo->prepare($sql1);
        $stem->bindParam(1, $inbound_order_code);
        $stem->bindParam(2, $v['material_code']);
        $stem->bindParam(3, $v['quantity']);
        $stem->bindParam(4, $inbound_order_code);
        $stem->execute();
      }
    }

    // 修改库存数量
    $sql = "select sum (quantity) as total_quantity from (select a.warehouse_code,b.material_code,b.quantity from inbound_order a inner join inbound_detail b on a.inbound_order_code = b.inbound_order_code order by a.id) where material_code=? and warehouse_code=?";
    // $sql2 = "select * from inventory where warehouse_code=? and material_code=?";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $material_code);
    $stmt->bindParam(2, $warehouse_code);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($data) > 0) {
      // 有数据，修改
      foreach ($data as $v) {
        $total_quantity = (int)$v['TOTAL_QUANTITY'];
        $sql3 = "update inventory set quantity=:total_quantity where warehouse_code=:warehouse_code and material_code=:material_code";
        $stmt = $pdo->prepare($sql3);
        $stmt->bindParam(':total_quantity', $total_quantity);
        $stmt->bindParam(':warehouse_code', $warehouse_code);
        $stmt->bindParam(':material_code', $material_code);
        $stmt->execute();
      }
    }

    $pdo->commit();
    echo 'success';
  } catch (PDOException $e) {
    echo $e->getMessage();
    $pdo->rollBack();
  }

  // 不管成功与否，都需要在结束时将自动提交打开
  $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
}

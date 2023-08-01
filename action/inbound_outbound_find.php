<?php
$warehouse_code = $_POST['warehouse_code'];
$material_code = $_POST['material_code'];


if (empty($warehouse_code) || empty($material_code)) {
  echo "noValue";
} else {
  try {
    include("../pdo/pdo.php");
    $sql = "select * from (select a.inbound_order_code,a.warehouse_code,a.inbound_time,a.remark as remarks,b.material_code,b.quantity as inquantity from inbound_order a join inbound_detail b on a.inbound_order_code = b.inbound_order_code where a.warehouse_code =? and b.material_code =? order by a.inbound_time) a join (select a.outbound_order_code,a.warehouse_code,a.outbound_time,a.remark,b.material_code,b.quantity from outbound_order a join outbound_detail b on a.outbound_order_code = b.outbound_order_code where a.warehouse_code =? and b.material_code =? order by a.outbound_time) b on a.inbound_time = b.outbound_time";

    $stem = $pdo->prepare($sql);
    $stem->bindParam(1, $warehouse_code);
    $stem->bindParam(2, $material_code);
    $stem->bindParam(3, $warehouse_code);
    $stem->bindParam(4, $material_code);

    $stem->execute();
    $data = $stem->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

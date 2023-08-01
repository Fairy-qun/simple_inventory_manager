<?php
$warehouse_code = isset($_POST['warehouse_code']) ? $_POST['warehouse_code'] : '';

if (empty($warehouse_code)) {
  echo "noValue";
} else {
  include("../pdo/pdo.php");
  $sql = "delete from warehouse where warehouse_code=?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(1, $warehouse_code);
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
    echo "success";
  } else {
    echo "fail";
  }
}

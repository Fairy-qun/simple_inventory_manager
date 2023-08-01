<?php
$material_code = isset($_POST['material_code']) ? $_POST['material_code'] : '';

if (empty($material_code)) {
  echo "noValue";
} else {
  include("../pdo/pdo.php");
  $sql = "delete from material where material_code=?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(1, $material_code);
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
    echo "success";
  } else {
    echo "fail";
  }
}

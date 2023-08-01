<?php
$material_code = isset($_POST['data']) ?  $_POST['data'] : '';

if (!empty($material_code)) {
  include("../pdo/pdo.php");
  $sql = "select material_name from material where material_code=?";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(1, $material_code);
  $stmt->execute();
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($data as $v) {
    echo $v['MATERIAL_NAME'];
  }
}

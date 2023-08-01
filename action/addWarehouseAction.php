<?php
$warehouse_code = isset($_POST['warehouse_code']) ? trim($_POST['warehouse_code']) : '';
$warehouse_name = isset($_POST['warehouse_name']) ? trim($_POST['warehouse_name']) : '';
$maintainer = isset($_POST['maintainer']) ? trim($_POST['maintainer']) : '';
$remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';

$modify_time = date("Y-m-d H:i:s");

echo $warehouse_code . '-' . $warehouse_name . '-' . $maintainer . '-' . $remark . '--' . $modify_time;

if (empty($warehouse_code) || empty($warehouse_name) || empty($maintainer)) {
  echo "<script>alert('必填参数不能为空')</script>";
  echo "<script>location.href = '../pages/warehouse.php'</script>";
} else {
  // 数据库插值
  include("../pdo/pdo.php");

  $sql = "insert into warehouse(warehouse_code,warehouse_name,maintainer,modify_time,remark) values(?,?,?,to_date(to_char(?),'yyyy-MM-dd hh24:mi:ss'),?)";

  $stmt = $pdo->prepare($sql);

  $stmt->bindParam(1, $warehouse_code);
  $stmt->bindParam(2, $warehouse_name);
  $stmt->bindParam(3, $maintainer);
  $stmt->bindParam(4, $modify_time);
  $stmt->bindParam(5, $remark);

  $stmt->execute();
  if ($stmt->rowCount() > 0) {
    echo "<script>alert('添加数据成功')</script>";
  } else {
    echo "<script>alert('添加数据失败')</script>";
  }
  $location = <<<EOT
      <script>
        location.href = '../pages/warehouse.php'
      </script>
    EOT;
  echo $location;
}

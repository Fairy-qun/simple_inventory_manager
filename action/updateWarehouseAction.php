<?php
$id = isset((explode('=', $_SERVER['QUERY_STRING']))[1]) ? (explode('=', $_SERVER['QUERY_STRING']))[1] : '';
$warehouse_code = isset($_POST['warehouse_code']) ? trim($_POST['warehouse_code']) : '';
$warehouse_name = isset($_POST['warehouse_name']) ? trim($_POST['warehouse_name']) : '';
$maintainer = isset($_POST['maintainer']) ? trim($_POST['maintainer']) : '';
$remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';
$modify_time = date("Y-m-d H:i:s");

if (empty($warehouse_code) || empty($warehouse_name) || empty($maintainer) || empty($id)) {
  echo "<script>alert('表单数据未填写完整')</script>";
} else {
  include('../pdo/pdo.php');

  $sql = "update warehouse set warehouse_code=?,warehouse_name=?,maintainer=?,modify_time=to_date(to_char(?),'yyyy-MM-dd hh24:mi:ss'),remark=? where id=?";

  $stmt = $pdo->prepare($sql);

  $stmt->bindParam(1, $warehouse_code);
  $stmt->bindParam(2, $warehouse_name);
  $stmt->bindParam(3, $maintainer);
  $stmt->bindParam(4, $modify_time);
  $stmt->bindParam(5, $remark);
  $stmt->bindParam(6, $id);
  $stmt->execute();
  if ($stmt->rowCount() > 0) {
    echo "<script>alert('修改数据成功')</script>";
  } else {
    echo "<script>alert('修改数据失败')</script>";
  }

  $location = <<<EOT
      <script>
        location.href = '../pages/warehouse.php'
      </script>
    EOT;
  echo $location;
}

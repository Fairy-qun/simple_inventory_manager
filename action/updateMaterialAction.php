<?php
$id = isset((explode('=', $_SERVER['QUERY_STRING']))[1]) ? (explode('=', $_SERVER['QUERY_STRING']))[1] : '';
$material_code = isset($_POST['material_code']) ? trim($_POST['material_code']) : '';
$material_name = isset($_POST['material_name']) ? trim($_POST['material_name']) : '';
$unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
$model = isset($_POST['model']) ? trim($_POST['model']) : '';
$specification = isset($_POST['spec']) ? trim($_POST['spec']) : '';
$maintainer = isset($_POST['maintainer']) ? trim($_POST['maintainer']) : '';
$remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';
$modify_time = date("Y-m-d H:i:s");

if (empty($material_code) || empty($material_name) || empty($unit) || empty($model) || empty($specification) || empty($maintainer) || empty($id)) {
  echo "<script>alert('表单数据未填写完整')</script>";
} else {
  include('../pdo/pdo.php');

  $sql = "update material set material_code=:material_code,material_name=:material_name,unit=:unit,model=:model,specification=:specification,maintainer=:maintainer,modify_time=to_date(to_char(:modify_time),'yyyy-MM-dd hh24:mi:ss'),remark=:remark where id=:id";

  $stmt = $pdo->prepare($sql);
  $arr = array(
    "material_code" => $material_code,
    "material_name" => $material_name,
    "unit" => $unit,
    "model" => $model,
    "specification" => $specification,
    "maintainer" => $maintainer,
    "modify_time" => $modify_time,
    "remark" => $remark,
    "id" => $id
  );
  $stmt->execute($arr);
  if ($stmt->rowCount() > 0) {
    echo "<script>alert('修改数据成功')</script>";
  } else {
    echo "<script>alert('修改数据失败')</script>";
  }

  $location = <<<EOT
      <script>
        location.href = '../pages/material.php'
      </script>
    EOT;
  echo $location;
}

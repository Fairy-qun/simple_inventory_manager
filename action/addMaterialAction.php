<?php
$material_code = isset($_POST['material_code']) ? trim($_POST['material_code']) : '';
$material_name = isset($_POST['material_name']) ? trim($_POST['material_name']) : '';
$unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
$model = isset($_POST['model']) ? trim($_POST['model']) : '';
$spec = isset($_POST['spec']) ? trim($_POST['spec']) : '';
$maintainer = isset($_POST['maintainer']) ? trim($_POST['maintainer']) : '';
$remark = isset($_POST['remark']) ? trim($_POST['remark']) : '';

$modify_time = date("Y-m-d H:i:s");

if (empty($material_code) || empty($material_name) || empty($unit) || empty($model) || empty($spec) || empty($maintainer)) {
  // header('Location: ../material.php');
  echo "<script>alert('必填参数不能为空')</script>";
  echo "<script>location.href = '../pages/material.php'</script>";
} else {
  // 数据库插值
  include("../pdo/pdo.php");
  $sql = "insert into material(material_code,material_name,unit,model,specification,maintainer,modify_time,remark) values(:material_code,:material_name,:unit,:model,:specification,:maintainer,to_date(to_char(:modify_time),'yyyy-MM-dd hh24:mi:ss'),:remark)";

  $stmt = $pdo->prepare($sql);
  $arr = array(
    "material_code" => $material_code,
    "material_name" => $material_name,
    "unit" => $unit,
    "model" => $model,
    "specification" => $spec,
    "maintainer" => $maintainer,
    "modify_time" => $modify_time,
    "remark" => $remark
  );
  $stmt->execute($arr);
  if ($stmt->rowCount() > 0) {
    echo "<script>alert('添加数据成功')</script>";
  } else {
    echo "<script>alert('添加数据失败')</script>";
  }
  $location = <<<EOT
      <script>
        location.href = '../pages/material.php'
      </script>
    EOT;
  echo $location;
}

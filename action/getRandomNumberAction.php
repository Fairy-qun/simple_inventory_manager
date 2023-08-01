<?php
try {
  include("../pdo/pdo.php");
  // 1.开启事务之前要将自动提交关闭
  $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
  // 2.开启事务
  $pdo->beginTransaction();

  // 调用数据库中存在的自定义函数更新


  // $sql = "update daily_report set start_quantity = get_random_number(),endbound_quantity = NVL(start_quantity,0) + NVL(inbound_quantity,0) - NVL(outbound_quantity,0)";
  $sql = "update daily_report set start_quantity = get_random_number(),endbound_quantity = get_random_number()";
  $stem = $pdo->prepare($sql);
  $stem->execute();

  if ($stem->rowCount() > 0) {
    echo "success";
  }
  // 都成功执行后关闭事务
  $pdo->commit();
} catch (PDOException $e) {
  echo $e->getMessage();
  // 事务失败时进行回滚操作
  $pdo->rollBack();
}

// 不管成功与否，都需要在结束时将自动提交打开
$pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);

<?php
try {
  include("../pdo/pdo.php");
  // 1.开启事务之前要将自动提交关闭
  $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
  // 2.开启事务
  $pdo->beginTransaction();

  // 调用数据库中存在的存储过程
  $sql = "BEGIN drop_record_table();END;";
  $stem = $pdo->prepare($sql);
  $stem->execute();


  $sql = "BEGIN generate_daily_report();END;";
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

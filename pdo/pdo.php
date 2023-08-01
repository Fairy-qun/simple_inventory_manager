<?php
try {
  $dsn = "oci:dbname=//localhost/oracle;port=1521;charset=utf8";
  $username = 'zotion';
  $password = 'zotion123';

  $pdo = new PDO($dsn, $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // var_dump($pdo);
} catch (PDOException $e) {
  echo "连接数据库失败" . $e->getMessage();
}

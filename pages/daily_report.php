<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>简易库存管理</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../font_icon/iconfont.css">
  <link rel="stylesheet" href="../css/daily_report.css">
  <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
<div class="main">
  <!-- 菜单 -->
  <div class="menu">
    <?php
    include("./menu.php")
    ?>
  </div>
  <!-- 页面 -->
  <div class="page">
    <!-- 标题 -->
    <h3>报表信息</h3>
    <!-- 内容 -->
    <div class="content">
      <!-- 操作按钮 -->
      <div class="btns">
        <div class="btn_item" onclick="setReportInfoHandler()">
          <span class="iconfont icon-iconfontyijiantuiguang"></span>
          <span>生成报表</span>
        </div>
        <div class="btn_item" onclick="getRandomNumberHandler()">
          <span class="iconfont icon-suijishushengcheng"></span>
          <span>随机数生成</span>
        </div>
        <div class="btn_item" onclick="resetReportInfoHandler()">
          <span class="iconfont icon-huifu"></span>
          <span>恢复</span>
        </div>
      </div>
      <!-- 查询结果 -->
      <div id="result">
        <table>
          <tr>
            <th>序号</th>
            <th>仓库编码</th>
            <th>物料编码</th>
            <th>生成日期</th>
            <th>期初数量</th>
            <th>入库数量</th>
            <th>出库数量</th>
            <th>结存数量</th>
          </tr>
          <?php
          include("../pdo/pdo.php");
          try {
            echo '<pre>';
            // 1.开启事务之前要将自动提交关闭
            $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
            // 2.开启事务
            $pdo->beginTransaction();
            $sql = "select warehouse_code,material_code,to_char(report_date,'yyyy-MM-dd') as report_date,start_quantity,inbound_quantity,outbound_quantity,endbound_quantity from daily_report";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($data) > 0) {
              $count = 0;
              foreach ($data as $v) {
                $count++;
                echo "<tr><td>" . $count . "</td><td>" . $v['WAREHOUSE_CODE'] . "</td><td>" . $v['MATERIAL_CODE'] . "</td><td>" . $v['REPORT_DATE'] . "</td><td>" . $v['START_QUANTITY'] . "</td><td>" . $v['INBOUND_QUANTITY'] . "</td><td>" . $v['OUTBOUND_QUANTITY'] . "</td><td>" . $v['ENDBOUND_QUANTITY'] . "</td></tr>";
              }
              // 都成功执行后关闭事务
              $pdo->commit();
            } else {
              echo "<tr><td colspan='8'>暂时无数据</td></tr>";
            }
          } catch (PDOException $e) {
            echo $e->getMessage();
            // 事务失败时进行回滚操作
            $pdo->rollBack();
          }
          // 不管成功与否，都需要在结束时将自动提交打开
          $pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
          ?>
        </table>
      </div>
    </div>
  </div>
</div>

<body>

</body>
<script>
  const setReportInfoHandler = () => {
    $.post('../action/generateReportAction.php', (message) => {
      console.log(message);
      if (message == 'success') {
        alert("生成报表成功");
        $("table").load(location.href + " table>*", "")
      }
    })
  }

  // 生成随机数
  const getRandomNumberHandler = () => {
    console.log(111);
    $.post('../action/getRandomNumberAction.php', (message) => {
      console.log(message);
      if (message == 'success') {
        alert("生成随机数报表成功");
        $("table").load(location.href + " table>*", "")
      }
    })
  }

  const resetReportInfoHandler = () => {
    $.post('../action/generateReportAction.php', (message) => {
      console.log(message);
      if (message == 'success') {
        alert("恢复报表成功");
        $("table").load(location.href + " table>*", "")
      }
    })
  }
</script>

</html>
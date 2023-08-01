<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>简易库存管理</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../font_icon/iconfont.css">
  <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <style>
    .main {
      display: flex;
      justify-content: flex-start;
    }

    .page {
      flex: 1;
      padding: 10px;
      width: calc(100vw - 260px);
      min-height: calc(100vh - 20px);
      background-color: #fff;
      border-radius: 5px;
      overflow-y: scroll;
    }


    .content {
      margin-top: 10px;
    }

    select {
      padding: 0px 10px;
      width: 400px;
      height: 40px;
      font-size: 18px;
      border: 1px solid #B3B6B7;
      border-radius: 5px;
    }

    select:focus {
      outline: none;
    }

    .name {
      margin-right: 10px;
      display: inline-block;
      font-size: 20px;
    }

    .search {
      display: flex;
      align-items: center;
    }

    .findBtn {
      display: flex;
      justify-content: center;
      align-items: center;
      margin-left: 20px;
      width: 80px;
      height: 35px;
      font-size: 18px;
      background-color: #3498DB;
      color: #fff;
      border-radius: 5px;
      cursor: pointer;
    }

    tr {
      border-bottom: 1px solid #BDC3C7;
    }
  </style>
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
    <h3>出入库信息</h3>
    <!-- 内容 -->
    <div class="content">
      <!-- 查询条件 -->
      <div class="search">
        <span class="name">物料</span>
        <select name="select" id="select">
          <option value="">请选择物料</option>
          <?php
          include("../pdo/pdo.php");
          $sql = "select material_code,material_name from material";
          $stmt = $pdo->prepare($sql);
          $stmt->execute();
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (count($data) > 0) {
            foreach ($data as $v) {
              echo "<option value=" . $v['MATERIAL_CODE'] . ">" . $v['MATERIAL_NAME'] . "</option>";
            }
          }
          ?>
        </select>
        <span class="name" style="margin-left: 20px;">仓库</span>
        <select name="select1" id="select1">
          <option value="">请选择仓库</option>
          <?php
          include("../pdo/pdo.php");
          $sql = "select warehouse_code,warehouse_name from warehouse";
          $stmt = $pdo->prepare($sql);
          $stmt->execute();
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (count($data) > 0) {
            foreach ($data as $v) {
              echo "<option value=" . $v['WAREHOUSE_CODE'] . ">" . $v['WAREHOUSE_NAME'] . "</option>";
            }
          }
          ?>
        </select>
        <div class="findBtn" onclick="findHandler()">
          <span class="iconfont icon-sousuo"></span>
          <span>查询</span>
        </div>
        <div class="findBtn" onclick="resetHandler()">
          <span class="iconfont icon-shuaxin"></span>
          <span>重置</span>
        </div>
      </div>
      <!-- 查询结果 -->
      <div id="result">
        <table>
          <tr>
            <th>序号</th>
            <th>时间</th>
            <th>入库单号</th>
            <th>入库数量</th>
            <th>备注</th>
            <th>出库单号</th>
            <th>出库数量</th>
            <th>备注</th>
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
            $sql = "select to_char(inbound_time,'yyyy-MM-dd') as inbound_time,inbound_order_code,inquantity,remarks,outbound_order_code,quantity,remark from (select a.inbound_order_code,a.warehouse_code,a.inbound_time,a.remark as remarks,b.material_code,b.quantity as inquantity from inbound_order a join inbound_detail b on a.inbound_order_code = b.inbound_order_code order by a.inbound_time) a join (select a.outbound_order_code,a.warehouse_code,a.outbound_time,a.remark,b.material_code,b.quantity from outbound_order a join outbound_detail b on a.outbound_order_code = b.outbound_order_code order by a.outbound_time) b on a.inbound_time = b.outbound_time ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $count = 0;
            foreach ($data as $v) {
              $count++;
              $new_quantity = (int)$v['INQUANTITY'] - (int)$v['QUANTITY'];
              echo "<tr><td>" . $count . "</td><td>" . $v['INBOUND_TIME'] . "</td><td>" . $v['INBOUND_ORDER_CODE'] . "</td><td>" . $v['INQUANTITY'] . "</td><td>" . $v['REMARKS'] . "</td><td>" . $v['OUTBOUND_ORDER_CODE'] . "</td><td>" . $v['QUANTITY'] . "</td><td>" . $v['REMARK'] . "</td><td>" . $new_quantity . "</td></tr>";
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
          ?>
        </table>
      </div>
    </div>
  </div>
</div>

<body>

</body>
<script>
  const findHandler = () => {
    const findObj = {
      warehouse_code: '',
      material_code: ''
    }
    findObj.material_code = document.querySelector("#select").value
    findObj.warehouse_code = document.querySelector("#select1").value
    console.log(findObj);
    $.post('../action/inbound_outbound_find.php', findObj, (message) => {
      console.log(message);
      if (message == 'noValue') {
        alert("请选择查询条件")
        return
      }
      if (Array.isArray(JSON.parse(message))) {
        const arr = JSON.parse(message)
        if (arr.length == 0) {
          // 没有数据
          $("table").remove()

          $("<table><tr><th>ID</th><th>时间</th><th>入库单号</th><th>入库数量</th><th>备注</th> <th>出库单号</th><th>出库数量</th><th>备注</th><th>结存数量</th></tr><tr><td colspan='9'>暂无数据</td></tr></table>").appendTo("#result")
        } else {
          // 有数据
          $("table").remove()
          $("<table><tr><th>ID</th><th>时间</th><th>入库单号</th><th>入库数量</th><th>备注</th><th>出库单号</th><th>出库数量</th><th>备注</th><th>结存数量</th></tr></table>").appendTo("#result")
          let tableArr = []
          for (let k in arr) {
            tableArr.push(arr[k])
          }
          let count = 0;
          tableArr.forEach(item => {
            const new_quantity = Number(item['INQUANTITY']) - Number(item['QUANTITY'])
            count++;
            $(`<tr><td>${count}</td><td>${item['INBOUND_TIME']}</td><td>${item['INBOUND_ORDER_CODE']}</td><td>${item['INQUANTITY']}</td><td>${item['REMARKS']}</td><td>${item['OUTBOUND_ORDER_CODE']}</td><td>${item['QUANTITY']}</td><td>${item['REMARK']}</td><td>${new_quantity}</td></tr>`).appendTo("table")
          })
        }
      }
    })
  }

  const resetHandler = () => {
    $(".content").load(location.href + " .content>*", "")
  }
</script>

</html>
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
    <h3>库存信息</h3>
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
            <th>ID</th>
            <th>仓库编码</th>
            <th>仓库名称</th>
            <th>物料编码</th>
            <th>物料名称</th>
            <th>库存数量</th>
          </tr>
          <?php
          include("../pdo/pdo.php");
          $sql = "select a.id,a.warehouse_code,a.material_code,a.quantity,b.warehouse_name,c.material_name from (inventory a left join warehouse b on a.warehouse_code = b.warehouse_code) left join material c on a.material_code=c.material_code";
          $stmt = $pdo->prepare($sql);
          $stmt->execute();
          $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
          if (count($data) > 0) {
            // 有数据
            foreach ($data as $v) {
              echo "<tr><td>" . $v['ID'] . "</td><td>" . $v['WAREHOUSE_CODE'] . "</td><td>" . $v['WAREHOUSE_NAME'] . "</td><td>" . $v['MATERIAL_CODE'] . "</td><td>" . $v['MATERIAL_NAME'] . "</td><td>" . $v['QUANTITY'] . "</td></tr>";
            }
          } else {
            // 无数据
            echo "<tr><td colspan='6'>暂时没有数据</td></tr>";
          }
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
    $.post('../action/findInventoryQuantity.php', findObj, (message) => {
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

          $("<table><tr><th>ID</th><th>仓库编码</th><th>仓库名称</th><th>物料编码</th><th>物料名称</th> <th>库存数量</th></tr><tr><td colspan='6'>暂无数据</td></tr></table>").appendTo("#result")
        } else {
          // 有数据
          $("table").remove()
          $("<table><tr><th>ID</th><th>仓库编码</th><th>仓库名称</th><th>物料编码</th><th>物料名称</th><th>库存数量</th></tr></table>").appendTo("#result")
          // $("<table><tr><th>ID</th><th>仓库编码</th><th>仓库名称</th><th>物料编码</th><th>物料名称</th> <th>库存数量</th></tr><tr><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td></tr></table>").appendTo("#result")
          let tableArr = []
          for (let k in arr) {
            tableArr.push(arr[k])
          }
          tableArr.forEach(item => {
            $(`<tr><td>${item['ID']}</td><td>${item['WAREHOUSE_CODE']}</td><td>${item['WAREHOUSE_NAME']}</td><td>${item['MATERIAL_CODE']}</td><td>${item['MATERIAL_NAME']}</td><td>${item['QUANTITY']}</td></tr>`).appendTo("table")
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
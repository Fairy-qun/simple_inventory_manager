<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>简易库存管理</title>
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/material.css">
  <link rel="stylesheet" href="../font_icon/iconfont.css">
  <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <style>
    .update {
      margin: 0px 10px;
      display: inline-block;
      width: 60px;
      height: 30px;
      text-align: center;
      line-height: 30px;
      background-color: #5DADE2;
      cursor: pointer;
      border-radius: 5px;
      color: #fff;
      opacity: .8;
    }

    .delete {
      display: inline-block;
      width: 60px;
      height: 30px;
      text-align: center;
      line-height: 30px;
      cursor: pointer;
      background-color: #CB4335;
      border-radius: 5px;
      color: #fff;
      opacity: .8;
    }

    .delete_toast {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 999;
      display: none;
      width: 100%;
      height: 100%;
    }

    .delete_dialog {
      display: flex;
      flex-direction: column;
      padding: 10px;
      width: 400px;
      height: 200px;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: #F0F3F4;
      border-radius: 10px;
      z-index: 9999;
    }

    .delete_btn {
      display: flex;
      justify-content: space-evenly;
      align-items: center;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      width: 100%;
      height: 60px;
      text-align: center;
      line-height: 60px;
      background-color: #85929E;
      border-radius: 0 0 10px 10px;
    }

    .comfirm {
      width: 100px;
      height: 40px;
      background-color: #3498DB;
      border: none;
      opacity: .8;
      border-radius: 5px;
      font-size: 20px;
      font-family: serif;
      cursor: pointer;
      color: #fff;
    }

    .cancle {
      width: 100px;
      height: 40px;
      background-color: #D7DBDD;
      border: none;
      opacity: .8;
      border-radius: 5px;
      font-size: 20px;
      font-family: serif;
      cursor: pointer;
      color: #fff;
    }

    .delete_content {
      font-size: 20px;
    }

    .toast_title {
      font-size: 18px;
      margin-bottom: 20px;
    }

    .add_btn {
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #3498DB;
      width: 140px;
      height: 40px;
      font-size: 20px;
      color: #fff;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="main">
    <div class="menu">
      <?php
      include("./menu.php");
      ?>
    </div>
    <div class="page">
      <div class="title">入库单信息</div>
      <div class="content">
        <!-- 按钮 -->
        <div class="btn">
          <div class="add_btn" onclick="addHandler()">
            <span class="iconfont icon-icadd" style="font-size: 28px;"></span>
            <span>创建入库单</span>
          </div>
        </div>
        <!-- 数据 -->
        <div class="data">
          <table>
            <tr>
              <th>ID</th>
              <th>入库单号</th>
              <th>仓库编码</th>
              <th>物料编码</th>
              <th>入库数量</th>
              <th>入库时间</th>
              <th>录入时间</th>
              <th>操作人</th>
              <th>备注</th>
              <th>操作</th>
            </tr>
            <?php
            include("../pdo/pdo.php");
            $sql = "select a.id,a.inbound_order_code,a.warehouse_code,to_char(a.inbound_time,'yyyy-MM-dd') inbound_time,a.operator,to_char(a.create_time,'yyyy-MM-dd hh24:mi:ss') create_time,a.remark,b.material_code,b.quantity from inbound_order a inner join inbound_detail b on a.inbound_order_code = b.inbound_order_code order by a.id";
            // $sql = "select a.id,a.inbound_order_code,a.warehouse_code,a.inbound_time,a.operator,a.create_time,a.remark,b.material_code,b.quantity from inbound_order a inner join inbound_detail b on a.inbound_order_code = b.inbound_order_code order by a.id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($data);
            $arrayLength = count($data);
            // 如果没有数据
            if ($arrayLength === 0) {
              echo "<tr><td class='noData' colspan='10'>暂时没有数据<td></tr>";
            }
            // 如果有数据
            foreach ($data as $v) {
              echo "<tr><td>" . $v['ID'] . "</td><td>" . $v['INBOUND_ORDER_CODE'] . "</td><td>" . $v['WAREHOUSE_CODE'] . "</td><td>" . $v['MATERIAL_CODE'] . "</td><td>" . $v['QUANTITY'] . "</td><td>" . $v['INBOUND_TIME'] . "</td><td>" . $v['CREATE_TIME'] . "</td><td>" . $v['OPERATOR'] . "</td><td>" . $v['REMARK'] . "</td><td style='width: 180px;'><buttom class='update' onclick='updateHandler(this)'>修改</buttom><buttom class='delete' onclick='deleteHandler(this)'>删除</buttom></td></tr>";
            }
            ?>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 主要实现模态框功能 -->
  <div class="delete_toast">
    <div class="delete_dialog">
      <div class="toast_title">删除数据</div>
      <div class="delete_content">是否删除当前数据?</div>
      <!-- 按钮 -->
      <div class="delete_btn">
        <button class="comfirm" onclick="comfirmHandler()">确认</button>
        <button class="cancle" onclick="cancleHandler()">取消</button>
      </div>
    </div>
  </div>
</body>
<script>
  const deleteObj = {
    inbound_order_code: '',
    warehouse_code: '',
    material_code: '',
    quantity: ''
  }
  const addHandler = () => {
    sessionStorage.removeItem('checkItem')
    location.href = './add_inbound_order.php'
  }
  // 删除数据
  const deleteHandler = (obj) => {
    // console.log("删除数据");
    document.querySelector('.delete_toast').style.display = 'block'
    deleteObj.inbound_order_code = obj.parentNode.parentNode.children[1].innerHTML
    deleteObj.warehouse_code = obj.parentNode.parentNode.children[2].innerHTML
    deleteObj.material_code = obj.parentNode.parentNode.children[3].innerHTML
    deleteObj.quantity = obj.parentNode.parentNode.children[4].innerHTML
    console.log(deleteObj);
  }

  // 取消
  const cancleHandler = () => {
    document.querySelector('.delete_toast').style.display = 'none'
  }

  // 确认
  const comfirmHandler = () => {
    $.post('../action/deleteInboundOrderAction.php', deleteObj, (message) => {
      console.log(message);
      if (message == 'noValue') {
        alert("必须参数不能为空")
      } else if (message == 'success') {
        alert("删除数据成功")
        location.reload()
      } else {
        alert("删除数据失败")
      }
    })
  }

  // 修改数据
  const updateHandler = (obj) => {
    const updateObj = {
      inbound_order_code: obj.parentNode.parentNode.children[1].innerHTML,
      warehouse_code: obj.parentNode.parentNode.children[2].innerHTML,
      material_code: obj.parentNode.parentNode.children[3].innerHTML,
      quantity: obj.parentNode.parentNode.children[4].innerHTML,
      inbound_time: obj.parentNode.parentNode.children[5].innerHTML,
      create: obj.parentNode.parentNode.children[6].innerHTML,
      operator: obj.parentNode.parentNode.children[7].innerHTML,
      remark: obj.parentNode.parentNode.children[8].innerHTML
    }
    sessionStorage.setItem('updateItem', JSON.stringify(updateObj))
    location.href = './update_inbound_order.php'
  }
</script>









</html>
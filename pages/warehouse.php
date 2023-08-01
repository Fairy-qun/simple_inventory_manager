<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>仓库信息</title>
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

    .icon-icadd {
      font-size: 30px;
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
      <div class="title">仓库资料</div>
      <div class="content">
        <!-- 按钮 -->
        <div class="btn">
          <div class="add_btn" onclick="addHandler()">
            <span class="iconfont icon-icadd" style="font-size: 28px;"></span>
            <span>新增仓库</span>
          </div>
        </div>
        <!-- 数据 -->
        <div class="data">
          <table>
            <tr>
              <th>ID</th>
              <th>仓库编码</th>
              <th>仓库名称</th>
              <th>维护人</th>
              <th>维护时间</th>
              <th>备注</th>
              <th>操作</th>
            </tr>
            <?php
            include("../pdo/pdo.php");
            $sql = "SELECT id,warehouse_code,warehouse_name,maintainer,to_char(modify_time,'yyyy-MM-dd hh24:mi:ss') modify_time,remark FROM warehouse order by id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($data);
            $arrayLength = count($data);
            // 如果没有数据
            if ($arrayLength === 0) {
              echo "<tr><td class='noData' colspan='7'>暂时没有数据<td></tr>";
            }
            // 如果有数据
            foreach ($data as $v) {
              echo "<tr><td>" . $v['ID'] . "</td><td>" . $v['WAREHOUSE_CODE'] . "</td><td>" . $v['WAREHOUSE_NAME'] . "</td><td>" . $v['MAINTAINER'] . "</td><td>" . $v['MODIFY_TIME'] . "</td><td>" . $v['REMARK'] . "</td><td style='width: 180px;'><buttom class='update' onclick='updateHandler(this)'>修改</buttom><buttom class='delete' onclick='deleteHandler(this)'>删除</buttom></td></tr>";
            }
            ?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- 弹出表单框 -->
  <?php
  include("../utils/warehouseToast.php");
  ?>

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
  let warehouse_code
  const addHandler = () => {
    const toast = document.querySelector('.toast')
    const ids = document.querySelector('.ids')
    toast.style.display = 'block'
    ids.style.display = 'none'
  }
  // 删除数据
  const deleteHandler = (obj) => {
    // console.log("删除数据");
    document.querySelector('.delete_toast').style.display = 'block'
    warehouse_code = obj.parentNode.parentNode.children[1].innerHTML
  }

  // 取消
  const cancleHandler = () => {
    document.querySelector('.delete_toast').style.display = 'none'
  }

  // 确认
  const comfirmHandler = () => {
    $.post('../action/deleteWarehouseAction.php', {
      warehouse_code
    }, (message) => {
      console.log(message);
      if (message == 'noValue') {
        alert("必传参数为空")
        return
      }
      if (message == 'success') {
        alert("删除物料成功")
        location.reload()
      } else {
        alert("删除物料失败")
      }
    })
  }

  // 修改数据
  const updateHandler = (obj) => {
    const toast = document.querySelector('.toast')
    toast.style.display = 'block'
    document.querySelector('.toast_title').innerHTML = '修改数据'
    console.log(obj.parentNode.parentNode.children);
    document.querySelector('#id').value = obj.parentNode.parentNode.children[0].innerHTML
    document.querySelector('#warehouse_code').value = obj.parentNode.parentNode.children[1].innerHTML
    document.querySelector('#warehouse_name').value = obj.parentNode.parentNode.children[2].innerHTML
    document.querySelector('#maintainer').value = obj.parentNode.parentNode.children[3].innerHTML
    document.querySelector('#remark').value = obj.parentNode.parentNode.children[5].innerHTML
    document.querySelector('#id').disabled = 'disabled'
    document.querySelector('#id').style.backgroundColor = '#F2F3F4'
  }
</script>

</html>
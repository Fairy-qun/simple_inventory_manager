<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>简易库存管理</title>
  <link rel="stylesheet" href="../css/add_inbound_order.css">
  <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>

<body>
  <div class="main">
    <!-- 菜单 -->
    <div>
      <?php
      include("./menu.php")
      ?>
    </div>
    <!-- 页面 -->
    <div class="page">
      <div class="title">修改入库单</div>
      <!-- 表单 -->
      <form name="form" method="post" id="form">
        <div class="form_item">
          <label for="inbound_order_code">入库单号：</label>
          <input type="text" id="inbound_order_code" name="inbound_order_code" required placeholder="入库单号">
        </div>
        <div class="form_item">
          <label for="warehouse_code">仓库：</label>
          <select name="warehouse_code" id="warehouse_code">
            <option value="0">请选择仓库</option>
            <?php
            include("../pdo/pdo.php");
            $sql = "select warehouse_code,warehouse_name from warehouse order by id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            var_dump($data);
            foreach ($data as $v) {
              echo "<option value=" . $v['WAREHOUSE_CODE'] . ">" . $v['WAREHOUSE_NAME'] . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="form_item">
          <label for="inbound_time">入库时间：</label>
          <input type="date" id="inbound_time" name="inbound_time" required placeholder="入库时间">
        </div>
        <div class="form_item">
          <label for="operator">录入人：</label>
          <input type="text" id="operator" name="operator" required placeholder="录入人">
        </div>
        <div class="form_item textarea">
          <label for="remark">备注：</label>
          <textarea name="remark" id="remark" cols="30" rows="10"></textarea>
        </div>
      </form>
      <!-- 物料信息 -->
      <div class="material">
        <div class="top_title">
          <h3 class="material_info">物料明细</h3>
          <button class="add_material" onclick="addMaterialHandler()">添加物料</button>
        </div>
        <div class="buttom_content" id="buttom_content">
          <table border="1" id="table1">
            <tr>
              <th>序号</th>
              <th>物料编码</th>
              <th>物料名称</th>
              <th style='width: 300px'>物料数量</th>
              <th>操作</th>
            </tr>
          </table>
        </div>
      </div>
      <!-- 操作按钮 -->
      <div class="btn">
        <button class="cancle" onclick="cancleHandler()">取消</button>
        <button class="confirm" onclick="confirmHandler()">保存</button>
      </div>
    </div>
  </div>
  <!-- 引入添加物料对话框 -->
  <div class="toast">
    <div class="dialog">
      <h3>添加物料</h3>
      <table border="1">
        <tr>
          <th>选择</th>
          <th>物料编号</th>
          <th>物料名称</th>
        </tr>
        <?php
        include("../pdo/pdo.php");
        $sql = "select material_code,material_name from material";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($data as $v) {
          echo "<tr><td><input type='checkbox' class='checkbox' onclick='checkHandler1(this)'></td><td>" . $v['MATERIAL_CODE'] . "</td><td>" . $v['MATERIAL_NAME'] . "</td></tr>";
        }
        ?>
      </table>
      <!-- 按钮 -->
      <div class="btn_s">
        <button class="close" onclick="closeHandler()">取消</button>
        <button class="open" onclick="openHandler()">确认</button>
      </div>
    </div>
  </div>
</body>
<script>
  const updateItem = JSON.parse(sessionStorage.getItem('updateItem'))
  if (updateItem) {
    document.querySelector('#inbound_order_code').value = updateItem.inbound_order_code
    document.querySelector('#warehouse_code').value = updateItem.warehouse_code
    document.querySelector('#inbound_time').value = updateItem.inbound_time
    document.querySelector('#operator').value = updateItem.operator
    document.querySelector('#remark').value = updateItem.remark

    const data = updateItem.material_code
    $.post("../action/findMaterialInfo.php", {
      data
    }, (message) => {
      console.log(message);
      $(`<tr id='trs'><td>1</td><td>${updateItem.material_code}</td><td>${message}</td><td class='numberbox'><buttom class='number reduce' disabled onclick='reduceNumberHandler()'>-</buttom><input type='text' class='input1' value='1' placeholder='数量' class='number_ipt'><buttom class='number' onclick='addNumberHandler()'>+</buttom></td><td class='delete' onclick='deleteHandler(this)'>删除</td></tr>`).appendTo($("#table1"))
      document.querySelector('.input1').value = updateItem.quantity

    })


  }
  const cancleHandler = () => {
    sessionStorage.removeItem("updateItem")
    location.href = './inbound_order.php'
  }

  let dataArr = []
  const confirmHandler = () => {
    dataArr = []
    const trs = document.querySelectorAll('#trs')
    trs.forEach(item => {
      const obj = {
        material_code: item.children[1].innerHTML,
        material_name: item.children[2].innerHTML,
        quantity: item.children[3].children[1].value
      }
      dataArr.push(obj)
    })
    // document.form.action = '../action/addInboundOrderAction.php?arr=' + dataArr
    // document.form.submit()
    $.ajax({
      contentType: 'application/x-www-form-urlencoded', //这行可有可无都行
      type: 'POST',
      url: "../action/updateInboundOrderAction.php",
      data: {
        data: dataArr,
        inbound_order_code: document.querySelector('#inbound_order_code').value,
        warehouse_code: document.querySelector('#warehouse_code').value,
        inbound_time: document.querySelector('#inbound_time').value,
        operator: document.querySelector('#operator').value,
        remark: document.querySelector('#remark').value,
      },
      success: function(message) {
        console.log(message);
        if (message == 'noValue') {
          alert("必须参数不能为空");
        } else if (message == 'success') {
          alert("修改数据成功")
          location.href = '../pages/inbound_order.php';
        } else {
          alert("修改数据失败")
        }
      }
    });
  }

  // 添加物料操作
  const addMaterialHandler = () => {
    document.querySelector(".toast").style.display = 'block'
    deleteHandler()
  }

  const deleteHandler = (obj) => {
    $("#table1").load(location.href + " #table1>*", "");
  }

  /**
   * 物料数量增加事件
   */
  const addNumberHandler = () => {
    let inputValue = document.querySelector(".input1").value
    inputValue++
    document.querySelector(".input1").value = inputValue
  }

  /**
   * 物料数量减少事件
   */
  const reduceNumberHandler = () => {
    let inputValue = document.querySelector(".input1").value
    if (inputValue != 1) {
      inputValue--
      document.querySelector(".input1").value = inputValue
    }
  }
  const closeHandler = () => {
    document.querySelector('.toast').style.display = 'none'
  }


  /**
   * 选择物料事件
   */
  const checkHandler1 = (value) => {
    const checkbox = document.querySelectorAll('.checkbox')
    const checkbox1 = document.querySelector('.checkbox1')
    checkbox.forEach(item => {
      item.checked = false
    })
    value.checked = !value.checked

  }

  /**
   * 选择物料确认事件
   */
  const openHandler = () => {
    const checkbox = document.querySelectorAll('.checkbox')
    console.log(checkbox);
    const obj = {
      material_code: '',
      material_name: ''
    }
    checkbox.forEach(item => {
      if (item.checked) {
        const material_code = item.parentNode.parentNode.childNodes[1].innerHTML
        const material_name = item.parentNode.parentNode.childNodes[2].innerHTML
        obj.material_code = material_code
        obj.material_name = material_name
      }
    })

    $(`<tr id='trs'><td>1</td><td>${obj.material_code}</td><td>${obj.material_name}</td><td class='numberbox'><buttom class='number reduce' disabled onclick='reduceNumberHandler()'>-</buttom><input type='text' class='input1' value='1' placeholder='数量' class='number_ipt'><buttom class='number' onclick='addNumberHandler()'>+</buttom></td><td class='delete' onclick='deleteHandler(this)'>删除</td></tr>`).appendTo($("#table1"))
    document.querySelector('.toast').style.display = 'none'
  }
</script>

</html>
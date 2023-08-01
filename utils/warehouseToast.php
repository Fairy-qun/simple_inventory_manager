<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/warehouseToast.css">
</head>

<body>
  <!-- 主要实现模态框功能 -->
  <div class="toast">
    <div class="dialog">
      <div class="close_btn" onclick="closeHandler()">
        <img src="../img/cancel.png" alt="">
      </div>
      <div class="toast_title">添加数据</div>
      <!-- 表单 -->
      <form method="post" name="form">
        <div class="form_item ids">
          <label for="material_code">ID：</label>
          <input type="text" id="id" name="id">
        </div>
        <div class="form_item">
          <label for="warehouse_code">仓库编码：</label>
          <input type="text" id="warehouse_code" name="warehouse_code" required>
        </div>
        <div class="form_item">
          <label for="warehouse_name">名称：</label>
          <input type="text" id="warehouse_name" name="warehouse_name" required>
        </div>
        <div class="form_item">
          <label for="maintainer">维护人：</label>
          <input type="text" id="maintainer" name="maintainer" required>
        </div>
        <div class="form_item_remark form_item">
          <label for="remark">备注：</label>
          <textarea name="remark" id="remark" cols="30" rows="10"></textarea>
        </div>
        <div class="btns">
          <button class="ipt_btn" type="button" onclick="submitHandler()">提交</button>
        </div>
      </form>
    </div>
  </div>

</body>
<script>
  const closeHandler = () => {
    const toast = document.querySelector('.toast')
    toast.style.display = 'none'
    location.reload()
  }
  const submitHandler = () => {
    const title_txt = document.querySelector('.toast_title').innerHTML
    if (title_txt === '添加数据') {
      console.log("添加");
      document.form.action = '../action/addWarehouseAction.php'
      document.form.submit()
    } else if (title_txt === '修改数据') {
      console.log("修改数据");
      const id = document.querySelector('#id').value
      document.form.action = '../action/updateWarehouseAction.php?id=' + id
      document.form.submit()
    }
  }
</script>

</html>
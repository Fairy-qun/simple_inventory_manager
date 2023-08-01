<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../css/delete_toast.css">
</head>

<body>
  <!-- 主要实现模态框功能 -->
  <div class="delete_toast" style="display: none;">
    <div class="delete_dialog">
      <div class="toast_title">删除数据</div>
      <div class="content">是否删除当前数据?</div>
      <!-- 按钮 -->
      <div class="delete_btn">
        <button class="comfirm">确认</button>
        <button class="cancle">取消</button>
      </div>
    </div>
  </div>

</body>
<script>
  const closeHadler = () => {
    document.querySelector('.delete_toast').style.display = 'none'
  }

  const comfirmHadler = (data) => {
    console.log(data);
  }
</script>

</html>
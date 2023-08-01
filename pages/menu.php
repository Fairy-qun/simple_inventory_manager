<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>菜单信息</title>
  <link rel="stylesheet" href="../font_icon/iconfont.css">
  <link rel="stylesheet" href="../css/global.css">
  <link rel="stylesheet" href="../css/menu.css">
  <style>
    .global_title {
      display: flex;
      align-items: center;
      color: #2874A6;
    }

    .icon-wendangguanlixitong-baoetongxitongtubiao {
      font-size: 40px;
      color: #2874A6;
    }
  </style>
</head>

<body>
  <div class="menu_box">
    <div class="global_title" onclick="goHome()">
      <span class="iconfont icon-wendangguanlixitong-baoetongxitongtubiao
"></span>
      简易库存管理
    </div>
    <div class="link">
      <div class="item">
        <span class="iconfont icon-shouyeshouye icon"></span>
        <a href="./index.php">首页</a>
      </div>
      <div class="item">
        <span class="iconfont icon-wuliaoguanli icon"></span>
        <a href="./material.php">物料信息</a>
      </div>
      <div class="item">
        <span class="iconfont icon-cangku_kucun icon"></span>
        <a href="./warehouse.php">仓库信息</a>
      </div>
      <div class="item">
        <span class="iconfont icon-navicon-rkd icon"></span>
        <a href="./inbound_order.php">入库单</a>
      </div>
      <div class="item">
        <span class="iconfont icon-navicon-ckd icon"></span>
        <a href="./outbound_order.php">出库单</a>
      </div>
      <div class="item">
        <span class="iconfont icon-kucun icon"></span>
        <a href="./inventoryInquiry.php">库存查询</a>
      </div>
      <div class="item">
        <span class="iconfont icon-ico_xibaoyinhang_xibaochurukuchaxun icon"></span>
        <a href="./inbound_outbound_find.php">出入库查询</a>
      </div>
      <div class="item">
        <span class="iconfont icon-baobiao icon"></span>
        <a href="./daily_report.php">报表数据</a>
      </div>
    </div>
  </div>
</body>
<script>
  const goHome = () => {
    location.href = './index.php'
  }
  const links = document.querySelectorAll('a')
  const current = location.pathname
  links.forEach(item => {
    if (item.pathname == current) {
      item.parentNode.style.backgroundColor = '#2DA3F0'
      item.parentNode.style.color = "#fff"
      item.style.color = "#fff"
    }
  })
</script>

</html>
<?php

require_once("db_connect.php");
if (isset($_GET["search"]) && isset($_GET["search_type"])) {
    $search = $_GET["search"];
    $search_type = $_GET["search_type"];
    $search_type_name = "";
    switch ($search_type) {
        case 1:
            $search_type_name = "model";
            break;
        case 2:
            $search_type_name = "product_brand";
            break;
        case 3:
            $search_type_name = "product_CPU";
            break;
        case 4:
            $search_type_name = "product_display_card";
            break;
        default:
            $search_type_name = "model";
            break;
    }

    $sql = "SELECT * FROM product WHERE $search_type_name LIKE '%$search%'  AND valid = 1 ";
} else {
    $sql = "SELECT * FROM product WHERE valid = 1 ";
}
$resultAll = $conn->query($sql);
$productCount = $resultAll->num_rows;

$per_page = 10;
$page = 1;
$start_item = 0;

$totlapage = ceil($productCount / $per_page);
// echo "total page:".$totlap
if (isset($_GET["order"])) {
    $order = $_GET["order"];
} else {
    $order = 0;
}

switch ($order) {
    case 0:
        $where_clause = "ORDER BY product_id ASC";
        break;
    case 1:
        $where_clause = "ORDER BY model ASC";
        break;
    case 2:
        $where_clause = "ORDER BY product_brand ASC";
        break;
    case 3:
        $where_clause = "ORDER BY list_price ASC";
        break;
    case 4:
        $where_clause = "ORDER BY affordance ASC";
        break;
    case 5:
        $where_clause = "ORDER BY product_color ASC";
        break;
    case 6:
        $where_clause = "ORDER BY product_size ASC";
        break;
    case 7:
        $where_clause = "ORDER BY product_weight ASC";
        break;
    case 8:
        $where_clause = "ORDER BY product_CPU ASC";
        break;
    case 9:
        $where_clause = "ORDER BY product_RAM ASC";
        break;
    case 10:
        $where_clause = "ORDER BY product_display_card ASC";
        break;

    default:
        header("location:product-list.php?p=1&order=0");
        break;
}
$sql .=  $where_clause;


if (isset($_GET["p"])) {

    $page = $_GET["p"];
} else {
    $page = 1;
}
$start_item = ($page - 1) * $per_page;
$sql .= " LIMIT $start_item, $per_page";

$result = $conn->query($sql);


?>
<!doctype html>
<html lang="en">

<head>
    <title>商品列表</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php
    require_once "css.php";
    ?>
</head>

<body>
    <div class="container">
        <h1 class="text-center">商品列表</h1>
        <div class="py-2">
            <?php if (isset($_GET["search"])) : ?>
                <a class="btn btn-secondary" href="product-list.php"><i class="fa-solid fa-arrow-left" title="回商品列表"></i></a>
            <?php endif; ?>
            <a class="btn btn-secondary" href="create-product.php"><i class="fa-solid fa-square-plus"></i></i></a>
        </div>
        <form>
            <div class="input-group">
                <div>
                    <select class="form-select" name="order">
                        <option value="0" <?= isset($_GET["order"]) && $_GET["order"] == 0 ? "selected" : ""; ?>>按id排序</option>
                        <option value="1" <?= isset($_GET["order"]) && $_GET["order"] == 1 ? "selected" : ""; ?>>按商品型號排序</option>
                        <option value="2" <?= isset($_GET["order"]) && $_GET["order"] == 2 ? "selected" : ""; ?>>按廠牌排序</option>
                        <option value="3" <?= isset($_GET["order"]) && $_GET["order"] == 3 ? "selected" : ""; ?>>按定價排序</option>
                        <option value="4" <?= isset($_GET["order"]) && $_GET["order"] == 4 ? "selected" : ""; ?>>按用途排序</option>
                        <option value="5" <?= isset($_GET["order"]) && $_GET["order"] == 5 ? "selected" : ""; ?>>按顏色排序</option>
                        <option value="6" <?= isset($_GET["order"]) && $_GET["order"] == 6 ? "selected" : ""; ?>>按尺寸排序</option>
                        <option value="7" <?= isset($_GET["order"]) && $_GET["order"] == 7 ? "selected" : ""; ?>>按重量排序</option>
                        <option value="8" <?= isset($_GET["order"]) && $_GET["order"] == 8 ? "selected" : ""; ?>>按處理器排序</option>
                        <option value="9" <?= isset($_GET["order"]) && $_GET["order"] == 9 ? "selected" : ""; ?>>按記憶體排序</option>
                        <option value="10" <?= isset($_GET["order"]) && $_GET["order"] == 10 ? "selected" : ""; ?>>按顯示晶片排序</option>
                    </select>
                </div>
                <div>
                    <select class="form-select" name="search_type">
                        <option value="1" <?= isset($_GET["search_type"]) && $_GET["search_type"] == 1 ? "selected" : ""; ?>>搜尋商品型號</option>
                        <option value="2" <?= isset($_GET["search_type"]) && $_GET["search_type"] == 2 ? "selected" : ""; ?>>搜尋廠牌</option>
                        <option value="3" <?= isset($_GET["search_type"]) && $_GET["search_type"] == 3 ? "selected" : ""; ?>>搜尋處理器</option>
                        <option value="4" <?= isset($_GET["search_type"]) && $_GET["search_type"] == 4 ? "selected" : ""; ?>>搜尋顯示晶片</option>
                    </select>
                </div>
                <input type="search" class="form-control" name="search" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>">
                <button class="btn btn-secondary" type="submit"><i class="fa-solid fa-search"></i></button>
            </div>

        </form>


        <?php $rows = $result->fetch_all(MYSQLI_ASSOC); ?>
        共有<?= $productCount ?> 筆商品

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>商品id</th>
                    <th>商品圖片</th>
                    <th>商品型號</th>
                    <th>廠牌</th>
                    <th>定價</th>
                    <th>用途</th>
                    <th>顏色</th>
                    <th>尺寸</th>
                    <th>重量</th>
                    <th>處理器</th>
                    <th>記憶體</th>
                    <th>獨顯</th>
                    <th>顯示晶片</th>
                    <th>編輯</th>
                    <th>下架</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($productCount > 0) {

                    foreach ($rows as $row) {
                ?>
                        <tr>
                            <td><?= $row["product_id"] ?></td>
                            <td><img src="" alt="<?= $row["model"] ?>" style="width: 100px;"></td>
                            <td><?= $row["model"] ?></td>
                            <td><?= $row["product_brand"] ?></td>
                            <td><?= $row["list_price"] ?></td>
                            <td><?= $row["affordance"] ?></td>
                            <td><?= $row["product_color"] ?></td>
                            <td><?= $row["product_size"] ?></td>
                            <td><?= $row["product_weight"] ?></td>
                            <td><?= $row["product_CPU"] ?></td>
                            <td><?= $row["product_RAM"] ?></td>
                            <td><?= $row["discrete_display_card"] ?></td>
                            <td><?= $row["product_display_card"] ?></td>
                            <td><a class="text-secondary" href="edit-product.php?id=<?= $row["product_id"] ?>"><i class="fa-solid fa-pencil"></i></a></td>
                            <td><a class="text-secondary delete_product" data-delete_id="<?= $row["product_id"] ?>" class="delete_product"><i class="fa-solid fa-trash"></i></a></td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="13">查無資料</td>
                    </tr>
                <?php
                }
                ?>

        </table>
        <ul class="pagination">
            <!-- 頁數 -->
            <?php
            if (isset($_GET["p"])) {
                $lastpage = $_GET["p"] + 5;
                if ($lastpage > $totlapage) {
                    $lastpage = $totlapage;
                }
            } else {
                $lastpage = 10;
            }
            if ($lastpage - 9 < 1) {
                $lastpage = 10;
            }
            
            for ($i = $lastpage - 9; $i <= $totlapage  && $i <= ($lastpage); $i++) : ?>
                <li class="page-item <?php if ($page == $i) echo "active"; ?>">
                    <?php
                    if (isset($_GET["search"])) {
                        $search = $_GET["search"];
                    ?>
                        <a class="page-link" href="?p=<?= $i ?>&order=<?= $order ?>&search=<?= $search ?>&search_type=<?= $search_type ?>"><?= $i ?></a>
                    <?php
                    } else {
                    ?>
                        <a class="page-link" href="?p=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a>
                    <?php
                    }
                    ?>
                </li>
            <?php endfor; ?>

        </ul>
    </div>
    <?php
    require_once "js.php";
    ?>
    
    <script>
        // 刪除商品
        var delete_id = document.querySelectorAll(".delete_product");
        delete_id.forEach(function(item) {
            item.addEventListener("click", function() {
                var delete_id = this.dataset.delete_id;
                if (confirm("確定要刪除商品編號" + delete_id + "嗎?")) {
                    $.ajax({
                        url: "delete-product.php",
                        type: "POST",
                        dataType: "json",
                        data: {
                            delete_id: delete_id
                        }
                    }).done(function(response) {
                        if (response.status == 1) {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message);
                        }
                    }).fail(function(jqxhr, textStatus) {
                        console.log("Request failed: " + textStatus);
                    });
                }
            });
        });
    </script>
    <?php
    $conn->close();
    ?>
</body>

</html>
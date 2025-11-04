<?php
require_once "header.php";
try {
    require_once 'db.php';

    $searchtxt = $_POST["searchtxt"] ?? "";
    $order = $_POST["order"] ?? ""; 

    $sql = "select * from job";

    if ($searchtxt){
        $sql .= " WHERE company LIKE '%$searchtxt%' OR content LIKE '%$searchtxt%'";
    }

    if ($order){

        $allowed_orders = ['company', 'content', 'pdate'];
        if (in_array($order, $allowed_orders)) {
            $sql .= " ORDER BY $order";
        }
    }
    $result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
    <form action="job.php" method="post" class="mb-3">
        <div class="input-group">
            <select name="order" class="form-select" style="max-width: 150px;">
                <option value="">預設排序</option>
                <option value="company" <?= ($order == 'company') ? 'selected' : '' ?>>求才廠商</option>
                <option value="content" <?= ($order == 'content') ? 'selected' : '' ?>>求才內容</option>
                <option value="pdate" <?= ($order == 'pdate') ? 'selected' : '' ?>>刊登日期</option>
            </select>
            <input class="form-control" placeholder="搜尋廠商或內容" type="text" name="searchtxt" value="<?= htmlspecialchars($searchtxt) ?>">
            <input class="btn btn-primary" type="submit" value="搜尋/排序">
        </div>
    </form>

    <h3 class="mb-3">求才資訊列表</h3>
    <table class="table table-bordered table-striped">
        <tr>
        <td>編號</td>
        <td>求才廠商</td>
        <td>求才內容</td>
        <td>刊登日期</td>
        <td></td>
        </tr>
        <?php
        while($row = mysqli_fetch_assoc($result)) {?>
        <tr>
        <td><?=$row["postid"]?></td>
        <td><?=$row["company"]?></td>
        <td><?=$row["content"]?></td>
        <td><?=$row["pdate"]?></td>
        <td>
            <a href="job_update.php?postid=<?=$row["postid"]?>" class="btn btn-primary">修改</a>
            <a href="job_delete.php?postid=<?=$row["postid"]?>" class="btn btn-danger">刪除</a>
        </td>
        </tr>
        <?php
        }
        ?>
    </table>
    </div>
    <div class="container position-relative">
    <a href="job_insert.php" class="btn btn-primary position-absolute" style="top: 1rem; right: 1rem;">+</a>

<?php
    mysqli_close($conn);
}
catch(Exception $e) {
    echo 'Message: ' .$e.getMessage();
}
require_once "footer.php";
?>

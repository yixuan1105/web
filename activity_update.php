<?php
require_once "header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    echo '<div class="container mt-4">';
    echo '  <div class="alert alert-danger">';
    echo ' 只有管理員可以修改活動。';
    echo '  </div>';
    echo '</div>';
    require_once "footer.php";
    exit;
}

$msg = "";
$id = ""; 
$name = "";
$description = "";

try {
    require_once 'db.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $sql_update = "UPDATE event SET name=?, description=? WHERE id=?";
        $stmt_update = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt_update, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "ssi", $name, $description, $id);
        $result = mysqli_stmt_execute($stmt_update);

        if ($result) {
            header('location: index.php'); 
            exit;
        } else {
            $msg = "資料更新失敗";
        }
    } 

    else if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql_select = "select id, name, description from event where id=?";
        $stmt_select = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt_select, $sql_select);
        mysqli_stmt_bind_param($stmt_select, "i", $id);
        mysqli_stmt_execute($stmt_select);
        mysqli_stmt_bind_result($stmt_select, $id, $name, $description);
        mysqli_stmt_fetch($stmt_select);
    }

} catch(Exception $e) {
    $msg = '資料庫連線錯誤: ' .$e->getMessage();
}
?>

<div class="container mt-4">
    <h3 class="mb-3">編輯活動</h3>

    <?php if ($msg): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form action="activity_update.php" method="post">
        <div class="mb-3 row">
            <label for="_name" class="col-sm-2 col-form-label">活動名稱</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="name" id="_name" 
                       placeholder="活動名稱" value="<?= htmlspecialchars($name) ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="_description" class="form-label">活動說明</label>
            <textarea class="form-control" id="_description" name="description" 
                      rows="10" required><?= htmlspecialchars($description) ?></textarea>
        </div>

        <input type="hidden" name="id" value="<?= $id ?>">
        
        <input class="btn btn-primary" type="submit" value="確認修改">
    </form>
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "footer.php";
?>
<?php
require_once "header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    echo '<div class="container mt-4">';
    echo '  <div class="alert alert-danger">';
    echo ' 只有管理員可以修改職缺。';
    echo '  </div>';
    echo '</div>';
    require_once "footer.php";
    exit;
}

$msg = "";
$postid = "";
$company = "";
$content = "";
$pdate = "";

try {
    require_once 'db.php';
    if ($_SERVER["REQUEST_METHOD"] === "POST") {       
        $postid = $_POST['postid'];
        $company = $_POST['company'];
        $content = $_POST['content'];
        $sql_update = "UPDATE job SET company=?, content=? WHERE postid=?";
        $stmt_update = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt_update, $sql_update);
        mysqli_stmt_bind_param($stmt_update, "ssi", $company, $content, $postid);
        $result = mysqli_stmt_execute($stmt_update);

        if ($result) {
            header('location: job.php');
            exit;
        } else {
            $msg = "資料更新失敗";
        }
    }

    else if (isset($_GET['postid'])) {
        $postid = $_GET['postid'];
        
        $sql_select = "select postid, company, content, pdate from job where postid=?";
        $stmt_select = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt_select, $sql_select);
        mysqli_stmt_bind_param($stmt_select, "i", $postid);
        mysqli_stmt_execute($stmt_select);    
        mysqli_stmt_bind_result($stmt_select, $postid, $company, $content, $pdate);
        mysqli_stmt_fetch($stmt_select);
    }

} catch(Exception $e) {
    $msg = '資料庫連線錯誤: ' .$e->getMessage();
}
?>

<div class="container mt-4"> <h3 class="mb-3">修改求才資訊</h3>

    <?php if ($msg): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form action="job_update.php" method="post">
        <div class="mb-3 row">
            <label for="_company" class="col-sm-2 col-form-label">求才廠商</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="company" id="_company" 
                       placeholder="公司名稱" value="<?= htmlspecialchars($company) ?>" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="_content" class="form-label">求才內容</label>
            <textarea class="form-control" id="_content" name="content" 
                      rows="10" required><?= htmlspecialchars($content) ?></textarea>
        </div>

        <input type="hidden" name="postid" value="<?= $postid ?>">
        
        <input class="btn btn-primary" type="submit" value="確認修改">
    </form>
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "footer.php";
?>
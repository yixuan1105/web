<?php
require_once "header.php";

if (!isset($_SESSION['account'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

$msg = "";
$msg_type = "danger";
$account = $_SESSION['account'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_name = $_POST['name'] ?? "";
    $sql_name_update = "UPDATE user SET name = ? WHERE account = ?";
    $stmt_name = mysqli_prepare($conn, $sql_name_update);
    mysqli_stmt_bind_param($stmt_name, "ss", $new_name, $account);
    mysqli_stmt_execute($stmt_name);
    
    // 更新 Session
    $_SESSION['name'] = $new_name; 
    
    $msg = "姓名已成功更新。";
    $msg_type = "success";

    //更新密碼
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($new_password)) {
        $sql_pass_check = "SELECT password FROM user WHERE account = ?";
        $stmt_pass_check = mysqli_prepare($conn, $sql_pass_check);
        mysqli_stmt_bind_param($stmt_pass_check, "s", $account);
        mysqli_stmt_execute($stmt_pass_check);
        $result_pass = mysqli_stmt_get_result($stmt_pass_check);
        $user = mysqli_fetch_assoc($result_pass);
        $current_db_password = $user['password'];

        //檢查舊密碼
        if ($old_password === $current_db_password) {
            if ($new_password === $confirm_password) {
                $sql_pass_update = "UPDATE user SET password = ? WHERE account = ?";
                $stmt_pass_update = mysqli_prepare($conn, $sql_pass_update);
                mysqli_stmt_bind_param($stmt_pass_update, "ss", $new_password, $account);
                mysqli_stmt_execute($stmt_pass_update);
                
                $msg .= "密碼已成功更新。";
                $msg_type = "success";

            } else {
                $msg = "新密碼與確認密碼不相符。";
                $msg_type = "danger";
            }
        } else {
            $msg = "舊密碼不正確。";
            $msg_type = "danger";
        }
    }
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
            <h3 class="mb-3">個人資料</h3>
            <form action="profile.php" method="post" class="p-4 border rounded bg-light">
                
                <?php if ($msg): ?>
                    <div class="alert alert-<?= $msg_type ?>">
                        <?= htmlspecialchars($msg) ?>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label">帳號</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['account']) ?>" disabled>
                </div>
                
                <div class="mb-3">
                    <label for="name" class="form-label">姓名</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="<?= htmlspecialchars($_SESSION['name']) ?>" required>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">修改密碼</h5>

                <div class="mb-3">
                    <label for="old_password" class="form-label">舊密碼</label>
                    <input type="password" class="form-control" id="old_password" name="old_password">
                </div>
                
                <div class="mb-3">
                    <label for="new_password" class="form-label">新密碼</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>
                
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">確認新密碼</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                </div>

                <button type="submit" class="btn btn-primary">更新</button>
            </form>
    </div>
</div>

<?php
if (isset($conn)) {
    mysqli_close($conn);
}
require_once "footer.php";
?>
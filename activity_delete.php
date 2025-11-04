<?php
require_once "header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    echo '<div class="container mt-4">';
    echo '  <div class="alert alert-danger">';
    echo ' 只有管理員可以刪除活動。';
    echo '  </div>';
    echo '</div>';
    require_once "footer.php";
    exit;
}

try {
    $id = ""; 
    $name = "";
    $description = "";

    if ($_GET) {
        require_once 'db.php';
        $action = $_GET["action"] ?? "";

        if ($action == "confirmed"){
            $id = $_GET["id"]; 
            
            $sql = "delete from event where id=?"; 
            
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id);
            $result = mysqli_stmt_execute($stmt);
            
            mysqli_close($conn);
            header('location:index.php'); 
            exit;
        }
        else {
            $id = $_GET["id"]; 
            
            $sql = "select id, name, description from event where id=?"; 
            
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            mysqli_stmt_bind_param($stmt, "i", $id); 
            $res = mysqli_stmt_execute($stmt);
            
            if ($res){
                mysqli_stmt_bind_result($stmt, $id, $name, $description); 
                mysqli_stmt_fetch($stmt);
            }
        }
        mysqli_close($conn);
    }
} catch(Exception $e) {
    echo 'Message: ' .$e->getMessage();
}
?>

<div class="container mt-4">
    <div class="row justify-content-center">
                <div class="card-body d-flex flex-column">                   
                    <h4 class="card-title"><?= htmlspecialchars($name) ?></h4>
                    <p class="card-text">
                        <?= htmlspecialchars($description) ?>
                    </p>

                    <div class="mt-auto text-end">
                        <a href="index.php" class="btn btn-secondary me-2">取消</a>
                        <a href="activity_delete.php?id=<?= $id ?>&action=confirmed" class="btn btn-danger">刪除</a>
                    </div>
                </div>
    </div>
</div>

<?php
require_once "footer.php";
?>
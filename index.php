<?php
include('header.php');
require_once('db.php'); 

$sql = "SELECT * FROM event ORDER BY id DESC"; 
$result = mysqli_query($conn, $sql);
?>

<div class="container my-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>最新活動</h2>
        <a href="activity_insert.php" class="btn btn-primary">+ 新增活動</a>
    </div>
    <div class="row">

        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>

            <div class="col-md-6 mb-4">
                <div class="card h-100 bg-white text-dark">
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title"><?= htmlspecialchars($row['name']) ?></h3>
                        <p class="card-text">
                            <?= htmlspecialchars($row['description']) ?>
                        </p>

                        <div class="mt-auto text-end"> 
                            <a href="activity_update.php?id=<?=$row["id"]?>" class="btn btn-primary btn-sm">編輯</a>
                            <a href="activity_delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">刪除</a>
                        </div>
                        </div>
                </div>
            </div>

        <?php
        } 
        ?>

    </div> 
</div> 

<?php
mysqli_close($conn);
include('footer.php');
?>
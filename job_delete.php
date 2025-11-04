<?php
require_once "header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'M') {
    echo '<div class="container mt-4">';
    echo '  <div class="alert alert-danger">';
    echo ' 只有管理員可以刪除職缺。';
    echo '  </div>';
    echo '</div>';
    require_once "footer.php";
    exit;
}

try {
  $postid = "";
  $company = "";
  $content = "";
  $pdate = "";
  if ($_GET) {
    require_once 'db.php';
    $action = $_GET["action"]??"";
    if ($action=="confirmed"){
      //update data
      $postid = $_GET["postid"];
      $company = $_POST["company"];
      $content = $_POST["content"];
      $sql="update job set company=?, content=? where postid=?";
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "ssi", $company, $content, $postid);
      $result = mysqli_stmt_execute($stmt);
      mysqli_close($conn);
      header('location:job.php');
    }
    else{
      //show data
      $postid = $_GET["postid"];
      $sql="select postid, company, content, pdate from job where postid=?";    
      // $result = mysqli_query($conn, $sql);
      $stmt = mysqli_stmt_init($conn);
      mysqli_stmt_prepare($stmt, $sql);
      mysqli_stmt_bind_param($stmt, "ssi", $company, $content, $postid);
      $res = mysqli_stmt_execute($stmt);
      if ($res){
        mysqli_stmt_bind_result($stmt, $postid, $company, $content, $pdate);
        mysqli_stmt_fetch($stmt);
      }
    }//confirmed else
    mysqli_close($conn);
  }//$_GET
} catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}
?>
<div class="container">
  <table class="table table-bordered table-striped">
    <tr>
      <td>編號</td>
      <td>求才廠商</td>
      <td>求才內容</td>
      <td>刊登日期</td>
    </tr>
    <tr>
      <td><?=$postid?></td>
      <td><?=$company?></td>
      <td><?=$content?></td>
      <td><?=$pdate?></td>
    </tr>
  </table>
  <a href="job_delete.php?postid=<?=$postid?>&action=confirmed" class="btn btn-danger">刪除</a>
</div>
<?php
require_once "footer.php";
?>
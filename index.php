<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }

$conn = mysqli_connect("localhost", "root", "", "simple_db");

// 1. เพิ่มข้อมูล
if (isset($_POST['add'])) {
    $tracking = $_POST['tracking'];
    $receiver = $_POST['receiver'];
    $status   = $_POST['status'];
    mysqli_query($conn, "INSERT INTO parcels (tracking, receiver, status) VALUES ('$tracking', '$receiver', '$status')");
    header("Location: index.php");
}

// 2. ลบข้อมูล
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM parcels WHERE id=$id");
    header("Location: index.php");
}

// 3. ออกจากระบบ
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
}

// ดึงข้อมูลมาแสดง
$result = mysqli_query($conn, "SELECT * FROM parcels");
?>

<!DOCTYPE html>
<html>
<head>
    <title>ระบบพัสดุ</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f4f6f9; }
        table { width: 100%; border-collapse: collapse; background: white; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #333; color: white; }
        .btn-del { color: red; text-decoration: none; }
        .form-box { background: white; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        input, select, button { padding: 8px; margin-right: 5px; }
    </style>
</head>
<body>

    <h2>จัดการพัสดุ | ยินดีต้อนรับคุณ <?=$_SESSION['admin']?> 
        <a href="index.php?logout=1" style="color:red; font-size:16px;">[ออกจากระบบ]</a>
    </h2>

    <!-- ฟอร์มเพิ่มข้อมูล -->
    <div class="form-box">
        <h3>+ เพิ่มพัสดุใหม่</h3>
        <form method="POST">
            <input type="text" name="tracking" placeholder="เลข Tracking" required>
            <input type="text" name="receiver" placeholder="ชื่อผู้รับ" required>
            <select name="status">
                <option value="กำลังส่ง">กำลังส่ง</option>
                <option value="ส่งเรียบร้อย">ส่งเรียบร้อย</option>
            </select>
            <button type="submit" name="add" style="background:#007bff; color:white; border:none;">บันทึก</button>
        </form>
    </div>

    <!-- ตารางแสดงข้อมูล -->
    <table>
        <tr>
            <th>ID</th>
            <th>Tracking</th>
            <th>ชื่อผู้รับ</th>
            <th>สถานะ</th>
            <th>จัดการ</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?=$row['id']?></td>
            <td><?=$row['tracking']?></td>
            <td><?=$row['receiver']?></td>
            <td><?=$row['status']?></td>
            <td>
                <a href="index.php?delete=<?=$row['id']?>" class="btn-del" onclick="return confirm('ยืนยันการลบ?')">ลบ</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
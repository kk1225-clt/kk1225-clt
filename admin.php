<?php
session_start();
include 'db.php';

// 检查用户是否已登录
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // 如果未登录，重定向到登录页面
    header("Location: admin_login.php");
    exit;
}

// 检查用户是否为管理员
$username = $_SESSION['username'];
$sql = "SELECT * FROM admin WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    // 如果用户不是管理员，重定向到登录页面
    header("Location: admin_login.php");
    exit;
}

// 审核待处理的用户
if (isset($_GET['approve'])) {
    $id = $_GET['approve'];

    // 获取待审核用户信息
    $sql = "SELECT * FROM pending_users WHERE id = $id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    // 插入到 users 表
    $insert_sql = "INSERT INTO users (student_id, name, password, major, email) 
                   VALUES ('{$user['student_id']}', '{$user['name']}', '{$user['password']}', '{$user['major']}', '{$user['email']}')";
    
    if ($conn->query($insert_sql) === TRUE) {
        // 删除待审核用户
        $delete_sql = "DELETE FROM pending_users WHERE id = $id";
        $conn->query($delete_sql);
        echo "<p style='color: green;'>用户审核通过，已成功添加至用户表！</p>";
    } else {
        echo "<p style='color: red;'>错误: " . $conn->error . "</p>";
    }
}

// 获取待审核用户列表
$sql = "SELECT * FROM pending_users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员审核用户</title>
    <link rel="stylesheet" href="styles.css"> <!-- 引入CSS样式 -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #E6F2FF;
            color: #333;
            padding: 20px;
        }
        .main-content {
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2, h3 {
            color: #007BFF;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #007BFF;
            text-align: left;
        }
        th {
            background-color: #E6F2FF;
        }
        img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>
<body>

<div class="main-content">
    <h2>待审核用户列表</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>学号/工号</th>
            <th>姓名</th>
            <th>专业</th>
            <th>邮箱</th>
            <th>身份证明照片</th>
            <th>操作</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['student_id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['major']}</td>
                        <td>{$row['email']}</td>
                        <td><img src='{$row['id_photo']}' alt='身份证明'></td>
                        <td><a href='?approve={$row['id']}'>审核通过</a></td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>没有待审核的用户</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

<?php
session_start();
include 'db.php';

// 处理登录表单
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 查询 users 表，检查用户名和密码
    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit;
    } else {
        $error_message = "用户名或密码错误！";
    }
}
?>

<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员登录</title>
    <link rel="stylesheet" href="styles.css"> <!-- 引入CSS样式 -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #E6F2FF;
            color: #333;
            padding: 80px;
        }
        .login-form {
            background-color: #FFFFFF;
            border-radius: 10px;
            padding: 15px; /* 减少内边距 */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto; /* 水平居中 */
            max-width: 400px; /* 限制最大宽度 */
        }
        .login-form h2 {
            text-align: center;
            color: #007BFF;
        }
        .login-form input[type="text"],
        .login-form input[type="password"] {
            width: calc(100% - 20px);
            padding: 8px; /* 减少输入框的内边距 */
            margin-bottom: 10px;
            border: 1px solid #007BFF;
            border-radius: 5px;
            font-size: 14px; /* 减小字体大小 */
        }
        .login-form input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 8px; /* 减少按钮的内边距 */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 14px; /* 减小字体大小 */
        }
        .login-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="login-form">
    <h2>管理员登录</h2>
    <form method="post">
        用户名: <input type="text" name="username" required><br>
        密码: <input type="password" name="password" required><br>
        <input type="submit" value="登录">
    </form>
    <?php
    if (isset($error_message)) {
        echo "<div class='error-message'>$error_message</div>";
    }
    ?>
</div>

</body>
</html>
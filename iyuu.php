<?php

// 提前约定的私有密钥
$secret = 'your_secret_key';

// 接收GET请求参数
$token = isset($_GET['token']) ? $_GET['token'] : null;
$id = isset($_GET['id']) ? $_GET['id'] : null;
$verity = isset($_GET['verity']) ? $_GET['verity'] : null;

// 检查参数是否完整
if (empty($token) || empty($id) || empty($verity)) {
    $response = array('success' => false, 'msg' => 'Incomplete parameters');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// 连接数据库（请替换为你的实际数据库连接信息）
$servername = "localhost";
$username = "nexusphp";
$password = "nexusphp";
$dbname = "nexusphp";

$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 从数据库获取passkey
$passkey = getUserPasskey($conn, $id);

// 验证算法
$expectedVerity = md5($token . $id . sha1($passkey) . $secret);

// 验证结果
if ($verity === $expectedVerity) {
    $response = array('success' => true);
} else {
    $response = array('success' => false, 'msg' => 'Wrong Message');
}

// 返回JSON格式的响应
header('Content-Type: application/json');
echo json_encode($response);

// 关闭数据库连接
$conn->close();

// 示例函数：从数据库获取用户passkey的函数
function getUserPasskey($conn, $userId) {
    $sql = "SELECT passkey FROM users WHERE id = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row["passkey"];
    } else {
        // 如果找不到用户，可以返回一个默认值或者触发错误处理
        return 'Failed to find';
    }
}

?>

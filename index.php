<?php
$conn = new mysqli("localhost", "root", "", "smart_methods");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $age = $_POST["age"];
    
    if (!empty($name) && !empty($age)) {
        $stmt = $conn->prepare("INSERT INTO users (name, age, status) VALUES (?, ?, 0)");
        $stmt->bind_param("si", $name, $age);
        $stmt->execute();
        $stmt->close();
    }
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Smart Methods</title>
    <style>
        body { font-family: Arial; padding: 30px; direction: rtl; }
        input[type="text"], input[type="number"] { padding: 5px; margin: 5px; }
        button { padding: 5px 10px; }
        table { border-collapse: collapse; margin-top: 20px; width: 100%; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
    </style>
</head>
<body>

<h2>الأساليب الذكية - Smart Methods</h2>

<form method="POST">
    <input type="text" name="name" placeholder="الاسم" required>
    <input type="number" name="age" placeholder="العمر" required>
    <button type="submit">إرسال</button>
</form>

<table>
    <tr>
        <th>الرقم</th><th>الاسم</th><th>العمر</th><th>الحالة</th><th>إجراء</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row["id"] ?></td>
            <td><?= $row["name"] ?></td>
            <td><?= $row["age"] ?></td>
            <td id="status-<?= $row["id"] ?>"><?= $row["status"] ?></td>
            <td>
                <button onclick="toggleStatus(<?= $row['id'] ?>)">تغيير</button>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<script>
function toggleStatus(id) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "toggle.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (this.status == 200) {
            document.getElementById("status-" + id).innerText = this.responseText;
        }
    };
    xhr.send("id=" + id);
}
</script>

</body>
</html>
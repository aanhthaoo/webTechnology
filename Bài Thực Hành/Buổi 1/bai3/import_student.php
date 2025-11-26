<?php
require __DIR__ . '/../db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_csv'])) {
    $file = $_FILES['file_csv']['tmp_name'];
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        fgetcsv($handle); 
        $count = 0;
        
        $sql = "INSERT INTO students (username, password, lastname, firstname, city, email, course1) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            try {
                $stmt->execute([
                    $row[0], // username
                    $row[1], // password
                    $row[2], // lastname
                    $row[3], // firstname
                    $row[4], // city
                    $row[5], // email
                    $row[6]  // course1
                ]);
                $count++;
            } catch (Exception $e) {
                continue; 
            }
        }
        fclose($handle);
        $message = "Đã nhập thành công $count sinh viên vào CSDL!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head><title>Import Sinh Viên</title></head>
<body>
    <h1>Bài 4.3: Import Sinh viên từ CSV</h1>
    <?php if($message) echo "<p style='color:green'>$message</p>"; ?>
    
    <form method="POST" enctype="multipart/form-data">
        <label>Chọn file data.csv:</label>
        <input type="file" name="file_csv" required accept=".csv">
        <button type="submit">Upload & Import</button>
    </form>
</body>
</html>
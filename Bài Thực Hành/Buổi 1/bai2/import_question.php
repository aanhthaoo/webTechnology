<?php
require __DIR__ . '/../db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_txt'])) {
    $file = $_FILES['file_txt']['tmp_name'];
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $current_question = [];
    $count = 0;

    foreach ($lines as $line) {
        $line = trim($line);

        if (strpos($line, 'ANSWER:') === 0) {
            $current_question['answer'] = trim(substr($line, 8));

            $sql = "INSERT INTO questions (question_content, option_a, option_b, option_c, option_d, correct_answer) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            try {
                $stmt->execute([
                    $current_question['title'],
                    $current_question['options']['A'],
                    $current_question['options']['B'],
                    $current_question['options']['C'],
                    $current_question['options']['D'],
                    $current_question['answer']
                ]);
                $count++;
            } catch (Exception $e) {
                $message .= "Lỗi dòng: " . $e->getMessage();
            }

            $current_question = [];
        } elseif (preg_match('/^[A-D]\./', $line)) {
            $key = substr($line, 0, 1);
            $current_question['options'][$key] = $line;
        } else {
            $current_question['title'] = $line;
        }
    }
    $message = "Đã nhập thành công $count câu hỏi vào CSDL!";
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <title>Import Câu Hỏi</title>
</head>

<body>
    <h1>Bài 4.2: Import Câu hỏi từ TXT</h1>
    <?php if ($message) echo "<p style='color:green'>$message</p>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Chọn file questions.txt:</label>
        <input type="file" name="file_txt" required accept=".txt">
        <button type="submit">Upload & Import</button>
    </form>
</body>

</html>
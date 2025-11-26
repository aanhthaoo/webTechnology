<?php
// Kéo logic và dữ liệu về đây dùng
require_once 'handle_test.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trắc nghiệm</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            line-height: 1.6;
        }

        .question-block {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 5px solid #007bff;
        }

        .question-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .option {
            display: block;
            margin: 5px 0;
            cursor: pointer;
        }

        .result-box {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .btn-submit {
            background: #007bff;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-submit:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .btn-reset {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-reset:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>

    <h1>Bài Thi Trắc Nghiệm</h1>

    <?php if ($show_result): ?>
        <div class="result-box">
            <h2>Kết quả: Bạn làm đúng <?= $score ?> / <?= $total_questions ?> câu!</h2>

            <a href="index.php" class="btn-reset">Làm lại bài thi</a>
        </div>
    <?php endif; ?>

    <?php if (empty($questions)): ?>
        <p class="error">Không tìm thấy dữ liệu câu hỏi trong file data.txt!</p>
    <?php else: ?>

        <form method="POST" action="">
            <?php foreach ($questions as $index => $q): ?>
                <div class="question-block">
                    <div class="question-title">Câu <?= $index + 1 ?>: <?= htmlspecialchars($q['title']) ?></div>

                    <?php if (isset($q['options'])): ?>
                        <?php foreach ($q['options'] as $key => $option_text): ?>
                            <label class="option">
                                <input type="radio"
                                    name="question_<?= $index ?>"
                                    value="<?= $key ?>"
                                    <?php

                                    if (isset($_POST['question_' . $index]) && $_POST['question_' . $index] === $key) {
                                        echo 'checked';
                                    }
                                    ?>>

                                <?= htmlspecialchars($option_text) ?>
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                </div>
            <?php endforeach; ?>

            <button type="submit" class="btn-submit">Nộp bài thi</button>
        </form>

    <?php endif; ?>

</body>

</html>
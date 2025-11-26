<?php
$filename = 'data.txt';
$questions = [];
$show_result = false; 
$score = 0;
$total_questions = 0;

function loadQuestions($file)
{
    if (!file_exists($file)) {
        return [];
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $data = [];
    $current_question = [];

    foreach ($lines as $line) {
        $line = trim($line);

        if (strpos($line, 'ANSWER:') === 0) {
            $current_question['correct_answer'] = trim(substr($line, 8));
            $data[] = $current_question;
            $current_question = [];
        } elseif (preg_match('/^[A-D]\./', $line)) {
            $key = substr($line, 0, 1);
            $current_question['options'][$key] = $line;
        } else {
            $current_question['title'] = $line;
        }
    }
    return $data;
}

$questions = loadQuestions($filename);
$total_questions = count($questions);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $show_result = true; 

    foreach ($questions as $index => $q) {
        if (isset($_POST['question_' . $index])) {
            $user_ans = $_POST['question_' . $index];
            if ($user_ans === $q['correct_answer']) {
                $score++;
            }
        }
    }
}

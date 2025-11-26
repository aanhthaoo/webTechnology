<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Danh Sách Tài Khoản</h2>

        <?php
        $filename = 'data.csv';
        $data = []; 

        if (file_exists($filename) && ($handle = fopen($filename, "r")) !== FALSE) {          
            $headers = fgetcsv($handle, 1000, ",");
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $data[] = $row;
            }
            fclose($handle);
        } else {
            echo "<div class='alert alert-danger'>Không tìm thấy file data.csv!</div>";
        }
        ?>

        <?php if (!empty($headers) && !empty($data)): ?>

            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <?php foreach ($headers as $colName): ?>
                            <th><?= htmlspecialchars(strtoupper($colName)) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <?php foreach ($row as $cell): ?>
                                <td><?= htmlspecialchars($cell) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

</body>

</html>
<?php

require_once __DIR__ . '../../project-php/include/include.php';

$users = db_select("select * from user_tb");
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

$per_page = 15;
$total_records = count($users);

$start = ($current_page - 1) * $per_page;

$total_pages = ceil($total_records / $per_page);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>php pagination</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-4">
        <table class="table table-bordered">
            <tbody>
                <?php if ($total_records > 0) :
                    $num = $start;
                    foreach ($users as $user) :
                ?>
                        <?php if ($num >= $start && $num < $start + $per_page) : ?>
                            <tr>
                                <td><?= ($num + 1) ?></td>
                                <td><?= $user['user_name'] ?></td>
                                <td><?= $user['user_pass'] ?></td>
                                <td><?= $user['user_phone'] ?></td>
                                <td><?= $user['user_email'] ?></td>
                            </tr>
                        <?php endif; ?>
                    <?php $num++; endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php if ($total_records > 0) : ?>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <?php if ($current_page > 1) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $current_page - 1; ?>">ก่อนหน้า</a>
                        </li>
                    <?php else : ?>
                        <li class="page-item disabled">
                            <a class="page-link">ก่อนหน้า</a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?= ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($current_page < $total_pages) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $current_page + 1; ?>">ต่อไป</a>
                        </li>
                    <?php else : ?>
                        <li class="page-item disabled">
                            <a class="page-link">ต่อไป</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</body>

</html>
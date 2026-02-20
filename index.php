<?php
require_once 'db.php';

$pdo = getPDO();
$stmt = $pdo->query('SELECT * FROM students ORDER BY id DESC');
$students = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Students — List</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="container">
        <h1>Student Records</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="create.php" class="btn">Add Student</a>
        </nav>
    </div>
</header>
<main class="container">

<section>
    <div class="card">
        <h2>All Students</h2>
        <?php if (count($students) === 0): ?>
            <p>No students found. <a href="create.php">Add one</a>.</p>
        <?php else: ?>
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($students as $s): ?>
                    <tr>
                        <td><?php echo $s['id']; ?></td>
                        <td><?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($s['email']); ?></td>
                        <td><?php echo htmlspecialchars($s['course']); ?></td>
                        <td>
                            <a class="btn small" href="edit.php?id=<?php echo $s['id']; ?>">Edit</a>
                            <a class="btn small danger" href="delete.php?id=<?php echo $s['id']; ?>" onclick="return confirm('Delete this student?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</section>

</main>
<footer class="site-footer">
    <div class="container">&copy; <?php echo date('Y'); ?> Student CRUD</div>
</footer>
</body>
</html>

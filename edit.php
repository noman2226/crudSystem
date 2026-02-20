<?php
require_once 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT * FROM students WHERE id = ?');
$stmt->execute([$id]);
$student = $stmt->fetch();
if (!$student) {
    header('Location: index.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = trim($_POST['dob'] ?? null);
    $course = trim($_POST['course'] ?? '');

    if ($first === '' || $last === '') $errors[] = 'First and last name are required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';

    if (empty($errors)) {
        $upd = $pdo->prepare('UPDATE students SET first_name=?, last_name=?, email=?, dob=?, course=? WHERE id=?');
        $upd->execute([$first, $last, $email, $dob, $course, $id]);
        header('Location: index.php');
        exit;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Student</title>
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
        <h2>Edit Student</h2>
        <?php if ($errors): ?>
            <div class="errors"><ul><?php foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>'; ?></ul></div>
        <?php endif; ?>

        <form method="post" class="form">
            <label>First name
                <input name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? $student['first_name']); ?>">
            </label>
            <label>Last name
                <input name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? $student['last_name']); ?>">
            </label>
            <label>Email
                <input name="email" type="email" value="<?php echo htmlspecialchars($_POST['email'] ?? $student['email']); ?>">
            </label>
            <label>Date of birth
                <input name="dob" type="date" value="<?php echo htmlspecialchars($_POST['dob'] ?? $student['dob']); ?>">
            </label>
            <label>Course
                <input name="course" value="<?php echo htmlspecialchars($_POST['course'] ?? $student['course']); ?>">
            </label>
            <div class="actions">
                <button class="btn" type="submit">Save</button>
                <a class="btn" href="index.php">Cancel</a>
            </div>
        </form>
    </div>
</section>

</main>
<footer class="site-footer">
    <div class="container">&copy; <?php echo date('Y'); ?> Student CRUD</div>
</footer>
</body>
</html>

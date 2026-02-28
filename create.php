<?php
require_once 'db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $reg = trim($_POST['reg_no'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $dob = trim($_POST['dob'] ?? null);
    $course = trim($_POST['course'] ?? '');

    if ($first === '' || $last === '') $errors[] = 'First and last name are required.';
    if ($reg === '') $errors[] = 'Registration number is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';

    if (empty($errors)) {
        $pdo = getPDO();
        $stmt = $pdo->prepare('INSERT INTO students (first_name,last_name,reg_no,email,dob,course,created_at) VALUES (?,?,?,?,?,?,NOW())');
        $stmt->execute([$first, $last, $reg, $email, $dob, $course]);
        // redirect with created status for alert on list page
        header('Location: index.php?status=created');
        exit;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Add Student</title>
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
        <h2>Add Student</h2>
        <?php if ($errors): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>'; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" class="form">
            <label>First name
                <input name="first_name" value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>">
            </label>
            <label>Last name
                <input name="last_name" value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>">
            </label>
            <label>Registration No.
                <input name="reg_no" value="<?php echo htmlspecialchars($_POST['reg_no'] ?? ''); ?>">
            </label>
            <label>Email
                <input name="email" type="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </label>
            <label>Date of birth
                <input name="dob" type="date" value="<?php echo htmlspecialchars($_POST['dob'] ?? ''); ?>">
            </label>
            <label>Course
                <input name="course" value="<?php echo htmlspecialchars($_POST['course'] ?? ''); ?>">
            </label>
            <div class="actions">
                <button class="btn" type="submit">Create</button>
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

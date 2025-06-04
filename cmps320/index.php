<?php
require 'db_config.php';

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $mysqli->query("DELETE FROM ranking WHERE id=$id");
    header("Location: index.php");
    exit;
}

// Adding Entries
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    if (!empty($_POST['name']) && !empty($_POST['time']) && !empty($_POST['platform'])) {
        $name = $mysqli->real_escape_string($_POST['name']);
        $time = $mysqli->real_escape_string($_POST['time']);
        $platform = $mysqli->real_escape_string($_POST['platform']);
        $mysqli->query("INSERT INTO ranking (name, time, platform) VALUES ('$name', '$time', '$platform')");
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>All fields are required.</p>";
    }
}

// Updating Entries
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    if (!empty($_POST['name']) && !empty($_POST['time']) && !empty($_POST['platform'])) {
        $id = (int) $_POST['id'];
        $name = $mysqli->real_escape_string($_POST['name']);
        $time = $mysqli->real_escape_string($_POST['time']);
        $platform = $mysqli->real_escape_string($_POST['platform']);
        $mysqli->query("UPDATE ranking SET name='$name', time='$time', platform='$platform' WHERE id=$id");
        header("Location: index.php");
        exit;
    } else {
        echo "<p style='color:red;'>All fields are required for update.</p>";
    }
}

// Fetch data
$result = $mysqli->query("SELECT * FROM ranking ORDER BY STR_TO_DATE(time, '%H:%i:%s') ASC");
$edit_id = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Speedrun Rankings</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<nav class="navbar">
  <ul>
    <li><a href="#">Home</a></li>
    <li><a href="#">Rankings</a></li>
    <li><a href="#">Community</a></li>
    <li><a href="#">Merch</a></li>
    <li><a href="#">About</a></li>
  </ul>
</nav>

<h2>Speedrun Rankings</h2>

<table class="ranking-table">
  <thead>
    <tr>
      <th>Rank</th>
      <th>Name</th>
      <th>Time</th>
      <th>Platform</th>
      <th>Edit</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $rank = 1;
    while ($row = $result->fetch_assoc()):
      if ($edit_id === (int)$row['id']):
    ?>
    <tr>
      <form method="post">
        <td class="rank"><?= $rank ?></td>
        <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required></td>
        <td><input type="text" name="time" value="<?= htmlspecialchars($row['time']) ?>" required></td>
        <td><input type="text" name="platform" value="<?= htmlspecialchars($row['platform']) ?>" required></td>
        <td>
          <input type="hidden" name="id" value="<?= $row['id'] ?>">
          <button type="submit" name="update">Save</button>
          <a href="index.php" class="cancel-link">Cancel</a>
        </td>
      </form>
    </tr>
    <?php else: ?>
    <tr>
      <td class="rank"><?= $rank ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['time']) ?></td>
      <td><?= htmlspecialchars($row['platform']) ?></td>
      <td>
        <a href="?edit=<?= $row['id'] ?>" class="edit-link">Edit</a> |
        <a href="?delete=<?= $row['id'] ?>" class="delete-link" onclick="return confirm
        ('Are you sure you want to delete this entry?');">Delete</a>
      </td>
    </tr>
    <?php endif; $rank++; endwhile; ?>
  </tbody>
</table>

<h3>Add New Entry</h3>
<form method="post" class="entry-form">
  <input type="text" name="name" placeholder="Name" required>
  <input type="text" name="time" placeholder="Time (HH:MM:SS)" required>
  <input type="text" name="platform" placeholder="Platform (e.g. PC, PS5, Switch)" required>
  <button type="submit" name="add">Add</button>
</form>

<footer class="footer">
  <p>Made By: Josh Ting<br>
  <p>&copy; <?= date("Y") ?> SpeedRankings. All rights reserved.</p>
  <a href="nigeljosh.ting@la-verne.edu">nigeljosh.ting@la-verne.edu</a></p>
</footer>
</body>
</html>
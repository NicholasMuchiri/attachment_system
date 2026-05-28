<?php
session_start();
include 'db.php';

// Only allow admin session
if (!isset($_SESSION['admin'])) { 
    header("Location: index.php"); 
    exit(); 
}

// Delete attachee if requested
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM attachees WHERE id='$id'");
}

// Fetch data
$attachees = $conn->query("SELECT * FROM attachees");
$reports = $conn->query("SELECT r.*, a.name FROM reports r JOIN attachees a ON r.attachee_id=a.id ORDER BY r.uploaded_at DESC");
$attendance = $conn->query("SELECT att.*, a.name FROM attendance att JOIN attachees a ON att.attachee_id=a.id ORDER BY att.sign_in DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<style>
body {margin:0; font-family:'Segoe UI',Arial,sans-serif; background:#eef2f7;}
.sidebar {width:110px; background:#00BFF0; height:100vh; position:fixed; color:#fff; padding:20px;}
.sidebar h2 {color:#030901; margin-bottom:30px;}
.sidebar a {display:block; color:#cbd5e1; text-decoration:none; margin:55px 0; font-weight:400; cursor:pointer;}
.sidebar a:hover {color:#1e293b;}
.topbar {margin-left:150px; background:#fff; padding:20px; box-shadow:0 2px 5px rgba(124,222,6,0.53);}
.topbar h2 {margin:0; color:#1e293b;}
.content {margin-left:110px; padding:20px;}
.card {background:#fff; padding:20px; margin-bottom:20px; border-radius:12px; box-shadow:0 4px 10px rgba(3,73,17,0.05);}
.card h3 {margin-top:0; color:#0ea5e9;}
table {width:100%; border-collapse:collapse; margin-top:10px;}
th,td {border:1px solid #e2e8f0; padding:10px; text-align:center;}
th {background:#0ea5e9; color:#fff;}
tr:nth-child(even){background:#f9fafb;}
a.delete {color:#ef4444; text-decoration:none; font-weight:bold;}
a.delete:hover {text-decoration:underline;}
@keyframes fadeIn {from{opacity:0;} to{opacity:1;}}
.footer {
  position: fixed;
  bottom: 0;
  left: 160px;
  right: 0;
  background: #f8f9fa;
  border-top: 1px solid rgb(22, 242, 2);
  padding: 10px 0;
  text-align: center;
  font-size: 10px;
  color: #555;
  z-index: 10;
}
.footer .dev-name {font-weight: 600; color: #023e8a;}
.footer .dev-company {font-weight: 500; color: #0bb01e;}
.footer .social-links {margin-top: 5px;}
.footer .social-links a {
  margin: 0 1px;
  text-decoration: none;
  color: #ec0b0b;
  font-weight: 800;
  transition: color 0.3s ease, transform o.2s ease;
  font-size: 10px;
}
.footer .social-links a:hover {color: #0bb01e;}
</style>
<script>
function showSection(sectionId) {
    document.querySelectorAll('.card').forEach(card => card.style.display='none');
    document.getElementById(sectionId).style.display='block';
}
</script>
</head>
<body>
<div class="sidebar">
    <h2></h2>
    <a onclick="showSection('attachees')">👥 Attachees</a>
    <a onclick="showSection('reports')">📄 Reports</a>
    <a onclick="showSection('attendance')">🗓 Attendance</a>
    <a href="logout.php">🚪 Logout</a>
    
</div>
<div class="topbar">
   <h2>Admin Panel</h2>
</div>
<div class="content">
    <!-- Attachees -->
    <div class="card" id="attachees">
        <h3>Attachees</h3>
        <table>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Institution</th>
              <th>Course</th>
              <th>Action</th>
            </tr>
            <?php while($row=$attachees->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['institution'] ?></td>
                    <td><?= $row['course'] ?></td>
                    <td><a class="delete" href="admin.php?delete=<?= $row['id'] ?>">Delete</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Reports -->
    <div class="card" id="reports" style="display:none;">
        <h3>Reports</h3>
        <table>
            <tr><th>ID</th><th>Attachee</th><th>Report</th><th>File</th><th>Uploaded At</th></tr>
            <?php while($row=$reports->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['report_text'] ?></td>
                    <td><a href="<?= $row['file_path'] ?>" target="_blank">Download</a></td>
                    <td><?= date("d M Y, h:i A", strtotime($row['uploaded_at'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- Attendance -->
    <div class="card" id="attendance" style="display:none;">
        <h3>Attendance</h3>
        <table>
            <tr><th>ID</th><th>Attachee</th><th>Date</th><th>Sign In</th><th>Sign Out</th></tr>
            <?php while($row=$attendance->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= date("d M Y", strtotime($row['sign_in'])) ?></td>
                    <td><?= date("h:i A", strtotime($row['sign_in'])) ?></td>
                    <td><?= $row['sign_out'] ? date("h:i A", strtotime($row['sign_out'])) : "—" ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <div class="container">
    <!-- Footer Section -->
<footer class="footer">
  <p>System developed by <span class="dev-name"></span><span class="dev-company">devops.co.ke</span></p>
  <div class="social-links">
    <a href="https://github.com/yourusername" target="_blank">GitHub</a>
    <a href="https://twitter.com/yourusername" target="_blank">Twitter</a>
    <a href="mailto:yourmail@example.com">Email</a>
  </div>
</footer>

</div>
</body>
</html>
<?php
session_start();
include 'db.php';
if (!isset($_SESSION['attachee_id'])) { header("Location: index.php"); exit(); }
$id = $_SESSION['attachee_id'];

// Fetch attachee details
$attachee = $conn->query("SELECT * FROM attachees WHERE id='$id'")->fetch_assoc();

// Handle report upload
if (isset($_POST['report'])) {
    $report_text = $_POST['report_text'];
    $file_name = $_FILES['report_file']['name'];
    $file_tmp = $_FILES['report_file']['tmp_name'];
    $upload_path = "uploads/" . basename($file_name);
    move_uploaded_file($file_tmp, $upload_path);
    $conn->query("INSERT INTO reports (attachee_id, report_text, file_path, uploaded_at) 
                  VALUES ('$id','$report_text','$upload_path',NOW())");
    header("Location: dashboard.php?section=reportHistory");
    exit();
}

// Handle attendance sign in
if (isset($_POST['sign_in'])) {
    $now = date("Y-m-d H:i:s");
    $check = $conn->query("SELECT * FROM attendance WHERE attachee_id='$id' AND DATE(sign_in)=CURDATE()");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO attendance (attachee_id, sign_in) VALUES ('$id','$now')");
    }
    header("Location: dashboard.php?section=attendanceSection");
    exit();
}

// Handle attendance sign out
if (isset($_POST['sign_out'])) {
    $now = date("Y-m-d H:i:s");
    $check = $conn->query("SELECT * FROM attendance WHERE attachee_id='$id' AND DATE(sign_in)=CURDATE()");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE attendance SET sign_out='$now' WHERE attachee_id='$id' AND DATE(sign_in)=CURDATE()");
    }
    header("Location: dashboard.php?section=attendanceSection");
    exit();
}

// Handle report deletion
if (isset($_GET['delete_report'])) {
    $report_id = $_GET['delete_report'];
    $conn->query("DELETE FROM reports WHERE id='$report_id' AND attachee_id='$id'");
    header("Location: dashboard.php?section=reportHistory");
    exit();
}

// Determine active section
$activeSection = isset($_GET['section']) ? $_GET['section'] : 'reportSection';
?>
<!DOCTYPE html>
<html>
<head>
<title>Attachee Dashboard</title>
<style>
body {margin:0; font-family:'Segoe UI',Arial,sans-serif; background:#f4f6f9;}
.sidebar {width:130px; background:#00BFF0; height:100vh; position:fixed; color:#fff; padding:15px;}
.sidebar h2 {color:#ffffff; margin-bottom:25px; font-size:18px; text-align:center;}
.sidebar a {display:block; color:#ffffff; text-decoration:none; margin:55px 0; font-weight:400; cursor:pointer; font-size:14px; transition:0.3s;}
.sidebar a:hover {color:#1e293b; background:rgba(37, 212, 6, 0.2); padding-left:10px; border-radius:6px;}
.topbar {margin-left:160px; background:#fff; padding:12px; box-shadow:0 2px 5px rgba(14, 144, 2, 0.1);}
.topbar h2 {margin:0; color:#1e293b;}
.content {margin-left:160px; padding:20px;}
.card {background:#fff; padding:20px; margin-bottom:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.05); animation:fadeIn 0.3s ease;}
.card h3 {margin-top:0; color:#0ea5e9;}
textarea,input[type=file]{width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;}
button {background:#0ea5e9; color:#fff; border:none; padding:10px 15px; border-radius:5px; cursor:pointer;}
button:hover {background:#0284c7;}
table {width:100%; border-collapse:collapse; margin-top:10px;}
th,td {border:1px solid #e2e8f0; padding:10px; text-align:left;}
th {background:#0ea5e9; color:#fff;}
tr:nth-child(even){background:#f9fafb;}
a.delete {color:#ef4444; text-decoration:none; font-weight:bold;}
a.delete:hover {text-decoration:underline;}
@keyframes fadeIn {from{opacity:0;} to{opacity:1;}}

.update-form label {display:block; margin-top:10px; font-size:14px; color:#333;}
.update-form input {width:100%; padding:8px; margin-top:5px; border:1px solid #ccc; border-radius:6px;}
.update-form button {margin-top:15px; background:teal; color:#fff; border:none; padding:10px 15px; border-radius:6px; cursor:pointer;}
.update-form button:hover {background:#006666;}

.footer {position: fixed; bottom: 0; left: 160px; right: 0; background: #f8f9fa; border-top: 1px solid rgb(22, 242, 2); padding: 10px 0; text-align: center; font-size: 10px; color: #555; z-index: 10;}
.footer .dev-name {font-weight: 600; color: #023e8a;}
.footer .dev-company {font-weight: 500; color: #0bb01e;}
.footer .social-links {margin-top: 5px;}
.footer .social-links a {margin: 0 1px; text-decoration: none; color: #ec0b0b; font-weight: 800; transition: color 0.3s ease, transform o.2s ease; font-size: 10px;}
.footer .social-links a:hover {color: #0bb01e;}
</style>
<script>
function showSection(sectionId) {
    document.querySelectorAll('.card').forEach(card => card.style.display='none');
    document.getElementById(sectionId).style.display='block';
    document.getElementById('pageTitle').innerText = document.getElementById(sectionId).getAttribute('data-title');
}
window.onload = function() {
    showSection('<?php echo $activeSection; ?>');
};
</script>
</head>
<body>
<div class="sidebar">
    <h2>Attachee</h2>
    <a onclick="showSection('profileSection')">👤 My Details</a>
    <a onclick="showSection('reportSection')">📄 Upload Report</a>
    <a onclick="showSection('reportHistory')">📑 My Reports</a>
    <a onclick="showSection('attendanceSection')">🗓 Attendance</a>
    <a href="logout.php">🚪 Logout</a>
</div>
<div class="topbar">
    <h2 id="pageTitle">Dashboard</h2>
</div>
<div class="content">
    <!-- Profile Section -->
    <div class="card" id="profileSection" data-title="My Details" style="display:none;">
        <h3>My Details</h3>
        <form method="post" action="update_details.php" class="update-form">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $attachee['name']; ?>" required>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $attachee['email']; ?>" required>
            <label>Institution:</label>
            <input type="text" name="institution" value="<?php echo $attachee['institution']; ?>" required>
            <label>Course:</label>
            <input type="text" name="course" value="<?php echo $attachee['course']; ?>" required>
            <button type="submit" name="update">Save Changes</button>
        </form>
    </div>

    <!-- Upload Report -->
    <div class="card" id="reportSection" data-title="Upload Report">
        <h3>Upload Report</h3>
        <form method="post" enctype="multipart/form-data">
            <textarea name="report_text" rows="4" placeholder="Write your report summary..." required></textarea><br><br>
            <input type="file" name="report_file" accept=".pdf,.doc,.docx" required><br><br>
            <button type="submit" name="report">Submit Report</button>
        </form>
    </div>

    <!-- Report History -->
    <div class="card" id="reportHistory" data-title="My Reports" style="display:none;">
        <h3>My Reports</h3>
        <table>
            <tr><th>ID</th><th>Report</th><th>File</th><th>Uploaded At</th><th>Action</th></tr>
            <?php
            $myReports = $conn->query("SELECT * FROM reports WHERE attachee_id='$id' ORDER BY uploaded_at DESC");
            while($row=$myReports->fetch_assoc()){
                echo "<tr>
                         <td>".$row['id']."</td>
                         <td>".$row['report_text']."</td>
                         <td><a href='".$row['file_path']."' target='_blank'>View</a></td>
                         <td>".date('d M Y H:i',strtotime($row['uploaded_at']))."</td>
                         <td><a href='dashboard.php?delete_report=".$row['id']."' class='delete'>Delete</a></td>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <!-- Attendance -->
    <div class="card" id="attendanceSection" data-title="Attendance" style="display:none;">
        <h3>Mark Attendance</h3>
        <form method="post" style="display:inline;">
            <button type="submit" name="sign_in">Sign In (Morning)</button>
        </form>
        <form method="post" style="display:inline;">
            <button type="submit" name="sign_out">Sign Out (Afternoon)</button>
        </form>
        <br><br>
        <table>
            <tr><th>Date</th><th>Sign In</th><th>Sign Out</th></tr>
            <?php
            $att = $conn->query("SELECT * FROM attendance WHERE attachee_id='$id' ORDER BY sign_in DESC");
            while($row=$att->fetch_assoc()){
                echo "<tr>
                        <td>".date('d M Y',strtotime($row['sign_in']))."</td>
                        <td>".date('h:i A',strtotime($row['sign_in']))."</td>
                        <td>".($row['sign_out'] ? date('h:i A',strtotime($row['sign_out'])) : '—')."</td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
  <p>System developed by <span class="dev-name">devops.co.ke</span></p>
  <p>follow us on:</p>
  <div class="social-links">
     <a href="https://github.com/NicholasMuchiri" target="_blank">GitHub</a>
     <a href="https://twitter.com/cnotex1" target="_blank">Twitter</a>
     <a href="mailto:nicholasmuchiri45@gmail.com">Email</a>
  </div>
</footer>
</body>
</html>

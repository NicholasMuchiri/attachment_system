<?php
session_start();
include 'db.php';

/* Signup logic */
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $institution = $_POST['institution'];
    $course = $_POST['course'];

    $conn->query("INSERT INTO attachees (name,email,password,institution,course) 
                  VALUES ('$name','$email','$password','$institution','$course')");

    // Redirect to login page with success flag
    header("Location: index.php?signup=success");
    exit();
}

/* Login logic */
$login_error = null;
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Admin login shortcut
    if ($email === "admin@gmail.com" && $password === "admin123") {
        $_SESSION['admin'] = true;
        header("Location: admin.php?login=success");
        exit();
    }

    // Attachee login
    $result = $conn->query("SELECT * FROM attachees WHERE email='$email'");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['attachee_id'] = $row['id'];
            header("Location: dashboard.php?login=success");
            exit();
        } else {
            $login_error = "Invalid password.";
        }
    } else {
        $login_error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login / Signup</title>
<style>
body {
  font-family: 'Segoe UI', Arial, sans-serif;
  background: url('images/Copilot_20260521_210833.jpg') no-repeat center center fixed;
  background-size: cover;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

/* Modern container styling */
.container {
  background: rgb(239, 235, 235);
  backdrop-filter: blur(12px);
  border-radius: 16px;
  box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
  width: 320px;
  padding: 30px 25px;
  text-align: center;
  border: 1px solid rgba(255, 255, 255, 0.3);
  transition: all 0.3s ease;
}

.container:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
}

/* Logo */
.logo {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #b0240b;
  margin: 10px auto;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* Headings */
h2 {
  font-size: 20px;
  font-weight: 500;
  color: #0bb01e;
  margin-bottom: 10px;
  letter-spacing: 0.5px;
}

/* Inputs */
input {
  width: 100%;
  padding: 10px;
  margin: 8px 0;
  border: 1px solid rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.8);
  font-size: 14px;
  outline: none;
  transition: border 0.2s ease;
}

input:focus {
  border-color: #0bb01e;
}

/* Buttons */
button {
  width: 100%;
  padding: 10px;
  background: linear-gradient(135deg, #2206be, #023e8a);
  color: #fff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 500;
  transition: background 0.3s ease;
}

button:hover {
  background: linear-gradient(135deg, #023e8a, #2206be);
}

/* Toggle links */
.toggle {
  margin-top: 10px;
  font-size: 13px;
}

.toggle a {
  color: #0077b6;
  text-decoration: none;
  font-weight: 600;
}

.toggle a:hover {
  text-decoration: underline;
}

/* Success & Error messages with fade-in and auto-disappear */
.success-msg, .error-msg {
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 15px;
  font-size: 14px;
  text-align: center;
  animation: fadeIn 0.8s ease-in-out;
}

.success-msg {
  background: #d1e7dd;
  color: #07ba07;
}

.error-msg {
  background: #f8d7da;
  color: #842029;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
<script>
function toggleForm() {
  const loginForm = document.getElementById('loginForm');
  const signupForm = document.getElementById('signupForm');
  if (loginForm.style.display === 'none') {
    loginForm.style.display = 'block';
    signupForm.style.display = 'none';
  } else {
    loginForm.style.display = 'none';
    signupForm.style.display = 'block';
  }
}

// Auto-hide success messages after 3 seconds
window.onload = function() {
  setTimeout(function() {
    const successMsg = document.querySelector('.success-msg');
    if (successMsg) {
      successMsg.style.display = 'none';
    }
  }, 3000);
};
</script>
</head>
<body>
<div class="container">
   <h2>County Government of Laikipia.</h2>
   <h2> Attachee's portal</h2>
   <img src="images/logo.jpg" alt="County Logo" class="logo">

   <!-- Success & Error messages inside container -->
   <?php
   if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
       echo "<div class='success-msg'>Account created successfully! Please login.</div>";
   }
   if (isset($_GET['login']) && $_GET['login'] === 'success') {
       echo "<div class='success-msg'>Login successful! Welcome back.</div>";
   }
   if (!empty($login_error)) {
       echo "<div class='error-msg'>$login_error</div>";
   }
   ?>

  <!-- Login Form -->
  <div id="loginForm">
    <form method="post" action="index.php">
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
    </form>
    <div class="toggle">
      <p>Don't have an account? <a href="#" onclick="toggleForm()">Sign up</a></p>
    </div>
  </div>

  <!-- Signup Form -->
  <div id="signupForm" style="display:none;">
    <h2>Signup</h2>
    <form method="post" action="index.php">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="text" name="institution" placeholder="Institution Name" required>
      <input type="text" name="course" placeholder="Course Undertaken" required>
      <button type="submit" name="signup">Signup</button>
    </form>
    <div class="toggle">
      <p>Already have an account? <a href="#" onclick="toggleForm()">Login</a></p>
    </div>
  </div>
</div>
</body>
</html>
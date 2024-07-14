<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Database connection
include('db_connect.php');

// Fetch all members
$members = [];
$result = $conn->query("SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
    $members[] = $row;
}

// Delete a member
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $conn->query("DELETE FROM users WHERE id = $delete_id");
    header("Location: manage_members.php");
    exit;
}

// Search functionality
$search_results = [];
if (isset($_POST['search'])) {
    $search_term = $_POST['search_term'];
    if (is_numeric($search_term)) {
        $result = $conn->query("SELECT * FROM users WHERE id = $search_term");
    } else {
        $result = $conn->query("SELECT * FROM users WHERE first_name LIKE '%$search_term%' OR last_name LIKE '%$search_term%'");
    }
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Members</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('assets/img/background5.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
            z-index: 1;
            opacity: 0; /* Initially hide the body for animation */
            animation: fadeIn 1s forwards; /* Fade in animation */
        }

        @keyframes fadeIn {
            to {
                opacity: 1; /* Fade to fully visible */
            }
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.12);
            z-index: -1;
        }

        header, footer {
            position: relative;
            z-index: 2;
        }

        header {
            background-color: #353a3f;
            color: white;
            padding: 15px 0;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
            width: 100%;
            display: flex;
            justify-content: center;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .nav-bar nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .nav-bar nav ul li {
            margin-left: 9px;
            position: relative;
        }

        .nav-bar nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .nav-bar nav ul li a:hover,
        .nav-bar nav ul li a:focus {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly transparent white background */
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 1200px;
            margin: 100px auto 20px;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
        }

        .container p {
            margin: 10px 0;
            font-size: 1.3em;
        }

        .container a {
            color: #007bff;
            text-decoration: none;
        }

        .container a:hover {
            text-decoration: underline;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar input[type="text"] {
            width: calc(100% - 130px);
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-right: 10px;
        }

        .search-bar input[type="submit"] {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            background-color: #0d6efd;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-bar input[type="submit"]:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .action-buttons a {
            background-color: #0d6efd;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 5px;
            display: inline-block;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
        }

        .action-buttons .delete-button {
            background-color: #dc3545;
        }

        .action-buttons .delete-button:hover {
            background-color: #c82333;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.3em;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: 100%;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        .back-dashboard-button {
            background-color: #6c757d; /* Gray color */
            color: white;
            border: none;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.3em;
            margin-top: 20px;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: 100%;
        }

        .back-dashboard-button:hover {
            background-color: #5a6268;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 90%;
            border-radius: 10px;
            max-width: 1300px;
            max-height: 90%;
            overflow-y: auto;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal h3 {
            margin-top: 0;
        }

        .modal table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .modal table, .modal th, .modal td {
            border: 1px solid #ddd;
        }

        .modal th, .modal td {
            padding: 12px;
            text-align: left;
        }

        .modal th {
            background-color: #f2f2f2;
        }

        .modal .action-buttons a {
            background-color: #0d6efd;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin: 0 5px;
            display: inline-block;
        }

        .modal .action-buttons a:hover {
            background-color: #0056b3;
        }

        .modal .action-buttons .delete-button {
            background-color: #dc3545;
        }

        .modal .action-buttons .delete-button:hover {
            background-color: #c82333;
        }
          /* New Section: Footer */
          footer {
            background-color: #353a3f;
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        .footer-content {
            display: flex;
            justify-content: space-around;
            text-align: center;
            width: 75%;
            margin: 0 auto;
            flex-wrap: wrap;
        }

        .footer-content div {
            flex: 1;
            margin: -8px 10px;
        }

        .footer-content h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .footer-content p,
        .footer-content a {
            font-size: 16px;
            line-height: 1.2;
            color: #ccc;
            text-decoration: none;
        }

        .footer-content a:hover {
            text-decoration: underline;
        }

        .footer-bottom {
            margin-top: 20px;
        }

        .footer-content div p {
            margin: 3px 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-bar">
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                    <li><a href="manage_members.php">Manage Members</a></li>
                    <li><a href="manage_appointments_admin.php">Manage Appointments</a></li>
                    <li><a href="manage_products.php">Manage Products</a></li>
                    <li><a href="admin_class_schedule.php">Manage Classes</a></li>
                    <li><a href="manage_news.php">Manage News</a></li>
                    <li><a href="manage_orders.php">Manage Orders</a></li>
                    <li><a href="manage_messages.php">Manage Messages</a></li>
                    <li><a href="manage_class_recordings.php">Manage Recording</a></li>
                    <li><a href="manage_class_fees.php">Manage Fees</a></li>
                   
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h2>Manage Members</h2>

        <div class="search-bar">
            <form method="POST" action="">
                <input type="text" name="search_term" placeholder="Enter name or ID to search">
                <input type="submit" name="search" value="Search">
            </form>
        </div>

        <div class="members-list">
            <h3>Registered Members:</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?php echo $member['id']; ?></td>
                        <td><?php echo $member['first_name']; ?></td>
                        <td><?php echo $member['last_name']; ?></td>
                        <td><?php echo $member['age']; ?></td>
                        <td><?php echo $member['address']; ?></td>
                        <td><?php echo $member['phone']; ?></td>
                        <td><?php echo $member['email']; ?></td>
                        <td><?php echo $member['gender']; ?></td>
                        <td><?php echo $member['username']; ?></td>
                        <td class="action-buttons">
                            <a href="update_member.php?id=<?php echo $member['id']; ?>">Update</a>
                            <a href="manage_members.php?delete_id=<?php echo $member['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <button class="logout-button" onclick="window.location.href='logout.php'">Logout</button>
        <button class="back-dashboard-button" onclick="window.location.href='admin_dashboard.php'">Back to Dashboard</button>
    </div>

    <!-- The Modal -->
    <div id="searchModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Search Results</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Age</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
                <?php if (!empty($search_results)): ?>
                    <?php foreach ($search_results as $member): ?>
                        <tr>
                            <td><?php echo $member['id']; ?></td>
                            <td><?php echo $member['first_name']; ?></td>
                            <td><?php echo $member['last_name']; ?></td>
                            <td><?php echo $member['age']; ?></td>
                            <td><?php echo $member['address']; ?></td>
                            <td><?php echo $member['phone']; ?></td>
                            <td><?php echo $member['email']; ?></td>
                            <td><?php echo $member['gender']; ?></td>
                            <td><?php echo $member['username']; ?></td>
                            <td class="action-buttons">
                                <a href="update_member.php?id=<?php echo $member['id']; ?>">Update</a>
                                <a href="manage_members.php?delete_id=<?php echo $member['id']; ?>" class="delete-button" onclick="return confirm('Are you sure you want to delete this member?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No results found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div>
                <h2>About Us</h2>
                <p>Fitness Kingdom is your ultimate destination for achieving your fitness goals. Our certified trainers and state-of-the-art facilities ensure you get the best out of your workouts.</p>
            </div>
            <div>
                <h2>Quick Links</h2>
                <p><a href="index.php">Home</a></p>
                <p><a href="about_us.php">About Us</a></p>
                <p><a href="pricing.php">Pricing</a></p>
                <p><a href="products.php">Products</a></p>
                <p><a href="news.php">News</a></p>
                <p><a href="login.php">Login</a></p>
                <p><a href="contact_us.php">Contact Us</a></p>
            </div>
            <div>
                <h2>Contact Us</h2>
                <p>678/9B, Church Rd, Ragama</p>
                <p>071 7879 168</p>
                <p>fitnesskingdomgym@gmail.com</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2024 Fitness Kingdom Gym. All Rights Reserved.</p>
        </div>
    </footer>

    <script>
        // Get the modal
        var modal = document.getElementById("searchModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Open the modal if there are search results
        <?php if (!empty($search_results)): ?>
            modal.style.display = "block";
        <?php endif; ?>

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>

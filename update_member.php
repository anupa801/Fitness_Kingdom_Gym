<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Database connection
include('db_connect.php');

// Get member details
$member_id = $_GET['id'];
$member = $conn->query("SELECT * FROM users WHERE id = $member_id")->fetch_assoc();

// Update member details
if (isset($_POST['update'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];

    $sql = "UPDATE users SET 
                first_name = '$first_name',
                last_name = '$last_name',
                age = $age,
                address = '$address',
                phone = '$phone',
                email = '$email',
                gender = '$gender',
                username = '$username'
            WHERE id = $member_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: manage_members.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Member</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            color: #333;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 90%;
            max-width: 600px;
            margin: 100px auto 20px;
        }

        .container h2 {
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
        }

        .container form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .container form label {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .container form input, .container form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .container form button {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .container form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Member</h2>
        <form method="POST" action="">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $member['first_name']; ?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $member['last_name']; ?>" required>

            <label for="age">Age</label>
            <input type="number" id="age" name="age" value="<?php echo $member['age']; ?>" required>

            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo $member['address']; ?>" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo $member['phone']; ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $member['email']; ?>" required>

            <label for="gender">Gender</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php if($member['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if($member['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            </select>

            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $member['username']; ?>" required>

            <button type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>

<?php
include('db_connect.php');

if (isset($_GET['member_id'])) {
    $member_id = $_GET['member_id'];
    $search_query = $conn->prepare("SELECT appointments.*, coaches.name AS coach_name FROM appointments JOIN coaches ON appointments.coach_id = coaches.id WHERE username = ? ORDER BY appointment_date, appointment_time");
    $search_query->bind_param("s", $member_id);
    $search_query->execute();
    $search_results = $search_query->get_result()->fetch_all(MYSQLI_ASSOC);

    echo json_encode($search_results);
}
?>

<?php include "./partials/connection.php";

$query = isset($_GET['q']) ? trim($_GET['q']) : '';
if ($query === '') { exit; }

$current_user = $_SESSION['user_id'] ?? null;

if ($current_user) {
    // logged-in: only their links
    $sql = "SELECT link_name, link_url FROM links WHERE user_id = ? AND link_name LIKE ? LIMIT 5";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("is", $current_user, $searchTerm);
} else {
    // not logged-in: show public links (user_id IS NULL)
    $sql = "SELECT link_name, link_url FROM links WHERE user_id IS NULL AND link_name LIKE ? LIMIT 5";
    $stmt = $conn->prepare($sql);
    $searchTerm = '%' . $query . '%';
    $stmt->bind_param("s", $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li><a target='_blank' class='dropdown-item text-primary' href='" . htmlspecialchars($row['link_url']) . "'>" . htmlspecialchars($row['link_name']) . "</a></li>";
    }
} else {
    echo "<li class='dropdown-item text-muted'>No results found</li>";
}
$stmt->close();
$conn->close();
?>

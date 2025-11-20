<?php include "./partials/connection.php";?>

<?php
// current logged-in user (null when not logged in)
$current_user = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // ---- ADD LINK ----
  if (isset($_POST['add-link'])) {
    $link_name = $_POST['link-name'];
    $link_url = $_POST['link-url'];
    $category_id = (int)$_POST['category-id'];

    if ($current_user === null) {
      // insert as public (user_id IS NULL)
      $sql1 = "INSERT INTO links (category_id,link_name,link_url) VALUES (?,?,?)";
      $stmt = $conn->prepare($sql1);
      $stmt->bind_param("iss", $category_id, $link_name, $link_url);
    } else {
      $sql1 = "INSERT INTO links (category_id,link_name,link_url,user_id) VALUES (?,?,?,?)";
      $stmt = $conn->prepare($sql1);
      $stmt->bind_param("issi", $category_id, $link_name, $link_url, $current_user);
    }
    $stmt->execute();
    header("Location:index.php");
    exit();

  // ---- DELETE LINK ----
  } else if (isset($_POST['delete-link'])) {
    $link_id = (int)$_POST['link-id'];
    if ($current_user === null) {
      // disallow deleting public links from anonymous users
      header("HTTP/1.1 403 Forbidden");
      exit('Not allowed');
    }
    $sql2 = "DELETE FROM links WHERE link_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql2);
    $stmt->bind_param("ii", $link_id, $current_user);
    $stmt->execute();
    header("Location:index.php");
    exit();

  // ---- EDIT LINK ----
  } else if (isset($_POST['edit-link'])) {
    $link_id = (int)$_POST['link-id'];
    $link_name = $_POST['edit-link-name'];
    $link_url = $_POST['edit-link-url'];
    if ($current_user === null) {
      header("HTTP/1.1 403 Forbidden");
      exit('Not allowed');
    }
    $sql3 = "UPDATE links SET link_name = ?, link_url = ? WHERE link_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql3);
    $stmt->bind_param("ssii", $link_name, $link_url, $link_id, $current_user);
    $stmt->execute();
    header("Location:index.php");
    exit();

  // ---- SAVE NEW CATEGORY ----
  } else if (isset($_POST['save-new-category'])) {
    $category = $_POST['new-category'];
    if ($current_user === null) {
      $sql = "INSERT INTO category (category) VALUES (?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $category);
    } else {
      $sql = "INSERT INTO category (category, user_id) VALUES (?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("si", $category, $current_user);
    }
    $stmt->execute();
    header("Location:index.php");
    exit();

  // ---- DELETE CATEGORY ----
  } else if (isset($_POST['delete-category-btn'])) {
    $category_id = (int)$_POST['category-id'];
    if ($current_user === null) {
      // disallow deleting public categories via anonymous users
      header("HTTP/1.1 403 Forbidden");
      exit('Not allowed');
    }
    $sql = "DELETE FROM category WHERE category_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $category_id, $current_user);
    $stmt->execute();
    header("Location:index.php");
    exit();
  }
}
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="icon" href="./partials/favicon.png" style="border-radius:50%;" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="./styles/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>My Bookmarks</title>
</head>

<body>
  <?php include "./partials/nav.php" ?>
  <div class="container-fluid mt-5" id="main-container">
    <div class="d-flex flex-wrap py-4 justify-content-center"><!-- Main div that contains all the cards  -->
      <?php
      // Fetch categories depending on login state
      if ($current_user) {
        $stmt = $conn->prepare("SELECT * FROM category WHERE user_id = ?");
        $stmt->bind_param("i", $current_user);
        $stmt->execute();
        $result = $stmt->get_result();
      } else {
        $result = mysqli_query($conn, "SELECT * FROM category WHERE user_id IS NULL");
      }

      if ($result) {
        while ($card = mysqli_fetch_assoc($result)) {
          ?>
          <div class="p-4 border rounded d-flex flex-column card mx-3 my-3" id="card">
            <!--Card that is created at the run time with all the links-->
            <h2 class="text-center fs-4 mb-3"><?php echo htmlspecialchars($card['category']); ?>
              <form class="form-inline" method="POST" action="#" onsubmit="return confirmDelete();">
                <input type="hidden" name="category-id" value="<?php echo $card['category_id']; ?>">
                <button type="submit" class="btn btn-danger btn-icon" id="delete-category-btn" name="delete-category-btn">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </h2>
            <?php
            // Fetch links for this category depending on login
            if ($current_user) {
              $link_query = "SELECT * FROM links WHERE category_id = ? AND user_id = ?";
              $stmt = $conn->prepare($link_query);
              $stmt->bind_param("ii", $card['category_id'], $current_user);
            } else {
              $link_query = "SELECT * FROM links WHERE category_id = ? AND user_id IS NULL";
              $stmt = $conn->prepare($link_query);
              $stmt->bind_param("i", $card['category_id']);
            }
            $stmt->execute();
            $links_result = $stmt->get_result();
            if ($links_result->num_rows > 0) {
              echo "<div class='list-container'><ul>";
              while ($links = $links_result->fetch_assoc()) {
                ?>
                <li>
                  <form method="POST" class="form-inline" action="#" onsubmit="return confirmDelete();">
                    <input type="hidden" name="link-id" value="<?php echo $links['link_id']; ?>">
                    <button type="submit" class="btn btn-outline-danger delete-btn btn-icon" name="delete-link">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                  <button type="button" class="btn btn-outline-warning edit-btn btn-icon" name="edit-link"
                    data-link-id="<?php echo $links['link_id']; ?>" data-link-name="<?php echo htmlspecialchars($links['link_name'], ENT_QUOTES); ?>"
                    data-link-url="<?php echo htmlspecialchars($links['link_url'], ENT_QUOTES); ?>" data-link-cat-id="<?php echo $links['category_id']; ?>">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                  <a target="_blank" class="link fs-6"
                    href="<?php echo htmlspecialchars($links['link_url']); ?>"><?php echo htmlspecialchars($links['link_name']); ?></a>
                </li>
                <?php
              }
              ?>
              </ul>
            </div>
            <div class="link-edit-input p-2 text-center d-flex align-items-center">
              <div class="edit-container" id="edit-container-<?php echo $card['category_id']; ?>">
                <form method="POST" action="#" class="d-flex align-items-center form gap-2 w-100 edit-form">
                  <button class="btn btn-outline-primary" type="submit" name="edit-link">Save</button>
                  <input type="hidden" name="link-id" class="link-edit-id">
                  <input class="form-control link-edit-name" type="text" placeholder="Link Name" name="edit-link-name">
                  <input class="form-control link-edit-url" type="text" placeholder="Paste link here" name="edit-link-url">
                </form>
              </div>
            </div>
            <?php
            } else {
              echo "<div class='list-container'>";
              echo "<ul>";
              echo "<li>No Links are found for this Category. Added links will be shown below</li>";
              echo "</ul>";
              echo "</div>";
            }
            ?>
          <div class="link-add-input p-2 text-center d-flex align-items-center">
            <form method="POST" action="#" class="d-flex align-items-center add-link-form gap-2 w-100">
              <button class="btn btn-outline-primary" type="submit" name="add-link">Add</button>
              <input class="form-control link-name" type="text" placeholder="Link Name" name="link-name" required>
              <input class="form-control link-url" type="text" placeholder="Paste link here" name="link-url" required>
              <input type="hidden" name="category-id" value="<?php echo $card['category_id']; ?>">
            </form>
          </div>
        </div>
        <?php
        }
      }
      ?>


    <div class="p-4 border rounded d-flex flex-column card mx-3 my-3">
      <form method="POST" action="#" class="form-inline">
        <h2 class="text-center mb-3"><input type="text" class="new-link-category d-none" placeholder="New Category Name"
            name="new-category" required></h2>
        <div class="list-container">
          <div class="new-card d-flex justify-content-center align-items-center">
            <a id="new-card-btn">+</a>
            <button class="btn btn-outline-primary save-new-category d-none w-50" name="save-new-category"
              type="submit">Save</button>
          </div>
        </div>
      </form>
    </div>


  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="./script.js"></script>
</body>

</html>

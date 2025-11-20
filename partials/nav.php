
<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$DEFAULT_AVATAR = '/assets/default-avatar.svg';

function e($s) {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

$loggedIn  = !empty($_SESSION['user_id']);
$uname     = $loggedIn ? e($_SESSION['user_name']) : '';
$upic_raw  = $loggedIn ? ($_SESSION['user_picture'] ?? '') : '';
$upic      = ($upic_raw && filter_var($upic_raw, FILTER_VALIDATE_URL)) ? e($upic_raw) : '';


?>

<link rel="stylesheet" href="./styles/_navStyles.css">

<nav class="navbar navbar-expand-md fixed-top bg-nav">
    <div class="container-fluid">

        <a class="navbar-brand text-light fw-bold" href="#">Bookmarks</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#bmNav" aria-controls="bmNav" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="bmNav">

        <form class="d-flex ms-md-3 my-2 my-md-0 position-relative" role="search">
            <input class="form-control" id="search-box" autocomplete="off" placeholder="Search link names...">
            <ul class="dropdown-menu w-100 mt-1 position-absolute" id="search-results" style="top:100%; left:0;"></ul>
        </form>


            <!-- <form class="d-flex ms-md-3 my-2 my-md-0 w-100 w-md-50" role="search">
                <input class="form-control me-2" id="search-box" autocomplete="off"
                    placeholder="Search link names...">
                <ul class="dropdown-menu" id="search-results"></ul>
            </form> -->

            <div class="ms-auto d-flex align-items-center gap-2 mt-2 mt-md-0">

                <?php if ($loggedIn): ?>

                    <img src="avatar.php?u=<?= urlencode($upic_raw) ?>"width="32" height="32" style="border-radius:50%;" onerror="this.src='./assets/default-avatar.svg'">
                    <span class="text-light d-none d-sm-inline"><?= $uname ?></span>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>

                <?php else: ?>

                    <a class="btn btn-light btn-sm"
                        href="https://accounts.google.com/o/oauth2/v2/auth?client_id=1057071399510-0l23e2bbmjqsioft56366qhs47nl3dad.apps.googleusercontent.com&redirect_uri=http://localhost/php_projects/Bookmarks/google-login.php&response_type=code&scope=email%20profile">
                        Login with Google
                    </a>

                <?php endif; ?>

            </div>

        </div>
    </div>
</nav>

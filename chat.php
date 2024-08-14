<?php
include 'dbconfig.php';
session_start();
$username = $_SESSION['username'];

$sql = "SELECT * FROM users WHERE username='$username';";
$result = mysqli_query($conn, $sql);
$info = mysqli_fetch_assoc($result);

$name = $info['name'];
$email = $info['email'];

if (isset($_POST['search_query'])) {
    $searchQuery = $_POST['search_query'];
    // Exclude the logged-in user from search results
    $sql = "SELECT username, name FROM users WHERE (username LIKE '%$searchQuery%' OR name LIKE '%$searchQuery%') AND username != '$username' LIMIT 10";
    $result = mysqli_query($conn, $sql);
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    echo json_encode($users);
    exit;
}

if (isset($_POST['fetch_messages'])) {
    $receiver = $_POST['receiver'];
    $sql = "SELECT * FROM messages WHERE (sender='$username' AND receiver='$receiver') OR (sender='$receiver' AND receiver='$username') ORDER BY timestamp";
    $result = mysqli_query($conn, $sql);
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
    echo json_encode($messages);
    exit;
}

if (isset($_POST['send_message'])) {
    $receiver = $_POST['receiver'];
    $message = $_POST['message'];
    $sql = "INSERT INTO messages (sender, receiver, message) VALUES ('$username', '$receiver', '$message')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXTREAM - Chat</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="additional-files/extra.css">
    <link rel="stylesheet" href="additional-files/me.css">
    <link rel="icon" type="image/x-icon" href="assets/LOGO_tab.svg" />

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">
            <img src="assets/LOGO.svg" width="30" height="30" class="d-inline-block align-top" alt="" id="dash-icon">
            <b>PIXTREAM</b>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php"><b>Home</b><span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="people.php"><b>People</b><span class="sr-only"></span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="chat.php"><b>Chat</b><span class="sr-only"></span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b><?php echo $name; ?></b>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h3>Chat</h3>
                <input type="text" class="form-control" id="searchUser" placeholder="Search users..." onkeyup="liveSearch()">
                <div id="userList" class="user-list"></div>
                <div id="noChats" class="text-center mt-3">No chats</div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body chat-body" id="chatBody"></div>
                <div class="modal-footer">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="messageInput" placeholder="Type a message">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" onclick="sendMessage()">
                                <i class="fa-solid fa-arrow-up"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="library-files/js/search.js"></script>
</body>
</html>

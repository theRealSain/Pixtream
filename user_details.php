<?php
// Include the database connection
include 'dbconfig.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: authen.php");
    exit();
}

// Fetch the user_id based on the logged-in username
$username = $_SESSION['username'];
$query_user_id = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($query_user_id);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($user_id);
$stmt->fetch();
$stmt->close();

// Fetch all categories and options from the database
$query_categories = "SELECT c.id AS category_id, c.category_name, o.id AS option_id, o.option_name 
                     FROM categories c
                     JOIN options o ON c.id = o.category_id
                     ORDER BY c.category_name, o.option_name";
$result_categories = $conn->query($query_categories);

// Check for errors in the query
if (!$result_categories) {
    die("Query Failed: " . $conn->error);
}

// Initialize an array to store categories and their options
$categories = [];
while ($row = $result_categories->fetch_assoc()) {
    $categories[$row['category_name']][] = $row;
}

// Get the last three categories for the new column
$last_three_categories = array_slice($categories, -3, 3, true);
$remaining_categories = array_slice($categories, 0, -3, true);

// Fetch selected interests for the logged-in user
$query_user_interests = "SELECT option_id FROM user_selections WHERE user_id = ?";
$stmt = $conn->prepare($query_user_interests);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$selected_interests = [];
while ($row = $result->fetch_assoc()) {
    $selected_interests[] = $row['option_id'];
}

// Fetch the user's location and bio
$query_user_details = "SELECT location, bio FROM users WHERE id = ?";
$stmt_details = $conn->prepare($query_user_details);
$stmt_details->bind_param("i", $user_id);
$stmt_details->execute();
$result_details = $stmt_details->get_result();
$user_details = $result_details->fetch_assoc();

$location = $user_details['location'] ?? '';
$bio = $user_details['bio'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Profile - Pixtream</title>
    <link rel="stylesheet" href="library-files/css/bootstrap.min.css">
    <link rel="stylesheet" href="library-files/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/me.css">
    <link rel="icon" type="image/x-icon" href="assets/img/LOGO_tab.svg">
</head>
<body>
    <div class="container mt-5">
        <form id="interestForm" action="profile_complete.php" method="POST" class="p-4 shadow rounded">
            <h3 class="mb-4 text-center text-uppercase">Complete Your Profile</h3>

            <div class="row">
                <div class="col-md-6">
                    <!-- Loop through remaining categories -->
                    <?php foreach ($remaining_categories as $category_name => $options): ?>
                        <div class="form-group mb-3">
                            <label class="d-block text-uppercase"><b><?php echo htmlspecialchars($category_name); ?></b></label>
                            <?php foreach ($options as $option): ?>
                                <div class="form-check">
                                    <input class="form-check-input mycheck" type="checkbox" name="interests[]" value="<?php echo $option['option_id']; ?>" id="option_<?php echo $option['option_id']; ?>" 
                                    <?php echo in_array($option['option_id'], $selected_interests) ? 'checked' : ''; ?>>
                                    <label class="form-check-label mycheck-label" for="option_<?php echo $option['option_id']; ?>">
                                        <?php echo htmlspecialchars($option['option_name']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-6">
                    <!-- Loop through last three categories -->
                    <?php foreach ($last_three_categories as $category_name => $options): ?>
                        <div class="form-group mb-3">
                            <label class="d-block text-uppercase"><b><?php echo htmlspecialchars($category_name); ?></b></label>
                            <?php foreach ($options as $option): ?>
                                <div class="form-check">
                                    <input class="form-check-input mycheck" type="checkbox" name="interests[]" value="<?php echo $option['option_id']; ?>" id="option_<?php echo $option['option_id']; ?>" 
                                    <?php echo in_array($option['option_id'], $selected_interests) ? 'checked' : ''; ?>>
                                    <label class="form-check-label mycheck-label" for="option_<?php echo $option['option_id']; ?>">
                                        <?php echo htmlspecialchars($option['option_name']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="inputState" class="d-block text-uppercase"><b>State</b></label>
                        <select class="form-control" id="inputState" name="location">
                            <option selected hidden>Select a city</option>
                            <?php
                            // Array of options for the cities
                            $cities = [
                                "Agartala", "Ahmedabad", "Ahmadnagar", "Alappuzha", "Aligarh", 
                                "Almora", "Alipur Duar", "Ambala", "Ambikapur", "Amravati", 
                                "Amritsar", "Anantapur", "Anantnag", "Angul", "Ara", 
                                "Arrah", "Arcot", "Aurangabad", "Aizawl", "Badami", 
                                "Badaun", "Balaghat", "Balangir", "Baleshwar", "Ballari", 
                                "Banda", "Bara Banki", "Baramula", "Bardhaman", "Bariyarpur", 
                                "Baripada", "Barwani", "Bastar", "Bhopal", "Bhubaneshwar", 
                                "Bilaspur", "Bihar Sharif", "Bikaner", "Bodhan", "Bodh Gaya", 
                                "Bongaigaon", "Brahmapur", "Budaun", "Bulandshahr", "Chandigarh", 
                                "Chennai", "Chhatarpur", "Chhindwara", "Churachandpur", "Coimbatore", 
                                "Cuddalore", "Cuttack", "Dahod", "Daman", "Davanagere", 
                                "Deoghar", "Deoria", "Dehradun", "Delhi", "Dhanbad", 
                                "Dhar", "Dharamshala", "Dibrugarh", "Dimapur", "Dindigul", 
                                "Durg", "Durgapur", "Eluru", "Ernakulam", "Erode", "Faridabad", 
                                "Faridkot", "Fatehgarh", "Fatehpur", "Firozpur", "Gadag", 
                                "Gandhinagar", "Gangtok", "Gaya", "Ghaziabad", "Ghazipur", 
                                "Giridih", "Gurgaon", "Guwahati", "Hamirpur", "Hapur", 
                                "Hazaribagh", "Hindupur", "Hissar", "Hoshiarpur", "Hubballi-Dharwad", 
                                "Hyderabad", "Idukki", "Imphal", "Indore", "Itanagar", "Itarsi", 
                                "Jabalpur", "Jaipur", "Jaisalmer", "Jalandhar", "Jammu", 
                                "Jamshedpur", "Jodhpur", "Junagadh", "Kachchh", "Kadra", 
                                "Kangra", "Kannur", "Kanpur", "Kanyakumari", "Karaikal", 
                                "Kargil", "Karimnagar", "Karnal", "Kasargod", "Kendujhar", "Khammam", 
                                "Khargone", "Kolar", "Kolhapur", "Kollam", "Kottayam",
                                "Kozhikode", "Krishnanagar", "Lakhimpur", "Latur", "Madhubani", 
                                "Madurai", "Mahabubnagar", "Malappuram", "Malda", "Mandi", 
                                "Mangaluru", "Manipal", "Mathura", "Mau", "Meerut", 
                                "Mehsana", "Midnapore", "Mizoram", "Moga", "Moradabad", 
                                "Muzaffarpur", "Nagapattinam", "Nagpur", "Nalgonda", "Nanded", 
                                "Nashik", "Navi Mumbai", "Nellore", "Nizamabad", "Noida", 
                                "Ooty", "Palakkad", "Panchkula", "Panipat", "Pathanamthitta", "Patiala", 
                                "Patna", "Pimpri-Chinchwad", "Puducherry", "Pune", "Raebareli", 
                                "Raipur", "Rajkot", "Rajpur", "Ranchi", "Ratnagiri", 
                                "Rudrapur", "Sagar", "Sangli", "Sangrur", "Sant Ravidas Nagar", 
                                "Satara", "Satna", "Shahjahanpur", "Shimla", "Sikar", 
                                "Siliguri", "Solapur", "Srinagar", "Surat", "Tirunelveli", 
                                "Tirupati", "Thane", "Thiruvananthapuram", "Thrissur", "Udaipur", "Ujjain", 
                                "Vadodara", "Varanasi", "Vellore", "Vijayawada", "Visakhapatnam", 
                                "Warangal", "Wayanad", "Yamunanagar"
                            ];

                            // Loop through cities to create <option> elements
                            foreach ($cities as $city) {
                                $selected = ($city == $location) ? 'selected' : '';
                                echo "<option value=\"$city\" $selected>$city</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="bio" class="d-block text-uppercase"><b>Add a Bio</b></label>
                        <textarea name="bio" class="form-control" rows="1"><?php echo htmlspecialchars($bio); ?></textarea>
                    </div>
                </div>
            </div>
        


            <div class="d-flex justify-content-between mt-4">
                <button type="reset" class="btn mybtn-outline me-2">Restore to default</button>
                <div class="ms-auto">
                    <a href="profile.php"><button type="button" class="btn mybtn-outline mr-3">Skip</button></a>
                    <button type="submit" class="btn mybtn">Submit <i class="fa-regular fa-circle-right"></i></button>
                </div>
            </div>
        </form>
    </div>

    <script src="assets/js/select.js"></script>
    <script src="library-files/js/jquery.min.js"></script>
    <script src="library-files/js/bootstrap.bundle.min.js"></script>    
</body>
</html>



<input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($location); ?>" required>
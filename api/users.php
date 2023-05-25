<?php

// Database configuration
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Create a new user
function createUser($data) {
    global $servername, $username, $password, $dbname;
    
    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare the SQL statement
    $sql = "INSERT INTO user (name, user_name, email, mobile_no, address, pincode, create_time, update_time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare and bind the parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssss",
        $data['name'],
        $data['user_name'],
        $data['email'],
        $data['mobile_no'],
        $data['address'],
        $data['pincode'],
        $data['create_time'],
        $data['update_time']
    );
    
    // Execute the statement
    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;
        $stmt->close();
        $conn->close();
        return $user_id;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

// Retrieve all users
function getAllUsers() {
    global $servername, $username, $password, $dbname;

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "SELECT * FROM user";

    // Execute the statement
    $result = $conn->query($sql);
    
    // Fetch all rows and store them in an array
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    
    // Close the connection and return the users
    $result->free();
    $conn->close();
    return $users;
}

// Retrieve a specific user by ID
function getUserById($id) {
    global $servername, $username, $password, $dbname;

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL statement
    $sql = "SELECT * FROM user WHERE id = ?";

    // Prepare and bind the parameter
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Fetch the user
    $user = $result->fetch_assoc();

    // Close the connection and return the user
    $stmt->close();
    $conn->close();
    return $user;
}

// Update an existing user
function updateUser($id, $data) {
    global $servername, $username, $password, $dbname;
    
    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare the SQL statement
    $sql = "UPDATE user SET name = ?, user_name = ?, email = ?, mobile_no = ?, address = ?, pincode = ?, update_time = ? WHERE id = ?";
    
    // Prepare and bind the parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssi",
        $data['name'],
        $data['user_name'],
        $data['email'],
        $data['mobile_no'],
        $data['address'],
        $data['pincode'],
        $data['update_time'],
        $id
    );
    
    // Execute the statement
    $success = $stmt->execute();
    
    // Close the connection and return the success status
    $stmt->close();
    $conn->close();
    return $success;
}

// Delete a user
function deleteUser($id) {
    global $servername, $username, $password, $dbname;
    
    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare the SQL statement
    $sql = "DELETE FROM user WHERE id = ?";
    
    // Prepare and bind the parameter
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    // Execute the statement
    $success = $stmt->execute();
    
    // Close the connection and return the success status
    $stmt->close();
    $conn->close();
    return $success;
}

// Example usage

// Create a new user
$newUser = array(
    "name" => "John Doe",
    "user_name" => "johndoe",
    "email" => "johndoe@example.com",
    "mobile_no" => "1234567890",
    "address" => "123 Main St",
    "pincode" => "12345",
    "create_time" => date('Y-m-d H:i:s'),
    "update_time" => date('Y-m-d H:i:s')
);

$user_id = createUser($newUser);
if ($user_id) {
    echo "New user created successfully. ID: " . $user_id;
} else {
    echo "Failed to create user.";
}

// Retrieve all users
$users = getAllUsers();
print_r($users);

// Retrieve a specific user by ID
$user = getUserById(1);
print_r($user);

// Update an existing user
$updatedUser = array(
    "name" => "Jane Doe",
    "user_name" => "janedoe",
    "email" => "janedoe@example.com",
    "mobile_no" => "9876543210",
    "address" => "456 Elm St",
    "pincode" => "54321",
    "update_time" => date('Y-m-d H:i:s')
);

$success = updateUser(1, $updatedUser);
if ($success) {
    echo "User updated successfully.";
} else {
    echo "Failed to update user.";
}

// Delete a user
$success = deleteUser(1);
if ($success) {
    echo "User deleted successfully.";
} else {
    echo "Failed to delete user.";
}


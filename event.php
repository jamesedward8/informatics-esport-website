<?php 

// Membuat koneksi MySQL
$mysqli = new mysqli ("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

else {
    echo "Koneksi database berhasil!";
    echo "<br>";
}

//! SELECT event query
$stmt = $mysqli->prepare("SELECT * FROM event");
$stmt->execute();
$res = $stmt->get_result();

echo "<br><br>";

echo "<table border=2>";
echo "<tr>
        <th>Number</th>     
        <th>Event Name</th>
        <th>Date</th>
        <th>Description</th>
        <th colspan=2>Action</th>
      </tr>";

while ($row = $res->fetch_assoc()) {
    if ($row == null) {
        echo "No data found!";
        exit;
    }

    else {
        $date = new DateTime($row['date']);
        $formatDate = $date->format('d F Y');

        echo "<tr>
                <td>" . $row['idevent'] . "</td>
                <td>" . $row['name'] . "</td>
                <td>" . $formatDate . "</td>
                <td>" . $row['description'] . "</td>
                <td><a href='edit_event.php?idevent=". $row['idevent'] ."'>Edit</a></td>
                <td><a href='delete_event.php?idevent=". $row['idevent'] ."'>Delete</a></td>
              </tr>";
    }

    echo "</table>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>
    <style>
        .table {
            border: 2px solid black;
        }
    </style>
</head>
<body>
</body>
</html>
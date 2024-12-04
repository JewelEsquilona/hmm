<?php
$connectionFile = '../connection.php';
if (!file_exists($connectionFile)) {
    die("Connection file not found.");
}
include($connectionFile);
if (!$con) {
    die("Database connection failed: " . $con->errorInfo()[2]);
}
try {
    $statement = $con->query("
        SELECT 
        a.*, 
        e.Employment, 
        e.Employment_Status, 
        e.Present_Occupation, 
        e.Name_of_Employer, 
        e.Address_of_Employer, 
        e.Number_of_Years_in_Present_Employer, 
        e.Type_of_Employer, 
        e.Major_Line_of_Business,
        CONCAT('AL', LPAD(a.Alumni_ID_Number, 5, '0')) AS Alumni_ID_Number_Format
    FROM `2024-2025` a
    LEFT JOIN `2024-2025_ed` e 
        ON a.`Alumni_ID_Number` = e.`Alumni_ID_Number`
    WHERE e.`Alumni_ID_Number` IS NULL OR e.`ID` = (SELECT MAX(`ID`) FROM `2024-2025_ed` WHERE `Alumni_ID_Number` = a.`Alumni_ID_Number`)
    ");

} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni List</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous" />
</head>
<body class="bg-content">
    <main class="dashboard d-flex">
        <?php include "component/sidebar.php"; ?>
        <div class="container-fluid px">
            <?php include "component/header.php"; ?>
            <div class="alumni-list-header d-flex justify-content-between align-items-center py-2">
                <div class="title h6 fw-bold">Alumni List</div>
                <div class="btn-add d-flex gap-3 align-items-center">
                    <div class="short">
                        <i class="far fa-sort"></i>
                    </div>
                    <?php include 'alumni_add.php'; ?>
                </div>
            </div>
            <div class="table-responsive table-container">
                <table class="table alumni_list table-borderless">
                    <thead>
                        <tr class="align-middle">
                            <th>ID</th>
                            <th>Alumni ID Number</th>
                            <th>Student Number</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>College</th>
                            <th>Department</th>
                            <th>Section</th>
                            <th>Year Graduated</th>
                            <th>Contact Number</th>
                            <th>Personal Email</th>
                            <th>Employment</th>
                            <th>Employment Status</th>
                            <th>Present Occupation</th>
                            <th>Name of Employer</th>
                            <th>Address of Employer</th>
                            <th>Number of Years in Present Employer</th>
                            <th>Type of Employer</th>
                            <th>Major Line of Business</th>
                            <th class="opacity">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($statement->rowCount() > 0): ?>
                            <?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['ID']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Alumni_ID_Number_Format']); ?></td> <!-- Updated to use formatted ID -->
                                    <td><?php echo htmlspecialchars($row['Student_Number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Last_Name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['First_Name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Middle_Name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['College']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Department']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Section']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Year_Graduated']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Contact_Number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Personal_Email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Employment']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Employment_Status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Present_Occupation']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Name_of_Employer']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Address_of_Employer']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Number_of_Years_in_Present_Employer']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Type_of_Employer']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Major_Line_of_Business']); ?></td>
                                    <td>
                                        <a href="alumni_edit.php?Alumni_ID_Number=<?php echo $row['Alumni_ID_Number'] ?>"><i class="far fa-pen"></i></a>
                                        <a href="alumni_process.php?action=delete&alumni_id=<?php echo $row['Alumni_ID_Number']; ?>"><i class="far fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="20" class="text-center">No alumni records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="../assets/js/script.js"></script>
    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>

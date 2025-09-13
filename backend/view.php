<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Inquiries</title>
    <!-- Use the Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Include Tailwind CSS via CDN for streamlined styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen p-8">
    <div class="max-w-7xl mx-auto bg-white p-8 rounded-xl shadow-2xl border border-slate-200">
        <h1 class="text-4xl md:text-5xl font-bold font-display text-center mb-10 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Business Inquiries</h1>

        <?php
        // --- Database configuration ---
        $servername = "localhost";
        $username   = "chatrock";
        $password   = "!7KdItQt(1tD67";
        $dbname     = "chatrock_business_db";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("<div class='text-red-600 text-center font-semibold'>Connection failed: " . $conn->connect_error . "</div>");
        }

        // SQL query to select all data from the inquiries table, ordered by submission date
        // This query now correctly uses the 'inquiries' table and 'submitted_at' column
        $sql = "SELECT id, project_name, business_name, business_contact, website, developer_name, developer_contact, email, expectedMessages, business_details, submitted_at FROM inquiries ORDER BY submitted_at DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data in a responsive table
            echo "<div class='overflow-x-auto'>";
            echo "<table class='min-w-full divide-y divide-slate-200 rounded-lg shadow-md'>";
            echo "<thead class='bg-slate-50'>";
            echo "<tr>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider rounded-tl-lg'>ID</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>Project Name</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>Business Name</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>Business Contact</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>Website</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>Developer Name</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>Developer Contact</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>Email</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>expectedMessages</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider'>Business Details</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider rounded-tr-lg'>Date Submitted</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody class='bg-white divide-y divide-slate-200'>";
            // Loop through each row of the result set
            while($row = $result->fetch_assoc()) {
                echo "<tr class='hover:bg-slate-50 transition-colors duration-200'>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900'>" . $row["id"] . "</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-slate-600'>" . htmlspecialchars($row["project_name"]) . "</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-slate-600'>" . htmlspecialchars($row["business_name"]) . "</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-slate-600'>" . htmlspecialchars($row["business_contact"]) . "</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-slate-600'>" . htmlspecialchars($row["website"]) . "</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-slate-600'>" . htmlspecialchars($row["developer_name"]) . "</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-slate-600'>" . htmlspecialchars($row["developer_contact"]) . "</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-800'><a href='mailto:" . htmlspecialchars($row["email"]) . "'>" . htmlspecialchars($row["email"]) . "</a></td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-slate-600'>" . htmlspecialchars($row["expectedMessages"]) . "</td>";
                echo "<td class='px-6 py-4 text-sm text-slate-600 max-w-xs overflow-hidden text-ellipsis'>" . nl2br(htmlspecialchars($row["business_details"])) . "</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-slate-600'>" . $row["submitted_at"] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<div class='text-center text-slate-500 py-10'>No inquiries found. The table is empty.</div>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</body>
</html>



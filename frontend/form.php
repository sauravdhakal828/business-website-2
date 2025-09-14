<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$successMsg = "";
$errorMsg = "";

// --- Database configuration ---
$servername = "localhost";
$username   = "chatrock_chatrock_user";
$password   = "LWCe^Qr#shLs#.U)";
$dbname     = "chatrock_business_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Basic server-side validation to ensure fields are not empty
    if (empty($_POST['projectName']) || empty($_POST['businessName']) || empty($_POST['email'])) {
        $errorMsg = "Please fill in all required fields.";
    } else {
        $projectName      = htmlspecialchars($_POST['projectName']);
        $businessName     = htmlspecialchars($_POST['businessName']);
        $businessContact  = htmlspecialchars($_POST['businessContact']);
        $website          = htmlspecialchars($_POST['website']);
        $developerName    = htmlspecialchars($_POST['developerName']);
        $developerContact = htmlspecialchars($_POST['developerContact']);
        $email            = htmlspecialchars($_POST['email']);
        $expectedMessages = htmlspecialchars($_POST['expectedMessages']);
        $businessDetails  = htmlspecialchars($_POST['businessDetails']);

        $sql = "INSERT INTO inquiries (project_name, business_name, business_contact, website, developer_name, developer_contact, email, expectedMessages, business_details)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // "s" for string, "i" for integer (for expectedMessages)
            $stmt->bind_param("sssssssis", $projectName, $businessName, $businessContact, $website, $developerName, $developerContact, $email, $expectedMessages, $businessDetails);

            if ($stmt->execute()) {
                $successMsg = "Thank you! Your inquiry has been submitted successfully.";

                // ------------------------------
                // Email Notification Section
                // ------------------------------
                $to = "chatrock.ai@gmail.com"; // 👈 Replace with your email
                $subject = "New Inquiry Submitted";
                $body = "A new inquiry has been submitted:\n\n"
                      . "Project Name: $projectName\n"
                      . "Business Name: $businessName\n"
                      . "Business Contact: $businessContact\n"
                      . "Website: $website\n"
                      . "Developer Name: $developerName\n"
                      . "Developer Contact: $developerContact\n"
                      . "Email: $email\n"
                      . "Expected Messages: $expectedMessages\n"
                      . "Business Details: $businessDetails\n";

                $headers = "From: sauravdhakal828@gmail.com";

                // Try sending the email
                if (!mail($to, $subject, $body, $headers)) {
                    // Optional: log or show error if email fails
                    error_log("Email notification failed to send.");
                }

            } else {
                $errorMsg = "Error executing query: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $errorMsg = "Error preparing statement: " . $conn->error;
        }
    }
}

$conn->close();
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatRock AI</title>
    <!-- Use the Inter font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Include Tailwind CSS via CDN for streamlined styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Custom Tailwind configuration to define colors and fonts
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Space Grotesk', 'sans-serif'],
                    },
                    colors: {
                        primary: '#667eea',
                        secondary: '#764ba2',
                        // Define other colors as needed for gradients
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom CSS for the animated background shapes and particles */
        @keyframes float-shapes {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            33% { transform: translateY(-30px) rotate(120deg); }
            66% { transform: translateY(20px) rotate(240deg); }
        }
        .shape {
            animation: float-shapes 20s infinite ease-in-out;
            filter: blur(40px);
            opacity: 0.4;
        }

        @keyframes particle-float {
            0% { transform: translateY(100vh) translateX(0) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) translateX(50px) scale(1); opacity: 0; }
        }
        .particle {
            animation: particle-float 15s infinite linear;
            pointer-events: none;
        }

        /* Custom styles for pseudo-elements not possible with Tailwind */
        .logo::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 1px;
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .logo:hover::after {
            transform: scaleX(1);
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
            transform: translateX(-50%);
        }

        .modern-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }
        .modern-button:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-50 via-blue-50 to-sky-100 text-slate-700 overflow-x-hidden min-h-screen flex flex-col font-sans antialiased">
    <!-- Animated background shapes for a dynamic visual effect -->
    <div class="fixed top-0 left-0 w-full h-full z-[-1] overflow-hidden">
        <div class="shape absolute rounded-full bg-gradient-to-br from-[#667eea] to-[#764ba2] w-72 h-72 top-10 left-10 animate-[float-shapes_20s_infinite_ease-in-out]"></div>
        <div class="shape absolute rounded-full bg-gradient-to-br from-[#f093fb] to-[#f5576c] w-48 h-48 top-60 right-10 animate-[float-shapes_20s_infinite_ease-in-out_-5s]"></div>
        <div class="shape absolute rounded-full bg-gradient-to-br from-[#4facfe] to-[#00f2fe] w-64 h-64 bottom-10 left-20 animate-[float-shapes_20s_infinite_ease-in-out_-10s]"></div>
        <div class="shape absolute rounded-full bg-gradient-to-br from-[#43e97b] to-[#38f9d7] w-44 h-44 top-1/4 right-1/4 animate-[float-shapes_20s_infinite_ease-in-out_-15s]"></div>
    </div>
    <!-- Particles for a subtle floating effect -->
    <div class="particles fixed top-0 left-0 w-full h-full z-[-1]" id="particles"></div>
    
    <!-- Fixed header with navigation, using Tailwind for styling -->
    <header class="sticky top-0 w-full bg-white/95 backdrop-blur-2xl px-4 md:px-8 py-4 z-[1000] border-b border-indigo-100 shadow-lg transition-all duration-300">
        <nav class="flex justify-between items-center max-w-7xl mx-auto">
            <a href="#" class="logo text-4xl font-bold font-display bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent relative transition-all duration-300">ChatRock AI</a>
            <ul class="hidden md:flex items-center space-x-8 lg:space-x-12">
                <li><a href="index.html" class="text-slate-800 font-medium text-lg relative transition-all duration-300 hover:text-indigo-600 hover:before:opacity-10 hover:before:bg-indigo-50/10 hover:before:rounded-2xl hover:before:absolute hover:before:inset-0">Home</a></li>
                <li><a href="#services" class="text-slate-800 font-medium text-lg relative transition-all duration-300 hover:text-indigo-600 hover:before:opacity-10 hover:before:bg-indigo-50/10 hover:before:rounded-2xl hover:before:absolute hover:before:inset-0">Services</a></li>
                <li><a href="#features" class="text-slate-800 font-medium text-lg relative transition-all duration-300 hover:text-indigo-600 hover:before:opacity-10 hover:before:bg-indigo-50/10 hover:before:rounded-2xl hover:before:absolute hover:before:inset-0">Features</a></li>
                <li><a href="#contact" class="text-slate-800 font-medium text-lg relative transition-all duration-300 hover:text-indigo-600 hover:before:opacity-10 hover:before:bg-indigo-50/10 hover:before:rounded-2xl hover:before:absolute hover:before:inset-0">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main content section with a 'glassmorphism' form card -->
    <main class="flex-grow flex justify-center items-center px-4 py-24 md:py-32">
        <div class="bg-white/70 backdrop-blur-3xl p-6 md:p-12 rounded-3xl border border-white/30 shadow-2xl max-w-3xl w-full">
            <h2 class="section-title text-4xl md:text-5xl font-bold font-display text-center mb-12 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent relative">Business Inquiry Form</h2>
            
            <?php if (!empty($successMsg)): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg mb-8 transition-all duration-300" role="alert">
                    <p class="font-bold">Success!</p>
                    <p><?php echo htmlspecialchars($successMsg); ?></p>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMsg)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-8 transition-all duration-300" role="alert">
                    <p class="font-bold">Error!</p>
                    <p><?php echo htmlspecialchars($errorMsg); ?></p>
                </div>
            <?php endif; ?>
            
            <!-- The action attribute now points to the submit.php file -->
            <form id="inquiryForm" action="" method="POST">
                <div class="relative z-10">
                    <label for="projectName" class="block text-slate-800 font-semibold mb-2">Project Name: <span class="text-slate-500 font-normal">(Please give your project a name)</span></label>
                    <input type="text" id="projectName" name="projectName" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                </div><br>
                <div class="relative z-10">
                    <label for="businessName" class="block text-slate-800 font-semibold mb-2">Business Name:</label>
                    <input type="text" id="businessName" name="businessName" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                </div><br>
                <div class="relative z-10">
                    <label for="businessContact" class="block text-slate-800 font-semibold mb-2">Business Contact Number:</label>
                    <input type="tel" id="businessContact" name="businessContact" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                </div><br>
                <div class="relative z-10">
                    <label for="website" class="block text-slate-800 font-semibold mb-2">Business official website:</label>
                    <input type="text" id="website" name="website" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                </div><br>
                <div class="relative z-10">
                    <label for="developerName" class="block text-slate-800 font-semibold mb-2">Your Name: <span class="text-slate-500 font-normal">(The person filling out the form)</span></label>
                    <input type="text" id="developerName" name="developerName" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                </div><br>
                <div class="relative z-10">
                    <label for="developerContact" class="block text-slate-800 font-semibold mb-2">Your Contact Number:</label>
                    <input type="tel" id="developerContact" name="developerContact" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                </div><br>
                <div class="relative z-10">
                    <label for="email" class="block text-slate-800 font-semibold mb-2">E-mail: <span class="text-slate-500 font-normal">(Where we can send the project and messages)</span></label>
                    <input type="email" id="email" name="email" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                </div><br>
                <!-- New field for "Expected Messages" -->
                <div class="relative z-10">
                    <label for="expectedMessages" class="block text-slate-800 font-semibold mb-2">Expected Messages: <span class="text-slate-500 font-normal">(The number of messages you expect to receive per day)</span></label>
                    <select id="expectedMessages" name="expectedMessages" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400">
                        <option value="" disabled selected>Select an option</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="150">150</option>
                        <option value="200">200</option>
                    </select>
                </div><br>
                <div class="relative z-10">
                    <label for="businessDetails" class="block text-slate-800 font-semibold mb-2">Business Details: <span class="text-slate-500 font-normal">(The details must include all information you can share in public. These details will be added to the knowledge base.)<br> <strong>Note:</strong> AI can only respond with the details available in the knowledge base.</span></label>
                    <textarea id="businessDetails" name="businessDetails" rows="8" required class="w-full p-4 bg-white/90 border-2 border-indigo-200 rounded-xl text-slate-800 text-lg transition-all duration-300 resize-y focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"></textarea>
                </div><br>
                <span class="text-slate-500 font-normal"><strong>Note:</strong>You will need API keys for your chatbot to respond to queries. Once the project is complete, we will guide you to obtain your API keys and connect them.<br>(Please avoid sharing your API keys.)</span>
                <div class="text-center mt-12">
                    <button type="submit" class="modern-button inline-block py-4 px-10 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full font-semibold text-lg relative overflow-hidden transition-all duration-400 ease-in-out shadow-xl hover:scale-105 hover:shadow-2xl">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer section -->
    <footer class="bg-white/95 backdrop-blur-3xl py-12 px-4 text-center border-t border-indigo-100 text-slate-500">
        <p>&copy; 2025 ChatRock AI. Transforming businesses with intelligent automation and AI solutions.</p>
    </footer>

    <script>
        // JavaScript for creating floating particles
        function createParticle() {
            const particle = document.createElement('div');
            particle.className = 'particle absolute w-1 h-1 rounded-full opacity-60 animate-[particle-float_15s_infinite_linear]';
            // Set particle color based on a random number
            const rand = Math.random();
            if (rand < 0.25) {
                particle.className += ' bg-gradient-to-r from-[#667eea] to-[#764ba2]';
                particle.style.animationDelay = (Math.random() * 2) + 's';
            } else if (rand < 0.5) {
                particle.className += ' bg-gradient-to-r from-[#f093fb] to-[#f5576c]';
                particle.style.animationDelay = (Math.random() * 2 - 3) + 's';
            } else if (rand < 0.75) {
                particle.className += ' bg-gradient-to-r from-[#4facfe] to-[#00f2fe]';
                particle.style.animationDelay = (Math.random() * 2 - 6) + 's';
            } else {
                particle.className += ' bg-gradient-to-r from-[#43e97b] to-[#38f9d7]';
                particle.style.animationDelay = (Math.random() * 2 - 9) + 's';
            }

            particle.style.left = Math.random() * 100 + 'vw';
            document.getElementById('particles').appendChild(particle);

            setTimeout(() => {
                particle.remove();
            }, 30000);
        }

        setInterval(createParticle, 300);

        // Dynamic header effects on scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            const scrolled = window.scrollY;
            
            if (scrolled > 100) {
                header.classList.remove('shadow-lg');
                header.classList.add('bg-white/98', 'shadow-2xl');
            } else {
                header.classList.remove('bg-white/98', 'shadow-2xl');
                header.classList.add('shadow-lg');
            }
        });
    </script>
</body>
</html>
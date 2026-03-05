<?php
session_start();
include '../includes/db.php';

// Auth Check
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'vendor'){
    header("Location: /food-wastage-system/login.php");
    exit();
}

$vendor_id = $_SESSION['user_id'];
$message = "";

// --- NEW: Fetch User Name from Database ---
$user_query = $conn->query("SELECT name FROM users WHERE id = '$vendor_id'");
$user_data = $user_query->fetch_assoc();
$user_name = $user_data['name']; 

// Handle New Food Post
if(isset($_POST['post_food'])){
    $food_name = $conn->real_escape_string($_POST['food_name']);
    $quantity = $conn->real_escape_string($_POST['quantity']);
    $category = $conn->real_escape_string($_POST['category']);
    $pickup_location = $conn->real_escape_string($_POST['pickup_location']);
    $upload_time = $_POST['upload_time'];

    $sql = "INSERT INTO food_posts (vendor_id, food_name, quantity, category, pickup_location, upload_time, status)
            VALUES ('$vendor_id','$food_name','$quantity','$category','$pickup_location','$upload_time','Fresh')";
    
    if($conn->query($sql)){
        $message = "Success: Food item listed on the bridge!";
    }
}

// Stats Calculation
$total_posts = $conn->query("SELECT COUNT(*) as c FROM food_posts WHERE vendor_id='$vendor_id'")->fetch_assoc()['c'];
$active_donations = $conn->query("SELECT COUNT(*) as c FROM food_posts WHERE vendor_id='$vendor_id' AND status='Fresh'")->fetch_assoc()['c'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vendor Central | Food Bridge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8fafc; }
        .sidebar-link.active { background: rgba(255,255,255,0.15); border-left: 4px solid #f39c12; }
        .hidden-section { display: none; }
        .glass-effect { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-72 bg-[#1a3a17] text-white flex flex-col fixed h-full shadow-2xl z-50">
        <div class="p-8 text-center border-b border-white/10">
            <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg rotate-3">
                <i class="fas fa-store text-2xl"></i>
            </div>
            <h1 class="text-xl font-black tracking-tighter uppercase italic">Food Bridge</h1>
            <p class="text-[10px] text-green-400 font-bold tracking-widest uppercase mt-1">Vendor Panel</p>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-2">
            <button onclick="showSection('overview')" id="btn-overview" class="sidebar-link active w-full flex items-center space-x-3 p-4 rounded-xl transition hover:bg-white/5">
                <i class="fas fa-chart-pie"></i> <span class="font-semibold">Overview</span>
            </button>
            <button onclick="showSection('post')" id="btn-post" class="sidebar-link w-full flex items-center space-x-3 p-4 rounded-xl transition hover:bg-white/5">
                <i class="fas fa-plus-circle"></i> <span class="font-semibold">Post Food</span>
            </button>
            <button onclick="showSection('history')" id="btn-history" class="sidebar-link w-full flex items-center space-x-3 p-4 rounded-xl transition hover:bg-white/5">
                <i class="fas fa-list-ul"></i> <span class="font-semibold">My Donations</span>
            </button>
        </nav>

        <div class="p-6 border-t border-white/10">
            <a href="../logout.php" class="flex items-center space-x-3 p-4 text-red-400 hover:bg-red-500/10 rounded-xl transition font-bold">
                <i class="fas fa-power-off"></i> <span>Sign Out</span>
            </a>
        </div>
    </aside>

    <main class="ml-72 flex-1 p-10">
        
        <header class="flex justify-between items-center mb-10">
            <div>
                <h2 id="header-title" class="text-3xl font-black text-gray-800 italic uppercase">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h2>
                <p class="text-gray-500 font-medium">Ready to save lives today, <?php echo explode(' ', $user_name)[0]; ?>?</p>
            </div>
            <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-full shadow-sm border border-gray-100">
                <div class="w-12 h-12 rounded-full bg-[#2d5a27] flex items-center justify-center text-white font-bold border-2 border-green-100">
                    <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase leading-none tracking-tighter">Verified Vendor</p>
                    <p class="text-sm font-bold text-green-600 flex items-center gap-1">
                        <?php echo htmlspecialchars($user_name); ?>
                    </p>
                </div>
            </div>
        </header>

        <?php if($message): ?>
            <div id="alert" class="bg-green-600 text-white p-4 rounded-2xl mb-8 flex justify-between items-center shadow-lg animate-bounce">
                <span class="font-bold"><i class="fas fa-check-double mr-2"></i> <?php echo $message; ?></span>
                <button onclick="document.getElementById('alert').remove()"><i class="fas fa-times"></i></button>
            </div>
        <?php endif; ?>

        <section id="sec-overview" class="">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="bg-white p-8 rounded-3xl shadow-sm border-b-4 border-green-600 hover:shadow-xl transition">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Total Donated</p>
                    <h3 class="text-5xl font-black mt-2 text-gray-800"><?php echo $total_posts; ?></h3>
                </div>
                <div class="bg-white p-8 rounded-3xl shadow-sm border-b-4 border-orange-500 hover:shadow-xl transition">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Active Listings</p>
                    <h3 class="text-5xl font-black mt-2 text-gray-800"><?php echo $active_donations; ?></h3>
                </div>
                <div class="bg-[#2d5a27] p-8 rounded-3xl shadow-xl text-white relative overflow-hidden">
                    <i class="fas fa-medal absolute -right-4 -bottom-4 text-8xl opacity-10"></i>
                    <p class="text-green-300 text-xs font-bold uppercase tracking-widest">Impact Rank</p>
                    <h3 class="text-3xl font-black mt-2 italic">Platinum Donor</h3>
                </div>
            </div>

            <div class="bg-orange-50 p-8 rounded-3xl border-2 border-orange-100 flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center text-2xl">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">Have surplus food right now?</h4>
                        <p class="text-gray-600">Don't wait! Listing takes less than 60 seconds.</p>
                    </div>
                </div>
                <button onclick="showSection('post')" class="bg-orange-500 text-white px-8 py-3 rounded-xl font-bold hover:bg-orange-600 transition shadow-lg shadow-orange-200">Post Now</button>
            </div>
        </section>

        <section id="sec-post" class="hidden-section">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50 p-6 border-b border-gray-100">
                    <h3 class="text-xl font-black text-gray-800 uppercase italic">Create Food Listing</h3>
                </div>
                <form method="POST" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-500 uppercase">Food Name</label>
                        <input type="text" name="food_name" placeholder="e.g. 50 Packs of Veg Thali" class="w-full p-4 bg-gray-50 rounded-2xl border-2 border-transparent focus:border-green-500 outline-none transition" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-500 uppercase">Category</label>
                        <select name="category" class="w-full p-4 bg-gray-50 rounded-2xl border-2 border-transparent focus:border-green-500 outline-none transition">
                            <option value="Veg">Pure Veg</option>
                            <option value="Non-Veg">Non-Veg</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-500 uppercase">Quantity</label>
                        <input type="text" name="quantity" placeholder="e.g. 10 KG / 20 Boxes" class="w-full p-4 bg-gray-50 rounded-2xl border-2 border-transparent focus:border-green-500 outline-none transition" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-500 uppercase">Pickup Location</label>
                        <input type="text" name="pickup_location" placeholder="Full Address / Landmark" class="w-full p-4 bg-gray-50 rounded-2xl border-2 border-transparent focus:border-green-500 outline-none transition" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-gray-500 uppercase">Expiry Estimate (Date & Time)</label>
                        <input type="datetime-local" name="upload_time" class="w-full p-4 bg-gray-50 rounded-2xl border-2 border-transparent focus:border-green-500 outline-none transition" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" name="post_food" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-orange-200 transition">
                            PUBLISH TO BRIDGE
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section id="sec-history" class="hidden-section">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-widest">
                        <tr>
                            <th class="p-6">Food Item</th>
                            <th class="p-6">Quantity</th>
                            <th class="p-6">Location</th>
                            <th class="p-6">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        $res = $conn->query("SELECT * FROM food_posts WHERE vendor_id='$vendor_id' ORDER BY id DESC");
                        while($row = $res->fetch_assoc()):
                            $color = ($row['status'] == 'Fresh') ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600';
                        ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="p-6 font-bold text-gray-800"><?php echo $row['food_name']; ?></td>
                            <td class="p-6 font-medium text-gray-600"><?php echo $row['quantity']; ?></td>
                            <td class="p-6 text-gray-500 text-sm"><?php echo $row['pickup_location']; ?></td>
                            <td class="p-6">
                                <span class="<?php echo $color; ?> px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <script>
        function showSection(id) {
            document.querySelectorAll('section').forEach(sec => sec.classList.add('hidden-section'));
            document.querySelectorAll('.sidebar-link').forEach(btn => btn.classList.remove('active'));
            
            document.getElementById('sec-' + id).classList.remove('hidden-section');
            document.getElementById('btn-' + id).classList.add('active');

            // Dynamic Header Title
            let userName = "<?php echo $user_name; ?>";
            let titles = {
                'overview': 'Welcome, ' + userName + '!',
                'post': 'Create Listing',
                'history': 'Donation History'
            };
            document.getElementById('header-title').innerText = titles[id];
        }
    </script>
</body>
</html>
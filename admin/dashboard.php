<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include '../includes/db.php'; 

// 1. SECURITY & AUTH
if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
    header("Location: /food-wastage-system/login.php");
    exit();
}

// 2. ACTION LOGIC (Same as before)
if(isset($_GET['action']) && isset($_GET['req_id'])){
    $req_id = mysqli_real_escape_string($conn, $_GET['req_id']);
    $new_status = ($_GET['action'] == 'approve') ? 'Approved' : 'Rejected';
    $conn->query("UPDATE food_requests SET status = '$new_status' WHERE id = '$req_id'");
    header("Location: dashboard.php?msg=StatusUpdated");
    exit();
}

if(isset($_GET['delete_user'])){
    $user_id = mysqli_real_escape_string($conn, $_GET['delete_user']);
    $conn->query("DELETE FROM users WHERE id = '$user_id'");
    header("Location: dashboard.php?msg=Deleted");
    exit();
}

// 3. DATA FETCHING (Same as before)
$v_count = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE role='vendor'")->fetch_assoc()['cnt'] ?? 0;
$n_count = $conn->query("SELECT COUNT(*) as cnt FROM users WHERE role='ngo'")->fetch_assoc()['cnt'] ?? 0;
$f_total = $conn->query("SELECT COUNT(*) as cnt FROM food_posts")->fetch_assoc()['cnt'] ?? 0;
$req_pending = $conn->query("SELECT COUNT(*) as cnt FROM food_requests WHERE status='Pending'")->fetch_assoc()['cnt'] ?? 0;
$f_exp   = $conn->query("SELECT COUNT(*) as cnt FROM food_posts WHERE status='Expired'")->fetch_assoc()['cnt'] ?? 0;
$f_fresh = $conn->query("SELECT COUNT(*) as cnt FROM food_posts WHERE status='Fresh'")->fetch_assoc()['cnt'] ?? 0;
$req_app = $conn->query("SELECT COUNT(*) as cnt FROM food_requests WHERE status='Approved'")->fetch_assoc()['cnt'] ?? 0;
$req_rej = $conn->query("SELECT COUNT(*) as cnt FROM food_requests WHERE status='Rejected'")->fetch_assoc()['cnt'] ?? 0;

$ratings_data = [1=>0, 2=>0, 3=>0, 4=>0, 5=>0];
$stars = $conn->query("SELECT rating, COUNT(*) as cnt FROM feedback GROUP BY rating");
if($stars){ while($r = $stars->fetch_assoc()){ $ratings_data[$r['rating']] = $r['cnt']; } }

include '../includes/navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Pro | Food Bridge</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar { background: #064e3b; width: 260px; height: 100vh; position: fixed; top: 60px; left: 0; z-index: 40; }
        .main-content { margin-left: 260px; padding: 30px; margin-top: 60px; }
        .nav-link { display: flex; align-items: center; padding: 14px 24px; color: #94a3b8; transition: 0.3s; cursor: pointer; border: none; background: none; text-align: left; }
        .active-link { background: #10b981 !important; color: white !important; font-weight: 700; border-radius: 12px; margin: 0 10px; }
        .glass-card { background: white; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        .section { display: none; }
        .section.active { display: block; animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="flex">

    <aside class="sidebar shadow-2xl">
        <div class="p-6 border-b border-emerald-800 text-center">
            <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center text-white text-2xl font-black mx-auto mb-2 shadow-lg">A</div>
            <h4 class="text-white font-bold tracking-wide">ADMIN PANEL</h4>
            <p class="text-emerald-400 text-[10px] font-black uppercase tracking-widest">Master Dashboard</p>
        </div>

        <div class="mt-6 space-y-2">
            <button onclick="tab('home')" id="l-home" class="nav-link w-full active-link"><i class="fas fa-chart-pie mr-3"></i> Analysis</button>
            <button onclick="tab('requests')" id="l-requests" class="nav-link w-full"><i class="fas fa-tasks mr-3"></i> Food Requests</button>
            <button onclick="tab('vendors')" id="l-vendors" class="nav-link w-full"><i class="fas fa-store mr-3"></i> Vendors</button>
            <button onclick="tab('ngos')" id="l-ngos" class="nav-link w-full"><i class="fas fa-hand-holding-heart mr-3"></i> NGO Partners</button>
            <button onclick="tab('feedback')" id="l-feedback" class="nav-link w-full"><i class="fas fa-star mr-3"></i> Reviews</button>
        </div>
    </aside>

    <main class="main-content flex-1">
        
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
            <div>
                <h1 class="text-2xl font-black text-slate-800 italic">Welcome back, Super Admin!</h1>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">You are currently managing Food Bridge Data</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="bg-emerald-100 text-emerald-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider">Online</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="glass-card p-5 border-l-4 border-emerald-500">
                <p class="text-[10px] font-black text-gray-400 uppercase">Vendors</p>
                <h3 class="text-2xl font-black text-slate-800">🏪 <?php echo $v_count; ?></h3>
            </div>
            <div class="glass-card p-5 border-l-4 border-emerald-600">
                <p class="text-[10px] font-black text-gray-400 uppercase">NGOs</p>
                <h3 class="text-2xl font-black text-slate-800">🤝 <?php echo $n_count; ?></h3>
            </div>
            <div class="glass-card p-5 border-l-4 border-orange-500">
                <p class="text-[10px] font-black text-gray-400 uppercase">Total Food</p>
                <h3 class="text-2xl font-black text-slate-800">🍲 <?php echo $f_total; ?></h3>
            </div>
            <div class="glass-card p-5 border-l-4 border-rose-500">
                <p class="text-[10px] font-black text-gray-400 uppercase">Pending Req</p>
                <h3 class="text-2xl font-black text-slate-800">⏳ <?php echo $req_pending; ?></h3>
            </div>
        </div>

        <div id="home" class="section active">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="glass-card p-4"><h4 class="text-[10px] font-bold text-gray-400 mb-4 uppercase text-center">User Mix</h4><canvas id="c1"></canvas></div>
                <div class="glass-card p-4"><h4 class="text-[10px] font-bold text-gray-400 mb-4 uppercase text-center">Food Status</h4><canvas id="c2"></canvas></div>
                <div class="glass-card p-4"><h4 class="text-[10px] font-bold text-gray-400 mb-4 uppercase text-center">Success Rate</h4><canvas id="c3"></canvas></div>
                <div class="glass-card p-4"><h4 class="text-[10px] font-bold text-gray-400 mb-4 uppercase text-center">Ratings</h4><canvas id="c4"></canvas></div>
            </div>
        </div>

        <div id="requests" class="section">
            <div class="glass-card overflow-hidden">
                <div class="p-4 bg-emerald-50 border-b font-bold text-emerald-800">📋 Manage NGO Requests</div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400">
                        <tr><th class="p-4">NGO Name</th><th class="p-4">Food Name</th><th class="p-4">Status</th><th class="p-4 text-center">Action</th></tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php 
                        $req_res = $conn->query("SELECT fr.id, fr.status, u.name as ngo_name, fp.food_name 
                                                FROM food_requests fr 
                                                LEFT JOIN users u ON fr.ngo_id = u.id 
                                                LEFT JOIN food_posts fp ON fr.food_id = fp.id 
                                                ORDER BY fr.id DESC");
                        while($r = $req_res->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-bold text-slate-800"><?php echo $r['ngo_name'] ?? 'N/A'; ?></td>
                            <td class="p-4 text-slate-600"><?php echo $r['food_name'] ?? 'Deleted'; ?></td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase 
                                    <?php echo ($r['status'] == 'Approved') ? 'bg-emerald-100 text-emerald-600' : (($r['status'] == 'Rejected') ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600'); ?>">
                                    <?php echo $r['status']; ?>
                                </span>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <?php if($r['status'] == 'Pending'): ?>
                                    <a href="?action=approve&req_id=<?php echo $r['id']; ?>" class="bg-emerald-500 text-white px-3 py-1 rounded text-[10px] font-bold hover:bg-emerald-600">APPROVE</a>
                                    <a href="?action=reject&req_id=<?php echo $r['id']; ?>" class="bg-red-500 text-white px-3 py-1 rounded text-[10px] font-bold hover:bg-red-600">REJECT</a>
                                <?php else: ?>
                                    <span class="text-gray-400 text-[10px] font-bold italic tracking-widest uppercase">Completed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="vendors" class="section">
             <div class="glass-card overflow-hidden">
                <div class="p-4 bg-slate-50 border-b font-bold text-slate-700">🏪 Active Vendors</div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400">
                        <tr><th class="p-4">Name</th><th class="p-4">Contact Info</th><th class="p-4 text-center">Action</th></tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php $v_res = $conn->query("SELECT * FROM users WHERE role='vendor'");
                        while($v = $v_res->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-bold"><?php echo $v['name']; ?></td>
                            <td class="p-4">
                                <div class="text-emerald-600 font-mono text-xs"><?php echo $v['mobile']; ?></div>
                                <div class="text-slate-400 text-[10px]"><?php echo $v['email']; ?></div>
                            </td>
                            <td class="p-4 text-center"><a href="?delete_user=<?php echo $v['id']; ?>" class="text-red-400 hover:text-red-600" onclick="return confirm('Delete this user?')"><i class="fas fa-trash"></i></a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
             </div>
        </div>

        <div id="ngos" class="section">
             <div class="glass-card overflow-hidden">
                <div class="p-4 bg-slate-50 border-b font-bold text-slate-700">🤝 Registered NGO Partners</div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400">
                        <tr><th class="p-4">Name</th><th class="p-4">Contact Info</th><th class="p-4 text-center">Action</th></tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php $n_res = $conn->query("SELECT * FROM users WHERE role='ngo'");
                        while($n = $n_res->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="p-4 font-bold"><?php echo $n['name']; ?></td>
                            <td class="p-4">
                                <div class="text-emerald-600 font-mono text-xs"><?php echo $n['mobile']; ?></div>
                                <div class="text-slate-400 text-[10px]"><?php echo $n['email']; ?></div>
                            </td>
                            <td class="p-4 text-center"><a href="?delete_user=<?php echo $n['id']; ?>" class="text-red-400 hover:text-red-600" onclick="return confirm('Delete this NGO?')"><i class="fas fa-trash"></i></a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
             </div>
        </div>

        <div id="feedback" class="section">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php 
                $f_res = $conn->query("SELECT f.*, u.name FROM feedback f JOIN users u ON f.user_id = u.id ORDER BY f.id DESC");
                if($f_res && $f_res->num_rows > 0):
                    while($f = $f_res->fetch_assoc()): ?>
                    <div class="glass-card p-5 border-t-4 border-emerald-400">
                        <div class="justify-between items-center mb-3">
                            <span class="font-bold text-slate-800 text-sm"><?php echo $f['name']; ?></span>
                            <span class="text-yellow-500 font-black text-xs float-right"><?php echo $f['rating']; ?> ★</span>
                        </div>
                        <p class="text-slate-500 italic text-xs leading-relaxed">"<?php echo $f['comment']; ?>"</p>
                    </div>
                <?php endwhile; else: ?>
                    <p class="text-gray-400 italic">No feedback received yet.</p>
                <?php endif; ?>
            </div>
        </div>

    </main>

    <script>
        function tab(name) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active-link'));
            document.getElementById(name).classList.add('active');
            document.getElementById('l-'+name).classList.add('active-link');
        }

        const colors = ['#10b981', '#059669', '#f59e0b', '#ef4444', '#6366f1'];
        window.onload = function() {
            new Chart(document.getElementById('c1'), { type: 'doughnut', data: { labels: ['Vendors', 'NGOs'], datasets: [{ data: [<?php echo $v_count; ?>, <?php echo $n_count; ?>], backgroundColor: [colors[0], colors[1]], borderWidth: 0 }] }, options: { plugins: { legend: { display: false } } } });
            new Chart(document.getElementById('c2'), { type: 'bar', data: { labels: ['Fresh', 'Exp'], datasets: [{ data: [<?php echo $f_fresh; ?>, <?php echo $f_exp; ?>], backgroundColor: [colors[0], colors[3]], borderRadius: 5 }] }, options: { scales: { y: { display: false }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } } });
            new Chart(document.getElementById('c3'), { type: 'pie', data: { labels: ['App', 'Rej'], datasets: [{ data: [<?php echo $req_app; ?>, <?php echo $req_rej; ?>], backgroundColor: [colors[0], colors[3]], borderWidth: 0 }] }, options: { plugins: { legend: { display: false } } } });
            new Chart(document.getElementById('c4'), { type: 'line', data: { labels: ['1','2','3','4','5'], datasets: [{ data: [<?php echo implode(",",$ratings_data); ?>], borderColor: colors[0], backgroundColor: colors[0]+'22', fill: true, tension: 0.4 }] }, options: { scales: { y: { display: false }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } } });
        }
    </script>
</body>
</html>
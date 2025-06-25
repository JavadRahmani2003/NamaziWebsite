<?php
session_start();
require_once 'includes/database.php';

$db = new Database();
$conn = $db->getConnection();

// پردازش عملیات
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'approve':
                $id = (int)$_POST['id'];
                $notes = $conn->real_escape_string($_POST['notes'] ?? '');
                
                $query = "UPDATE registrations SET status = 'approved', approved_date = NOW(), notes = '$notes' WHERE id = $id";
                if ($conn->query($query)) {
                    $success_message = "ثبت نام با موفقیت تایید شد.";
                }
                break;
                
            case 'reject':
                $id = (int)$_POST['id'];
                $notes = $conn->real_escape_string($_POST['notes'] ?? '');
                
                $query = "UPDATE registrations SET status = 'rejected', notes = '$notes' WHERE id = $id";
                if ($conn->query($query)) {
                    $success_message = "ثبت نام رد شد.";
                }
                break;
        }
    }
}

// فیلترها
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// ساخت کوئری
$where_conditions = [];
if ($status_filter) {
    $where_conditions[] = "status = '" . $conn->real_escape_string($status_filter) . "'";
}
if ($search) {
    $search_term = $conn->real_escape_string($search);
    $where_conditions[] = "(first_name LIKE '%$search_term%' OR last_name LIKE '%$search_term%' OR mobile LIKE '%$search_term%' OR national_code LIKE '%$search_term%')";
}

$where_clause = '';
if (!empty($where_conditions)) {
    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
}

// صفحه‌بندی
$page = (int)($_GET['page'] ?? 1);
$per_page = 20;
$offset = ($page - 1) * $per_page;

// شمارش کل رکوردها
$count_query = "SELECT COUNT(*) as total FROM registrations $where_clause";
$count_result = $conn->query($count_query);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $per_page);

// دریافت داده‌ها
$query = "
    SELECT r.*, 
           COALESCE(SUM(p.amount), 0) as total_paid
    FROM registrations r
    LEFT JOIN payments p ON r.id = p.registration_id AND p.status = 'completed'
    $where_clause
    GROUP BY r.id
    ORDER BY r.registration_date DESC
    LIMIT $per_page OFFSET $offset
";
$registrations = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مدیریت ثبت نام‌ها - باشگاه ورزشی آرین رزم</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .filters-row {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }
        
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        .actions-cell {
            display: flex;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .btn-success { background-color: #28a745; }
        .btn-danger { background-color: #dc3545; }
        .btn-info { background-color: #17a2b8; }
        
        .btn-success:hover { background-color: #218838; }
        .btn-danger:hover { background-color: #c82333; }
        .btn-info:hover { background-color: #138496; }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        
        .pagination a {
            padding: 8px 12px;
            margin: 0 2px;
            background: white;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 3px;
        }
        
        .pagination a:hover,
        .pagination a.active {
            background-color: #e63946;
            color: white;
            border-color: #e63946;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
        }
        
        .close {
            color: #aaa;
            float: left;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover {
            color: black;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar (same as dashboard.php) -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-dumbbell"></i> آرین رزم</h2>
                <p>پنل مدیریت</p>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> داشبورد</a></li>
                    <li><a href="registrations.php" class="active"><i class="fas fa-user-plus"></i> ثبت نام‌ها</a></li>
                    <li><a href="classes.php"><i class="fas fa-dumbbell"></i> کلاس‌ها</a></li>
                    <li><a href="payments.php"><i class="fas fa-credit-card"></i> پرداخت‌ها</a></li>
                    <li><a href="instructors.php"><i class="fas fa-chalkboard-teacher"></i> مربیان</a></li>
                    <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> حضور و غیاب</a></li>
                    <li><a href="reports.php"><i class="fas fa-chart-bar"></i> گزارشات</a></li>
                    <li><a href="settings.php"><i class="fas fa-cog"></i> تنظیمات</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1>مدیریت ثبت نام‌ها</h1>
                <div class="header-actions">
                    <button class="btn btn-primary" onclick="location.href='add-registration.php'">
                        <i class="fas fa-plus"></i> ثبت نام جدید
                    </button>
                </div>
            </header>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <!-- Filters -->
            <div class="filters">
                <form method="GET" class="filters-row">
                    <div class="filter-group">
                        <label>جستجو:</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="نام، موبایل یا کد ملی">
                    </div>
                    
                    <div class="filter-group">
                        <label>وضعیت:</label>
                        <select name="status">
                            <option value="">همه</option>
                            <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>در انتظار</option>
                            <option value="approved" <?php echo $status_filter === 'approved' ? 'selected' : ''; ?>>تایید شده</option>
                            <option value="rejected" <?php echo $status_filter === 'rejected' ? 'selected' : ''; ?>>رد شده</option>
                            <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>لغو شده</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> جستجو
                        </button>
                    </div>
                </form>
            </div>

            <!-- Registrations Table -->
            <div class="dashboard-card">
                <div class="card-content">
                    <?php if ($registrations->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>شناسه</th>
                                        <th>نام و نام خانوادگی</th>
                                        <th>موبایل</th>
                                        <th>کلاس‌ها</th>
                                        <th>وضعیت</th>
                                        <th>مبلغ پرداختی</th>
                                        <th>تاریخ ثبت نام</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $registrations->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                                        <td><?php echo htmlspecialchars($row['classes']); ?></td>
                                        <td>
                                            <span class="status status-<?php echo $row['status']; ?>">
                                                <?php 
                                                $status_labels = [
                                                    'pending' => 'در انتظار',
                                                    'approved' => 'تایید شده',
                                                    'rejected' => 'رد شده',
                                                    'cancelled' => 'لغو شده'
                                                ];
                                                echo $status_labels[$row['status']] ?? $row['status'];
                                                ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($row['total_paid']) . ' تومان'; ?></td>
                                        <td><?php echo date('Y/m/d', strtotime($row['registration_date'])); ?></td>
                                        <td class="actions-cell">
                                            <button class="btn btn-info btn-sm" onclick="viewDetails(<?php echo $row['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if ($row['status'] === 'pending'): ?>
                                                <button class="btn btn-success btn-sm" onclick="approveRegistration(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="rejectRegistration(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="pagination">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>&status=<?php echo $status_filter; ?>&search=<?php echo urlencode($search); ?>" 
                                       class="<?php echo $i === $page ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="no-data">هیچ ثبت نامی یافت نشد.</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal for Actions -->
    <div id="actionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3 id="modalTitle">عملیات</h3>
            <form id="actionForm" method="POST">
                <input type="hidden" name="action" id="actionType">
                <input type="hidden" name="id" id="registrationId">
                
                <div class="filter-group">
                    <label>یادداشت:</label>
                    <textarea name="notes" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;"></textarea>
                </div>
                
                <div style="margin-top: 15px; text-align: center;">
                    <button type="submit" class="btn btn-primary">تایید</button>
                    <button type="button" class="btn" onclick="closeModal()" style="background-color: #6c757d; color: white; margin-right: 10px;">لغو</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function approveRegistration(id) {
            document.getElementById('modalTitle').textContent = 'تایید ثبت نام';
            document.getElementById('actionType').value = 'approve';
            document.getElementById('registrationId').value = id;
            document.getElementById('actionModal').style.display = 'block';
        }
        
        function rejectRegistration(id) {
            document.getElementById('modalTitle').textContent = 'رد ثبت نام';
            document.getElementById('actionType').value = 'reject';
            document.getElementById('registrationId').value = id;
            document.getElementById('actionModal').style.display = 'block';
        }
        
        function viewDetails(id) {
            window.location.href = 'registration-details.php?id=' + id;
        }
        
        function closeModal() {
            document.getElementById('actionModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('actionModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
        
        // Close modal with X button
        document.querySelector('.close').onclick = closeModal;
    </script>
</body>
</html>

<?php $conn->close(); ?>

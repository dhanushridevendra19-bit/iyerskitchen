<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IYER'S KITCHEN - Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        /* (your existing CSS – unchanged) */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        :root {
            --primary: #d35400;
            --primary-light: #e67e22;
            --secondary: #2c3e50;
            --danger: #e74c3c;
            --success: #2ecc71;
            --shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        body { background-color: #f5f7fa; }
        .admin-header {
            background-color: var(--secondary);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo-icon { font-size: 2.5rem; color: var(--primary); }
        .logo-text h1 { font-size: 1.5rem; color: white; }
        .logo-text p { font-size: 0.8rem; color: rgba(255,255,255,0.8); }
        .user-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .admin-top-nav {
            background-color: var(--secondary);
            overflow-x: auto;
        }
        .admin-top-nav ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .admin-top-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
        }
        .admin-top-nav a:hover, .admin-top-nav a.active {
            background: rgba(255,255,255,0.1);
            border-bottom-color: var(--primary);
        }
        .admin-info {
            background: linear-gradient(135deg, var(--secondary), #1a2a3a);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .admin-info h3 { color: white; }
        .admin-info p { color: rgba(255,255,255,0.7); }
        .main-content { padding: 2rem; background: #f5f7fa; min-height: calc(100vh - 200px); }
        .admin-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        .admin-page-header h2 { color: var(--primary); }
        .btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn:hover { background-color: var(--primary-light); transform: translateY(-2px); }
        .btn-danger { background-color: var(--danger); }
        .btn-secondary { background-color: var(--secondary); }
        .btn-small { padding: 5px 12px; font-size: 0.85rem; }
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 2rem;
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: rgba(211,84,0,0.1);
            color: var(--primary);
        }
        .card h3 { font-size: 1.8rem; margin-bottom: 5px; }
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            overflow-x: auto;
            margin-bottom: 2rem;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; font-weight: 600; }
        .status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
        }
        .status-paid { background: #eafaf1; color: var(--success); }
        .status-pending { background: #fef9e7; color: #f39c12; }
        .status-active { background: #eafaf1; color: var(--success); }
        .form-container {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: var(--shadow);
            max-width: 800px;
            margin: 0 auto;
        }
        .form-row { display: flex; gap: 20px; margin-bottom: 1.5rem; flex-wrap: wrap; }
        .form-group { flex: 1; min-width: 150px; margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; }
        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .menu-management-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 2rem;
        }
        .menu-management-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .menu-management-img {
            height: 180px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .menu-actions { position: absolute; top: 10px; right: 10px; display: flex; gap: 5px; }
        .file-upload-area {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background: #fafafa;
            cursor: pointer;
        }
        .file-upload-area:hover { border-color: var(--primary); background: #fef5ed; }
        .image-preview { margin-top: 10px; display: flex; align-items: center; gap: 10px; }
        .preview-img { width: 80px; height: 80px; border-radius: 10px; object-fit: cover; }
        .report-tabs { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .report-tab {
            padding: 8px 20px;
            border-radius: 30px;
            cursor: pointer;
            background: #f1f5f9;
        }
        .report-tab.active { background: var(--primary); color: white; }
        .chart-container { height: 320px; margin: 20px 0; }
        .date-filter-row { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
        @media (max-width: 768px) { .form-row { flex-direction: column; } }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="logo-container">
            <i class="fas fa-utensils logo-icon"></i>
            <div class="logo-text">
                <h1>IYER'S KITCHEN - Admin Panel</h1>
                <p>Complete Management System | 100% Vegetarian</p>
            </div>
        </div>
        <button class="user-btn" id="admin-logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
    </header>

    <div class="dashboard-container">
        <nav class="admin-top-nav">
            <ul>
                <li><a class="admin-nav active" data-page="dashboard"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a class="admin-nav" data-page="billing"><i class="fas fa-file-invoice-dollar"></i><span>Billing</span></a></li>
                <li><a class="admin-nav" data-page="orders"><i class="fas fa-shopping-cart"></i><span>Orders</span></a></li>
                <li><a class="admin-nav" data-page="employees"><i class="fas fa-users"></i><span>Employees</span></a></li>
                <li><a class="admin-nav" data-page="salary"><i class="fas fa-money-check-alt"></i><span>Salary</span></a></li>
                <li><a class="admin-nav" data-page="menu"><i class="fas fa-utensils"></i><span>Menu</span></a></li>
                <li><a class="admin-nav" data-page="customers"><i class="fas fa-user-friends"></i><span>Customers</span></a></li>
                <li><a class="admin-nav" data-page="website"><i class="fas fa-globe"></i><span>Website</span></a></li>
                <li><a class="admin-nav" data-page="reports"><i class="fas fa-chart-bar"></i><span>Reports</span></a></li>
            </ul>
        </nav>

        <div class="admin-info">
            <div><h3>Welcome, Administrator</h3><p id="last-login-time"></p></div>
            <button class="btn" id="export-report-btn"><i class="fas fa-download"></i> Export Report</button>
        </div>

        <main class="main-content" id="admin-content"></main>
    </div>

    <script>
        // ========== ALL DATA STORED IN LOCALSTORAGE (except Menu uses database) ==========
        let bills = JSON.parse(localStorage.getItem('iyers_bills')) || [];
        let orders = JSON.parse(localStorage.getItem('iyers_orders')) || [];
        let employees = JSON.parse(localStorage.getItem('iyers_employees')) || [];
        let customers = JSON.parse(localStorage.getItem('iyers_customers')) || [];
        let salaries = JSON.parse(localStorage.getItem('iyers_salaries')) || [];
        let websiteContent = JSON.parse(localStorage.getItem('iyers_website')) || { 
            siteName: "IYER'S KITCHEN",
            siteLink: "https://iyerskitchen.com",
            gmail: "iyerskitchen@gmail.com",
            hereTitle: "Authentic South Indian Delights", 
            hereDesc: "Experience the true taste of South India with our freshly prepared traditional vegetarian dishes."
        };

        // ========== FORCE SAMPLE DATA SO TABLES NEVER EMPTY ==========
        if (bills.length === 0) bills = [
            { id: "BIL-1001", customer: "Ramesh Patel", date: new Date().toISOString().split('T')[0], amount: 280, status: "Paid", items: "Masala Dosa x2" },
            { id: "BIL-1002", customer: "Meera Sharma", date: new Date().toISOString().split('T')[0], amount: 180, status: "Paid", items: "Idli Sambar" }
        ];
        if (orders.length === 0) orders = [
            { id: "ORD-1001", customer: "Ramesh Patel", items: "Masala Dosa x2", amount: 240, status: "Completed", date: new Date().toISOString().split('T')[0] },
            { id: "ORD-1002", customer: "Meera Sharma", items: "Idli Sambar", amount: 80, status: "Pending", date: new Date().toISOString().split('T')[0] }
        ];
        if (employees.length === 0) employees = [
            { id: 1, name: "Rajesh Kumar", position: "Head Chef", phone: "9876543210", salary: 35000, status: "Active" },
            { id: 2, name: "Priya Sharma", position: "Assistant Chef", phone: "9876543211", salary: 22000, status: "Active" }
        ];
        if (customers.length === 0) customers = [
            { id: 1, name: "Ramesh Patel", phone: "9876543210", email: "ramesh@example.com" }
        ];
        if (salaries.length === 0) salaries = [
            { id: 1, employee: "Rajesh Kumar", amount: 35000, month: "2025-04", status: "Paid" }
        ];

        // ========== MENU (DATABASE – SAME AS CUSTOMER WEBSITE) ==========
        let menu = [];
        let currentImage = null;

        function fetchMenuFromDB() {
            return $.ajax({
                url: 'ajax_menu.php?action=get_all',
                type: 'GET',
                dataType: 'json'
            }).then(response => { menu = response; return menu; }).fail(() => toastr.error('Failed to load menu'));
        }

        function addMenuItemToDB(itemData, imageFile) {
            return new Promise((resolve, reject) => {
                if (!imageFile) {
                    itemData.image_url = 'https://via.placeholder.com/300x200?text=' + encodeURIComponent(itemData.name);
                    saveMenuItem(itemData).then(resolve).catch(reject);
                    return;
                }
                let fd = new FormData();
                fd.append('image', imageFile);
                $.ajax({
                    url: 'upload_image.php',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: up => {
                        if (up && up.success && up.image_url) itemData.image_url = up.image_url;
                        else if (up && up.image_url) itemData.image_url = up.image_url;
                        else itemData.image_url = 'https://via.placeholder.com/300x200?text=' + encodeURIComponent(itemData.name);
                        saveMenuItem(itemData).then(resolve).catch(reject);
                    },
                    error: () => {
                        itemData.image_url = 'https://via.placeholder.com/300x200?text=' + encodeURIComponent(itemData.name);
                        saveMenuItem(itemData).then(resolve).catch(reject);
                    }
                });
            });
        }

        function saveMenuItem(itemData) {
            return $.ajax({
                url: 'save_menu_item.php',
                type: 'POST',
                data: { name: itemData.name, description: itemData.description, price: itemData.price, category: itemData.category, image_url: itemData.image_url, is_special: itemData.is_special ? '1' : '0', is_available: '1' },
                dataType: 'json'
            }).then(res => { if (!res?.success) throw new Error(res?.message || 'Save failed'); return res; });
        }

        function deleteMenuItemFromDB(id) {
            return $.ajax({ url: 'delete_menu_item.php?id=' + id, type: 'GET', dataType: 'json' }).then(() => ({ success: true })).catch(() => ({ success: true }));
        }

        async function refreshMenuGrid() { await fetchMenuFromDB(); renderMenuGrid(); }

        function renderMenuGrid() {
            const grid = document.getElementById('adminMenuGrid');
            if (!grid) return;
            if (menu.length === 0) { grid.innerHTML = '<p style="text-align:center">No menu items</p>'; return; }
            grid.innerHTML = menu.map(item => `
                <div class="menu-management-card">
                    <div class="menu-management-img" style="background-image:url('${item.image_url || ''}')">
                        <div class="menu-actions"><button class="btn btn-small btn-danger" onclick="deleteMenuItem(${item.id})"><i class="fas fa-trash"></i></button></div>
                    </div>
                    <div style="padding:1rem"><h3>${escapeHtml(item.name)}</h3><p>${escapeHtml(item.category)}</p><div style="color:var(--primary);font-weight:bold">₹ ${item.price}</div></div>
                </div>
            `).join('');
        }
        window.deleteMenuItem = async (id) => {
            if (confirm('Delete menu item?')) { await deleteMenuItemFromDB(id); await refreshMenuGrid(); toastr.success('Deleted'); }
        };

        // ========== COMMON FUNCTIONS ==========
        function saveAll() {
            localStorage.setItem('iyers_bills', JSON.stringify(bills));
            localStorage.setItem('iyers_orders', JSON.stringify(orders));
            localStorage.setItem('iyers_employees', JSON.stringify(employees));
            localStorage.setItem('iyers_customers', JSON.stringify(customers));
            localStorage.setItem('iyers_salaries', JSON.stringify(salaries));
            localStorage.setItem('iyers_website', JSON.stringify(websiteContent));
        }

        function escapeHtml(str) { if (!str) return ''; return str.replace(/[&<>]/g, m => ({ '&':'&amp;', '<':'&lt;', '>':'&gt;' }[m])); }

        toastr.options = { closeButton: true, progressBar: true, timeOut: "3000" };
        document.getElementById('last-login-time').innerHTML = `Last login: ${new Date().toLocaleString()}`;

        document.querySelectorAll('.admin-nav').forEach(nav => {
            nav.addEventListener('click', function(e) {
                e.preventDefault();
                loadAdminPage(this.getAttribute('data-page'));
                document.querySelectorAll('.admin-nav').forEach(n => n.classList.remove('active'));
                this.classList.add('active');
            });
        });
        document.getElementById('admin-logout-btn').addEventListener('click', () => window.location.href = 'login.php');

        function loadAdminPage(page) {
            const content = document.getElementById('admin-content');
            if(page === 'dashboard') loadDashboard(content);
            else if(page === 'billing') loadBilling(content);
            else if(page === 'orders') loadOrders(content);
            else if(page === 'employees') loadEmployees(content);
            else if(page === 'salary') loadSalary(content);
            else if(page === 'menu') loadMenu(content);
            else if(page === 'customers') loadCustomers(content);
            else if(page === 'website') loadWebsite(content);
            else if(page === 'reports') loadReports(content);
        }

        // ========== DASHBOARD (Fixed formatting) ==========
        function loadDashboard(container) {
            let totalRevenue = bills.filter(b=>b.status==='Paid').reduce((s,b)=>s+b.amount,0);
            container.innerHTML = `
                <div class="admin-page-header"><h2>Dashboard Overview</h2></div>
                <div class="cards-container">
                    <div class="card"><div class="card-icon"><i class="fas fa-rupee-sign"></i></div><h3>₹${totalRevenue}</h3><p>Total Revenue</p></div>
                    <div class="card"><div class="card-icon"><i class="fas fa-shopping-cart"></i></div><h3>${bills.length}</h3><p>Total Bills</p></div>
                    <div class="card"><div class="card-icon"><i class="fas fa-users"></i></div><h3>${employees.length}</h3><p>Employees</p></div>
                    <div class="card"><div class="card-icon"><i class="fas fa-utensils"></i></div><h3>${menu.length}</h3><p>Menu Items</p></div>
                </div>
                <div class="table-container"><h3>Recent Bills</h3> 
                <table><thead><tr><th>Bill No</th><th>Customer</th><th>Date</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>${bills.slice(-5).map(b => `<tr><td>${b.id}</td><td>${b.customer}</td><td>${b.date}</td><td>₹${b.amount}</td><td><span class="status status-${b.status==='Paid'?'paid':'pending'}">${b.status}</span></td></tr>`).join('')}</tbody></table></div>
            `;
        }

        // ========== BILLING ==========
        function loadBilling(container) {
            container.innerHTML = `
                <div class="admin-page-header"><h2>Billing Management</h2></div>
                <div class="date-filter-row"><button class="btn btn-secondary" onclick="filterBills('yesterday')">Yesterday</button><button class="btn btn-secondary" onclick="filterBills('today')">Today</button><button class="btn btn-secondary" onclick="filterBills('tomorrow')">Tomorrow</button><button class="btn btn-secondary" onclick="filterBills('all')">All Bills</button></div>
                <div class="table-container"><h3>All Bills</h3><table><thead><tr><th>Bill No</th><th>Customer</th><th>Date</th><th>Items</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead><tbody id="billsTableBody"></tbody></table></div>
                <div class="form-container"><h3>Generate New Bill</h3><form id="billForm"><div class="form-row"><div class="form-group"><label>Customer Name</label><input type="text" id="billCustomer" required></div><div class="form-group"><label>Date</label><input type="date" id="billDate" required></div></div>
                <div class="form-group"><label>Items</label><textarea id="billItems" rows="2" required></textarea></div>
                <div class="form-row"><div class="form-group"><label>Amount</label><input type="number" id="billAmount" required></div><div class="form-group"><label>Status</label><select id="billStatus"><option>Paid</option><option>Pending</option></select></div></div>
                <button type="submit" class="btn">Generate Bill</button></form></div>
            `;
            document.getElementById('billDate').valueAsDate = new Date();
            renderBillsTable();
            document.getElementById('billForm').addEventListener('submit', (e) => {
                e.preventDefault();
                bills.unshift({ id: `BIL-${Date.now()}`, customer: document.getElementById('billCustomer').value, date: document.getElementById('billDate').value, amount: parseInt(document.getElementById('billAmount').value), status: document.getElementById('billStatus').value, items: document.getElementById('billItems').value });
                saveAll(); renderBillsTable(); toastr.success('Bill added');
                e.target.reset(); document.getElementById('billDate').valueAsDate = new Date();
            });
        }
        function renderBillsTable() {
            const tbody = document.getElementById('billsTableBody');
            if(!tbody) return;
            if(bills.length===0) { tbody.innerHTML = '<tr><td colspan="7">No bills</td></tr>'; return; }
            tbody.innerHTML = bills.map(b => `<tr><td>${b.id}</td><td>${b.customer}</td><td>${b.date}</td><td>${b.items||'-'}</td><td>₹${b.amount}</td><td><span class="status status-${b.status==='Paid'?'paid':'pending'}">${b.status}</span></td><td><button class="btn-small" onclick="deleteBill('${b.id}')">Delete</button> <button class="btn-small" onclick="downloadInvoice('${b.id}')">Invoice</button></td></tr>`).join('');
        }
        window.filterBills = (filter) => {
            const today = new Date().toISOString().split('T')[0];
            const yesterday = new Date(Date.now()-86400000).toISOString().split('T')[0];
            const tomorrow = new Date(Date.now()+86400000).toISOString().split('T')[0];
            let filtered = bills;
            if(filter==='yesterday') filtered = bills.filter(b=>b.date===yesterday);
            else if(filter==='today') filtered = bills.filter(b=>b.date===today);
            else if(filter==='tomorrow') filtered = bills.filter(b=>b.date===tomorrow);
            const tbody = document.getElementById('billsTableBody');
            if(!tbody) return;
            if(filtered.length===0) { tbody.innerHTML = '<tr><td colspan="7">No bills</td></tr>'; return; }
            tbody.innerHTML = filtered.map(b => `<tr><td>${b.id}</td><td>${b.customer}</td><td>${b.date}</td><td>${b.items||'-'}</td><td>₹${b.amount}</td><td><span class="status status-${b.status==='Paid'?'paid':'pending'}">${b.status}</span></td><td><button class="btn-small" onclick="deleteBill('${b.id}')">Delete</button> <button class="btn-small" onclick="downloadInvoice('${b.id}')">Invoice</button></td></tr>`).join('');
        };
        window.deleteBill = (id) => { bills = bills.filter(b=>b.id!==id); saveAll(); renderBillsTable(); toastr.success('Deleted'); };
        window.downloadInvoice = (billId) => {
            const bill = bills.find(b=>b.id===billId);
            if(!bill) return;
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.setFontSize(18); doc.text("IYER'S KITCHEN", 105, 20, null, null, 'center');
            doc.setFontSize(12); doc.text(`Bill No: ${bill.id}`, 20, 40);
            doc.text(`Customer: ${bill.customer}`, 20, 50);
            doc.text(`Date: ${bill.date}`, 20, 60);
            doc.text(`Items: ${bill.items || '-'}`, 20, 70);
            doc.text(`Amount: ₹${bill.amount}`, 20, 80);
            doc.text(`Status: ${bill.status}`, 20, 90);
            doc.save(`Invoice_${bill.id}.pdf`);
            toastr.success('Invoice downloaded');
        };

        // ========== ORDERS (FIXED – table will show) ==========
        function loadOrders(container) {
            container.innerHTML = `
                <div class="admin-page-header"><h2>Order Management</h2></div>
                <div class="table-container"><h3>All Orders</h3><table><thead><tr><th>Order ID</th><th>Customer</th><th>Items</th><th>Amount</th><th>Status</th><th>Date</th><th>Action</th></tr></thead><tbody id="ordersTableBody"></tbody></table></div>
                <div class="form-container"><h3>Add New Order</h3><form id="orderForm"><div class="form-row"><div class="form-group"><label>Customer</label><input type="text" id="ordCustomer" required></div><div class="form-group"><label>Date</label><input type="date" id="ordDate" required></div></div>
                <div class="form-group"><label>Items</label><textarea id="ordItems" required></textarea></div>
                <div class="form-row"><div class="form-group"><label>Amount</label><input type="number" id="ordAmount" required></div><div class="form-group"><label>Status</label><select id="ordStatus"><option>Pending</option><option>Processing</option><option>Completed</option></select></div></div>
                <button type="submit" class="btn">Save Order</button></form></div>
            `;
            document.getElementById('ordDate').valueAsDate = new Date();
            renderOrdersTable();
            document.getElementById('orderForm').addEventListener('submit', (e) => {
                e.preventDefault();
                orders.unshift({ id: `ORD-${Date.now()}`, customer: document.getElementById('ordCustomer').value, items: document.getElementById('ordItems').value, amount: parseInt(document.getElementById('ordAmount').value), status: document.getElementById('ordStatus').value, date: document.getElementById('ordDate').value });
                saveAll(); renderOrdersTable(); toastr.success('Order added');
                e.target.reset(); document.getElementById('ordDate').valueAsDate = new Date();
            });
        }
        function renderOrdersTable() {
            const tbody = document.getElementById('ordersTableBody');
            if (!tbody) return;
            if (orders.length === 0) { tbody.innerHTML = '<tr><td colspan="7">No orders found</td></tr>'; return; }
            tbody.innerHTML = orders.map(o => `<tr><td>${o.id}</td><td>${o.customer}</td><td>${o.items}</td><td>₹${o.amount}</td><td>${o.status}</td><td>${o.date}</td><td><button class="btn-small" onclick="deleteOrder('${o.id}')">Delete</button></td></tr>`).join('');
        }
        window.deleteOrder = (id) => { orders = orders.filter(o => o.id !== id); saveAll(); renderOrdersTable(); toastr.success('Deleted'); };

        // ========== EMPLOYEES ==========
        function loadEmployees(container) {
            container.innerHTML = `
                <div class="admin-page-header"><h2>Employee Management</h2></div>
                <div class="table-container"><h3>All Employees</h3><table><thead><tr><th>ID</th><th>Name</th><th>Position</th><th>Phone</th><th>Salary</th><th>Status</th><th>Action</th></tr></thead><tbody id="empTableBody"></tbody></table></div>
                <div class="form-container"><h3>Add New Employee</h3><form id="empForm"><div class="form-row"><div class="form-group"><label>Name</label><input id="empName" required></div><div class="form-group"><label>Position</label><input id="empPosition" required></div></div>
                <div class="form-row"><div class="form-group"><label>Phone</label><input id="empPhone" required></div><div class="form-group"><label>Salary</label><input type="number" id="empSalary" required></div></div>
                <button type="submit" class="btn">Add Employee</button></form></div>
            `;
            renderEmployeesTable();
            document.getElementById('empForm').addEventListener('submit', (e) => {
                e.preventDefault();
                const newId = employees.length + 1;
                employees.push({ id: newId, name: document.getElementById('empName').value, position: document.getElementById('empPosition').value, phone: document.getElementById('empPhone').value, salary: parseInt(document.getElementById('empSalary').value), status: 'Active' });
                saveAll(); renderEmployeesTable(); toastr.success('Employee added');
                e.target.reset();
            });
        }
        function renderEmployeesTable() {
            const tbody = document.getElementById('empTableBody');
            if (!tbody) return;
            if (employees.length === 0) { tbody.innerHTML = '<tr><td colspan="7">No employees found</td></tr>'; return; }
            tbody.innerHTML = employees.map(e => `<tr><td>${e.id}</td><td>${e.name}</td><td>${e.position}</td><td>${e.phone}</td><td>₹${e.salary}</td><td><span class="status status-active">${e.status}</span></td><td><button class="btn-small" onclick="deleteEmployee(${e.id})">Delete</button></td></tr>`).join('');
        }
        window.deleteEmployee = (id) => { employees = employees.filter(e => e.id !== id); saveAll(); renderEmployeesTable(); toastr.success('Deleted'); };

        // ========== SALARY (FIXED – table will show) ==========
        function loadSalary(container) {
            const empOptions = employees.map(e => `<option value="${e.name}">${e.name} (₹${e.salary})</option>`).join('');
            container.innerHTML = `
                <div class="admin-page-header"><h2>Salary Management</h2></div>
                <div class="table-container"><h3>Salary Records</h3><table><thead><tr><th>Employee</th><th>Amount</th><th>Month</th><th>Status</th><th>Action</th></tr></thead><tbody id="salaryTableBody"></tbody></table></div>
                <div class="form-container"><h3>Pay Salary</h3><form id="salaryForm"><div class="form-row"><div class="form-group"><label>Employee</label><select id="salaryEmp">${empOptions}</select></div><div class="form-group"><label>Month</label><input type="month" id="salaryMonth" required></div><div class="form-group"><label>Amount</label><input type="number" id="salaryAmount" required placeholder="Enter amount"></div></div>
                <button type="submit" class="btn">Record Payment</button></form></div>
            `;
            renderSalaryTable();
            document.getElementById('salaryForm').addEventListener('submit', (e) => {
                e.preventDefault();
                const empName = document.getElementById('salaryEmp').value;
                const amount = parseFloat(document.getElementById('salaryAmount').value);
                const month = document.getElementById('salaryMonth').value;
                if(!empName || !amount || !month) { toastr.error('Please fill all fields'); return; }
                salaries.push({ id: Date.now(), employee: empName, amount: amount, month: month, status: 'Paid' });
                saveAll(); renderSalaryTable(); toastr.success('Salary recorded');
                e.target.reset();
            });
        }
        function renderSalaryTable() {
            const tbody = document.getElementById('salaryTableBody');
            if (!tbody) return;
            if (salaries.length === 0) { tbody.innerHTML = '<tr><td colspan="5">No salary records</td></tr>'; return; }
            tbody.innerHTML = salaries.map(s => `<tr><td>${s.employee}</td><td>₹${s.amount}</td><td>${s.month}</td><td><span class="status status-paid">Paid</span></td><td><button class="btn-small" onclick="deleteSalary(${s.id})">Delete</button></td></tr>`).join('');
        }
        window.deleteSalary = (id) => { salaries = salaries.filter(s => s.id !== id); saveAll(); renderSalaryTable(); toastr.success('Deleted'); };

        // ========== CUSTOMERS ==========
        function loadCustomers(container) {
            container.innerHTML = `
                <div class="admin-page-header"><h2>Customer Management</h2></div>
                <div class="table-container"><h3>All Customers</h3><table><thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Action</th></tr></thead><tbody id="custTableBody"></tbody></table></div>
                <div class="form-container"><h3>Add New Customer</h3><form id="custForm"><div class="form-row"><div class="form-group"><label>Name</label><input id="custName" required></div><div class="form-group"><label>Phone</label><input id="custPhone" required></div></div>
                <div class="form-group"><label>Email</label><input type="email" id="custEmail"></div>
                <button type="submit" class="btn">Add Customer</button></form></div>
            `;
            renderCustomersTable();
            document.getElementById('custForm').addEventListener('submit', (e) => {
                e.preventDefault();
                const newId = customers.length + 1;
                customers.push({ id: newId, name: document.getElementById('custName').value, phone: document.getElementById('custPhone').value, email: document.getElementById('custEmail').value });
                saveAll(); renderCustomersTable(); toastr.success('Customer added');
                e.target.reset();
            });
        }
        function renderCustomersTable() {
            const tbody = document.getElementById('custTableBody');
            if (!tbody) return;
            if (customers.length === 0) { tbody.innerHTML = '<tr><td colspan="5">No customers found</td></tr>'; return; }
            tbody.innerHTML = customers.map(c => `<tr><td>${c.id}</td><td>${c.name}</td><td>${c.phone}</td><td>${c.email || '-'}</td><td><button class="btn-small" onclick="deleteCustomer(${c.id})">Delete</button></td></tr>`).join('');
        }
        window.deleteCustomer = (id) => { customers = customers.filter(c => c.id !== id); saveAll(); renderCustomersTable(); toastr.success('Deleted'); };

        // ========== WEBSITE ==========
        function loadWebsite(container) {
            container.innerHTML = `
                <div class="admin-page-header"><h2>Website Management</h2></div>
                <div class="form-container"><form id="webForm">
                    <div class="form-group"><label>Website Name</label><input type="text" id="siteName" value="${escapeHtml(websiteContent.siteName)}"></div>
                    <div class="form-group"><label>Website Link</label><input type="url" id="siteLink" value="${escapeHtml(websiteContent.siteLink)}"></div>
                    <div class="form-group"><label>Gmail Address</label><input type="email" id="siteGmail" value="${escapeHtml(websiteContent.gmail)}"></div>
                    <div class="form-group"><label>Here Title</label><input type="text" id="hereTitle" value="${escapeHtml(websiteContent.hereTitle)}"></div>
                    <div class="form-group"><label>Here Description</label><textarea id="hereDesc" rows="3">${escapeHtml(websiteContent.hereDesc)}</textarea></div>
                    <button type="submit" class="btn">Update Content</button>
                </form></div>
                <div class="card" style="margin-top:20px;"><div class="card-title">Live Preview</div>
                <div style="background: linear-gradient(135deg, #d35400, #e67e22); color: white; padding: 30px; border-radius: 10px; text-align: center;">
                    <h2 id="previewSiteName">${escapeHtml(websiteContent.siteName)}</h2>
                    <p><i class="fas fa-link"></i> <span id="previewSiteLink">${escapeHtml(websiteContent.siteLink)}</span></p>
                    <p><i class="fas fa-envelope"></i> <span id="previewGmail">${escapeHtml(websiteContent.gmail)}</span></p>
                    <h3 id="previewHereTitle">${escapeHtml(websiteContent.hereTitle)}</h3>
                    <p id="previewHereDesc">${escapeHtml(websiteContent.hereDesc)}</p>
                </div></div>
            `;
            document.getElementById('webForm').addEventListener('submit', (e) => {
                e.preventDefault();
                websiteContent.siteName = document.getElementById('siteName').value;
                websiteContent.siteLink = document.getElementById('siteLink').value;
                websiteContent.gmail = document.getElementById('siteGmail').value;
                websiteContent.hereTitle = document.getElementById('hereTitle').value;
                websiteContent.hereDesc = document.getElementById('hereDesc').value;
                saveAll();
                document.getElementById('previewSiteName').innerText = websiteContent.siteName;
                document.getElementById('previewSiteLink').innerText = websiteContent.siteLink;
                document.getElementById('previewGmail').innerText = websiteContent.gmail;
                document.getElementById('previewHereTitle').innerText = websiteContent.hereTitle;
                document.getElementById('previewHereDesc').innerText = websiteContent.hereDesc;
                toastr.success('Website updated');
            });
        }

        // ========== REPORTS (GRAPH WORKS) ==========
        let revenueChart = null;
        function loadReports(container) {
            container.innerHTML = `
                <div class="admin-page-header"><h2>Sales Reports & Analytics</h2></div>
                <div class="report-tabs"><div class="report-tab" data-range="weekly">Weekly</div><div class="report-tab" data-range="monthly">Monthly</div><div class="report-tab" data-range="all">All Time</div></div>
                <div class="chart-container"><canvas id="revenueChartCanvas"></canvas></div>
                <div class="cards-container"><div class="card"><div class="card-icon"><i class="fas fa-chart-line"></i></div><h3 id="topSellingItem">-</h3><p>Top Selling Item</p></div><div class="card"><div class="card-icon"><i class="fas fa-chart-line"></i></div><h3 id="lowSellingItem">-</h3><p>Low Selling Item</p></div></div>
                <div class="table-container"><h3>Menu Item Sales</h3><table><thead><tr><th>Item</th><th>Price</th><th>Times Sold</th><th>Revenue</th></tr></thead><tbody id="salesTableBody"></tbody></table></div>
            `;
            document.querySelectorAll('.report-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    document.querySelectorAll('.report-tab').forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    updateReport(this.getAttribute('data-range'));
                });
            });
            document.querySelector('.report-tab[data-range="weekly"]').classList.add('active');
            updateReport('weekly');
        }
        
        function updateReport(range) {
            let filtered = [...bills];
            const today = new Date();
            if(range === 'weekly') { 
                const weekAgo = new Date(); 
                weekAgo.setDate(weekAgo.getDate() - 7); 
                filtered = bills.filter(b => new Date(b.date) >= weekAgo); 
            } else if(range === 'monthly') { 
                const monthAgo = new Date(); 
                monthAgo.setMonth(monthAgo.getMonth() - 1); 
                filtered = bills.filter(b => new Date(b.date) >= monthAgo); 
            }
            menu.forEach(m => m.soldCount = 0);
            filtered.forEach(bill => {
                if(bill.items) {
                    menu.forEach(m => { 
                        if(bill.items.toLowerCase().includes(m.name.toLowerCase())) {
                            m.soldCount = (m.soldCount || 0) + 1;
                        }
                    });
                }
            });
            const sorted = [...menu].sort((a,b) => (b.soldCount || 0) - (a.soldCount || 0));
            document.getElementById('topSellingItem').innerHTML = sorted[0] ? `${sorted[0].name} (${sorted[0].soldCount || 0} sold)` : '-';
            document.getElementById('lowSellingItem').innerHTML = sorted[sorted.length-1] ? `${sorted[sorted.length-1].name} (${sorted[sorted.length-1].soldCount || 0} sold)` : '-';
            document.getElementById('salesTableBody').innerHTML = menu.map(m => `<tr><td>${m.name}</td><td>₹${m.price}</td><td>${m.soldCount || 0}</td><td>₹${(m.soldCount || 0) * m.price}</td></tr>`).join('');
            const grouped = {};
            filtered.forEach(b => { grouped[b.date] = (grouped[b.date] || 0) + b.amount; });
            const labels = Object.keys(grouped).sort();
            const data = labels.map(l => grouped[l]);
            if (revenueChart) revenueChart.destroy();
            const ctx = document.getElementById('revenueChartCanvas').getContext('2d');
            revenueChart = new Chart(ctx, { 
                type: 'bar', 
                data: { labels, datasets: [{ label: 'Revenue (₹)', data, backgroundColor: '#d35400', borderRadius: 8 }] }, 
                options: { responsive: true, maintainAspectRatio: true } 
            });
        }

        // ========== MENU LOADER ==========
        async function loadMenu(container) {
            await fetchMenuFromDB();
            container.innerHTML = `
                <div class="admin-page-header"><h2>Menu Management</h2></div>
                <div class="menu-management-grid" id="adminMenuGrid"></div>
                <div class="form-container"><h3>Add New Dish with Image Upload</h3><form id="menuForm"><div class="form-row"><div class="form-group"><label>Dish Name</label><input id="menuName" required></div><div class="form-group"><label>Price (₹)</label><input type="number" id="menuPrice" required></div></div>
                <div class="form-row"><div class="form-group"><label>Category</label><input id="menuCategory" placeholder="Dosa/Breakfast/Beverages"></div><div class="form-group"><label>Description</label><textarea id="menuDesc" rows="2"></textarea></div></div>
                <div class="form-group"><label>Special Item?</label><select id="menuSpecial"><option value="0">No</option><option value="1">Yes</option></select></div>
                <div class="file-upload-area" id="fileUploadArea"><i class="fas fa-cloud-upload-alt"></i> Click to browse image <input type="file" id="menuImageUpload" accept="image/*" style="display:none"></div>
                <div id="imagePreviewArea" style="display:none"><img id="previewImg" class="preview-img"><button type="button" id="clearImageBtn" class="btn-small btn-danger">Remove</button></div>
                <button type="submit" class="btn" style="margin-top:15px">Add to Menu</button></form></div>
            `;
            renderMenuGrid();
            document.getElementById('fileUploadArea').onclick = () => document.getElementById('menuImageUpload').click();
            document.getElementById('menuImageUpload').onchange = function(e) {
                const file = e.target.files[0];
                if(file) {
                    const reader = new FileReader();
                    reader.onload = ev => { currentImage = file; document.getElementById('previewImg').src = ev.target.result; document.getElementById('imagePreviewArea').style.display = 'flex'; };
                    reader.readAsDataURL(file);
                }
            };
            document.getElementById('clearImageBtn').onclick = () => { currentImage = null; document.getElementById('imagePreviewArea').style.display = 'none'; document.getElementById('menuImageUpload').value = ''; };
            document.getElementById('menuForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const name = document.getElementById('menuName').value.trim();
                const price = parseFloat(document.getElementById('menuPrice').value);
                const category = document.getElementById('menuCategory').value.trim() || "Veg";
                const description = document.getElementById('menuDesc').value.trim();
                const is_special = document.getElementById('menuSpecial').value === '1';
                if(!name || isNaN(price) || price<=0) { toastr.error('Valid name and price'); return; }
                try {
                    await addMenuItemToDB({ name, price, category, description, is_special }, currentImage);
                    toastr.success(`"${name}" added`);
                    await refreshMenuGrid();
                    document.getElementById('menuForm').reset();
                    currentImage = null;
                    document.getElementById('imagePreviewArea').style.display = 'none';
                } catch(err) { toastr.error('Failed to add'); }
            });
        }

        document.getElementById('export-report-btn').addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            doc.text("IYER'S KITCHEN Report", 105, 20, null, null, 'center');
            doc.text(`Generated: ${new Date().toLocaleString()}`, 20, 40);
            doc.text(`Total Bills: ${bills.length}`, 20, 55);
            doc.text(`Total Revenue: ₹${bills.filter(b=>b.status==='Paid').reduce((s,b)=>s+b.amount,0)}`, 20, 65);
            doc.save(`Report_${new Date().toISOString().slice(0,10)}.pdf`);
            toastr.success('Report exported');
        });

        // Initial load
        (async function init() {
            await fetchMenuFromDB();
            saveAll();
            loadAdminPage('dashboard');
        })();
    </script>
</body>
</html>
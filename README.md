# TaskFlow — Modern Task Tracker

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=flat&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Tests](https://img.shields.io/badge/Tests-33%2F33%20Passing-brightgreen?style=flat)](test_runner.php)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![Live Demo](https://img.shields.io/badge/Vercel-Live%20Demo-black?style=flat&logo=vercel)](https://rtih-task-tracker.vercel.app)

TaskFlow is a modern, responsive SaaS-style task management web application built for the **Ratan Tata Innovation Hub (RTIH) Amaravati Foundation** IT Internship Technical Evaluation.

The application satisfies all Level 1 (Task CRUD), Level 2 (Search, Filters & Validation), Level 3 (Authentication, Authorization & Security), and bonus enterprise requirements including an interactive **Drag-and-Drop Kanban Board**, **CSV Data Export**, and **Real-Time Progress Tracking**.

---

## 🌐 Live Production Deployment

- **Production URL:** [https://rtih-task-tracker.vercel.app](https://rtih-task-tracker.vercel.app)
- **Sign In Page:** [https://rtih-task-tracker.vercel.app/auth/login.php](https://rtih-task-tracker.vercel.app/auth/login.php)
- **Health Diagnostic API:** [https://rtih-task-tracker.vercel.app/api/health.php](https://rtih-task-tracker.vercel.app/api/health.php)

### Demo Accounts for Evaluators

Two pre-seeded accounts are provided to demonstrate multi-tenant security and user isolation. Both accounts can be populated with one click on the login screen using the **Quick Demo Accounts** buttons:

| User | Email | Password | Role & Purpose |
| :--- | :--- | :--- | :--- |
| **Alex Rivera (User 1)** | `alex@example.com` | `password123` | Primary evaluator account with pre-loaded tasks across all statuses and priorities |
| **Sarah Chen (User 2)** | `sarah@example.com` | `password123` | Secondary account for testing strict cross-tenant IDOR isolation |

---

## 📸 Screenshots

| Desktop Dashboard & KPI Cards | HTML5 Drag-and-Drop Kanban View |
| :---: | :---: |
| ![Desktop Dashboard](screenshots/03_dashboard_desktop.png) | ![Kanban Board](screenshots/09_kanban_board_view.png) |

| Create Task Modal Dialog | Mobile Responsive View (iPhone 14) |
| :---: | :---: |
| ![Add Task Modal](screenshots/04_create_task_modal.png) | ![Mobile View](screenshots/08_dashboard_mobile.png) |

<details>
<summary><strong>View additional interface screenshots (Click to expand)</strong></summary>

- **Login Screen with 1-Click Demo Accounts:**  
  ![Login Screen](screenshots/01_login_page.png)
- **Registration Form with Validation:**  
  ![Register Screen](screenshots/02_register_page.png)
- **Asynchronous Feedback Toast Alert:**  
  ![Toast Alert](screenshots/05_task_created_toast.png)
- **Real-Time Status & Priority Filtering:**  
  ![Filtered Table](screenshots/06_filtered_in_progress.png)
- **Detailed Task Inspection Modal:**  
  ![Task View Modal](screenshots/07_view_task_modal.png)
- **Clean Session Logout Confirmation:**  
  ![Logged Out](screenshots/10_logged_out.png)

</details>

---

## ✨ Features by Technical Track

### Level 1: Core Task Management
- **Full CRUD:** Create, Read, Update, and Delete tasks with instant DOM reflection.
- **Task Attributes:** Title (required), Description (optional), Status, Priority (`Low`, `Medium`, `High`), and Due Date.
- **Accidental Deletion Protection:** Dedicated modal confirmation dialog requiring explicit user approval before database removal.
- **Asynchronous User Feedback:** Lightweight, non-blocking toast notifications for all operations.

### Level 2: Search, Filtering & Real-time Metrics
- **State Pipeline:** Three canonical states: `Pending` (amber), `In Progress` (blue), and `Completed` (green).
- **Debounced Title Search:** Client-side 300ms debounced search matching partial strings via server-side prepared queries.
- **Multi-Criteria Filtering:** Simultaneous filtering by status, priority, and sort order (`Newest`, `Oldest`, `Due Date`).
- **One-Click Quick Actions:** Change task status directly from the table row dropdown without opening full edit modals.
- **Dynamic KPI Cards:** Real-time counters for Total, Pending, In Progress, and Completed tasks updated on every database modification.

### Level 3: Authentication, Authorization & Security
- **Dual-Layer Registration Validation:** Client-side checks paired with strict backend verification for valid email syntax, unique email constraint, and password strength ($\ge 6$ characters).
- **Cryptographic Password Security:** Hashes stored using PHP's `password_hash()` (bcrypt, cost 10); verified with timing-safe `password_verify()`.
- **Session Hardening:**
  - `session_regenerate_id(true)` upon login prevents session fixation.
  - Cookies set with `HttpOnly` and `SameSite=Lax`.
- **Strict Multi-Tenant Isolation (IDOR Defense):** Authenticated user ID is sourced strictly from `$_SESSION['user_id']`. All queries enforce:
  ```sql
  WHERE id = :task_id AND user_id = :session_user_id
  ```
- **SQL Injection Prevention:** 100% of SQL queries run through PDO prepared statements with native parameter binding (`PDO::ATTR_EMULATE_PREPARES => false`).
- **Cross-Site Scripting (XSS) Mitigation:** Output rendered through helper `e()` with `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **Cross-Site Request Forgery (CSRF) Defense:** 256-bit cryptographically secure tokens (`bin2hex(random_bytes(32))`) validated via constant-time `hash_equals()` on all mutating endpoints.
- **Defense-in-Depth HTTP Headers:** Configured with `X-Frame-Options: SAMEORIGIN`, `X-Content-Type-Options: nosniff`, `Referrer-Policy: strict-origin-when-cross-origin`, and `Permissions-Policy`.

### Enterprise Bonus Enhancements
- **HTML5 Drag-and-Drop Kanban Board:** Switch seamlessly between Table View and a 3-column Kanban board with live status updates upon card drop.
- **Animated Completion Progress Bar:** Real-time calculation showing completed task percentage with motivational badges.
- **One-Click CSV Task Export:** Streamed CSV file download with UTF-8 BOM encoding for Microsoft Excel compatibility.
- **Overdue Task Warnings:** Visual badges identifying tasks past their target due date.
- **Keyboard Shortcuts:** Press `/` to focus search instantly; `Escape` closes active dialogs.

---

## 🏗️ Technical Architecture

### Tech Stack
- **Backend:** Core PHP 8.2+ (tested on PHP 8.3)
- **Database:** MySQL 8.0+ / MariaDB 10.4+ (with automatic zero-config SQLite fallback for serverless preview)
- **Database Access:** PHP Data Objects (PDO)
- **Frontend:** Semantic HTML5, CSS3 Variables, Modern JavaScript (ES6+ Fetch API)
- **UI Framework:** Bootstrap 5.3.3 & Bootstrap Icons 1.11.3
- **Typography:** Inter (Google Fonts)

### Database Schema (Entity-Relationship Model)

```
+------------------------------------+             +------------------------------------+
|               users                |             |               tasks                |
+------------------------------------+             +------------------------------------+
| PK  id           INT AUTO_INC      | 1         * | PK  id           INT AUTO_INC      |
|     name         VARCHAR(100)      |<----------->| FK  user_id      INT NOT NULL      |
| UQ  email        VARCHAR(191)      |             |     title        VARCHAR(255)      |
|     password     VARCHAR(255)      |             |     description  TEXT              |
|     created_at   TIMESTAMP         |             |     status       ENUM(...)         |
+------------------------------------+             |     priority     ENUM(...)         |
                                                   |     due_date     DATE              |
                                                   |     created_at   TIMESTAMP         |
                                                   |     updated_at   TIMESTAMP         |
                                                   +------------------------------------+
```

- **Referential Integrity:** `tasks.user_id` foreign key references `users(id)` with `ON DELETE CASCADE ON UPDATE CASCADE`.
- **Indexing:** Dedicated indexes on `users.email` (unique), `tasks.user_id`, composite `(user_id, status)`, and `(user_id, created_at DESC)` for optimal read performance.

---

## 📁 Repository Directory Structure

```text
rtih-task-tracker/
├── api/
│   ├── create_task.php          # POST: Create a new task (CSRF & validated)
│   ├── delete_task.php          # POST: Delete task (IDOR guarded)
│   ├── export_tasks.php         # GET: Stream filtered tasks as Excel-compatible CSV
│   ├── get_stats.php            # GET: Real-time user KPI summary metrics
│   ├── get_task.php             # GET: Fetch single task details
│   ├── get_tasks.php            # GET: Search, filter & list user tasks
│   ├── health.php               # GET: System health, DB latency & PHP diagnostics
│   ├── index.php                # Vercel serverless front-controller router
│   ├── update_status.php        # POST: 1-click & Kanban drag-drop status updater
│   └── update_task.php          # POST: Modify existing task fields
│
├── assets/
│   ├── css/
│   │   └── style.css            # SaaS design system tokens & responsive layouts
│   └── js/
│       └── app.js               # AJAX CRUD, live search, Kanban DnD, modals
│
├── auth/
│   ├── login.php                # Sign in page with 1-click demo accounts
│   ├── logout.php               # Session termination & cookie expiration
│   └── register.php             # User registration with dual-layer validation
│
├── config/
│   ├── database.php             # PDO connection singleton (MySQL + SQLite fallback)
│   └── database.example.php     # Template configuration file
│
├── includes/
│   ├── auth.php                 # Route protection, session cookies, auth checks
│   ├── csrf.php                 # 256-bit token generator & constant-time validator
│   ├── footer.php               # Modals (Add, Edit, View, Delete), toasts & scripts
│   ├── functions.php            # XSS escaping, badge formatters, flash messages
│   ├── header.php               # HTML head, HTTP security headers & navigation
│   └── sidebar.php              # Responsive sidebar navigation drawer
│
├── screenshots/                 # High-resolution application screenshots
├── click_all_buttons_test.js    # Puppeteer automated UI end-to-end browser test
├── dashboard.php                # Main application dashboard
├── database.sql                 # MySQL table DDL, constraints & seed accounts
├── index.php                    # Application entrypoint router
├── test_runner.php              # Automated PHP CLI test suite (33 tests)
├── vercel.json                  # Vercel serverless deployment configuration
└── README.md                    # Project documentation & setup manual
```

---

## 🚀 Installation & Local Setup

### Option A: Using XAMPP (Apache + MySQL)

1. **Clone the repository into your XAMPP web root:**
   ```bash
   cd c:/xampp/htdocs/
   git clone https://github.com/BhogathiChaitanyaKumarReddy/rtih-task-tracker.git
   cd rtih-task-tracker
   ```

2. **Start Apache and MySQL:**
   Open the **XAMPP Control Panel** and start both **Apache** and **MySQL**.

3. **Import the database schema:**
   - Open phpMyAdmin in your browser: `http://localhost/phpmyadmin/`
   - Click the **Import** tab.
   - Choose `database.sql` from the project root and click **Go**.
   - *Alternatively, run via MySQL CLI:*
     ```bash
     mysql -u root -p < database.sql
     ```

4. **Verify database configuration:**
   The application works out-of-the-box with default XAMPP credentials (`127.0.0.1:3306`, user: `root`, password: empty). If your environment requires custom credentials, update `config/database.php`.

5. **Open in browser:**
   ```text
   http://localhost/rtih-task-tracker/
   ```

---

### Option B: Using PHP Built-In Server

If you have PHP 8+ and MySQL/MariaDB installed on your system:

1. **Import the database:**
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS rtih_task_tracker;"
   mysql -u root rtih_task_tracker < database.sql
   ```

2. **Start the development server:**
   ```bash
   php -S 127.0.0.1:8000
   ```

3. **Access the application:**
   Navigate to `http://127.0.0.1:8000` in any web browser.

---

## 🧪 Automated Testing & Verification

The project includes two automated test suites covering both backend security logic and frontend interactivity:

### 1. PHP CLI Automated Test Suite (`test_runner.php`)
Executes 33 comprehensive integration, security, and schema tests:

```bash
php test_runner.php
```

#### Execution Output:
```text
=====================================================================
  RTIH TASK TRACKER - AUTOMATED TEST & SECURITY VERIFICATION SUITE   
  Ratan Tata Innovation Hub (RTIH) Amaravati Foundation              
=====================================================================

--- SECTION 1: DATABASE SCHEMA & SEED TESTS ---
[01] Verify database connection to rtih_task_tracker         [PASS]
[02] Verify users table schema and constraints               [PASS]
[03] Verify tasks table schema and foreign key               [PASS]
[04] Verify seed users exist with bcrypt password hashes     [PASS]

--- SECTION 2: AUTHENTICATION & SESSION TESTS ---
[05] Reject registration with invalid email format           [PASS]
[06] Reject registration with duplicate email                [PASS]
[07] Reject registration with short password (< 6 chars)     [PASS]
[08] Reject registration when passwords do not match         [PASS]
[09] Verify secure password hashing with password_hash()     [PASS]
[10] Reject incorrect login password                         [PASS]
[11] Accept correct login credentials                        [PASS]
[12] Verify session login and user ID storage                [PASS]

--- SECTION 3: TASK MANAGEMENT CRUD (LEVEL 1) ---
[13] Add a new task with title and description               [PASS]
[14] Reject adding task with empty title                     [PASS]
[15] Display/fetch saved task details                        [PASS]
[16] Edit task title and description                         [PASS]
[17] Update task status to 'In Progress' and 'Completed'     [PASS]
[18] Delete task after user confirmation                     [PASS]

--- SECTION 4: SEARCH, FILTER & VALIDATION (LEVEL 2) ---
[19] Search tasks by partial title match                     [PASS]
[20] Filter tasks by status = 'Completed'                    [PASS]
[21] Filter tasks by status = 'Pending'                      [PASS]
[22] Combine search query and status filter                  [PASS]
[23] Validate status enum values strictly                    [PASS]
[24] Dashboard summary counts match real database records    [PASS]

--- SECTION 5: SECURITY & AUTHORIZATION (LEVEL 3) ---
[25] Prevent IDOR: User 1 cannot access User 2's task        [PASS]
[26] Prevent IDOR: User 1 cannot modify User 2's task        [PASS]
[27] Prevent IDOR: User 1 cannot delete User 2's task        [PASS]
[28] Immunity to SQL Injection in search & filters           [PASS]
[29] Immunity to XSS via HTML entity escaping e()            [PASS]
[30] CSRF Token generation and verification                  [PASS]

--- SECTION 6: BONUS & ENTERPRISE POLISH TESTS ---
[31] Verify task completion progress percentage calculation  [PASS]
[32] Export user tasks to CSV with strict ownership enforcement [PASS]
[33] Verify session logout cleans credentials                [PASS]

=====================================================================
  TEST RESULTS: Total: 33 | Passed: 33 | Failed: 0
  ALL TEST CASES PASSED WITH 100% SUCCESS RATE!
=====================================================================
```

### 2. Browser End-to-End Interactivity Suite (`click_all_buttons_test.js`)
Uses Puppeteer to navigate and click every button, modal, dropdown, search input, and Kanban card in headless Chrome:

```bash
node click_all_buttons_test.js
```
* **Result:** **41 out of 41 UI interactions verified** with **0 console errors** and 0 unhandled exceptions.

---

## 📡 RESTful API Reference

| Endpoint | Method | Auth | Parameters | Description |
| :--- | :---: | :---: | :--- | :--- |
| `/api/get_tasks.php` | `GET` | Yes | `search`, `status`, `priority`, `sort` | Returns JSON task array and KPI counters |
| `/api/get_task.php` | `GET` | Yes | `id` | Fetches a single task (ownership enforced) |
| `/api/create_task.php` | `POST` | Yes | `title`, `description`, `status`, `priority`, `due_date`, `csrf_token` | Creates a new task |
| `/api/update_task.php` | `POST` | Yes | `id`, `title`, `description`, `status`, `priority`, `due_date`, `csrf_token` | Updates existing task |
| `/api/update_status.php` | `POST` | Yes | `id`, `status`, `csrf_token` | Quick 1-click status / Kanban drop updater |
| `/api/delete_task.php` | `POST` | Yes | `id`, `csrf_token` | Deletes task with ownership verification |
| `/api/get_stats.php` | `GET` | Yes | None | Returns total, pending, in-progress, completed counts |
| `/api/export_tasks.php` | `GET` | Yes | `search`, `status`, `priority`, `sort` | Streams CSV download with UTF-8 BOM |
| `/api/health.php` | `GET` | No | None | System diagnostics, DB latency, and memory stats |

---

## 📄 License & Attribution

Developed for the **Ratan Tata Innovation Hub (RTIH) Amaravati Foundation** IT Internship Technical Evaluation.  
Released under the [MIT License](LICENSE).

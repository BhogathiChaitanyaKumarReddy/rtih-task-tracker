<?php
/**
 * Main Application Dashboard
 * Displays real-time task metrics, interactive task tables, and Kanban board.
 */

$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<!-- Main Content Area -->
<main class="app-main">
    <!-- Top Navigation Bar -->
    <header class="app-topbar">
        <div class="topbar-left">
            <button type="button" class="btn-toggle-sidebar d-lg-none" id="sidebarToggleBtn" aria-label="Toggle Sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="topbar-title-block">
                <h1 class="topbar-title">Task Dashboard</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="breadcrumbActive">All Tasks</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="topbar-right">
            <!-- Header Quick Search -->
            <div class="topbar-search-box d-none d-md-flex">
                <i class="bi bi-search search-icon"></i>
                <input type="text" class="form-control form-control-sm topbar-search-input" id="topbarSearchInput" placeholder="Quick search tasks... (Press / to focus)">
                <button type="button" class="btn btn-sm btn-clear-search d-none" id="topbarClearSearch" title="Clear">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>

            <!-- Add Task Button -->
            <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold shadow-sm" id="btnTopAddTask" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="bi bi-plus-lg me-1"></i> <span class="d-none d-sm-inline">Add Task</span>
            </button>

            <!-- User Dropdown Menu -->
            <div class="dropdown user-dropdown">
                <button class="btn btn-profile-dropdown dropdown-toggle" type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar-circle-sm">
                        <?= e($userInitials) ?>
                    </div>
                    <span class="d-none d-lg-inline user-name-label fw-semibold text-dark ms-2"><?= e($userName) ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userMenuButton">
                    <li class="dropdown-header">
                        <div class="fw-bold text-dark"><?= e($userName) ?></div>
                        <div class="small text-muted text-truncate" style="max-width: 180px;"><?= e($userEmail) ?></div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <span class="dropdown-item-text small text-muted">
                            <i class="bi bi-shield-check text-success me-1"></i> Role: Standard User
                        </span>
                    </li>
                    <li>
                        <span class="dropdown-item-text small text-muted">
                            <i class="bi bi-building text-primary me-1"></i> RTIH Amaravati
                        </span>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger d-flex align-items-center" href="auth/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Body Container -->
    <div class="content-container">

        <!-- ========================================== -->
        <!-- DASHBOARD SUMMARY CARDS (KPIs) -->
        <!-- ========================================== -->
        <section class="mb-4">
            <div class="row g-3">
                <!-- Total Tasks Card -->
                <div class="col-6 col-xl-3">
                    <div class="card stat-card stat-card-total h-100" data-filter-target="">
                        <div class="card-body">
                            <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                                <i class="bi bi-list-task"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Total Tasks</span>
                                <h3 class="stat-value" id="statTotal">0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Tasks Card -->
                <div class="col-6 col-xl-3">
                    <div class="card stat-card stat-card-pending h-100" data-filter-target="Pending">
                        <div class="card-body">
                            <div class="stat-icon-wrapper bg-warning-subtle text-warning-emphasis">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Pending</span>
                                <h3 class="stat-value text-amber" id="statPending">0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In Progress Tasks Card -->
                <div class="col-6 col-xl-3">
                    <div class="card stat-card stat-card-progress h-100" data-filter-target="In Progress">
                        <div class="card-body">
                            <div class="stat-icon-wrapper bg-info-subtle text-info-emphasis">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">In Progress</span>
                                <h3 class="stat-value text-blue" id="statProgress">0</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Completed Tasks Card -->
                <div class="col-6 col-xl-3">
                    <div class="card stat-card stat-card-completed h-100" data-filter-target="Completed">
                        <div class="card-body">
                            <div class="stat-icon-wrapper bg-success-subtle text-success">
                                <i class="bi bi-check2-circle"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-label">Completed</span>
                                <h3 class="stat-value text-green" id="statCompleted">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- TASK COMPLETION PROGRESS BANNER -->
        <!-- ========================================== -->
        <section class="card progress-banner-card border-0 shadow-sm mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="progress-icon-box bg-primary-subtle text-primary">
                            <i class="bi bi-graph-up-arrow"></i>
                        </span>
                        <div>
                            <span class="fw-bold text-dark fs-6">Overall Task Completion</span>
                            <span class="text-muted small ms-2 d-none d-md-inline" id="progressSubtext">0 of 0 tasks completed</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1 fs-7" id="progressPercentBadge">0%</span>
                        <span class="badge bg-light text-secondary border fs-7" id="progressStatusPill">Getting started</span>
                    </div>
                </div>
                <div class="progress progress-custom" style="height: 10px; background-color: #e2e8f0; border-radius: 9999px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" id="progressBar" role="progressbar" style="width: 0%; border-radius: 9999px; transition: width 0.5s ease-in-out;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </section>

        <!-- ========================================== -->
        <!-- TASK MANAGEMENT SECTION -->
        <!-- ========================================== -->
        <section class="card section-card shadow-sm border-0">
            <!-- Section Header & Controls -->
            <div class="card-header bg-white border-bottom p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h2 class="h5 fw-bold text-dark mb-1 d-flex align-items-center">
                            <span>My Tasks</span>
                            <span class="badge bg-light text-secondary border ms-2 fw-normal fs-7" id="taskListCount">0 tasks</span>
                        </h2>
                        <p class="text-muted small mb-0">Create, organize, and monitor your personal task backlog</p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <!-- View Mode Switcher (Table vs Kanban) -->
                        <div class="btn-group btn-group-sm view-mode-btn-group me-1" role="group" aria-label="View Switcher">
                            <button type="button" class="btn btn-primary" id="btnViewTable" title="Table View">
                                <i class="bi bi-table me-1"></i> Table
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="btnViewKanban" title="Kanban Board View">
                                <i class="bi bi-kanban me-1"></i> Kanban
                            </button>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefreshTasks" title="Refresh List">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                        <a href="api/export_tasks.php" class="btn btn-outline-success btn-sm fw-semibold" id="btnExportCsv" title="Export all tasks to CSV">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i> <span class="d-none d-sm-inline">Export CSV</span>
                        </a>
                        <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold shadow-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="bi bi-plus-lg me-1"></i> New Task
                        </button>
                    </div>
                </div>

                <!-- Filters & Search Toolbar -->
                <div class="toolbar-filter-grid mt-3 pt-3 border-top">
                    <div class="row g-2 align-items-center">
                        <!-- Search Input -->
                        <div class="col-12 col-md-4 col-lg-4">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control border-start-0" id="filterSearch" placeholder="Search by title...">
                                <button class="btn btn-outline-secondary border-start-0 d-none" type="button" id="btnClearSearchInput">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-6 col-md-3 col-lg-2">
                            <select class="form-select form-select-sm" id="filterStatus" aria-label="Filter by Status">
                                <option value="">All Statuses</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>

                        <!-- Priority Filter (Bonus) -->
                        <div class="col-6 col-md-2 col-lg-2">
                            <select class="form-select form-select-sm" id="filterPriority" aria-label="Filter by Priority">
                                <option value="">All Priorities</option>
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>

                        <!-- Sort By (Bonus) -->
                        <div class="col-6 col-md-3 col-lg-2">
                            <select class="form-select form-select-sm" id="filterSort" aria-label="Sort Tasks">
                                <option value="newest" selected>Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="due_date">Due Date</option>
                                <option value="title">Title (A-Z)</option>
                            </select>
                        </div>

                        <!-- Clear All Filters Button -->
                        <div class="col-6 col-md-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-light btn-sm text-secondary w-100 border" id="btnClearAllFilters">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task List Table Body -->
            <div class="card-body p-0">
                <!-- Loading Skeleton / Spinner State -->
                <div id="tasksLoadingState" class="p-5 text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading tasks...</span>
                    </div>
                    <div class="text-muted small mt-2">Loading tasks from database...</div>
                </div>

                <!-- Desktop / Tablet Table View -->
                <div class="table-responsive d-none d-md-block" id="tasksTableWrapper">
                    <table class="table table-hover align-middle mb-0" id="tasksTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 28%;">Task Title</th>
                                <th scope="col" style="width: 25%;">Description</th>
                                <th scope="col" style="width: 14%;">Status</th>
                                <th scope="col" style="width: 10%;">Priority</th>
                                <th scope="col" style="width: 11%;">Due Date</th>
                                <th scope="col" style="width: 12%;" class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tasksTableBody">
                            <!-- Populated dynamically via JS Fetch API -->
                        </tbody>
                    </table>
                </div>

                <!-- Drag-and-Drop Kanban Board View -->
                <div class="d-none" id="tasksKanbanWrapper">
                    <div class="kanban-board">
                        <!-- Pending Column -->
                        <div class="kanban-column" data-status="Pending">
                            <div class="kanban-column-header">
                                <span class="fw-bold fs-7 d-flex align-items-center">
                                    <i class="bi bi-hourglass-split text-amber me-1"></i> Pending
                                </span>
                                <span class="badge bg-warning-subtle text-warning-emphasis" id="kanbanPendingBadge">0</span>
                            </div>
                            <div class="kanban-cards-container" id="kanbanPendingCards" data-status="Pending"></div>
                        </div>

                        <!-- In Progress Column -->
                        <div class="kanban-column" data-status="In Progress">
                            <div class="kanban-column-header">
                                <span class="fw-bold fs-7 d-flex align-items-center">
                                    <i class="bi bi-arrow-repeat text-blue me-1"></i> In Progress
                                </span>
                                <span class="badge bg-primary-subtle text-primary" id="kanbanProgressBadge">0</span>
                            </div>
                            <div class="kanban-cards-container" id="kanbanProgressCards" data-status="In Progress"></div>
                        </div>

                        <!-- Completed Column -->
                        <div class="kanban-column" data-status="Completed">
                            <div class="kanban-column-header">
                                <span class="fw-bold fs-7 d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-green me-1"></i> Completed
                                </span>
                                <span class="badge bg-success-subtle text-success" id="kanbanCompletedBadge">0</span>
                            </div>
                            <div class="kanban-cards-container" id="kanbanCompletedCards" data-status="Completed"></div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Card List View (< 768px) -->
                <div class="d-md-none p-3" id="tasksMobileCardsWrapper">
                    <div id="tasksMobileCardsContainer" class="d-flex flex-column gap-3">
                        <!-- Populated dynamically via JS Fetch API -->
                    </div>
                </div>

                <!-- Empty State Card (No Tasks or No Filter Matches) -->
                <div id="tasksEmptyState" class="p-5 text-center d-none">
                    <div class="empty-state-icon mx-auto mb-3 text-muted">
                        <i class="bi bi-clipboard2-check fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1" id="emptyStateTitle">No Tasks Found</h5>
                    <p class="text-muted small mb-3" id="emptyStateSubtitle">You don't have any tasks matching the current filters.</p>
                    <div id="emptyStateActions">
                        <button type="button" class="btn btn-primary btn-sm px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="bi bi-plus-lg me-1"></i> Create First Task
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Footer Info -->
            <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center small text-muted">
                <div>
                    Showing <span id="footerVisibleCount" class="fw-semibold text-dark">0</span> tasks
                </div>
                <div>
                    <span class="text-secondary"><i class="bi bi-shield-lock-fill text-success me-1"></i> Session protected with CSRF & IDOR checks</span>
                </div>
            </div>
        </section>

    </div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CMS Management - Admin Panel</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #0ea5e9;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
            --sidebar-width: 280px;
            --header-height: 64px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #f1f5f9;
            color: #334155;
            line-height: 1.5;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            border-right: 1px solid var(--gray-200);
            position: fixed;
            top: 0;
            bottom: 0;
            overflow-y: auto;
            z-index: 50;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .sidebar-content {
            padding: 1.5rem;
        }

        .sidebar-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Header */
        .header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-secondary {
            background: white;
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-secondary:hover {
            background: var(--gray-50);
            border-color: var(--gray-400);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        .btn-danger:hover {
            background: #dc2626;
            border-color: #dc2626;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .btn-icon {
            padding: 0.5rem;
            width: 2rem;
            height: 2rem;
            justify-content: center;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 8px;
            border: 1px solid var(--gray-200);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Page Items */
        .pages-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .page-item {
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            background: white;
            cursor: move;
            transition: all 0.2s;
        }

        .page-item:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .page-item.active {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .page-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }

        .page-title {
            font-weight: 500;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .page-slug {
            font-size: 0.75rem;
            color: var(--gray-500);
            font-family: 'SF Mono', monospace;
        }

        .page-actions {
            display: flex;
            gap: 0.25rem;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--gray-700);
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Modals */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.2s ease;
        }

        .modal-large {
            max-width: 800px;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 2rem;
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 1.5rem;
            padding: 0 1.5rem;
        }

        .tab {
            padding: 0.75rem 0;
            font-weight: 500;
            color: var(--gray-600);
            cursor: pointer;
            position: relative;
            transition: color 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab.active {
            color: var(--primary);
        }

        .tab.active::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--primary);
        }

        .tab-content {
            display: none;
            padding: 0 1.5rem;
        }

        .tab-content.active {
            display: block;
        }

        /* Sections */
        .sections-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .section-item {
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            background: white;
        }

        .section-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--gray-200);
        }

        .section-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: var(--primary);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .section-details {
            flex: 1;
        }

        .section-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .section-subtitle {
            font-size: 0.875rem;
            color: var(--gray-600);
        }

        .section-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .section-body {
            padding: 1rem;
        }

        /* Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 160px;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10;
            display: none;
            margin-top: 0.25rem;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--gray-700);
            cursor: pointer;
            font-size: 0.875rem;
            transition: background 0.2s;
        }

        .dropdown-item:hover {
            background: var(--gray-50);
        }

        .dropdown-item.danger {
            color: var(--danger);
        }

        .dropdown-item.danger:hover {
            background: #fef2f2;
        }

        /* Loading */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
        }

        .spinner {
            width: 2rem;
            height: 2rem;
            border: 2px solid var(--gray-300);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--gray-500);
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Items List */
        .items-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .item-card {
            border: 1px solid var(--gray-200);
            border-radius: 6px;
            background: white;
            padding: 1rem;
            cursor: move;
            transition: all 0.2s;
        }

        .item-card:hover {
            border-color: var(--primary);
            background: var(--gray-50);
        }

        .item-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .item-info {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            flex: 1;
        }

        .item-icon {
            width: 32px;
            height: 32px;
            background: var(--primary-light);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .item-details {
            flex: 1;
            min-width: 0;
        }

        .item-title {
            font-weight: 500;
            color: var(--dark);
            margin-bottom: 0.25rem;
            word-break: break-word;
        }

        .item-content {
            font-size: 0.875rem;
            color: var(--gray-600);
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .item-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .item-actions {
            display: flex;
            gap: 0.25rem;
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 1rem;
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        /* Utility Classes */
        .hidden {
            display: none !important;
        }

        .space-y-4>*+* {
            margin-top: 1rem;
        }

        .space-y-6>*+* {
            margin-top: 1.5rem;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 0.75rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .w-full {
            width: 100%;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }

        .text-gray-500 {
            color: var(--gray-500);
        }

        .text-gray-600 {
            color: var(--gray-600);
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 0 1rem;
            }

            .tabs {
                padding: 0 1rem;
                gap: 1rem;
            }

            .tab-content {
                padding: 0 1rem;
            }

            .grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .modal-content {
                max-width: 95%;
            }
        }

        /* Sortable Styles */
        .sortable-ghost {
            opacity: 0.4;
            background: var(--primary-light);
        }

        .sortable-drag {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: rotate(3deg);
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: white;
            border-left: 4px solid var(--success);
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 9999;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            min-width: 300px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.error {
            border-left-color: var(--danger);
        }

        .notification.warning {
            border-left-color: var(--warning);
        }

        .notification.info {
            border-left-color: var(--info);
        }

        .notification-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notification.success .notification-icon {
            color: var(--success);
        }

        .notification.error .notification-icon {
            color: var(--danger);
        }

        .notification.warning .notification-icon {
            color: var(--warning);
        }

        .notification.info .notification-icon {
            color: var(--info);
        }

        .notification-content {
            flex: 1;
        }

        .notification-message {
            font-weight: 500;
            color: var(--gray-800);
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span>CMS</span>
                </div>
                <button class="btn btn-icon btn-secondary sidebar-toggle lg:hidden" id="sidebarClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="sidebar-content">
                <div class="sidebar-title">
                    <span>Pages</span>
                    <span class="badge badge-info" id="pagesCount">0</span>
                </div>

                <div class="pages-list" id="pagesList">
                    <div class="loading" id="pagesLoading">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="header">
                <div class="flex items-center gap-4">
                    <button class="btn btn-icon btn-secondary lg:hidden" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="text-sm text-gray-600" id="currentPageInfo">
                        Select a page to manage
                    </div>
                </div>

                <div class="header-actions">
                    <a href="/" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-external-link-alt"></i>
                        View Site
                    </a>
                    <button class="btn btn-primary" id="addPageBtn">
                        <i class="fas fa-plus"></i>
                        New Page
                    </button>
                </div>
            </header>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab active" data-tab="sections">
                    <i class="fas fa-layer-group"></i>
                    Sections
                </div>
                <div class="tab" data-tab="settings">
                    <i class="fas fa-cog"></i>
                    Settings
                </div>
                <div class="tab" data-tab="preview">
                    <i class="fas fa-eye"></i>
                    Preview
                </div>
            </div>

            <!-- Tab Contents -->
            <div>
                <!-- Sections Tab -->
                <div id="sectionsTab" class="tab-content active">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">
                                    <i class="fas fa-layer-group text-primary"></i>
                                    <span id="pageTitle">Select a page</span>
                                </h2>
                                <p class="text-sm text-gray-500 mt-1" id="pageSlug">Choose from the sidebar</p>
                            </div>
                            <button class="btn btn-primary hidden" id="addSectionBtn">
                                <i class="fas fa-plus"></i>
                                Add Section
                            </button>
                        </div>

                        <div class="card-body">
                            <div id="sectionsList">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <p class="font-semibold text-gray-600">No page selected</p>
                                    <p class="text-sm text-gray-500 mt-1">Select a page from the sidebar to view its sections</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div id="settingsTab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <i class="fas fa-cog text-primary"></i>
                                Page Settings
                            </h2>
                        </div>
                        <div class="card-body">
                            <div id="settingsContent">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <p class="font-semibold text-gray-600">No page selected</p>
                                    <p class="text-sm text-gray-500 mt-1">Select a page to edit its settings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Tab -->
                <div id="previewTab" class="tab-content">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">
                                    <i class="fas fa-eye text-primary"></i>
                                    Page Preview
                                </h2>
                                <p class="text-sm text-gray-500 mt-1">Real-time preview</p>
                            </div>
                            <a href="#" target="_blank" id="previewLink" class="btn btn-primary hidden">
                                <i class="fas fa-external-link-alt"></i>
                                Open Preview
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="border rounded-lg overflow-hidden bg-gray-50">
                                <iframe id="previewFrame" class="w-full h-[600px] border-0" src="about:blank"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Page Modal -->
    <div id="pageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-file-alt"></i>
                    <span id="pageModalTitle">Create New Page</span>
                </h3>
                <button class="btn btn-icon btn-secondary" onclick="closeModal('pageModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="pageForm">
                <div class="modal-body">
                    <input type="hidden" id="pageId" name="id">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Title *</label>
                            <input type="text" id="pageTitleInput" name="title" required class="form-control" placeholder="Enter page title">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Slug *</label>
                            <input type="text" id="pageSlugInput" name="slug" required class="form-control" placeholder="page-slug">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Template</label>
                            <select id="pageTemplate" name="template" class="form-control">
                                <option value="default">Default</option>
                                <option value="legal">Legal</option>
                                <option value="brand">Brand</option>
                                <option value="faq">FAQ</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select id="pageStatus" name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Hero Text</label>
                        <textarea id="pageHeroText" name="hero_text" rows="2" class="form-control" placeholder="Optional hero text"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <div class="form-group">
                            <label class="form-label">Page Sort Order</label>
                            <input type="number" id="pageSortOrder" name="sort_order" class="form-control" min="1" placeholder="SortOrder">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Show In Nav</label>
                            <select id="pageShowInNav" name="show_in_nav" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Meta Title</label>
                            <input type="text" id="pageMetaTitle" name="meta_title" class="form-control" placeholder="SEO title">
                        </div>

                        <div class="form-group">
                            <label class="form-label">OG Image URL</label>
                            <input type="text" id="pageOgImage" name="og_image" class="form-control" placeholder="https://example.com/image.jpg">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Meta Description</label>
                        <textarea id="pageMetaDescription" name="meta_description" rows="2" class="form-control" placeholder="SEO description"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('pageModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Page
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section Modal -->
    <div id="sectionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-layer-group"></i>
                    <span id="sectionModalTitle">Create New Section</span>
                </h3>
                <button class="btn btn-icon btn-secondary" onclick="closeModal('sectionModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="sectionForm">
                <div class="modal-body">
                    <input type="hidden" id="sectionId" name="id">
                    <input type="hidden" id="sectionPageId" name="page_id">

                    <div class="form-group">
                        <label class="form-label">Heading *</label>
                        <input type="text" id="sectionHeading" name="heading" required class="form-control" placeholder="Section heading">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Subheading</label>
                        <input type="text" id="sectionSubheading" name="subheading" class="form-control" placeholder="Optional subheading">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Layout *</label>
                            <select id="sectionLayoutType" name="layout_type" required class="form-control">
                                <option value="text_block">Text Block</option>
                                <option value="accordion">Accordion/FAQ</option>
                                <option value="grid_2_col">2 Column Grid</option>
                                <option value="grid_3_col">3 Column Grid</option>
                                <option value="grid_4_col">4 Column Grid</option>
                                <option value="hero_split">Hero: Split Image/Text</option>
                                <option value="marquee">Scrolling Text Marquee</option>
                                <option value="testimonials">Testimonial Carousel</option>
                                <option value="contact_form">Interactive Contact Form</option>
                                <option value="feature_showcase">Detailed Product Feature</option>
                                <option value="stats_bar">Metric/Statistics Bar</option>
                                <option value="video_embed">Full-Width Video Background</option>
                                <option value="comparison_table">Comparison Table</option>
                            </select>
                        </div>



                        <div class="form-group">
                            <label class="form-label">Theme</label>
                            <select id="sectionBackgroundTheme" name="background_theme" class="form-control">
                                <option value="light">Light</option>
                                <option value="dark">Dark</option>
                                <option value="gradient">Gradient</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">CSS Classes</label>
                        <input type="text" id="sectionCssClasses" name="css_classes" class="form-control" placeholder="py-12 bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4">

                        <div class="form-group">
                            <label class="form-label">Sort Order</label>
                            <input type="number" id="sectionSortOrder" name="sort_order" class="form-control" placeholder="Enter sort order">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Visibility</label>
                            <select id="sectionIsVisible" name="is_visible" class="form-control">
                                <option value="1">Visible</option>
                                <option value="0">Hidden</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('sectionModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Section
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Items Management Modal -->
    <div id="itemsModal" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <div>
                    <h3 class="modal-title">
                        <i class="fas fa-cubes"></i>
                        <span id="itemsModalTitle">Manage Section Items</span>
                    </h3>
                    <p class="text-sm text-gray-500 mt-1" id="itemsModalSubtitle"></p>
                </div>
                <button class="btn btn-icon btn-secondary" onclick="closeModal('itemsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-sm text-gray-600">Drag and drop to reorder items</p>
                    <button class="btn btn-primary" id="addNewItemBtn">
                        <i class="fas fa-plus"></i>
                        Add New Item
                    </button>
                </div>

                <div class="items-list" id="itemsListContainer">
                    <div class="loading" id="itemsLoading">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('itemsModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Item Modal -->
    <div id="itemModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-cube"></i>
                    <span id="itemModalTitle">Create New Item</span>
                </h3>
                <button class="btn btn-icon btn-secondary" onclick="closeModal('itemModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="itemForm">
                <div class="modal-body">
                    <input type="hidden" id="itemId" name="id">
                    <input type="hidden" id="itemSectionId" name="section_id">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Title</label>
                            <input type="text" id="itemTitle" name="title" class="form-control" placeholder="Item title">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Icon</label>
                            <input type="text" id="itemIcon" name="icon" class="form-control" placeholder="fas fa-star">
                            <p class="text-xs text-gray-500 mt-1">Font Awesome icon class</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Content</label>
                        <textarea id="itemContent" name="content" rows="3" class="form-control" placeholder="Item content"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">Image URL</label>
                            <div class="flex gap-2">
                                <input type="text" id="itemImageUrl" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                                <button type="button" class="btn btn-secondary" onclick="openMediaUpload('itemImageUrl')">
                                    <i class="fas fa-upload"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Width</label>
                            <select id="itemWidth" name="width" class="form-control">
                                <option value="full">Full Width</option>
                                <option value="half">Half</option>
                                <option value="third">One Third</option>
                                <option value="quarter">One Quarter</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-group">
                            <label class="form-label">CTA Label</label>
                            <input type="text" id="itemCtaLabel" name="cta_label" class="form-control" placeholder="Learn more">
                        </div>

                        <div class="form-group">
                            <label class="form-label">CTA Link</label>
                            <input type="text" id="itemCtaLink" name="cta_link" class="form-control" placeholder="https://example.com">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sort Order</label>
                        <input type="number" id="itemSortOrder" name="sort_order" class="form-control" placeholder="Sort order">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('itemModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Media Upload Modal -->
    <div id="mediaModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-upload"></i>
                    Upload Media
                </h3>
                <button class="btn btn-icon btn-secondary" onclick="closeModal('mediaModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="mediaForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Select Image</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center cursor-pointer hover:border-primary transition-colors"
                            onclick="document.getElementById('mediaFile').click()">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="font-medium text-gray-600 mb-2">Click to upload or drag and drop</p>
                            <p class="text-sm text-gray-500">PNG, JPG, GIF up to 5MB</p>
                            <input type="file" id="mediaFile" name="image" accept="image/*" class="hidden" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <select id="mediaType" name="type" class="form-control" required>
                            <option value="item">Item Image</option>
                            <option value="section">Section Background</option>
                            <option value="og">OG Image</option>
                        </select>
                    </div>

                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200 hidden" id="mediaPreview">
                        <p class="text-sm text-blue-700">Image will appear here after upload</p>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('mediaModal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i>
                        Upload Image
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content" style="max-width: 400px;">
            <div class="modal-body">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2" id="confirmTitle">Confirm Delete</h3>
                    <p class="text-gray-600 mb-6" id="confirmMessage">Are you sure you want to delete this item?</p>

                    <div class="flex justify-center gap-3">
                        <button class="btn btn-secondary" onclick="closeModal('confirmModal')">Cancel</button>
                        <button class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification">
        <div class="notification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notification-content">
            <p class="notification-message" id="notificationMessage"></p>
        </div>
        <button class="text-gray-400 hover:text-gray-600" onclick="document.getElementById('notification').classList.remove('show')">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
        <div class="text-center">
            <div class="spinner mb-4"></div>
            <p class="text-gray-600">Loading...</p>
        </div>
    </div>

    <script>
        // Global variables
        let currentPage = null;
        let currentSection = null;
        let currentItems = [];
        let allPages = [];
        let deleteCallback = null;
        let mediaTargetField = null;

        // CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();
        });

        function initializeApp() {
            setupEventListeners();
            loadPages();
        }

        function setupEventListeners() {
            // Sidebar toggle
            document.getElementById('sidebarToggle').addEventListener('click', toggleSidebar);
            document.getElementById('sidebarClose').addEventListener('click', toggleSidebar);
            document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);

            // Tabs
            document.querySelectorAll('.tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.dataset.tab;
                    switchTab(tabId);
                });
            });

            // Page form
            document.getElementById('pageForm').addEventListener('submit', handlePageSubmit);
            document.getElementById('sectionForm').addEventListener('submit', handleSectionSubmit);
            document.getElementById('itemForm').addEventListener('submit', handleItemSubmit);
            document.getElementById('mediaForm').addEventListener('submit', handleMediaSubmit);

            // Add buttons
            document.getElementById('addPageBtn').addEventListener('click', () => openPageModal());
            document.getElementById('addSectionBtn').addEventListener('click', () => openSectionModal());
            document.getElementById('addNewItemBtn').addEventListener('click', () => openItemModal());

            // Auto-generate slug
            const pageTitleInput = document.getElementById('pageTitleInput');
            if (pageTitleInput) {
                pageTitleInput.addEventListener('input', function() {
                    const slug = this.value.toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-|-$/g, '');
                    document.getElementById('pageSlugInput').value = slug;
                });
            }

            // File input change
            const mediaFile = document.getElementById('mediaFile');
            if (mediaFile) {
                mediaFile.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const preview = document.getElementById('mediaPreview');
                            preview.innerHTML = `
                                <img src="${e.target.result}" class="max-w-full h-auto rounded" alt="Preview">
                                <p class="text-sm text-gray-600 mt-2">${file.name} (${(file.size / 1024).toFixed(1)} KB)</p>
                            `;
                            preview.classList.remove('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function switchTab(tabId) {
            // Update active tab
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.toggle('active', tab.dataset.tab === tabId);
            });

            // Show active content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.toggle('active', content.id === tabId + 'Tab');
            });

            // Load content if needed
            if (tabId === 'preview' && currentPage) {
                loadPreview();
            } else if (tabId === 'settings' && currentPage) {
                loadPageSettings();
            }
        }

        // Loading functions
        function showLoading() {
            document.getElementById('loadingOverlay').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.add('hidden');
        }

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = '';

            // Reset media preview
            const mediaPreview = document.getElementById('mediaPreview');
            if (mediaPreview) {
                mediaPreview.classList.add('hidden');
                mediaPreview.innerHTML = '<p class="text-sm text-blue-700">Image will appear here after upload</p>';
            }

            // Reset media file input
            const mediaFile = document.getElementById('mediaFile');
            if (mediaFile) {
                mediaFile.value = '';
            }
        }

        // Notification
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            const icon = notification.querySelector('.notification-icon i');
            const messageEl = document.getElementById('notificationMessage');

            messageEl.textContent = message;

            // Remove all type classes
            notification.classList.remove('success', 'error', 'warning', 'info');
            notification.classList.add(type);

            // Set icon
            switch (type) {
                case 'success':
                    icon.className = 'fas fa-check-circle';
                    break;
                case 'error':
                    icon.className = 'fas fa-exclamation-circle';
                    break;
                case 'warning':
                    icon.className = 'fas fa-exclamation-triangle';
                    break;
                case 'info':
                    icon.className = 'fas fa-info-circle';
                    break;
            }

            notification.classList.add('show');

            // Auto hide after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Load pages
        function loadPages() {
            showLoading();
            fetch('{{ route("admin.cms.getData") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        allPages = data.pages;
                        renderPagesList(data.pages);
                    } else {
                        showNotification(data.message || 'Failed to load pages', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Network error: ' + error.message, 'error');
                })
                .finally(() => {
                    hideLoading();
                });
        }

        function renderPagesList(pages) {
            const container = document.getElementById('pagesList');
            const countElement = document.getElementById('pagesCount');

            if (!pages || pages.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <p class="font-semibold text-gray-600">No pages found</p>
                        <p class="text-sm text-gray-500 mt-1">Create your first page to get started</p>
                        <button class="btn btn-primary mt-4" onclick="openPageModal()">
                            <i class="fas fa-plus"></i>
                            Create First Page
                        </button>
                    </div>
                `;
                countElement.textContent = '0';
                return;
            }

            container.innerHTML = '';
            pages.forEach(page => {
                const pageElement = document.createElement('div');
                pageElement.className = `page-item ${currentPage?.id === page.id ? 'active' : ''}`;
                pageElement.dataset.id = page.id;
                pageElement.innerHTML = `
                    <div onclick="selectPage(${page.id})">
                        <div class="page-item-header">
                            <div class="page-title">
                                <i class="fas fa-file ${page.status ? 'text-primary' : 'text-gray-400'}"></i>
                                ${escapeHtml(page.title)}
                            </div>
                            <div class="page-actions">
                                <span class="badge ${page.status ? 'badge-success' : 'badge-warning'}">
                                    ${page.status ? 'Active' : 'Inactive'}
                                </span>
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-sm btn-secondary" onclick="event.stopPropagation(); toggleDropdown(this)">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-item" onclick="event.stopPropagation(); editPage(${page.id})">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </div>
                                        <div class="dropdown-item danger" onclick="event.stopPropagation(); deletePage(${page.id})">
                                            <i class="fas fa-trash"></i>
                                            Delete
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="page-slug">/${escapeHtml(page.slug)}</div>
                    </div>
                `;
                container.appendChild(pageElement);
            });

            countElement.textContent = pages.length;

            // Initialize Sortable for pages
            new Sortable(container, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    const pages = Array.from(evt.from.children).map((child, index) => ({
                        id: child.dataset.id,
                        sort_order: index
                    }));

                    updateOrder('pages', pages);
                }
            });
        }

        function toggleDropdown(button) {
            const menu = button.closest('.dropdown').querySelector('.dropdown-menu');
            const isOpen = menu.classList.contains('show');

            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('show'));

            // Toggle current dropdown
            if (!isOpen) {
                menu.classList.add('show');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        });

        function selectPage(pageId) {
            const page = allPages.find(p => p.id == pageId);
            if (!page) return;

            currentPage = page;

            // Update UI
            document.getElementById('pageTitle').textContent = `Sections: ${escapeHtml(page.title)}`;
            document.getElementById('pageSlug').textContent = `/${page.slug}`;
            document.getElementById('currentPageInfo').textContent = `Editing: ${escapeHtml(page.title)}`;
            document.getElementById('addSectionBtn').classList.remove('hidden');
            document.getElementById('previewLink').classList.remove('hidden');
            document.getElementById('previewLink').href = `/admin/cms/preview/${page.id}`;

            // Update active page in sidebar
            document.querySelectorAll('.page-item').forEach(item => {
                item.classList.toggle('active', item.dataset.id == pageId);
            });

            // Load sections
            loadSections(pageId);

            // Switch to sections tab
            switchTab('sections');
        }

        function loadSections(pageId) {
            showLoading();
            fetch(`/admin/cms/pages/${pageId}/sections`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderSectionsList(data.sections);
                    } else {
                        showNotification('Failed to load sections', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Network error', 'error');
                })
                .finally(() => {
                    hideLoading();
                });
        }

        function renderSectionsList(sections) {
            const container = document.getElementById('sectionsList');

            if (!sections || sections.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <p class="font-semibold text-gray-600">No sections found</p>
                        <p class="text-sm text-gray-500 mt-1">Create your first section for this page</p>
                        <button class="btn btn-primary mt-4" onclick="openSectionModal()">
                            <i class="fas fa-plus"></i>
                            Create First Section
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            sections.forEach(section => {
                const itemsCount = section.items_count || section.items?.length || 0;
                const sectionElement = document.createElement('div');
                sectionElement.className = 'section-item';
                sectionElement.dataset.id = section.id;
                sectionElement.innerHTML = `
                    <div class="section-header">
                        <div class="section-info">
                            <div class="section-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="section-details">
                                <div class="section-title">${escapeHtml(section.heading)}</div>
                                ${section.subheading ? `<div class="section-subtitle">${escapeHtml(section.subheading)}</div>` : ''}
                                <div class="section-meta">
                                    <span class="badge ${section.is_visible ? 'badge-info' : 'badge-warning'}">
                                        ${section.is_visible ? 'Visible' : 'Hidden'}
                                    </span>
                                    <span class="text-sm text-gray-500">${section.layout_type.replace('_', ' ')}</span>
                                    <span class="text-sm text-gray-500">${section.background_theme} theme</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="btn btn-sm btn-secondary" onclick="openItemsModal(${section.id})">
                                <i class="fas fa-list"></i>
                                Items (${itemsCount})
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-icon btn-sm btn-secondary" onclick="event.stopPropagation(); toggleDropdown(this)">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <div class="dropdown-item" onclick="event.stopPropagation(); editSection(${section.id})">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </div>
                                    <div class="dropdown-item danger" onclick="event.stopPropagation(); deleteSection(${section.id})">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(sectionElement);
            });

            // Initialize Sortable for sections
            new Sortable(container, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    const sections = Array.from(evt.from.children).map((child, index) => ({
                        id: child.dataset.id,
                        sort_order: index
                    }));

                    updateOrder('sections', sections);
                }
            });
        }

        function openPageModal(page = null) {
            const modal = document.getElementById('pageModal');
            const title = document.getElementById('pageModalTitle');
            const form = document.getElementById('pageForm');

            if (page) {
                title.textContent = 'Edit Page';
                document.getElementById('pageId').value = page.id;
                document.getElementById('pageTitleInput').value = page.title;
                document.getElementById('pageSlugInput').value = page.slug;
                document.getElementById('pageTemplate').value = page.template || 'default';
                document.getElementById('pageStatus').value = page.status ? '1' : '0';
                document.getElementById('pageHeroText').value = page.hero_text || '';
                document.getElementById('pageShowInNav').value = page.show_in_nav ? '1' : '0';
                document.getElementById('pageSortOrder').value = page.sort_order || '';
                document.getElementById('pageMetaTitle').value = page.meta_title || '';
                document.getElementById('pageMetaDescription').value = page.meta_description || '';
                document.getElementById('pageOgImage').value = page.og_image || '';
            } else {
                title.textContent = 'Create New Page';
                form.reset();
                document.getElementById('pageId').value = '';
                document.getElementById('pageStatus').value = '1';
                document.getElementById('pageTemplate').value = 'default';
            }

            openModal('pageModal');
        }

        async function handlePageSubmit(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const pageId = formData.get('id');
            const isEdit = !!pageId;

            showLoading();

            const url = isEdit ? `/admin/cms/pages/${pageId}` : '/admin/cms/pages';
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showNotification(data.message);
                    closeModal('pageModal');
                    loadPages();

                    if (isEdit && currentPage?.id == pageId) {
                        selectPage(pageId);
                    }
                } else if (data.errors) {
                    showNotification(Object.values(data.errors).flat().join(', '), 'error');
                } else {
                    showNotification(data.message || 'Failed to save page', 'error');
                }
            } catch (error) {
                showNotification('Network error: ' + error.message, 'error');
            } finally {
                hideLoading();
            }
        }

        async function editPage(pageId) {
            showLoading();
            try {
                const response = await fetch(`/admin/cms/pages/${pageId}`);
                const data = await response.json();

                if (data.status === 'success') {
                    openPageModal(data.page);
                } else {
                    showNotification('Failed to load page', 'error');
                }
            } catch (error) {
                showNotification('Network error', 'error');
            } finally {
                hideLoading();
            }
        }

        function deletePage(pageId) {
            deleteCallback = () => {
                showLoading();
                fetch(`/admin/cms/pages/${pageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showNotification(data.message);
                            loadPages();

                            if (currentPage?.id == pageId) {
                                currentPage = null;
                                document.getElementById('sectionsList').innerHTML = `
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-layer-group"></i>
                                    </div>
                                    <p class="font-semibold text-gray-600">No page selected</p>
                                    <p class="text-sm text-gray-500 mt-1">Select a page from the sidebar to view its sections</p>
                                </div>
                            `;
                                document.getElementById('addSectionBtn').classList.add('hidden');
                                document.getElementById('pageTitle').textContent = 'Select a page';
                                document.getElementById('pageSlug').textContent = 'Choose from the sidebar';
                                document.getElementById('currentPageInfo').textContent = 'Select a page to manage';
                            }
                        } else {
                            showNotification(data.message || 'Failed to delete page', 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('Network error', 'error');
                    })
                    .finally(() => {
                        hideLoading();
                    });
            };

            document.getElementById('confirmTitle').textContent = 'Delete Page';
            document.getElementById('confirmMessage').textContent = 'Are you sure you want to delete this page? All sections and items will also be deleted.';
            openModal('confirmModal');
        }

        function openSectionModal(section = null) {
            if (!currentPage) {
                showNotification('Please select a page first', 'error');
                return;
            }

            const modal = document.getElementById('sectionModal');
            const title = document.getElementById('sectionModalTitle');
            const form = document.getElementById('sectionForm');

            if (section) {
                title.textContent = 'Edit Section';
                document.getElementById('sectionId').value = section.id;
                document.getElementById('sectionHeading').value = section.heading;
                document.getElementById('sectionSubheading').value = section.subheading || '';
                document.getElementById('sectionLayoutType').value = section.layout_type;
                document.getElementById('sectionBackgroundTheme').value = section.background_theme;
                document.getElementById('sectionCssClasses').value = section.css_classes || '';
                document.getElementById('sectionSortOrder').value = section.sort_order || '';
                document.getElementById('sectionIsVisible').value = section.is_visible ? '1' : '0';
            } else {
                title.textContent = 'Create New Section';
                form.reset();
                document.getElementById('sectionId').value = '';
                document.getElementById('sectionIsVisible').value = '1';
                document.getElementById('sectionLayoutType').value = 'text_block';
                document.getElementById('sectionBackgroundTheme').value = 'light';
            }

            document.getElementById('sectionPageId').value = currentPage.id;
            openModal('sectionModal');
        }

        async function handleSectionSubmit(e) {
            e.preventDefault();

            if (!currentPage) {
                showNotification('No page selected', 'error');
                return;
            }

            const form = e.target;
            const formData = new FormData(form);
            const sectionId = formData.get('id');
            const isEdit = !!sectionId;

            showLoading();

            const url = isEdit ? `/admin/cms/sections/${sectionId}` : '/admin/cms/sections';
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showNotification(data.message);
                    closeModal('sectionModal');
                    loadSections(currentPage.id);
                } else if (data.errors) {
                    showNotification(Object.values(data.errors).flat().join(', '), 'error');
                } else {
                    showNotification(data.message || 'Failed to save section', 'error');
                }
            } catch (error) {
                showNotification('Network error: ' + error.message, 'error');
            } finally {
                hideLoading();
            }
        }

        async function editSection(sectionId) {
            showLoading();
            try {
                const response = await fetch(`/admin/cms/sections/${sectionId}`);
                const data = await response.json();

                if (data.status === 'success') {
                    openSectionModal(data.section);
                } else {
                    showNotification('Failed to load section', 'error');
                }
            } catch (error) {
                showNotification('Network error', 'error');
            } finally {
                hideLoading();
            }
        }

        function deleteSection(sectionId) {
            deleteCallback = () => {
                showLoading();
                fetch(`/admin/cms/sections/${sectionId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showNotification(data.message);
                            loadSections(currentPage.id);
                        } else {
                            showNotification(data.message || 'Failed to delete section', 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('Network error', 'error');
                    })
                    .finally(() => {
                        hideLoading();
                    });
            };

            document.getElementById('confirmTitle').textContent = 'Delete Section';
            document.getElementById('confirmMessage').textContent = 'Are you sure you want to delete this section? All items in this section will also be deleted.';
            openModal('confirmModal');
        }

        // ITEMS MANAGEMENT FUNCTIONS
        function openItemsModal(sectionId) {
            showLoading();
            fetch(`/admin/cms/sections/${sectionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        currentSection = data.section;
                        document.getElementById('itemsModalTitle').textContent = `Items: ${escapeHtml(data.section.heading)}`;
                        document.getElementById('itemsModalSubtitle').textContent = `Manage items for "${escapeHtml(data.section.heading)}" section`;
                        openModal('itemsModal');
                        loadItems(sectionId);
                    } else {
                        showNotification('Failed to load section details', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Network error', 'error');
                })
                .finally(() => {
                    hideLoading();
                });
        }

        function loadItems(sectionId) {
            showLoading();
            fetch(`/admin/cms/sections/${sectionId}/items`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        currentItems = data.items;
                        renderItemsList(data.items);
                    } else {
                        showNotification('Failed to load items', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Network error', 'error');
                })
                .finally(() => {
                    hideLoading();
                });
        }

        function renderItemsList(items) {
            const container = document.getElementById('itemsListContainer');

            if (!items || items.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <p class="font-semibold text-gray-600">No items found</p>
                        <p class="text-sm text-gray-500 mt-1">Add your first item to this section</p>
                        <button class="btn btn-primary mt-4" onclick="openItemModal()">
                            <i class="fas fa-plus"></i>
                            Add First Item
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            items.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'item-card';
                itemElement.dataset.id = item.id;

                // Truncate content for preview
                const contentPreview = item.content ?
                    (item.content.length > 100 ? item.content.substring(0, 100) + '...' : item.content) :
                    'No content';

                itemElement.innerHTML = `
                    <div class="item-header">
                        <div class="item-info">
                            ${item.icon ? `<div class="item-icon"><i class="${escapeHtml(item.icon)}"></i></div>` : '<div class="item-icon"><i class="fas fa-cube"></i></div>'}
                            <div class="item-details">
                                <div class="item-title">${item.title ? escapeHtml(item.title) : '<span class="text-gray-400">Untitled Item</span>'}</div>
                                <div class="item-content">${escapeHtml(contentPreview)}</div>
                                <div class="item-meta">
                                    ${item.width ? `<span class="bg-gray-100 px-2 py-1 rounded text-xs">${item.width}</span>` : ''}
                                    ${item.cta_label ? `<span class="text-gray-500"><i class="fas fa-link mr-1"></i>${escapeHtml(item.cta_label)}</span>` : ''}
                                    ${item.image_url ? `<span class="text-gray-500"><i class="fas fa-image mr-1"></i>Has image</span>` : ''}
                                </div>
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-sm btn-secondary" onclick="event.stopPropagation(); toggleDropdown(this)">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <div class="dropdown-item" onclick="event.stopPropagation(); editItem(${item.id})">
                                        <i class="fas fa-edit"></i>
                                        Edit
                                    </div>
                                    <div class="dropdown-item danger" onclick="event.stopPropagation(); deleteItem(${item.id})">
                                        <i class="fas fa-trash"></i>
                                        Delete
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(itemElement);
            });

            // Initialize Sortable for items
            new Sortable(container, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: function(evt) {
                    const items = Array.from(evt.from.children).map((child, index) => ({
                        id: child.dataset.id,
                        sort_order: index
                    }));

                    updateOrder('items', items);
                }
            });
        }

        function openItemModal(item = null) {
            if (!currentSection) {
                showNotification('Please select a section first', 'error');
                return;
            }

            const modal = document.getElementById('itemModal');
            const title = document.getElementById('itemModalTitle');
            const form = document.getElementById('itemForm');

            if (item) {
                title.textContent = 'Edit Item';
                document.getElementById('itemId').value = item.id;
                document.getElementById('itemTitle').value = item.title || '';
                document.getElementById('itemContent').value = item.content || '';
                document.getElementById('itemIcon').value = item.icon || '';
                document.getElementById('itemImageUrl').value = item.image_url || '';
                document.getElementById('itemCtaLabel').value = item.cta_label || '';
                document.getElementById('itemCtaLink').value = item.cta_link || '';
                document.getElementById('itemWidth').value = item.width || 'full';
                document.getElementById('itemSortOrder').value = item.sort_order || '';
            } else {
                title.textContent = 'Create New Item';
                form.reset();
                document.getElementById('itemId').value = '';
                document.getElementById('itemWidth').value = 'full';
            }

            document.getElementById('itemSectionId').value = currentSection.id;
            closeModal('itemsModal');
            openModal('itemModal');
        }

        async function editItem(itemId) {
            showLoading();
            try {
                const response = await fetch(`/admin/cms/items/${itemId}`);
                const data = await response.json();

                if (data.status === 'success') {
                    openItemModal(data.item);
                } else {
                    showNotification('Failed to load item', 'error');
                }
            } catch (error) {
                showNotification('Network error', 'error');
            } finally {
                hideLoading();
            }
        }

        async function handleItemSubmit(e) {
            e.preventDefault();

            if (!currentSection) {
                showNotification('No section selected', 'error');
                return;
            }

            const form = e.target;
            const formData = new FormData(form);
            const itemId = formData.get('id');
            const isEdit = !!itemId;

            showLoading();

            const url = isEdit ? `/admin/cms/items/${itemId}` : '/admin/cms/items';
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const data = await response.json();

                if (data.status === 'success') {
                    showNotification(data.message);
                    closeModal('itemModal');
                    loadItems(currentSection.id);
                    openModal('itemsModal'); // Re-open items modal
                } else if (data.errors) {
                    showNotification(Object.values(data.errors).flat().join(', '), 'error');
                } else {
                    showNotification(data.message || 'Failed to save item', 'error');
                }
            } catch (error) {
                showNotification('Network error: ' + error.message, 'error');
            } finally {
                hideLoading();
            }
        }

        function deleteItem(itemId) {
            deleteCallback = () => {
                showLoading();
                fetch(`/admin/cms/items/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            showNotification(data.message);
                            loadItems(currentSection.id);
                        } else {
                            showNotification(data.message || 'Failed to delete item', 'error');
                        }
                    })
                    .catch(error => {
                        showNotification('Network error', 'error');
                    })
                    .finally(() => {
                        hideLoading();
                    });
            };

            document.getElementById('confirmTitle').textContent = 'Delete Item';
            document.getElementById('confirmMessage').textContent = 'Are you sure you want to delete this item? This action cannot be undone.';
            openModal('confirmModal');
        }

        // Media Upload
        function openMediaUpload(targetFieldId) {
            mediaTargetField = targetFieldId;
            openModal('mediaModal');
        }

        async function handleMediaSubmit(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            showLoading();

            try {
                const response = await fetch('{{ route("admin.cms.media.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.status === 'success') {
                    // Update the target field with the uploaded image URL
                    if (mediaTargetField) {
                        const targetField = document.getElementById(mediaTargetField);
                        if (targetField) {
                            targetField.value = data.url;
                            showNotification('Image uploaded successfully!');
                        }
                    }
                    closeModal('mediaModal');
                } else {
                    showNotification(data.message || 'Failed to upload image', 'error');
                }
            } catch (error) {
                showNotification('Network error: ' + error.message, 'error');
            } finally {
                hideLoading();
            }
        }

        function loadPreview() {
            if (!currentPage) return;

            const previewFrame = document.getElementById('previewFrame');
            previewFrame.src = `/admin/cms/preview/${currentPage.id}`;
        }

        function loadPageSettings() {
            if (!currentPage) return;

            const container = document.getElementById('settingsContent');
            container.innerHTML = `
                <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-cog text-blue-600 text-xl mr-3"></i>
                        <h4 class="font-semibold text-gray-800">Page Settings: ${escapeHtml(currentPage.title)}</h4>
                    </div>
                    <div class="bg-white p-5 rounded-lg border">
                        <p class="text-gray-600 mb-4">Edit page settings using the "Edit" button in the pages list.</p>
                        <button onclick="editPage(${currentPage.id})" class="btn btn-primary">
                            <i class="fas fa-edit"></i>
                            Edit Page Settings
                        </button>
                    </div>
                </div>
            `;
        }

        function updateOrder(type, items) {
            fetch(`/admin/cms/${type}/order`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        [type]: items
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'success') {
                        showNotification('Failed to update order', 'error');
                    }
                })
                .catch(() => {
                    showNotification('Network error', 'error');
                });
        }

        function confirmDelete() {
            if (deleteCallback) {
                deleteCallback();
            }
            closeModal('confirmModal');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>

</html>
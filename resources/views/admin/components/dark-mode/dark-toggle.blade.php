<button id="themeToggle"
    class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:scale-105 transition">
    <i class="fas fa-moon hidden dark:inline"></i>
    <i class="fas fa-sun dark:hidden"></i>
</button>

<script>
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;

    // Load saved mode
    if (localStorage.theme === 'dark') {
        html.classList.add('dark');
    }

    themeToggle.addEventListener('click', () => {
        html.classList.toggle('dark');
        if (html.classList.contains('dark')) {
            localStorage.theme = 'dark';
        } else {
            localStorage.theme = 'light';
        }
    });
</script>

<style>
    /* 🌙 Global Dark Mode Overrides */
html.dark body {
    background-color: #1F2937; /* dark gray */
    color: #F9FAFB; /* light text */
}

/* Common containers */
html.dark .bg-white {
    background-color: #1F2937 !important;
}
html.dark .bg-gray-50,
html.dark .bg-gradient-to-br {
    background: #111827 !important;
}

html.p {
    background-color: #ffffff; /* light gray */
    color: #1F2937; /* dark text */
}

/* Text */
html.dark .text-gray-800 {
    color: #F9FAFB !important;
}
html.dark .text-gray-700 {
    color: #E5E7EB !important;
}
html.dark .text-gray-500 {
    color: #9CA3AF !important;
}

/* Borders */
html.dark .border,
html.dark .border-gray-200,
html.dark .border-gray-300 {
    border-color: #374151 !important;
}

/* Shadow */
html.dark .shadow,
html.dark .shadow-sm {
    box-shadow: none !important;
}

/* Table */
html.dark table thead {
    background-color: #374151 !important;
}
html.dark table tbody tr {
    background-color: #1F2937 !important;
}
html.dark table tbody tr:hover {
    background-color: #111827 !important;
}

/* Sidebar */
html.dark aside {
    background-color: #111827 !important;
    color: #F9FAFB !important;
}
html.dark aside a {
    color: #F9FAFB !important;
}
html.dark aside a:hover {
    background-color: #374151 !important;
}
html.dark aside .active {
    background-color: #2563EB !important; /* blue */
    color: #FFFFFF !important;
}
html.dark aside .active:hover {
    background-color: #1D4ED8 !important; /* darker blue */
}
/* Buttons */
html.dark .bg-blue-500 {
    background-color: #2563EB !important; /* blue */
}
html.dark .bg-blue-500:hover {
    background-color: #1D4ED8 !important; /* darker blue */
}
html.dark .bg-green-500 {
    background-color: #16A34A !important; /* green */
}
html.dark .bg-green-500:hover {
    background-color: #15803D !important; /* darker green */
}
html.dark .bg-red-500 {
    background-color: #DC2626 !important; /* red */
}
html.dark .bg-red-500:hover {
    background-color: #B91C1C !important; /* darker red */
}
html.dark .bg-yellow-500 {
    background-color: #D97706 !important; /* yellow */
}
html.dark .bg-yellow-500:hover {
    background-color: #B45309 !important; /* darker yellow */
}
html.dark .bg-gray-200 {
    background-color: #374151 !important; /* dark gray */
}
html.dark .bg-gray-200:hover {
    background-color: #4B5563 !important; /* darker gray */
}
html.dark .text-white {
    color: #F9FAFB !important;
}
html.dark .text-gray-600 {
    color: #9CA3AF !important;
}
html.dark .text-gray-400 {
    color: #6B7280 !important;
}
html.dark .hover\:bg-gray-100:hover {
    background-color: #374151 !important; /* dark gray */
}
html.dark .hover\:bg-gray-200:hover {
    background-color: #4B5563 !important; /* darker gray */
}

/* Inputs */
html.dark input,
html.dark select,
html.dark textarea {
    background-color: #1F2937 !important;
    color: #F9FAFB !important;
    border-color: #374151 !important;
}
html.dark input::placeholder,
html.dark textarea::placeholder {
    color: #9CA3AF !important;
}
html.dark input:focus,
html.dark select:focus,
html.dark textarea:focus {
    border-color: #2563EB !important; /* blue */
    box-shadow: 0 0 0 1px #2563EB !important;
    outline: none !important;
}

/* Modals */
html.dark .modal-content {
    background-color: #1F2937 !important;
    color: #F9FAFB !important;
}
html.dark .modal-header,

html.dark .modal-footer {
    border-color: #374151 !important;
}
html.dark .modal-close {
    color: #F9FAFB !important;
}
html.dark .modal-close:hover {
    color: #FFFFFF !important;
}
/* Tooltips */
html.dark .tooltip {
    background-color: #374151 !important;
    color: #F9FAFB !important;
}
html.dark .tooltip::after {
    border-top-color: #374151 !important;
}
/* Notifications */
html.dark .notification {
    background-color: #1F2937 !important;
    color: #F9FAFB !important;
    border-color: #374151 !important;
}
html.dark .notification.success {
    border-left-color: #16A34A !important; /* green */
}
html.dark .notification.error {
    border-left-color: #DC2626 !important; /* red */
}
html.dark .notification.info {
    border-left-color: #2563EB !important; /* blue */
}
html.dark .notification.warning {
    border-left-color: #D97706 !important; /* yellow */
}
/* Pagination */
html.dark .pagination a {
    background-color: #374151 !important;
    color: #F9FAFB !important;
    border-color: #4B5563 !important;
}
html.dark .pagination a:hover {
    background-color: #4B5563 !important;
    border-color: #6B7280 !important;
}

/* Cards */


html.dark .card {
    background-color: #1F2937 !important;
    color: #F9FAFB !important;
    border-color: #374151 !important;
}
html.dark .card-header {
    border-bottom-color: #374151 !important;
}
html.dark .card-footer {
    border-top-color: #374151 !important;
}
/* Alerts */
html.dark .alert {
    background-color: #1F2937 !important;
    color: #F9FAFB !important;
    border-color: #374151 !important;
}
html.dark .alert.success {
    border-left-color: #16A34A !important; /* green */
}
html.dark .alert.error {
    border-left-color: #DC2626 !important; /* red */
}
html.dark .alert.info {
    border-left-color: #2563EB !important; /* blue */
}
html.dark .alert.warning {
    border-left-color: #D97706 !important; /* yellow */
}
/* Forms */
html.dark .form-label {
    color: #F9FAFB !important;
}
html.dark .form-helper-text {
    color: #9CA3AF !important;
}
html.dark .form-error-text {
    color: #F87171 !important; /* red-400 */
}
/* Buttons */
html.dark .btn {
    background-color: #374151 !important;
    color: #F9FAFB !important;
    border-color: #4B5563 !important;
}
html.dark .btn:hover {
    background-color: #4B5563 !important;
    border-color: #6B7280 !important;
}
/* Links */
html.dark a {
    color: #2563EB !important; /* blue */
}


html.dark h1, 
html.dark h2, 
html.dark h3, 
html.dark h4, 
html.dark h5, 
html.dark h6 {
    color: #F9FAFB !important;
}

html.dark p, 
html.dark span, 
html.dark li, 
html.dark label {
    color: #D1D5DB !important; /* gray-300 */
}
html.dark i, 
html.dark svg {
    color: #D1D5DB !important;
}
html.dark i.text-blue-500,
html.dark svg.text-blue-500 {
    color: #3B82F6 !important; /* keep accent colors */
}
html.dark i.text-green-500,
html.dark svg.text-green-500 {  
    color: #10B981 !important;
}
html.dark i.text-red-500,
html.dark svg.text-red-500 {
    color: #EF4444 !important;

}
html.dark i.text-yellow-500,
html.dark svg.text-yellow-500 {
    color: #F59E0B !important;

}
html.dark .status-badge {
    background-color: #374151 !important;
    color: #F9FAFB !important;
}
html.dark .status-badge.bg-green-100 {
    background-color: #065F46 !important; /* dark green */
    color: #A7F3D0 !important;
}
html.dark .status-badge.bg-yellow-100 {
    background-color: #78350F !important; /* dark yellow */
    color: #FDE68A !important;
}
html.dark .status-badge.bg-red-100 {
    background-color: #7F1D1D !important; /* dark red */
    color: #FCA5A5 !important;
}
html.dark .status-badge.bg-blue-100 {
    background-color: #1E3A8A !important; /* dark blue */
    color: #93C5FD !important;
}


html.dark input[type="search"],
html.dark .filter-box,
html.dark .filter-group {
    background-color: #1F2937 !important;
    color: #F9FAFB !important;
    border-color: #374151 !important;
}
html.dark input[type="search"]::placeholder {
    color: #9CA3AF !important;
}
html.dark .filter-box .filter-option:hover,
html.dark .filter-group .filter-option:hover {
    background-color: #374151 !important;
}
html.dark table th {
    background-color: #1F2937 !important;
    color: #E5E7EB !important;
}
html.dark table td {
    background-color: #111827 !important;
    color: #D1D5DB !important;
}
html.dark table tr:nth-child(even) td {
    background-color: #1F2937 !important;
}
html.dark table tr:hover td {
    background-color: #374151 !important;
}
html.dark .btn-primary,
html.dark .bg-blue-600 {
    background-color: #2563EB !important;
    color: #fff !important;
}
html.dark .btn-primary:hover {
    background-color: #1D4ED8 !important;
}
html.dark .offer-badge {
    background-color: #4B5563 !important;
    color: #F9FAFB !important;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 0.75rem;
}
html.dark .offer-badge.sale {
    background-color: #DC2626 !important; /* red */
}
</style>

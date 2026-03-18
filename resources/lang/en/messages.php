<?php

return [
    /*
    |--------------------------------------------------------------------------
    | General Messages
    |--------------------------------------------------------------------------
    */
    'app_name' => 'Equipment Management',
    'dashboard' => 'Dashboard',
    'search' => 'Search...',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'view' => 'View',
    'create' => 'Create',
    'add' => 'Add',
    'update' => 'Update',
    'back' => 'Back',
    'confirm' => 'Confirm',
    'close' => 'Close',
    'actions' => 'Actions',
    'status' => 'Status',
    'date' => 'Date',
    'time' => 'Time',
    'description' => 'Description',
    'note' => 'Note',
    'yes' => 'Yes',
    'no' => 'No',
    'all' => 'All',
    'none' => 'None',
    'loading' => 'Loading...',
    'processing' => 'Processing...',
    'success' => 'Success',
    'error' => 'Error',
    'warning' => 'Warning',
    'info' => 'Info',

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */
    'nav' => [
        'dashboard' => 'Dashboard',
        'equipment' => 'Equipment',
        'borrow' => 'Borrow Records',
        'reservations' => 'Reservations',
        'ai_assistant' => 'AI Assistant',
        'teaching_plans' => 'Teaching Plans',
        'admin' => 'Administration',
        'rooms' => 'Rooms/Warehouse',
        'users' => 'Users',
        'departments' => 'Departments',
        'inventory' => 'Inventory',
        'approvals' => 'Approvals',
        'reports' => 'Reports',
        'activity_logs' => 'Activity Logs',
        'maintenance' => 'Maintenance',
        'import' => 'Import Equipment',
        'damage_reports' => 'Damage Reports',
    ],

    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'login' => 'Login',
        'logout' => 'Logout',
        'register' => 'Register',
        'forgot_password' => 'Forgot Password',
        'reset_password' => 'Reset Password',
        'remember_me' => 'Remember Me',
        'email' => 'Email',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'login_failed' => 'Invalid credentials',
        'account_not_found' => 'Account not found',
        'password_reset_link_sent' => 'Password reset link sent',
        'password_reset_success' => 'Password reset successful',
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    */
    'theme' => [
        'light_mode' => 'Light Mode',
        'dark_mode' => 'Dark Mode',
    ],

    /*
    |--------------------------------------------------------------------------
    | Equipment
    |--------------------------------------------------------------------------
    */
    'equipment' => [
        'title' => 'Equipment',
        'list' => 'Equipment List',
        'add' => 'Add Equipment',
        'edit' => 'Edit Equipment',
        'detail' => 'Equipment Details',
        'name' => 'Name',
        'code' => 'Code',
        'category' => 'Category',
        'quantity' => 'Quantity',
        'available' => 'Available',
        'borrowed' => 'Borrowed',
        'maintenance' => 'In Maintenance',
        'damaged' => 'Damaged',
        'location' => 'Location',
        'room' => 'Room',
        'warehouse' => 'Warehouse',
        'status' => 'Status',
        'condition' => 'Condition',
        'good' => 'Good',
        'fair' => 'Fair',
        'poor' => 'Poor',
        'purchase_date' => 'Purchase Date',
        'warranty_date' => 'Warranty Expiry',
        'price' => 'Price',
        'supplier' => 'Supplier',
        'qr_code' => 'QR Code',
        'print_qr' => 'Print QR',
        'history' => 'Usage History',
        'import' => 'Import Equipment',
        'export' => 'Export List',
        'not_found' => 'Equipment not found',
        'create_success' => 'Equipment created successfully',
        'update_success' => 'Equipment updated successfully',
        'delete_success' => 'Equipment deleted successfully',
        'delete_confirm' => 'Are you sure you want to delete this equipment?',
    ],

    /*
    |--------------------------------------------------------------------------
    | Borrow
    |--------------------------------------------------------------------------
    */
    'borrow' => [
        'title' => 'Borrow Records',
        'list' => 'Borrow List',
        'create' => 'Create Borrow Record',
        'detail' => 'Borrow Details',
        'borrower' => 'Borrower',
        'borrow_date' => 'Borrow Date',
        'return_date' => 'Return Date',
        'expected_return' => 'Expected Return',
        'actual_return' => 'Actual Return',
        'purpose' => 'Purpose',
        'class' => 'Class',
        'period' => 'Period',
        'status' => 'Status',
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'borrowed' => 'Borrowed',
        'returned' => 'Returned',
        'overdue' => 'Overdue',
        'cancelled' => 'Cancelled',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'return' => 'Return Equipment',
        'extend' => 'Extend',
        'template' => 'Borrow Template',
        'save_template' => 'Save Template',
        'template_saved' => 'Template saved',
        'calendar' => 'Borrow Calendar',
        'create_success' => 'Borrow record created successfully',
        'update_success' => 'Borrow record updated successfully',
        'approve_success' => 'Borrow request approved',
        'reject_success' => 'Borrow request rejected',
        'return_success' => 'Equipment returned successfully',
        'cannot_convert' => 'Cannot convert this borrow record',
    ],

    /*
    |--------------------------------------------------------------------------
    | Reservations
    |--------------------------------------------------------------------------
    */
    'reservation' => [
        'title' => 'Reservations',
        'list' => 'Reservation List',
        'create' => 'Create Reservation',
        'detail' => 'Reservation Details',
        'date' => 'Date',
        'time_slot' => 'Time Slot',
        'status' => 'Status',
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed',
        'convert_to_borrow' => 'Convert to Borrow',
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Assistant
    |--------------------------------------------------------------------------
    */
    'ai' => [
        'title' => 'AI Assistant',
        'assistant_name' => 'Warehouse Assistant',
        'subtitle' => 'Book equipment using natural language',
        'description' => 'Describe your needs quickly, and the system will suggest and redirect to the booking form with pre-filled information.',
        'greeting' => 'Hello :name! I am the Warehouse Assistant. What equipment do you need today?',
        'example' => 'Example: "Borrow electrical practice kit for period 3 on Thursday"',
        'placeholder' => 'Enter your equipment request...',
        'processing' => 'Processing...',
        'redirecting' => 'Redirecting to booking form...',
        'error' => 'An error occurred. Please try again.',
        'connection_error' => 'Connection failed. Please use the manual form.',
        'manual_form' => 'Or use the manual booking form',
        'quick_actions' => [
            'borrow_microscope' => 'Borrow microscope',
            'check_inventory' => 'Check inventory',
            'borrow_projector' => 'Borrow projector',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rooms
    |--------------------------------------------------------------------------
    */
    'room' => [
        'title' => 'Room Management',
        'list' => 'Room List',
        'add' => 'Add Room',
        'edit' => 'Edit Room',
        'detail' => 'Room Details',
        'name' => 'Room Name',
        'location' => 'Location',
        'type' => 'Type',
        'warehouse' => 'Warehouse',
        'practice_room' => 'Practice Room',
        'manager' => 'Manager',
        'not_assigned' => 'Not Assigned',
        'equipment_count' => 'Equipment Count',
        'capacity' => 'Capacity',
        'people' => 'people',
        'create_success' => 'Room created successfully',
        'update_success' => 'Room updated successfully',
        'delete_success' => 'Room deleted successfully',
    ],

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    'user' => [
        'title' => 'User Management',
        'list' => 'User List',
        'add' => 'Add User',
        'edit' => 'Edit User',
        'detail' => 'User Details',
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'role' => 'Role',
        'admin' => 'Administrator',
        'teacher' => 'Teacher',
        'staff' => 'Staff',
        'department' => 'Department',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'impersonate' => 'Impersonate User',
        'impersonating' => 'Impersonating: :name',
        'stop_impersonate' => 'Stop Impersonating',
        'cannot_impersonate' => 'Cannot impersonate this user',
        'create_success' => 'User created successfully',
        'update_success' => 'User updated successfully',
        'delete_success' => 'User deleted successfully',
    ],

    /*
    |--------------------------------------------------------------------------
    | Departments
    |--------------------------------------------------------------------------
    */
    'department' => [
        'title' => 'Department Management',
        'list' => 'Department List',
        'add' => 'Add Department',
        'edit' => 'Edit Department',
        'detail' => 'Department Details',
        'name' => 'Name',
        'head' => 'Head',
        'members' => 'Members',
        'member_count' => 'Member Count',
        'cannot_delete' => 'Cannot delete department with teachers',
        'create_success' => 'Department created successfully',
        'update_success' => 'Department updated successfully',
        'delete_success' => 'Department deleted successfully',
    ],

    /*
    |--------------------------------------------------------------------------
    | Inventory
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        'title' => 'Inventory Management',
        'increase' => 'Increase Stock',
        'decrease' => 'Decrease Stock',
        'quantity' => 'Quantity',
        'reason' => 'Reason',
        'current_stock' => 'Current Stock',
        'new_stock' => 'New Stock',
        'increase_success' => 'Stock increased successfully',
        'decrease_success' => 'Stock decreased successfully',
    ],

    /*
    |--------------------------------------------------------------------------
    | Approvals
    |--------------------------------------------------------------------------
    */
    'approval' => [
        'title' => 'Approvals',
        'pending' => 'Pending Approval',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'approve_all' => 'Approve All',
        'reject_reason' => 'Rejection Reason',
        'no_pending' => 'No pending approvals',
    ],

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    'report' => [
        'title' => 'Reports',
        'equipment_list' => 'Equipment List',
        'borrow_tracking' => 'Borrow Tracking',
        'statistics' => 'Statistics',
        'export' => 'Export Report',
        'from_date' => 'From Date',
        'to_date' => 'To Date',
        'generate' => 'Generate Report',
        'scheduled' => 'Scheduled Reports',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activity Logs
    |--------------------------------------------------------------------------
    */
    'activity_log' => [
        'title' => 'Activity Logs',
        'user' => 'User',
        'action' => 'Action',
        'subject' => 'Subject',
        'time' => 'Time',
        'ip_address' => 'IP Address',
        'details' => 'Details',
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance
    |--------------------------------------------------------------------------
    */
    'maintenance' => [
        'title' => 'Equipment Maintenance',
        'list' => 'Maintenance List',
        'create' => 'Schedule Maintenance',
        'detail' => 'Maintenance Details',
        'equipment' => 'Equipment',
        'scheduled_date' => 'Scheduled Date',
        'completed_date' => 'Completed Date',
        'technician' => 'Technician',
        'cost' => 'Cost',
        'status' => 'Status',
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'cannot_cancel' => 'Cannot cancel this maintenance',
        'create_success' => 'Maintenance scheduled successfully',
        'update_success' => 'Maintenance updated successfully',
        'cancel_success' => 'Maintenance cancelled successfully',
    ],

    /*
    |--------------------------------------------------------------------------
    | Damage Reports
    |--------------------------------------------------------------------------
    */
    'damage_report' => [
        'title' => 'Damage Reports',
        'list' => 'Report List',
        'create' => 'Create Report',
        'detail' => 'Report Details',
        'equipment' => 'Equipment',
        'reporter' => 'Reporter',
        'damage_date' => 'Date Discovered',
        'damage_type' => 'Damage Type',
        'severity' => 'Severity',
        'minor' => 'Minor',
        'moderate' => 'Moderate',
        'severe' => 'Severe',
        'description' => 'Description',
        'images' => 'Images',
        'status' => 'Status',
        'reported' => 'Reported',
        'reviewing' => 'Reviewing',
        'fixing' => 'Fixing',
        'fixed' => 'Fixed',
        'disposed' => 'Disposed',
    ],

    /*
    |--------------------------------------------------------------------------
    | Import
    |--------------------------------------------------------------------------
    */
    'import' => [
        'title' => 'Import Equipment',
        'select_file' => 'Select File',
        'upload' => 'Upload',
        'download_template' => 'Download Template',
        'file_format' => 'File Format',
        'csv_file' => 'CSV File',
        'excel_file' => 'Excel File',
        'invalid_file' => 'CSV file is empty or invalid',
        'import_success' => 'Successfully imported :count equipment',
        'import_error' => 'Error importing data',
        'row_error' => 'Row :row error: :message',
    ],

    /*
    |--------------------------------------------------------------------------
    | Teaching Plans
    |--------------------------------------------------------------------------
    */
    'teaching_plan' => [
        'title' => 'Teaching Plans',
        'list' => 'Plan List',
        'create' => 'Create Plan',
        'edit' => 'Edit Plan',
        'detail' => 'Plan Details',
        'subject' => 'Subject',
        'class' => 'Class',
        'week' => 'Week',
        'lesson' => 'Lesson',
        'equipment_needed' => 'Equipment Needed',
        'notes' => 'Notes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */
    'notification' => [
        'title' => 'Notifications',
        'mark_read' => 'Mark as Read',
        'mark_all_read' => 'Mark All as Read',
        'marked_read' => 'Marked as read',
        'no_notifications' => 'No notifications',
        'view_all' => 'View All',
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    'profile' => [
        'title' => 'Profile',
        'edit' => 'Edit Profile',
        'change_password' => 'Change Password',
        'two_factor' => 'Two-Factor Authentication',
        'notifications' => 'Notification Settings',
        'update_success' => 'Profile updated successfully',
        'password_changed' => 'Password changed successfully',
    ],

    /*
    |--------------------------------------------------------------------------
    | Errors
    |--------------------------------------------------------------------------
    */
    'error' => [
        '403' => 'Access Forbidden',
        '403_message' => 'You do not have permission to access this page.',
        '404' => 'Page Not Found',
        '404_message' => 'The page you are looking for does not exist or has been moved.',
        '500' => 'Server Error',
        '500_message' => 'An error occurred. Please try again later.',
        '503' => 'Service Unavailable',
        '503_message' => 'The system is under maintenance. Please check back later.',
        'go_back' => 'Go Back',
        'go_home' => 'Go Home',
    ],

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */
    'table' => [
        'no_data' => 'No data available',
        'showing' => 'Showing',
        'to' => 'to',
        'of' => 'of',
        'entries' => 'entries',
        'previous' => 'Previous',
        'next' => 'Next',
        'first' => 'First',
        'last' => 'Last',
        'search' => 'Search',
        'filter' => 'Filter',
        'reset' => 'Reset',
        'export' => 'Export',
        'print' => 'Print',
    ],

    /*
    |--------------------------------------------------------------------------
    | Confirmation
    |--------------------------------------------------------------------------
    */
    'confirm' => [
        'delete' => 'Are you sure you want to delete?',
        'delete_message' => 'This action cannot be undone.',
        'cancel' => 'Are you sure you want to cancel?',
        'approve' => 'Are you sure you want to approve?',
        'reject' => 'Are you sure you want to reject?',
    ],
];

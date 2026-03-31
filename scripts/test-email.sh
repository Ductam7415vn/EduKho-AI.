#!/bin/bash
# Script test email functionality

echo "🧪 EMAIL TEST SCRIPT"
echo "===================="

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check if artisan exists
if [ ! -f "artisan" ]; then
    echo -e "${RED}❌ Error: Run this script from project root${NC}"
    exit 1
fi

echo -e "${YELLOW}1. Testing email configuration...${NC}"
php artisan tinker --execute="
try {
    \Mail::raw('Test email from Equipment Management System', function(\$message) {
        \$message->to('test@example.com')
                ->subject('Test Email - ' . now()->format('Y-m-d H:i:s'));
    });
    echo 'Email sent successfully! Check your Mailtrap inbox.';
} catch (\Exception \$e) {
    echo 'Error: ' . \$e->getMessage();
}
"

echo -e "\n${YELLOW}2. Current mail configuration:${NC}"
php artisan tinker --execute="
echo 'MAIL_MAILER: ' . config('mail.default');
echo 'MAIL_HOST: ' . config('mail.mailers.smtp.host');
echo 'MAIL_PORT: ' . config('mail.mailers.smtp.port');
echo 'MAIL_FROM: ' . config('mail.from.address');
"

echo -e "\n${YELLOW}3. Testing password reset email:${NC}"
php artisan tinker --execute="
\$user = \App\Models\User::first();
if (\$user) {
    \$token = \Str::random(60);
    \DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => \$user->email],
        [
            'token' => \Hash::make(\$token),
            'created_at' => now()
        ]
    );
    
    try {
        \Mail::send('emails.password-reset', [
            'name' => \$user->name,
            'resetUrl' => url('/reset-password?token=' . \$token . '&email=' . urlencode(\$user->email))
        ], function(\$message) use (\$user) {
            \$message->to(\$user->email, \$user->name)
                    ->subject('Đặt lại mật khẩu - Hệ thống Quản lý Thiết bị');
        });
        echo 'Password reset email sent to: ' . \$user->email;
    } catch (\Exception \$e) {
        echo 'Error: ' . \$e->getMessage();
    }
} else {
    echo 'No users found in database';
}
"

echo -e "\n${YELLOW}4. Testing notification email:${NC}"
php artisan tinker --execute="
\$admin = \App\Models\User::where('role', 'admin')->first();
if (\$admin) {
    try {
        \$admin->notify(new \App\Notifications\LowStockAlert(
            \App\Models\Equipment::first()
        ));
        echo 'Low stock notification sent to admin: ' . \$admin->email;
    } catch (\Exception \$e) {
        echo 'Error: ' . \$e->getMessage();
    }
} else {
    echo 'No admin user found';
}
"

echo -e "\n${GREEN}✅ Email test completed!${NC}"
echo -e "${YELLOW}Check your email at:${NC}"
echo "- Mailtrap: https://mailtrap.io/inboxes"
echo "- Or check logs: tail -f storage/logs/laravel.log"
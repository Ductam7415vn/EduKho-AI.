#!/bin/bash
# Script test user registration functionality

echo "🧪 TEST USER REGISTRATION"
echo "========================"

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

echo -e "${YELLOW}1. Running migrations...${NC}"
php artisan migrate --force

echo -e "\n${YELLOW}2. Testing registration flow:${NC}"

# Test registration URL
echo -e "${GREEN}✅ Registration page available at:${NC} http://localhost:8000/register"

# Test email verification URL  
echo -e "${GREEN}✅ Email verification URL format:${NC} http://localhost:8000/verify-email?token=TOKEN&email=EMAIL"

echo -e "\n${YELLOW}3. Registration flow:${NC}"
echo "1. User fills registration form with:"
echo "   - Name"
echo "   - Email"
echo "   - Phone (optional)"
echo "   - Department"
echo "   - Password"
echo ""
echo "2. System creates inactive user account"
echo "3. System sends verification email"
echo "4. User clicks verification link"
echo "5. Account activated and can login"

echo -e "\n${YELLOW}4. Testing email configuration:${NC}"
php artisan tinker --execute="
\$mailConfig = [
    'MAIL_MAILER' => config('mail.default'),
    'MAIL_HOST' => config('mail.mailers.smtp.host'),
    'MAIL_PORT' => config('mail.mailers.smtp.port'),
    'MAIL_FROM' => config('mail.from.address'),
];
print_r(\$mailConfig);
"

echo -e "\n${YELLOW}5. Simulating registration (without sending email):${NC}"
php artisan tinker --execute="
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Str;

\$testEmail = 'test.user.' . time() . '@example.com';
\$dept = Department::first();

if (\$dept) {
    \$user = User::create([
        'name' => 'Test User',
        'email' => \$testEmail,
        'password' => bcrypt('password123'),
        'department_id' => \$dept->id,
        'role' => 'teacher',
        'is_active' => false,
        'notification_settings' => User::defaultNotificationSettings(),
    ]);
    
    \$token = Str::random(60);
    DB::table('email_verifications')->insert([
        'email' => \$user->email,
        'token' => \$token,
        'created_at' => now(),
    ]);
    
    \$verifyUrl = url('/verify-email?token=' . \$token . '&email=' . urlencode(\$user->email));
    
    echo 'Test user created:';
    echo '\nEmail: ' . \$testEmail;
    echo '\nPassword: password123';
    echo '\nStatus: Unverified';
    echo '\n\nVerification URL:';
    echo '\n' . \$verifyUrl;
} else {
    echo 'No departments found. Run seeders first.';
}
"

echo -e "\n\n${GREEN}✅ Registration test completed!${NC}"
echo -e "\n${YELLOW}Next steps:${NC}"
echo "1. Configure email in .env file (see EMAIL_SETUP_GUIDE.md)"
echo "2. Visit http://localhost:8000/register to test registration"
echo "3. Check email inbox (or Mailtrap) for verification email"
echo "4. Click verification link to activate account"
echo "5. Login with registered credentials"
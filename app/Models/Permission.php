<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'group',
        'description',
    ];

    public static function getDefaultPermissions(): array
    {
        return [
            // Equipment
            ['name' => 'equipment.view', 'display_name' => 'Xem thiet bi', 'group' => 'equipment'],
            ['name' => 'equipment.create', 'display_name' => 'Them thiet bi', 'group' => 'equipment'],
            ['name' => 'equipment.edit', 'display_name' => 'Sua thiet bi', 'group' => 'equipment'],
            ['name' => 'equipment.delete', 'display_name' => 'Xoa thiet bi', 'group' => 'equipment'],
            ['name' => 'equipment.import', 'display_name' => 'Import thiet bi', 'group' => 'equipment'],

            // Borrow
            ['name' => 'borrow.view', 'display_name' => 'Xem phieu muon', 'group' => 'borrow'],
            ['name' => 'borrow.create', 'display_name' => 'Tao phieu muon', 'group' => 'borrow'],
            ['name' => 'borrow.approve', 'display_name' => 'Phe duyet phieu muon', 'group' => 'borrow'],
            ['name' => 'borrow.return', 'display_name' => 'Nhan tra thiet bi', 'group' => 'borrow'],

            // Users
            ['name' => 'users.view', 'display_name' => 'Xem nguoi dung', 'group' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Them nguoi dung', 'group' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Sua nguoi dung', 'group' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Xoa nguoi dung', 'group' => 'users'],
            ['name' => 'users.impersonate', 'display_name' => 'Gia lap nguoi dung', 'group' => 'users'],

            // Reports
            ['name' => 'reports.view', 'display_name' => 'Xem bao cao', 'group' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Xuat bao cao', 'group' => 'reports'],

            // System
            ['name' => 'system.settings', 'display_name' => 'Quan ly cai dat', 'group' => 'system'],
            ['name' => 'system.logs', 'display_name' => 'Xem nhat ky', 'group' => 'system'],
        ];
    }
}

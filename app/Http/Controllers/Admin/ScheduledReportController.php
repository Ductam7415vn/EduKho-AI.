<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduledReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduledReportController extends Controller
{
    public function index()
    {
        $reports = ScheduledReport::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.scheduled-reports.index', compact('reports'));
    }

    public function create()
    {
        return view('admin.scheduled-reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'report_type' => 'required|in:equipment_list,borrow_tracking,inventory_summary,overdue_report,maintenance_report',
            'frequency' => 'required|in:daily,weekly,monthly',
            'send_time' => 'required|date_format:H:i',
            'day_of_week' => 'nullable|integer|between:0,6',
            'day_of_month' => 'nullable|integer|between:1,31',
            'recipients' => 'required|string',
        ]);

        // Parse recipients (comma-separated emails)
        $recipients = array_map('trim', explode(',', $validated['recipients']));
        $recipients = array_filter($recipients, fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL));

        if (empty($recipients)) {
            return back()->withInput()->with('error', 'Vui long nhap it nhat mot email hop le.');
        }

        $report = ScheduledReport::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'report_type' => $validated['report_type'],
            'frequency' => $validated['frequency'],
            'send_time' => $validated['send_time'],
            'day_of_week' => $validated['frequency'] === 'weekly' ? $validated['day_of_week'] : null,
            'day_of_month' => $validated['frequency'] === 'monthly' ? $validated['day_of_month'] : null,
            'recipients' => $recipients,
            'is_active' => true,
        ]);

        $report->calculateNextRun();

        return redirect()
            ->route('admin.scheduled-reports.index')
            ->with('success', 'Da tao bao cao tu dong.');
    }

    public function show(ScheduledReport $scheduledReport)
    {
        return view('admin.scheduled-reports.show', compact('scheduledReport'));
    }

    public function edit(ScheduledReport $scheduledReport)
    {
        return view('admin.scheduled-reports.edit', compact('scheduledReport'));
    }

    public function update(Request $request, ScheduledReport $scheduledReport)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'report_type' => 'required|in:equipment_list,borrow_tracking,inventory_summary,overdue_report,maintenance_report',
            'frequency' => 'required|in:daily,weekly,monthly',
            'send_time' => 'required|date_format:H:i',
            'day_of_week' => 'nullable|integer|between:0,6',
            'day_of_month' => 'nullable|integer|between:1,31',
            'recipients' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $recipients = array_map('trim', explode(',', $validated['recipients']));
        $recipients = array_filter($recipients, fn($email) => filter_var($email, FILTER_VALIDATE_EMAIL));

        if (empty($recipients)) {
            return back()->withInput()->with('error', 'Vui long nhap it nhat mot email hop le.');
        }

        $scheduledReport->update([
            'name' => $validated['name'],
            'report_type' => $validated['report_type'],
            'frequency' => $validated['frequency'],
            'send_time' => $validated['send_time'],
            'day_of_week' => $validated['frequency'] === 'weekly' ? $validated['day_of_week'] : null,
            'day_of_month' => $validated['frequency'] === 'monthly' ? $validated['day_of_month'] : null,
            'recipients' => $recipients,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $scheduledReport->calculateNextRun();

        return redirect()
            ->route('admin.scheduled-reports.index')
            ->with('success', 'Da cap nhat bao cao tu dong.');
    }

    public function destroy(ScheduledReport $scheduledReport)
    {
        $scheduledReport->delete();

        return redirect()
            ->route('admin.scheduled-reports.index')
            ->with('success', 'Da xoa bao cao tu dong.');
    }

    public function toggle(ScheduledReport $scheduledReport)
    {
        $scheduledReport->update(['is_active' => !$scheduledReport->is_active]);

        if ($scheduledReport->is_active) {
            $scheduledReport->calculateNextRun();
        }

        return back()->with('success', $scheduledReport->is_active ? 'Da kich hoat bao cao.' : 'Da tam dung bao cao.');
    }
}

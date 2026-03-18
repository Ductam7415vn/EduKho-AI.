<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ICalController extends Controller
{
    /**
     * Export user's borrow records as iCal
     */
    public function exportBorrows(Request $request)
    {
        $user = Auth::user();

        $query = $user->isAdmin()
            ? BorrowRecord::query()
            : BorrowRecord::where('user_id', $user->id);

        $borrows = $query->with(['user', 'details.equipmentItem.equipment'])
            ->whereIn('status', ['active'])
            ->whereIn('approval_status', ['approved', 'auto_approved'])
            ->get();

        $ical = $this->generateIcal($borrows, 'borrow');

        return response($ical, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="borrow-schedule.ics"',
        ]);
    }

    /**
     * Export user's reservations as iCal
     */
    public function exportReservations(Request $request)
    {
        $user = Auth::user();

        $query = $user->isAdmin()
            ? Reservation::query()
            : Reservation::where('user_id', $user->id);

        $reservations = $query->with(['user', 'equipment'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reserved_date', '>=', now()->toDateString())
            ->get();

        $ical = $this->generateIcalReservations($reservations);

        return response($ical, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="reservations.ics"',
        ]);
    }

    /**
     * Export combined schedule (borrows + reservations)
     */
    public function exportAll(Request $request)
    {
        $user = Auth::user();

        // Get borrows
        $borrowQuery = $user->isAdmin()
            ? BorrowRecord::query()
            : BorrowRecord::where('user_id', $user->id);

        $borrows = $borrowQuery->with(['user', 'details.equipmentItem.equipment'])
            ->whereIn('status', ['active'])
            ->whereIn('approval_status', ['approved', 'auto_approved'])
            ->get();

        // Get reservations
        $reservationQuery = $user->isAdmin()
            ? Reservation::query()
            : Reservation::where('user_id', $user->id);

        $reservations = $reservationQuery->with(['user', 'equipment'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('reserved_date', '>=', now()->toDateString())
            ->get();

        $ical = $this->generateCombinedIcal($borrows, $reservations);

        return response($ical, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="equipment-schedule.ics"',
        ]);
    }

    private function generateIcal($borrows, $type = 'borrow'): string
    {
        $output = "BEGIN:VCALENDAR\r\n";
        $output .= "VERSION:2.0\r\n";
        $output .= "PRODID:-//QLTHB//Equipment Management//VI\r\n";
        $output .= "CALSCALE:GREGORIAN\r\n";
        $output .= "METHOD:PUBLISH\r\n";
        $output .= "X-WR-CALNAME:Lich muon thiet bi\r\n";

        foreach ($borrows as $borrow) {
            $equipmentNames = $borrow->details->map(function ($detail) {
                return $detail->equipmentItem->equipment->name;
            })->join(', ');

            $output .= "BEGIN:VEVENT\r\n";
            $output .= "UID:borrow-{$borrow->id}@qlthb.local\r\n";
            $output .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
            $output .= "DTSTART:" . $borrow->borrow_date->format('Ymd') . "\r\n";
            $output .= "DTEND:" . $borrow->expected_return_date->format('Ymd') . "\r\n";
            $output .= "SUMMARY:" . $this->escapeIcal("Muon: {$equipmentNames}") . "\r\n";
            $output .= "DESCRIPTION:" . $this->escapeIcal($this->buildBorrowDescription($borrow)) . "\r\n";

            if ($borrow->class_name) {
                $output .= "LOCATION:" . $this->escapeIcal("Lop {$borrow->class_name}") . "\r\n";
            }

            $output .= "STATUS:CONFIRMED\r\n";
            $output .= "END:VEVENT\r\n";
        }

        $output .= "END:VCALENDAR\r\n";

        return $output;
    }

    private function generateIcalReservations($reservations): string
    {
        $output = "BEGIN:VCALENDAR\r\n";
        $output .= "VERSION:2.0\r\n";
        $output .= "PRODID:-//QLTHB//Equipment Management//VI\r\n";
        $output .= "CALSCALE:GREGORIAN\r\n";
        $output .= "METHOD:PUBLISH\r\n";
        $output .= "X-WR-CALNAME:Lich dat truoc thiet bi\r\n";

        foreach ($reservations as $reservation) {
            $output .= "BEGIN:VEVENT\r\n";
            $output .= "UID:reservation-{$reservation->id}@qlthb.local\r\n";
            $output .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
            $output .= "DTSTART:" . $reservation->reserved_date->format('Ymd') . "\r\n";
            $output .= "DTEND:" . $reservation->reserved_date->addDay()->format('Ymd') . "\r\n";
            $output .= "SUMMARY:" . $this->escapeIcal("Dat truoc: {$reservation->equipment->name}") . "\r\n";
            $output .= "DESCRIPTION:" . $this->escapeIcal($this->buildReservationDescription($reservation)) . "\r\n";

            if ($reservation->class_name) {
                $output .= "LOCATION:" . $this->escapeIcal("Lop {$reservation->class_name}") . "\r\n";
            }

            $status = $reservation->status === 'confirmed' ? 'CONFIRMED' : 'TENTATIVE';
            $output .= "STATUS:{$status}\r\n";
            $output .= "END:VEVENT\r\n";
        }

        $output .= "END:VCALENDAR\r\n";

        return $output;
    }

    private function generateCombinedIcal($borrows, $reservations): string
    {
        $output = "BEGIN:VCALENDAR\r\n";
        $output .= "VERSION:2.0\r\n";
        $output .= "PRODID:-//QLTHB//Equipment Management//VI\r\n";
        $output .= "CALSCALE:GREGORIAN\r\n";
        $output .= "METHOD:PUBLISH\r\n";
        $output .= "X-WR-CALNAME:Lich thiet bi\r\n";

        // Add borrows
        foreach ($borrows as $borrow) {
            $equipmentNames = $borrow->details->map(function ($detail) {
                return $detail->equipmentItem->equipment->name;
            })->join(', ');

            $output .= "BEGIN:VEVENT\r\n";
            $output .= "UID:borrow-{$borrow->id}@qlthb.local\r\n";
            $output .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
            $output .= "DTSTART:" . $borrow->borrow_date->format('Ymd') . "\r\n";
            $output .= "DTEND:" . $borrow->expected_return_date->format('Ymd') . "\r\n";
            $output .= "SUMMARY:" . $this->escapeIcal("Muon: {$equipmentNames}") . "\r\n";
            $output .= "DESCRIPTION:" . $this->escapeIcal($this->buildBorrowDescription($borrow)) . "\r\n";
            $output .= "STATUS:CONFIRMED\r\n";
            $output .= "CATEGORIES:MUON\r\n";
            $output .= "END:VEVENT\r\n";
        }

        // Add reservations
        foreach ($reservations as $reservation) {
            $output .= "BEGIN:VEVENT\r\n";
            $output .= "UID:reservation-{$reservation->id}@qlthb.local\r\n";
            $output .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
            $output .= "DTSTART:" . $reservation->reserved_date->format('Ymd') . "\r\n";
            $output .= "DTEND:" . $reservation->reserved_date->addDay()->format('Ymd') . "\r\n";
            $output .= "SUMMARY:" . $this->escapeIcal("Dat truoc: {$reservation->equipment->name}") . "\r\n";
            $output .= "DESCRIPTION:" . $this->escapeIcal($this->buildReservationDescription($reservation)) . "\r\n";
            $status = $reservation->status === 'confirmed' ? 'CONFIRMED' : 'TENTATIVE';
            $output .= "STATUS:{$status}\r\n";
            $output .= "CATEGORIES:DAT TRUOC\r\n";
            $output .= "END:VEVENT\r\n";
        }

        $output .= "END:VCALENDAR\r\n";

        return $output;
    }

    private function buildBorrowDescription(BorrowRecord $borrow): string
    {
        $lines = [];
        $lines[] = "Ma phieu: #{$borrow->id}";
        $lines[] = "Nguoi muon: {$borrow->user->name}";

        if ($borrow->lesson_name) {
            $lines[] = "Bai hoc: {$borrow->lesson_name}";
        }
        if ($borrow->subject) {
            $lines[] = "Mon hoc: {$borrow->subject}";
        }
        if ($borrow->period) {
            $lines[] = "Tiet: {$borrow->period}";
        }
        if ($borrow->class_name) {
            $lines[] = "Lop: {$borrow->class_name}";
        }

        $lines[] = "";
        $lines[] = "Thiet bi:";
        foreach ($borrow->details as $detail) {
            $lines[] = "- {$detail->equipmentItem->specific_code} ({$detail->equipmentItem->equipment->name})";
        }

        return implode("\\n", $lines);
    }

    private function buildReservationDescription(Reservation $reservation): string
    {
        $lines = [];
        $lines[] = "Ma dat truoc: #{$reservation->id}";
        $lines[] = "Nguoi dat: {$reservation->user->name}";
        $lines[] = "Thiet bi: {$reservation->equipment->name}";
        $lines[] = "So luong: {$reservation->quantity} {$reservation->equipment->unit}";

        if ($reservation->lesson_name) {
            $lines[] = "Bai hoc: {$reservation->lesson_name}";
        }
        if ($reservation->subject) {
            $lines[] = "Mon hoc: {$reservation->subject}";
        }
        if ($reservation->period) {
            $lines[] = "Tiet: {$reservation->period}";
        }
        if ($reservation->class_name) {
            $lines[] = "Lop: {$reservation->class_name}";
        }

        $lines[] = "";
        $lines[] = "Trang thai: " . ($reservation->status === 'confirmed' ? 'Da xac nhan' : 'Cho xac nhan');

        return implode("\\n", $lines);
    }

    private function escapeIcal(string $text): string
    {
        $text = str_replace(['\\', ';', ','], ['\\\\', '\\;', '\\,'], $text);
        return $text;
    }
}

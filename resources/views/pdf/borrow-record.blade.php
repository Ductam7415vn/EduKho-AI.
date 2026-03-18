<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Phieu muon #{{ $borrowRecord->id }}</title>
    <style>
        * {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        body {
            margin: 20px;
            color: #0f172a;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #0f4c81;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 14px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            color: #0f4c81;
        }
        .header h2 {
            font-size: 16px;
            margin: 10px 0;
            color: #0f766e;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #334155;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: block;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #64748b;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e8f3ff;
            font-weight: bold;
            color: #0f4c81;
        }
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-approved { background: #ccfbf1; color: #115e59; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-rejected { background: #ffe4e6; color: #9f1239; }
        .status-active { background: #dbeafe; color: #0f4c81; }
        .status-returned { background: #e2e8f0; color: #334155; }
        .signatures {
            margin-top: 50px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px dotted #64748b;
            padding-top: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Truong THPT ABC</h1>
        <p>Dia chi: 123 Duong XYZ, Quan/Huyen, Thanh pho</p>
        <p>Dien thoai: (028) 1234 5678</p>
        <h2>PHIEU MUON THIET BI</h2>
        <p>So: {{ str_pad($borrowRecord->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Nguoi muon:</span>
            <span>{{ $borrowRecord->user->name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">To chuyen mon:</span>
            <span>{{ $borrowRecord->user->department?->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span>{{ $borrowRecord->user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Ngay muon:</span>
            <span>{{ $borrowRecord->borrow_date->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Ngay tra du kien:</span>
            <span>{{ $borrowRecord->expected_return_date->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tiet hoc:</span>
            <span>Tiet {{ $borrowRecord->period }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Lop:</span>
            <span>{{ $borrowRecord->class_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Mon hoc:</span>
            <span>{{ $borrowRecord->subject }}</span>
        </div>
        @if($borrowRecord->lesson_name)
        <div class="info-row">
            <span class="info-label">Bai hoc:</span>
            <span>{{ $borrowRecord->lesson_name }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Trang thai phe duyet:</span>
            <span class="status status-{{ $borrowRecord->approval_status }}">
                {{ match($borrowRecord->approval_status) {
                    'approved' => 'Da duyet',
                    'pending' => 'Cho duyet',
                    'rejected' => 'Tu choi',
                    'auto_approved' => 'Tu dong duyet',
                    default => $borrowRecord->approval_status
                } }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Trang thai muon:</span>
            <span class="status status-{{ $borrowRecord->status }}">
                {{ match($borrowRecord->status) {
                    'active' => 'Dang muon',
                    'returned' => 'Da tra',
                    'overdue' => 'Qua han',
                    default => $borrowRecord->status
                } }}
            </span>
        </div>
        @if($borrowRecord->approver)
        <div class="info-row">
            <span class="info-label">Nguoi phe duyet:</span>
            <span>{{ $borrowRecord->approver->name }}</span>
        </div>
        @endif
    </div>

    <h3>Chi tiet thiet bi muon:</h3>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">STT</th>
                <th>Ten thiet bi</th>
                <th style="width: 100px;">Ma thiet bi</th>
                <th style="width: 100px;">Tinh trang truoc</th>
                <th style="width: 100px;">Tinh trang sau</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowRecord->details as $index => $detail)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $detail->equipmentItem->equipment->name }}</td>
                <td>{{ $detail->equipmentItem->inventory_number }}</td>
                <td>
                    {{ match($detail->condition_before) {
                        'good' => 'Tot',
                        'fair' => 'Kha',
                        'poor' => 'Kem',
                        default => $detail->condition_before ?? 'N/A'
                    } }}
                </td>
                <td>
                    {{ match($detail->condition_after) {
                        'good' => 'Tot',
                        'fair' => 'Kha',
                        'poor' => 'Kem',
                        'damaged' => 'Hong',
                        'lost' => 'Mat',
                        default => $detail->condition_after ?? 'Chua tra'
                    } }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($borrowRecord->notes)
    <div class="info-section" style="margin-top: 20px;">
        <div class="info-row">
            <span class="info-label">Ghi chu:</span>
            <span>{{ $borrowRecord->notes }}</span>
        </div>
    </div>
    @endif

    <div class="signatures">
        <div class="signature-box">
            <strong>Nguoi muon</strong>
            <div class="signature-line">{{ $borrowRecord->user->name }}</div>
        </div>
        <div class="signature-box">
            <strong>Nhan vien kho</strong>
            <div class="signature-line"></div>
        </div>
        <div class="signature-box">
            <strong>Ban Giam Hieu</strong>
            <div class="signature-line">{{ $borrowRecord->approver?->name ?? '' }}</div>
        </div>
    </div>

    <div class="footer">
        <p>In ngay: {{ now()->format('d/m/Y H:i') }}</p>
        <p>He thong Quan ly Thiet bi Day hoc - {{ config('app.name') }}</p>
    </div>
</body>
</html>

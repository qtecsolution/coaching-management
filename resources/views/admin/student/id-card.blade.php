<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }

        .card-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            max-width: 800px;
            margin: 0 auto;
        }

        .id-card {
            width: 320px;
            /* height: 480px; */
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .logo {
            width: 80px;
            margin: 0 auto 5px;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 50px auto 20px auto;
            overflow: hidden;
            border: 3px solid #2c3e50;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .details {
            padding: 20px;
        }

        .detail-row {
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .label {
            font-weight: bold;
            color: #2c3e50;
        }

        .value {
            color: #34495e;
            text-align: right;
            flex: 1;
            margin-left: 10px;
        }

        .card-back {
            background: #fff;
        }

        .rules {
            padding: 20px;
            font-size: 12px;
        }

        .rules h3 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .rules ul {
            margin: 0;
            padding-left: 20px;
            color: #34495e;
        }

        .rules li {
            margin-bottom: 5px;
        }

        .signature {
            text-align: right;
            margin-top: 50px;
            padding: 20px;
        }

        .signature-line {
            width: 150px;
            border-top: 1px solid #2c3e50;
            margin-left: auto;
            margin-bottom: 5px;
        }

        .qr-code {
            text-align: center;
            margin: 20px 0;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
        }

        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #2c3e50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }

        .print-button:hover {
            background: #34495e;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            body {
                padding: 0;
                margin: 0;
                background: none;
            }

            .card-container {
                gap: 20px;
                padding: 20px;
            }

            .id-card {
                break-inside: avoid;
                page-break-inside: avoid;
                margin-bottom: 20px;
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .header {
                background-color: #2c3e50 !important;
                color: white !important;
            }

            .photo {
                border-color: #2c3e50 !important;
            }

            .print-button {
                display: none;
            }
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>

<body>
    @php
        $logo = asset('assets/static/images/logo/logo.svg');

        if (auth()->user()->avatar) {
            $avatar = asset('storage/' . auth()->user()->avatar);
        } else {
            $avatar = asset('assets/static/images/faces/2.jpg');
        }
    @endphp

    <div class="card-container">
        <!-- Front Side -->
        <div class="id-card">
            <div class="header">
                <div class="logo">
                    <img src="{{ $logo }}" alt="{{ config('app.name') }}">
                </div>
                <h2>{{ config('app.name') }}</h2>
                {{-- <p>Student Identity Card</p> --}}
            </div>

            <div class="photo">
                <img src="{{ $avatar }}" alt="{{ $student->user->name }}">
            </div>

            <div class="details">
                <div class="detail-row">
                    <span class="label">Name:</span>
                    <span class="value">{{ $student->user->name }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Student ID:</span>
                    <span class="value">{{ $student->reg_id }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Batch:</span>
                    <span class="value">{{ $student->currentBatch->batch->title }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Contact:</span>
                    <span class="value">{{ $student->phone }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Valid Until:</span>
                    <span class="value">{{ $student->created_at->addYear()->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Back Side -->
        <div class="id-card card-back">
            <div class="header">
                <h3>Student Information</h3>
            </div>

            <div class="rules">
                <h3>Rules & Regulations</h3>
                <ul>
                    <li>This card must be carried at all times within the campus</li>
                    <li>This card is non-transferable</li>
                    <li>Loss of card should be reported immediately</li>
                    <li>Duplicate card will be issued on payment of fees</li>
                    <li>Card must be surrendered upon completion/withdrawal</li>
                </ul>
            </div>

            <div class="qr-code">
                {{ QrCode::size(100)->generate(
                    json_encode([
                        'Name' => $student->user->name,
                        'Phone' => $student->phone,
                        'Email' => $student->user->email,
                        'ID' => $student->reg_id,
                    ]),
                ) }}
            </div>

            <div class="signature">
                <div class="signature-line"></div>
                <small>Authorized Signature</small>
            </div>
        </div>
    </div>

    <button class="print-button" onclick="window.print()">
        Print ID Card
    </button>
</body>

</html>

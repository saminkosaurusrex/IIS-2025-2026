<!DOCTYPE html>
<html>
<head>
    <title>Ticket</title>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 20mm; /* proper PDF margin */
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* CARD */
        .reservation-card {
            width: 100%;
            max-width: 600px;
            background: #fff;
            border: 1px solid #ccc; /* replaced shadow */
            border-radius: 8px;
            overflow: hidden;
            margin: auto;
        }

        /* HEADER */
        .header {
            background: #111827; /* solid color instead of gradient */
            padding: 10px;
            color: #fff;
        }

        .header-top {
            display: block; /* flex replaced with block for PDF */
            margin-bottom: 5px;
        }

        .name {
            font-size: 16px;
            font-weight: bold;
        }

        .score {
            background: #dc2626;
            color: #fff;
            padding: 2px 5px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
            margin-top: 3px;
        }

        .details {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .tags span {
            background: #374151;
            padding: 2px 5px;
            border-radius: 12px;
            font-size: 10px;
            color: #fff;
            display: inline-block;
            margin-right: 2px;
            margin-bottom: 2px;
        }

        /* CONTENT */
        .content {
            padding: 10px;
        }

        .info-box {
            background: #dc2626;
            color: #fff;
            border-radius: 5px;
            padding: 5px 8px;
            margin-bottom: 5px;
            display: block;
        }

        .info-box span:first-child {
            font-weight: bold;
        }

    </style>
</head>
<body>
@foreach($reservations as $reservation)
<div>
    <div style="margin-bottom: 20rem" class="reservation-card border p-4 rounded shadow flex flex-col justify-center">
        <header class="header">
            <div class="header-top flex justify-between items-center">
                <h1 class="name font-bold text-lg">{{ $reservation->event->show->name }}</h1>
            </div>
            <div class="details text-sm text-white">
                <span>{{ $reservation->event->ending_at_human}} min</span>
            </div>
            <div class="tags mt-1 flex flex-wrap gap-1 text-xs">
                @foreach($reservation->event->show->tags as $tag)
                    <span class="bg-gray-200 px-2 py-1 rounded">{{ $tag->name }}</span>
                @endforeach
            </div>
        </header>

        <div class="content mt-2">
            <div class="info-box flex justify-between text-sm">
                <span>Kde:</span>
                <span>{{ $reservation->event->hall->name }}</span>
            </div>

            <div class="info-box flex justify-between text-sm">
                <span>Adresa:</span>
                <span class="font-medium text-xs sm:text-sm">{{ $reservation->event->hall->address }}</span>
            </div>

            <div class="info-box flex justify-between text-sm">
                <span>Kedy:</span>
                <span>{{ $reservation->event->starting_at_human }}</span>
            </div>

            <div class="info-box flex justify-between text-sm">
                <span>Rada:</span>
                <span>{{ $reservation->row }}</span>
            </div>

            <div class="info-box flex justify-between text-sm">
                <span>Sedadlo:</span>
                <span>{{ $reservation->column}}</span>
            </div>
        </div>
        <div style="text-align: center; margin-top: 10px; margin-bottom: 10px">
            <img
                src="data:image/png;base64,{{ DNS2D::getBarcodePNG($reservation->access_code, 'QRCODE', 10, 10) }}"
                alt="QR Code"
                style="width: 200px; height: 200px;"
            >
        </div>
</div>
</div>
@endforeach
</body>
</html>

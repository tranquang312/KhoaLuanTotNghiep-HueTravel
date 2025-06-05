<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Thông tin chuyến đi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #666;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Thông tin chuyến đi {{ $departure->tour->name }}</h1>
        </div>

        <div class="content">
            <div class="info-item">
                <span class="info-label">Ngày khởi hành:</span>
                <p>{{ $departure->departure_date->format('d/m/Y') }}</p>
            </div>

            <div class="info-item">
                <span class="info-label">Điểm đến:</span>
                <p>
                    @foreach ($departure->tour->destinations as $destination)
                        {{ $destination->name }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </p>
            </div>

            <div class="info-item">
                <span class="info-label">Lịch trình:</span>
                <div>
                    {!! $departure->tour->itinerary !!}
                </div>
            </div>

            <div class="info-item">
                <span class="info-label">Hướng dẫn viên:</span>
                <p>{{ $departure->guide->name }}</p>
            </div>

            <div class="info-item">
                <span class="info-label">Thông tin liên hệ hướng dẫn viên:</span>
                <p>Email: {{ $departure->guide->email }}</p>
                <p>Thông tin: {{ $departure->guide->profile }}</p>
            </div>
        </div>

        <div class="footer">
            <p>Cảm ơn bạn đã lựa chọn chúng tôi!</p>
            <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.</p>
        </div>
    </div>
</body>

</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cảm ơn bạn đã tham gia chuyến đi</title>
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
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Cảm ơn bạn đã tham gia chuyến đi {{ $departure->tour->name }}</h1>
        </div>

        <div class="content">
            <p>Kính gửi {{ $booking->name }},</p>

            <p>Chúng tôi xin chân thành cảm ơn bạn đã lựa chọn và tham gia chuyến đi {{ $departure->tour->name }} cùng chúng tôi. Chúng tôi hy vọng bạn đã có một trải nghiệm tuyệt vời.</p>

            <p>Để giúp chúng tôi cải thiện dịch vụ và phục vụ bạn tốt hơn trong tương lai, chúng tôi rất mong nhận được đánh giá của bạn về chuyến đi vừa qua.</p>

            <div style="text-align: center;">
                <a href="{{ route('tour.review', ['booking' => $booking->id]) }}" class="button">
                    Đánh giá chuyến đi
                </a>
            </div>

            <p>Một lần nữa, chúng tôi xin chân thành cảm ơn sự tin tưởng của bạn. Chúng tôi mong được phục vụ bạn trong những chuyến đi tiếp theo.</p>
        </div>

        <div class="footer">
            <p>Trân trọng,</p>
            <p>Đội ngũ {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html> 
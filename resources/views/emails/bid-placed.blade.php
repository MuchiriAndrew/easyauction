<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            background-color: #007bff;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            color: #fff;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 20px 0;
        }

        .content p {
            margin: 0 0 10px;
            color: #333;
        }

        .content a {
            display: inline-block;
            color: #fff;
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }

        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 0 0 5px 5px;
            text-align: center;
        }

        .footer p {
            margin: 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bid Placed</h1>
        </div>
        <div class="content">
            <p>Hello {{ $details['username'] }}, your bid for {{ $details['car_name'] }} at {{ $details['auction_name'] }} auction has been placed successfully.</p>
            <p>Your bid amount is {{ $details['bid_amount'] }}. 
            <br>
            Your bid is currently the highest bid for this car. Kindly wait for the auction to end to see if you have won the car or place a higher bid incase you have lost another bidder bids higher than you.</p>
            {{-- <a href="{{ $details['link'] }}">Confirm Account</a> --}}
        </div>
        <div class="footer">
            <p>Thank you for using our platform.</p>
        </div>
    </div>
</body>
</html>
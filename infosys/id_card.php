<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .id-card {
            width: 320px;
            height: 450px;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            font-family: Arial, sans-serif;
        }

        .id-card img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .id-card h4, .id-card p {
            margin: 5px 0;
        }

        .qr-code {
            margin-top: 15px;
        }
    </style>
    <title>ID Card</title>
</head>
<body>
    <div class="id-card">
        <img src="path_to_logo" alt="Logo">
        <h4 id="full_name">John Doe</h4>
        <p><strong>Barangay:</strong> <span id="barangay">Barangay XYZ</span></p>
        <p><strong>Precinct No:</strong> <span id="precinct">123456</span></p>
        <p><strong>UID:</strong> <span id="uid">UID12345</span></p>
        <p id="member_uidm_container"><strong>UIDM:</strong> <span id="uidm">UIDM67890</span></p>
        <div class="qr-code">
            <img id="qr_code_img" src="#" alt="QR Code">
        </div>
    </div>
</body>
</html>

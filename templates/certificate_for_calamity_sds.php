<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Certification for Calamity</title>
  <style>
    body {
      font-family: "Times New Roman", serif;
      margin: 40px;
      line-height: 1.6;
    }
    .header {
      text-align: center;
    }
    .header img {
      width: 90px;
      vertical-align: middle;
      margin: 0 15px;
    }
    .header h2, .header h3, .header h4 {
      margin: 3px 0;
    }
    .title {
      text-align: center;
      font-weight: bold;
      text-decoration: underline;
      margin: 30px 0;
      font-size: 18px;
    }
    .content {
      text-align: justify;
      font-size: 16px;
    }
    .signature {
      margin-top: 60px;
      text-align: right;
    }
    .signature p {
      margin: 2px 0;
    }
    .signature img {
        width: 250px;
        display: inline-block;
        margin-right: 0;
        }

    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 14px;
    }
  </style>
</head>
<body>

  

  <div class="title">
    CERTIFICATION FOR CALAMITY
  </div>

  <div class="content">
    <p><strong>TO WHOM IT MAY CONCERN</strong></p>

    <p>This is to certify that <strong><?=strtoupper($fullname) ?></strong> of legal age, is a resident of Barangay 400, Zone 41, District IV, with postal address at <strong><?=ucfirst($user_address)?></strong>.</p>

    <p>He/she has known me of good moral character and can be trusted. He/she has never been involved in any unlawful activities and has been a law-abiding citizen of this Barangay up to the present.</p>

    <p>This further certifies that due to the continued rainfall caused by <em>"<?=$what_is_caused?>"</em> last <?=$calamity_dateWord?>, the aforementioned suffered from flood and were affected by the calamity.</p>

    <p>This certification is issued upon the request of <strong><?=$requested_by?></strong> as supporting document for submission to her work office on Employees Provident Moratorium Program.</p>

    <p>WITNESS WHEREOF I have hereunto set my hand and affixed the official seal of this office. Done in the City of Manila, this <strong><?=$day?><sup><?=$daySuffix?></sup> day of <?=$month . ' ' . $year?></strong>.</p>
  </div>

<!-- SIGNATURE -->
  <div class="signature">
    <!-- <img src="../templates/captain.png" alt="Signature / Photo">  -->
    <p>Hon. FELIX "ELIE" TAGUBA</p>
    <p class="designation">PUNONG BARANGAY</p>
  </div>

</body>
</html>

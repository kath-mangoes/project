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
      width: 120px;
      display: inline-block;
      margin-right: 0;
    }
  </style>
</head>
<body>

  <div class="title">
    CERTIFICATION FOR CALAMITY
  </div>

  <div class="content">
    <p><strong>TO WHOM IT MAY CONCERN</strong></p>

    <p>This is to certify that <strong><?=strtoupper($fullname) ?></strong>, of legal age, is a bonafide resident of Barangay 400, Zone 41, District IV, with postal address at <strong><?=ucfirst($user_address)?></strong>.</p>

    <p>This further certifies that due to Fire Incident caused by <em>"<?=$what_is_caused?>"</em> last <?=$calamity_dateWord?>, at <?=$calamity_timeFormatted?> at <?=$location?> the aforementioned suffered from Fire and affected by the incident.</p>

    <p>This certification is issued upon the request of <strong><?=$requested_by?></strong> for <?=$calamity_purpose?>.</p>

    <p>WITNESS WHEREOF I have hereunto set my hand and affixed the official seal of this office. Done in the City of Manila, this <strong><?=$day?><sup><?=$daySuffix?></sup> day of <?=$month . ' ' . $year?></strong>.</p>
  </div>

  <!-- SIGNATURE -->
  <div class="signature">
    <!-- <img src="captain-signature.png" alt="Signature / Photo"> -->
    <p><strong>Hon. FELIX "ELIE" TAGUBA</strong></p>
    <p class="designation">PUNONG BARANGAY</p>
  </div>

</body>
</html>

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

    <p>This is to certify that <strong><?=ucfirst($fullname)?></strong> of legal age, is a bonafide resident of Barangay 400, Zone 41, District IV, with postal address at 
    <strong><?=ucfirst($row['user_address'])?></strong>.</p>

    <p>She has known me of good moral character and can be trusted. She has never been involved in any unlawful activities and has been a law-abiding citizen of this Barangay up to the present.</p>

    <p>This further certifies that due to the continued rainfall caused by <em>"<?=ucfirst($row['type_of_calamity'])?>"</em> last <?= date("F j, Y", strtotime($row['calamity_date'])); ?>, the aforementioned suffered from flood and were affected by the calamity.</p>

    <p>This certification is issued upon the request of <strong><?=ucfirst($row['requested_by'])?></strong> for Calamity Claim Purposes.</p>

    <p>WITNESS WHEREOF I have hereunto set my hand and affixed the official seal of this office. Done in the City of Manila, this 
    <strong><?php echo date('jS'); ?><sup></sup> day of  <?php echo date('F Y'); ?></strong>.</p>
  </div>

  <!-- SIGNATURE -->
  <div class="signature">
      <!-- <img src="../templates/captain.png" alt="Signature / Photo"> -->
    <p><strong>Hon. FELIX "ELIE" TAGUBA</strong></p>
    <p class="designation">PUNONG BARANGAY</p>
  </div>

</body>
</html>

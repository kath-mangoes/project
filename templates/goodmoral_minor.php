<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Certification</title>
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      line-height: 1.6;
      margin: 40px;
      text-align: justify;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 5px;
    }
    .header img {
      width: 90px;
      height: auto;
    }
    .header-center {
      flex: 1;
      text-align: center;
      font-size: 14px;
      line-height: 1.3;
    }
    .header-center .office {
      color: red;
      font-weight: bold;
    }
    .line-separator {
      border-top: 3px solid navy;
      border-bottom: 3px solid maroon;
      height: 3px;
      margin: 5px 0 0 0;
    }
    h2 {
      text-align: center;
      margin-top: 10px;
      font-weight: bold;
      letter-spacing: 2px;
    }
    .to-whom {
      font-weight: bold;
      margin-top: 20px;
    }
    .indent {
      text-indent: 40px;
    }
    .underline {
      text-decoration: underline;
    }
    .signature {
      margin-top: 60px;
      text-align: right;
    }
    .signature p {
      margin: 2px 0;
    }
    .designation {
      font-size: 13px;
    }
  </style>
</head>
<body>
  

  <h2>CERTIFICATION</h2>

  <p class="to-whom">TO WHOM IT MAY CONCERN;</p>

  <p class="indent">
    This is to certify that <b><u><?= $fullname;?></u></b>, <b><?=$age?> yrs old</b>, born on <b><?=$birthdayWord?></b> is a bonafide resident of Barangay 400 Zone 41 with postal address <b><u><?=$user_address?></u></b> <?=$row['thr_relationship']?> of <b><u><?=ucfirst($row['fullname_of_head'])?></u></b>. He has known to me a Good Moral Character and can be trusted. He has never been involved in any unlawful activities and a law abiding of this Barangay.
  </p>

  <p class="indent">
    This certification is being issued upon the request of the above mentioned name for. This certification shall serve as for: <b><u><?= $row['purpose']?></u></b>.
  </p>

  <p class="indent">
    <b>IN WITNESS WHEREOF</b> I have been here unto set my hand and affixed the official seal of this office. Done in the City of Manila, this 
    <b><u><?=$day?><sup><?=$daySuffix?></sup></u></b> day of <b><u><?=$month?> <?=$year?></u></b>.
  </p>

  <div class="signature">
    <p>Hon. FELIX "ELIE" TAGUBA</p>
    <p class="designation">PUNONG BARANGAY</p>
  </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BARANGAY CERTIFICATION</title>
  <style>
    body {
      font-family: "Times New Roman", Times, serif;
      line-height: 1.6;
      margin: 40px;
      text-align: justify;
    }
    .top-note {
        text-align: right;
        font-size: 12px;
        margin-right: 50px; /* bawasan o dagdagan para sa tamang layo */
        }

    h2 {
      text-align: center;
      margin-top: 10px;
      margin-bottom: 0;
    }
    h3 {
      text-align: center;
      margin: 0;
      font-size: 14px;
      font-style: italic;
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
    .witness {
      margin-top: 60px;
      text-align: right;
    }
    .witness p {
      margin: 2px 0;
    }
    .footer-note {
      margin-top: 40px;
      font-size: 12px;
      text-align: left;
    }
  </style>
</head>

  <!-- Top right note -->
  <div class="top-note">
    Revised as of 16 June 2021<br>
    Barangay Certificate Number: <br>
    ________________________
  </div>

  <!-- Title -->
  <h2>BARANGAY CERTIFICATION</h2>
  <h3 class="mb-3">( First Time Jobseekers Assistance Act – RA 11261 )</h3>

  <!-- Body -->
  <p class="indent">
    This is to certify that Mr./Ms. <b><span class="underline"><?=ucfirst($fullname)?></span></b>, 
    a resident of <span class="underline"><?=ucfirst($user_address)?></span>, 
    for <span class="underline"><?=$age;?> years</span>, is qualified availer of RA 11261 or the First Time Jobseekers Assistance Act of 2019.
  </p>

  <p class="indent">
    I hereby certify that the holder / bearer were informed of his/her rights, including the duties and responsibilities accorded by RA 11261 through the Oath of undertaking he/she signed and executed in the presence of Barangay Officials.
  </p>

  <?php
    echo '<p class="indent">
    Signed this <span class="underline">' . $day . '<sup>' . $daySuffix . '</sup></span> day of <span class="underline">' . $month . ' ' . $year . '</span> in the City Municipality of Manila.
    </p>';

    echo '<p class="indent">
        Certification is valid only until <span class="underline">' . $validMonth . ' ' . $validDay . ', ' . $validYear . '</span>, one (1) year from the issuance.
    </p>';
  ?>

  

  <!-- Signature -->
  <div class="signature">
    <p><b>FELIX C. TAGUBA</b></p>
    <p>Punong Barangay /<br>Authorized Barangay Official and Position</p>
    <p><span class="underline"><?=$todayWord?></span><br>Date</p>
  </div>

  <!-- Witness -->
  <div class="witness">
    <p>Witnessed by:</p>
    <p><b>MA. ELIEZEL D. CRUZ / Kagawad</b></p>
    <p>Barangay Official / Designation / Position</p>
    <p><span class="underline"><?=$todayWord?></span><br>Date</p>
  </div>

  <!-- Footer Note -->
  <div class="footer-note">
    THIS FORM NEED NOT BE NOTARIZED
  </div>

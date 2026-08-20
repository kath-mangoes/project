<style>
    body {
      font-family: "Times New Roman", Times, serif;
      line-height: 1.6;
      margin: 40px;
      text-align: justify;
    }
    h2 {
      text-align: center;
      text-decoration: underline;
      margin-top: 5px;
    }
    .indent {
      text-indent: 40px;
    }
    ol {
      margin-left: 40px;
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
    /* --- LINE SEPARATOR --- */
    .line-separator {
      border-top: 3px solid navy;   /* blue */
      border-bottom: 3px solid maroon; /* red */
      height: 3px;
      margin: 5px 0 0 0;
    }
    .signature {
      margin-top: 60px;
      text-align: right;
    }
    .witness {
      margin-top: 80px;
      text-align: right;
    }
    .underline {
      text-decoration: underline;
    }
</style>

<h2 class="mb-3">OATH OF UNDERTAKING</h2>

  <p class="indent">
    I, <b><span class="underline"><?=ucfirst($fullname)?></span></b>, <?=$age?> years of age, resident of Barangay 400, Zone 41 for 23 yrs. availing the benefits of Republic Act of 11261, otherwise known as the <b>First Time Jobseekers Act of 2019</b>, do hereby declare, agree and undertake to abide and be bound by the following;
  </p>

  <ol>
    <li>That this the first time that I will actively look for a job and therefore requesting a Barangay Certification be issued in may favor to avail the benefits of the law;</li>
    <li>That I am aware that the benefits and privileges under the said law shall be valid only for one (1) year from the date that the barangay certification is issued;</li>
    <li>That I can avail the benefits of the law once;</li>
    <li>That I understand that my personal information shall be included in the Roster/list of First time jobseekers and will not be used in any unlawful purpose;</li>
    <li>That I will inform and/or report to Barangay personally through text or other means, or through my family / relatives once I get employed and;</li>
    <li>That I am not a beneficiary of Job Start Program under R.A. 10869 and the other laws that give similar to exemptions for the documents or transactions exempted under R.A. 11261;</li>
    <li>That if issued the requested certification , I will not use the same in any fraud, neither falsify nor help and/or assist in the fabrication of the said certification;</li>
    <li>That this undertaking is made solely for the purposes of obtaining a Barangay Certification consistent with the objectives of R.A. 11261 and not for any other purposes;</li>
    <li>That I consent to the use of my personal information pursuant to the Data Private Act and other applicable laws, rules and regulations.</li>
  </ol>

  <?php
    echo '<p class="indent">
        Signed this <span class="underline">' . $day . '<sup>' . $daySuffix . '</sup></span> day of <span class="underline">' . $month . ' ' . $year . '</span> in the City Municipality of Manila.
    </p>';
  ?>


  <div class="signature">
   <?php
    $first = strtoupper($row['first_name']);
    $middleInitial = strtoupper(substr($row['middle_name'], 0, 1)) . '.';
    $last = strtoupper($row['last_name']);
    ?>
    <p><b><?= $first . ' ' . $middleInitial . ' ' . $last ?></b><br>

    First Time Jobseeker</p>
  </div>

  <div class="witness">
    <p>Witnessed by:</p>
    <br><br>
    <p><b>MA. ELIEZEL D. CRUZ / KAGAWAD</b></p>
    <p>Barangay Official / Designation / Position</p>
    <br>
    <p>Date: <span class="underline"><?=$todayWord?></span></p>
  </div>

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
    /* Separator line */
    .line-separator {
      border-top: 3px solid navy;   /* blue */
      border-bottom: 3px solid maroon; /* red */
      height: 3px;
      margin: 5px 0 0 0;
    }
    h2 {
      text-align: center;
      text-decoration: underline;
      margin-top: 5px;
      font-weight: bold;
      letter-spacing: 2px;
    }
    .indent {
      text-indent: 40px;
    }
    .underline {
      text-decoration: underline;
    }
    .checklist {
      margin-left: 60px;
      margin-top: 15px;
    }
    .checklist div {
      margin: 4px 0;
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

    .designation {
      font-size: 13px;
    }
  </style>

<!-- TITLE -->
  <h2>CERTIFICATION</h2>

  <!-- BODY -->
  <p><b>TO WHOM IT MAY CONCERN:</b></p>

  <p class="indent">
    This is to certify that <b>__<span class="underline"><?= ucfirst($fullname);?></span>___</b> of legal age, a bonafide resident of Barangay 400, 
    Zone 41, District IV, with postal address __<span class="underline"><?=ucfirst($row['user_address'])?></span>__.
  </p>

  <p class="indent">
    He / She has known to me of <b><i>good moral character</i></b> and can be trusted. He / She has never been involved in 
    any unlawful activities and a law-abiding citizen of this Barangay up to the present.
  </p>

  <p>
    This certification is being issued upon the request of the above-mentioned name for:
  </p>

  <!-- CHECKLIST -->
 <div class="checklist">
    <?php
      // Helper function
      function checkItem($rowPurpose, $text) {
          return ($rowPurpose === $text) ? "(✔) $text" : "( ) $text";
      }
      ?>

      <div><?= checkItem($row['purpose'], "Local Employment"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <?= checkItem($row['purpose'], "ID Renewal for PWD"); ?>
      </div>
      <div><?= checkItem($row['purpose'], "Hospital Requirement"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <?= checkItem($row['purpose'], "Transfer Residency"); ?>
      </div>
      <div><?= checkItem($row['purpose'], "Bank Transaction"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <?= checkItem($row['purpose'], "Proof Of Indigency"); ?>
      </div>
      <div><?= checkItem($row['purpose'], "School Requirement"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <?= checkItem($row['purpose'], "Proof Of Residency"); ?> <i>(Not valid for Loan Purposes)</i>
      </div>
      <div><?= checkItem($row['purpose'], "Financial Assistance"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <?= checkItem($row['purpose'], "Maynilad Requirement"); ?>
      </div>
      <div><?= checkItem($row['purpose'], "Medical Assistance"); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <?= checkItem($row['purpose'], "Others: TIN Requirements"); ?>
      </div>
  </div>


  <p class="indent">
    <b>WITNESS WHEREOF</b> I have hereunto set my hand and affixed the official seal of this office.
  </p>

 <p class="indent">
    Done in the City of Manila, this 
    <span class="underline">
        <?php echo date('jS'); ?>
    </span> 
    day of 
    <span class="underline">
        <?php echo date('F Y'); ?>
    </span>.
</p>


  <!-- SIGNATURE -->

  
  <div class="signature">
    <!-- <img src="../templates/captain.png" alt="Signature / Photo">  -->
    <p>Hon. FELIX "ELIE" TAGUBA</p>
    <p class="designation">PUNONG BARANGAY</p> 
  </div>
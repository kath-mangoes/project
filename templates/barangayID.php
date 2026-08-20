<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Barangay ID (Front & Back)</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex justify-center items-center min-h-screen bg-gray-200 p-6">

  <div class="flex gap-6">
    
    <!-- FRONT SIDE -->
    <div class="w-[700px] border-2 border-black bg-white p-6">
      
      <!-- Header -->
      <div class="text-center">
        <h1 class="font-bold text-sm uppercase">Republic of the Philippines</h1>
        <p class="text-xs">City of Manila</p>
        <p class="text-xs">District IV</p>
        <p class="text-xs font-bold uppercase">Barangay 400 Zone 41</p>
      </div>

      <!-- Logos -->
      <div class="flex justify-between items-center mt-2">
        <img src="https://upload.wikimedia.org/wikipedia/commons/4/42/Manila_Seal.svg" alt="Seal" class="w-16 h-16">
        <img src="https://upload.wikimedia.org/wikipedia/commons/1/12/Barangay_Logo.png" alt="Barangay Logo" class="w-16 h-16">
      </div>

      <!-- Title -->
      <h2 class="text-center font-bold mt-3 underline uppercase text-sm">
        Barangay Identification Card
      </h2>

      <!-- Certificate Text -->
      <div class="mt-4 text-xs leading-5">
        <p>This is to certify that</p>
        <div class="border-b border-black w-full mt-2"></div>
        <p class="mt-2">of <span class="border-b border-black inline-block w-64"></span></p>
        <p class="mt-2">
          Whose picture and signature appears hereon is a 
          <span class="font-bold uppercase">Registered Member</span> of this barangay.
        </p>
        <p class="mt-2">
          This identification card is being issued for whatever purpose it may serve.
        </p>
      </div>

      <!-- ID + Signature -->
      <div class="flex justify-between mt-6">
        <!-- Photo Box -->
        <div class="w-28 h-32 border-2 border-black flex items-center justify-center text-[10px]">
          PHOTO
        </div>
        
        <!-- Signature -->
        <div class="flex flex-col items-center justify-end">
          <div class="border-b border-black w-40"></div>
          <p class="text-[10px] mt-1">Signature</p>
        </div>
      </div>

      <!-- Footer -->
      <div class="mt-6 text-xs">
        <p>I.D. No.: <span class="border-b border-black inline-block w-40"></span></p>
        <p class="mt-2">Date of issuance: <span class="font-bold">VALID 1 YR UPON ISSUANCE</span></p>
      </div>

    </div>

    <!-- BACK SIDE -->
    <!-- BACK SIDE -->
<div class="w-[700px] border-2 border-black bg-white p-6 flex flex-col justify-between">

  <!-- Top content -->
  <div>
    <!-- Info Section -->
    <div class="grid grid-cols-2 gap-4 text-xs">
      <div>
        <p>Precinct No.: <span class="border-b border-black inline-block w-32"></span></p>
        <p class="mt-2">Date of Birth: <span class="border-b border-black inline-block w-32"></span></p>
        <p class="mt-2">Height: <span class="border-b border-black inline-block w-16"></span>
           Weight: <span class="border-b border-black inline-block w-16"></span></p>
        <p class="mt-2">SSS / GSIS No.: <span class="border-b border-black inline-block w-32"></span></p>
      </div>

      <div>
        <p>Blood Type: <span class="border-b border-black inline-block w-32"></span></p>
        <p class="mt-2">Place of Birth: <span class="border-b border-black inline-block w-32"></span></p>
        <p class="mt-2">Status: <span class="border-b border-black inline-block w-32"></span></p>
        <p class="mt-2">TIN No.: <span class="border-b border-black inline-block w-32"></span></p>
      </div>
    </div>

    <!-- Emergency Contact -->
    <div class="mt-6 text-xs">
      <p class="text-red-600 font-bold">IN CASE OF EMERGENCY, PLEASE NOTIFY:</p>
      <p class="mt-2">Name: <span class="border-b border-black inline-block w-64"></span></p>
      <p class="mt-2">Address: <span class="border-b border-black inline-block w-72"></span></p>
      <p class="mt-2">Contact No.: <span class="border-b border-black inline-block w-64"></span></p>
    </div>
  </div>

  <!-- Officials (Always at bottom) -->
  <div class="mt-10 flex justify-between items-center">
    <div class="text-center">
      <p class="font-bold underline">IMELDA M. SAQUNG</p>
      <p class="text-red-600 text-xs">Barangay Secretary</p>
    </div>

    <div class="flex flex-col items-center">
      <div class="w-20 h-20 border border-black mb-2 flex items-center justify-center text-[10px]">
        PHOTO
      </div>
      <p class="font-bold underline">Hon. FELIX C. TAGUBA</p>
      <p class="text-red-600 text-xs">Punong Barangay</p>
    </div>
  </div>

</div>

  </div>

</body>
</html>

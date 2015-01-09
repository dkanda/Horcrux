<?php
require_once("Horcrux.php");
$Horcrux = new Horcrux;
$Horcrux -> ReadFile("input.txt");
$output = $Horcrux -> Split(3);
// $Horcrux -> SaveSplitToDisk();
echo $Horcrux -> JoinTogether($output);


require_once("src/Endroid/QrCode/QrCode.php");
        $qrCode = new QrCode();
        $qrCode->setText("Life is too short to be generating QR codes");
        $qrCode->setSize(300);
        $qrCode->create();

        $this->assertTrue(true);

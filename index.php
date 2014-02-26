<?php
require_once("Horcrux.php");
$Horcrux = new Horcrux;
$Horcrux -> ReadFile("input.txt");
$Horcrux -> SetNumPieces(3);
$Horcrux -> Split();
$Horcrux -> SaveSplitToDisk();
$Horcrux -> JoinTogether();
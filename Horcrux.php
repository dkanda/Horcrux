<?php

class Horcrux {
	protected $inputFile;
	protected $keyString;
	protected $encryptedInputFile;
	protected $piecesArr = array();
	protected $lengthOfEachPiece;
	protected $outputFile;
	protected $encryptedFile;

	public function ReadFile($handle)
	{
		$this -> inputFile = file_get_contents($handle);
	}
	private function EncryptFile($string, $key)
	{
		return mcrypt_ecb (MCRYPT_3DES, $key, $string, MCRYPT_ENCRYPT);
	}

	private function DecryptFile($string, $key)
	{
		return mcrypt_ecb (MCRYPT_3DES, $key, $string, MCRYPT_DECRYPT);
	}
	
	public function ReadString($str)
	{
		$this -> inputFile = $str;
	}

	//encrypt and split encrypted file
	public function Split($numPieces)
	{
		$this -> keyString = $this -> genRandomString($numPieces);
		$encryptedFile = $this -> EncryptFile($this -> inputFile, $this -> keyString);
		
		$pieceLen = floor(strlen($encryptedFile)/$numPieces);
		for($i=0; $i<$numPieces; $i++)//split up according to # of desired pieces
			$this -> piecesArr[$i] = substr($encryptedFile, $pieceLen*$i, $pieceLen).$this->keyString[$i].str_pad($i, 3, "0", STR_PAD_LEFT);
		
		if(strlen($encryptedFile) % $numPieces != 0)//remaining piece get tacked on the end of last piece
			$this->piecesArr[$numPieces -1] = substr_replace($this->piecesArr[$numPieces -1],substr($encryptedFile,$pieceLen*($numPieces)),-4,0);
		return $this->piecesArr;
	}
	
	public function JoinTogether($arr)
	{
		$decryptKey = array();
		$encryptedString = "";
		foreach ($arr as $key => $value) {
				//get index of this piece
				$idx = intval(substr($value, -3,3));
				$decryptKey[$idx] = substr($value,-4,1);
				$encryptedString[$idx] = substr($value, 0, -4);
		}

		$decryptKey = implode($decryptKey,"");
		$encryptedString = implode($encryptedString,"");
		$this->outputFile = $this->DecryptFile($encryptedString, $decryptKey);
		return $this ->outputFile;
	}

	public function SaveSplitToDisk()
	{
		foreach ($this -> piecesArr as $key => $value) {
			file_put_contents("out".$key, $value);
		}
	}

	private function genRandomString($length) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	    $string = '';
	    for ($p = 0; $p < $length; $p++) {
	        $string .= $characters[mt_rand(0, strlen($characters))];
	    }
    return $string;
	}

	public function MakeQRCodes(){
		$qrCode = new Endroid\QrCode\QrCode();
		$qrCode->setText($this->piecesArr[0]);
		$qrCode->setSize(300);
		$qrCode->setPadding(10);
		$qrCode->render();
	}
}
<?php

class Horcrux {
	protected $inputFile;
	protected $keyString;
	protected $encryptedInputFile;
	protected $piecesArr = array();
	protected $lengthOfEachPiece;
	protected $numPieces;
	protected $outputFile;
	protected $encryptedFile;

	public function ReadFile($handle)
	{
		$this -> inputFile = file_get_contents($handle);
	}

	public function SetNumPieces($num)
	{
		$this-> numPieces = $num;
		$this-> keyString = $this -> genRandomString($num);
	}

	private function EncryptFile($string, $key)
	{
		return mcrypt_ecb (MCRYPT_3DES, $key, $string, MCRYPT_ENCRYPT);
	}

	private function DecryptFile($string, $key)
	{
		return mcrypt_ecb (MCRYPT_3DES, $key, $string, MCRYPT_DECRYPT);
	}

	//encrypt and split encrypted file
	public function Split()
	{
		$encryptedFile = $this -> EncryptFile($this -> inputFile, $this -> keyString);
		echo $encryptedFile."\n";
		for($i=0; $i<$this -> numPieces; $i++)//split up according to # of desired pieces
			$this -> piecesArr[$i] = substr($encryptedFile, (strlen($encryptedFile)/$this ->numPieces)*$i, strlen($encryptedFile)/$this ->numPieces).$this->keyString[$i];
		if(strlen($encryptedFile)/$this ->numPieces % $this ->numPieces != 0)//remaining pieces
			array_push($this-> piecesArr, substr($encryptedFile, ((strlen($encryptedFile)/$this ->numPieces)*$this -> numPieces)-1));
		print_r($this->piecesArr);
	}
	public function JoinTogether()
	{
		$decryptKey = "";


		foreach ($this -> piecesArr as $key => $value) {
			if($key < $this->numPieces)
			{
				$decryptKey .= substr($value,-1);
				$this-> encryptedFile .= substr($value, 0, -1);
			}
		}
		if($this->numPieces < count($this -> piecesArr))
			$this-> encryptedFile .= $this -> piecesArr[count($this -> piecesArr)-1];
		$this->outputFile = $this->DecryptFile($this->encryptedFile, $decryptKey);
		print($this ->outputFile);
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
}
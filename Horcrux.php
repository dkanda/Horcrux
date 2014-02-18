<?php

class Horcrux {
	protected $inputFile;
	protected $keyString;
	protected $encryptedInputFile;
	protected $piecesArr = array();
	protected $lengthOfEachPiece;

	public function ReadFile($handle)
	{
		$this -> inputFile = file_get_contents($handle);
	}

	public function SetKeyString($str)
	{
		$this -> str = $str;
	}

	private function EncryptFile($string, $key)
	{
		return mcrypt_ecb (MCRYPT_3DES, $key, $string, MCRYPT_ENCRYPT);
	}

	private function Split()
	{
		$keyString = $this -> str;
		$pieceLen = $this -> lengthOfEachPiece;
		for($i=0; $i<strlen($keyString); $i++)
		{
			$piecesArr[$i] = EncryptFile($)
		}
	}
}
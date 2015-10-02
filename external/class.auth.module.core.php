<?php
/*
	Описание:
	Класс обычной авторизации. Подходит для авторизации из базы данных mysql.

	Исключения:
	"Provided invalid credentials"
	"Auth empty string error"

*/
class BasicAuth {

/* Keys */
protected $RSA_BlockDivider = " ";

	/* Main methods */

	public function AuthOnSSHA($authPassword, $authHash)
	{
		$keyChain = file("core/keys.chain");
		$this->RSA_PrivateKey = $keyChain[0];
		$this->RSA_PublicKey = $keyChain[1];
		if(($authPassword == "") or ($authHash == ""))
		{
			throw new Exception("Provided invalid credentials");
			return false;
		}
		$authSalt = $this->getSalt($authHash);
		$formNewHash = $this->encode_SSHA($authPassword, $authSalt);
		if($formNewHash === $authHash)
		{
			return true;
		} else {
			return false;
		}
	}

	/* Session management */
	public function sessionEstablish($credentialContainer)
	{
		$_SESSION['id'] = $credentialContainer['id'];
		$_SESSION['login'] = $credentialContainer['login'];
		$_SESSION['name'] = $credentialContainer['name'];
		$_SESSION['key'] = $this->RSAEncode($credentialContainer['password']);
		$_SESSION['group'] = $credentialContainer['class'];
	}

	public function sessionVerify($userHash)
	{
		if(isset($_SESSION['id']) and isset($_SESSION['key']))
		{
			$getKeyValue = $_SESSION['key'];
			$getUserSSHA = $this->RSADecode($getKeyValue);
			if($getUserSSHA == $userHash)
			{
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function sessionRegenerate()
	{
		$sessionCache = $_SESSION;
		unset($_SESSION);
		//session_destroy();
		//session_start();
		$_SESSION = $sessionCache;
	}

	public function sessionDestroy()
	{
		if(isset($_SESSION['id']) and isset($_SESSION['key']))
		{
			unset($_SESSION);
			session_destroy();
		}
	}

	/* Cryptography */
	public function RSAEncode($Data)
	{
		$myKey = $this->acquireKeys($this->RSA_PublicKey);
		/*
			At this point we are having [0] element referring to "E"-keypart and [1] element referring to the "N"-keypart of standard RSA algorithm
		*/
		$procFactor = 5;
	        $passing = ceil(strlen($Data) / $procFactor);
		for($count = 0; $count < $passing; $count++)
		{
			$currentPiece = substr($Data, $count*$procFactor, $procFactor);
			$newPiece = '0';
			for($submerge = 0; $submerge < $procFactor; $submerge++)
			{
				$newPiece = bcadd($newPiece, bcmul(ord($currentPiece[$submerge]), bcpow('512', $submerge)));
			}

			$newPiece = bcpowmod($newPiece, $myKey[0], $myKey[1]);
			$completeString[] = $newPiece;
        	}
		$completeString = @implode($this->RSA_BlockDivider, $completeString);
	      	return $completeString;
	}

	public function RSADecode($cryptedData)
	{
		$myKey = $this->acquireKeys($this->RSA_PrivateKey);
		/*
			At this point we are having [0] element referring to "D"-keypart and [1] element referring to the "N"-keypart of standard RSA algorithm
		*/
		$overallArray = split($this->RSA_BlockDivider, $cryptedData);
		$overallElements = count($overallArray);
		for($count = 0; $count < $overallElements; $count++)
		{
			$currentPiece = bcpowmod($overallArray[$count], $myKey[0], $myKey[1]);

			while(bccomp($currentPiece, '0') != 0) 
			{
				$symbol = bcmod($currentPiece, '512');
				$currentPiece = bcdiv($currentPiece, '512', 0);
				$formedString .= chr($symbol);
			}
		}

		return $formedString;
	}

	private function acquireKeys($keyStamp)
	{
		$keyStamp = base64_decode($keyStamp);
		$keyArray = explode(" ",$keyStamp);
		$optKey = array($keyArray[1], $keyArray[0]);
		return $optKey;
	}

	/* Utility. Hashing, mostly */

	private function reduceArray(array $targetArray)
	{
		foreach ($targetArray as $arrayEntry)
		{
			if($arrayEntry != "")
			{
				$newArray[] = $arrayEntry;
			}
		}
		return $newArray;
	}

	public function getSalt($saltedHash)
	{
		if($saltedHash == "")
		{
			throw new Exception("Auth empty string error");
			return false;
		}		
		$hashHeader = preg_split("/{|}/", $saltedHash);
		if(is_array($hashHeader))
		{
			$hashHeader = $this->reduceArray($hashHeader);
		} else {
			return false;
		}
		switch($hashHeader[0])
		{
			case 'SSHA':
				$hashDecode = base64_decode($hashHeader[1]);
				$hashDecode = substr($hashDecode, -8);
				return $hashDecode;
			break;
			default:
				return false;
		}

	}

	public function encode_SSHA($encData, $encSalt)
	{
		if(($encData == "") or ($encSalt == ""))
		{
			throw new Exception("Auth empty string error");
			return false;
		}
		$sshaGeneric = "{SSHA}".base64_encode(sha1($encData.$encSalt,true).$encSalt);
		return $sshaGeneric;	
	}

	public function construct_SSHA($encData)
	{
		if($encData == "")
		{
			throw new Exception("Auth empty string error");
			return false;
		}
		$hashSalt = substr(md5(rand(0,255).rand(0,255).rand(0,255).rand(0,255)), 0, 8);
		$sshaGeneric = "{SSHA}".base64_encode(sha1($encData.$hashSalt,true).$hashSalt);
		return $sshaGeneric;
	}
}

?>

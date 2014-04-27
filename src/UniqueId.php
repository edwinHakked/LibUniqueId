<?php
namespace EdwinAkagi\LibUnqiueId;

class UniqueId implements \JsonSerializable{

    private $value;

    public function __construct($arg = FALSE){
        if($arg){
	    if(strlen($arg) == 32){
		return self::fromHex($arg);
	    }elseif(strlen($arg)==16){
		return self::fromBin($arg);
	    }else{
		throw new Exception\InvalidInputException();
	    }
	}else{
	    return self::generate();
	}
    }

    /**
     * Generate a UniqueId
     * @return UniqueId Object
     */
    public static function generate() {
	// Returns a random UUID without "-"
	// By Andrew Moore
	// http://www.php.net/manual/en/function.uniqid.php#94959
        $obj = new self();
	$obj->value = hex2bin(sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
		// 32 bits for "time_low"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff),
		// 16 bits for "time_mid"
		mt_rand(0, 0xffff),
		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand(0, 0x0fff) | 0x4000,
		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand(0, 0x3fff) | 0x8000,
		// 48 bits for "node"
		mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
	));
        return $obj;
    }

    /**
     * Generate a UniqueId object with Hex representation string input
     * @param type $uuid
     * @return type
     * @throws \Libraries\Exception
     * @throws UniqueId\InvalidHexInputException
     */
    public static function fromHex($uuid) {
	if ((strlen($uuid) == 32)) {
	    try {
		$raw = hex2bin($uuid);
		$obj = new self();
		$obj->value = $raw;
                return $obj;
	    } catch (\Exception $ex) {
		throw $ex;
	    }
	} else {
	    throw new Exception\InvalidHexInputException();
	}
    }

    /**
     * Create a UniqueId object from binrary string value
     * @param binrary $uuid
     * @return UniqueId
     * @throws \Libraries\Exception
     * @throws UniqueId\InvalidBinInputException
     */
    public static function fromBin($uuid) {
	if(strlen($uuid) == 16){
	    try{
		$obj = new self();
		$obj->value = $uuid;
                return $obj;
	    } catch (\Exception $ex) {
		throw $ex;
	    }
	}else{
	    throw new Exception\InvalidBinInputException();
	}
    }

    /**
     * Return the UniqueId in Hex representation in string format
     * @return String Return the UniqueId in Hex representation in string format
     */
    public function __toString() {
	return $this->toHex();
    }
    
    public function jsonSerialize(){
        return $this->toHex();
    }

    /**
     * Return the UniqueId in Hex representation in string format
     * @return String Return the UniqueId in Hex representation in string format
     */
    public function toHex() {
	return bin2hex($this->value);
    }

    /**
     * Return the UniqueId in binrary form
     * @return Binrary Return the UniqueId in binrary form
     */
    public function toBin() {
	return $this->value;
    }

}



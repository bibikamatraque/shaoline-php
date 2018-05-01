<?php
/**
 * This class is a Content Managed Object (Cmo) used to store RSA public/private keys
 * ShaBddTable : shaoline_rsa
 *
 * Ex :
 *
 * ...
 *
 * @package    Shaoline/Cms/Components
 * @category   ShaCmo class
 *
 * @license    Contact bastien.duho@free.fr
 * @author     Bastien DUHOT <bastien.duho@free.fr>
 *
 * @version    1.0
 */
class ShaRsa extends ShaCmo
{


	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName(){
		return "shaoline_rsa";
	}

	/**
	 * Return SQL crating request
	 *
	 * @return string
	 */
	public static function getTableDescription(){

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("key_id")->setType("INT")->setAutoIncremental()->end()
            ->addField("public_key")->setType("TEXT")->end()
            ->addField("private_key")->setType("TEXT")->end()
            ->addField("creation_date")->setType("TIMESTAMP")->setDefault("CURRENT_TIMESTAMP")->end()
        ;

        return $table;

	}
	
	/**
	 * Return random a recent key
	 */
	public static function getRandomKey($age) {
		return ShaRsa::loadByWhereClause("creation_date >= (NOW() - INTERVAL $age SECOND) ORDER BY RAND() ", true);
	}
	
	/**
	 * Clear old keys
	 * Create missing keys
	 * 
	 * @param int $age  Old key age in seconds
	 * @param int $qty  Min valid key in database
	 *
	 */
	public static function updateKeys($age, $qty){
		//Delete old keys
		ShaContext::bddExecute("DELETE FROM shaoline_rsa WHERE creation_date < (NOW() - INTERVAL ".($age + 3600 * 24)." SECOND) ");
		
		//Create missings keys 
		$currentQty = ShaContext::bddSelectValue("SELECT COUNT(1) as qty FROM shaoline_rsa WHERE creation_date >= (NOW() - INTERVAL $age SECOND)");
		if ($currentQty < $qty) {
			self::generateKeys(ShaUtilsRand::getRandInt(1, $qty - $currentQty));
		}
	}
	
	/**
	 * Generate N keys
	 * 
	 * echo "---- START ... " > privFile
	 * openssl rsa -in privFile -pubout > pubFile 
	 * openssl rsautl -inkey pubFile -pubin -encrypt -in plainText -out cryptedText
	 * openssl rsautl -inkey privFile -decrypt -in cryptedText -out decryptedText
	 * 
	 * @param int $qty Qty key to add
	 */
	public static function generateKeys($qty) {

		try {
			$config = array (
				"digest_alg" => "sha512",
				"private_key_bits" => 1024,
				"private_key_type" => OPENSSL_KEYTYPE_RSA
			);
		
			for($i = 0; $i < $qty; $i ++) {
		
				// Create the private and public key
				$res = openssl_pkey_new ( $config );
				if ($res === false){
					die(openssl_error_string());
				}

				// Extract the private key from $res to $privKey
				$privKey = "";
				openssl_pkey_export ( $res, $privKey );


				// Extract the public key from $res to $pubKey
				$pubKey = openssl_pkey_get_details ( $res );
				$pubKey = $pubKey ["key"];
		
				$shaRsa = new ShaRsa();
				$shaRsa->setValue("public_key", ShaUtilsString::cleanForSQL( $pubKey ));
				$shaRsa->setValue("private_key", ShaUtilsString::cleanForSQL ( $privKey ));
				$shaRsa->save();

				//openssl_free_key($pubKey);

			}
		
		} catch(Exception $e){
			die("ok");
		}
	
	}
	
	/**
	 * Return key
	 * 
	 * @param int $id
	 */
	public static function getKey($id) {

		$rsaKey = new ShaRsa();
		if (!ShaRsa::load((int)$id)){
			return NULL;
		}
		
		return $rsaKey;

	}
	
	public function crypt($msg) {
		$crypted = "";
		openssl_public_encrypt($msg, $crypted, $this->getValue("public_key"));
		return $crypted;
	}
	
	public function decrypt($msg) {
        try {
            $decrypted = "";
            $key = $this->getValue("private_key");
            $msg = base64_decode($msg);
            openssl_private_decrypt($msg, $decrypted,  $key);
            return $decrypted;
        }catch (Exception $e){
            die(openssl_error_string());
        }

	}
	
}

?>
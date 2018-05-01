<?php

/**
 * Description of ShaolineTreatmentInfo
 *
 * PHP version 5.3
 *
 * @category   Cms
 * @package    Core
 * @subpackage Component
 * @author     Bastien DUHOT <bastien.duhot@free.fr>
 * @license    Bastien DUHOT copyright
 * @link       No link
 *
 */
class ShaTreatmentInfo extends ShaCmo
{

	/**
	 * Return table name concerned by object
	 *
	 * @return string
	 */
	public static function getTableName() {
		return "shaoline_treatment_info";
	}


	/**
	 * Return SQL crating request
	 *
	 * @return string
	 */
	public static function getTableDescription() {

        $table = new ShaBddTable();
        $table
            ->setName(self::getTableName())
            ->addField("treatment_info_id")->setType("VARCHAR(100)")->setPrimary()->end()
            ->addField("treatment_info_group")->setType("VARCHAR(100)")->setPrimary()->setIndex()->end()
            ->addField("treatment_info_value")->setType("TEXT")->end()
        ;
        return $table;

	}

	/**
	 * Retrun true if the information exist
	 *
	 * @param string $id    Treatment id
	 * @param string $group Treatment group
	 *
	 * @return boolean
	 */
	public static function hasInfo($id, $group) {
		return self::bddExist("SELECT * FROM shaoline_treatment_info WHERE treatment_info_id = '" . $id . "' AND treatment_info_group='" . $group . "' LIMIT 0,1");
	}

	/**
	 * Return information
	 *
	 * @param string $id    Treatment id
	 * @param string $group Treatment group
	 *
	 * @return string
	 */
	public static function getInfo($id, $group) {
        return self::bddSelectValue("SELECT treatment_info_value as value FROM shaoline_treatment_info WHERE treatment_info_id = '" . $id . "' AND treatment_info_group='" . $group . "' group by treatment_info_id LIMIT 0,1");
	}

	/**
	 * Put an information
	 *
	 * @param string $id    Treatment id
	 * @param string $group Treatment group
	 * @param string $value Value
	 */
	public static function setInfo($id, $group, $value) {
		if (self::hasInfo($id, $group))
			$requete = "UPDATE shaoline_treatment_info SET treatment_info_value = '" . ShaUtilsString::cleanForSQL($value) . "' WHERE treatment_info_id='" . $id . "' AND treatment_info_group='" . $group . "'";
		else
			$requete = "INSERT INTO shaoline_treatment_info (treatment_info_id,treatment_info_value,treatment_info_group) VALUES ('" . $id . "','" . ShaUtilsString::cleanForSQL($value) . "','" . $group . "')";
		self::bddExecute($requete);
	}

	/**
	 * Increment the value of an information
	 *
	 * @param string $id    Treatment id
	 * @param string $group Treatment group
	 * @param int    $value Value
     */
	public static function incrementInfo($id, $group, $value) {
		if (!isset($value) && !ShaUtilsString::isRegexInteger($value)) {
            return;
        }

		$currentValue = self::getInfo($id, $group);
		if (isset($currentValue) && ShaUtilsString::isRegexInteger($currentValue)) {
			self::setInfo($id, $group, ((int)$currentValue + $value));
		} else {
			self::setInfo($id, $group, $value);
		}
	}

	/**
	 * Delete an information
	 *
	 * @param string $id    Treatment id
	 * @param string $group Treatment group
	 */
	public static function deleteInfo($id, $group) {
		self::bddDelete("DELETE FROM shaoline_treatment_info WHERE treatment_info_id='" . $id . "' AND treatment_info_group='" . $group . "'");
	}

	/**
	 * Clear a group of informations
	 *
	 * @param string $group Treatment group
	 */
	public static function clearGroupInfo($group) {
		self::bddDelete("DELETE FROM shaoline_treatment_info WHERE treatment_info_group='" . $group . "'");
	}

	/**
	 * Clear all information
	 */
	public static function clearAll() {
		self::bddExecute("TRUNCATE shaoline_treatment_info");
	}

	/**
	 * Concate inforamation
	 *
	 * @param string $id    Treatment id
	 * @param string $group Treatment group
	 * @param string $value Value
	 */
	public static function concateInfo($id, $group, $value) {
		if (!isset($value) && !ShaUtilsString::isRegexInteger($value)) {
            return;
        }

		if (self::hasInfo($id, $group)) {
            $requete = "UPDATE shaoline_treatment_info SET treatment_info_value = CONCAT(treatment_info_value,'" . ShaUtilsString::cleanForSQL($value) . "') WHERE treatment_info_id='" . $id . "' AND treatment_info_group='" . $group . "'";
        } else {
            $requete = "INSERT INTO shaoline_treatment_info (treatment_info_id,treatment_info_value,treatment_info_group) VALUES ('" . $id . "','" . ShaUtilsString::cleanForSQL($value) . "','" . $group . "')";
        }

		self::bddExecute($requete);
	}

}

?>
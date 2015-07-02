<?php
namespace Nijens\Utilities;

/**
 * Configuration class that adds caching
 *
 * @author Ron Rademaker
 * @since Thu Jul 2 2015
 */
class CachedConfiguration extends Configuration {
	/**
	 * Configuration cache
	 */
	private $configCache = ["alwaysArray" => [], "optionalArray" => [] ];

	/**
	 * get
	 *
	 * Overwritten get function to cache results (the json bit in Configuration is very slow)
	 *
	 * @since  Thu Jul 2 2015
	 * @access public
     * @param  string     $xpathExpression
     * @param  boolean    $alwaysReturnArray
     * @return array|null
     **/
	 public function get($xpathExpression, $alwaysReturnArray = false) {
		 if ($alwaysReturnArray) {
			 if (!array_key_exists($xpathExpression, $this->configCache["alwaysArray"]) ) {
				 $this->configCache["alwaysArray"][$xpathExpression] = parent::get($xpathExpression, $alwaysReturnArray);
			 }
			 return $this->configCache["alwaysArray"][$xpathExpression];
		 }
		 else {
			 if (!array_key_exists($xpathExpression, $this->configCache["optionalArray"]) ) {
				 $this->configCache["optionalArray"][$xpathExpression] = parent::get($xpathExpression, $alwaysReturnArray);
			 }
			 return $this->configCache["optionalArray"][$xpathExpression];
		 }
	 }
}

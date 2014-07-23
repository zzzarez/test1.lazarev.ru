<?php
/*
 * Simple PHP User agent
 * @version $Id: phpUserAgent.php,v 1.2 2010/11/27 12:02:50 Vlado Exp $
 * @package JotCache
 * @link      http://github.com/ornicar/php-user-agent
 * @version   1.0
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 * @license   MIT License
 *
 * Documentation: http://github.com/ornicar/php-user-agent/blob/master/README.markdown
 * Tickets:       http://github.com/ornicar/php-user-agent/issues
 */
require_once(dirname(__FILE__) . '/phpUserAgentStringParser.php');
class phpUserAgent {
protected $userAgentString;
protected $browserName;
protected $browserVersion;
protected $operatingSystem;
protected $engine;
public function __construct($userAgentString = null, phpUserAgentStringParser $userAgentStringParser = null) {
$this->configureFromUserAgentString($userAgentString, $userAgentStringParser);
}public function getBrowserName() {
return $this->browserName;
}public function setBrowserName($name) {
$this->browserName = $name;
}public function getBrowserVersion() {
return $this->browserVersion;
}public function setBrowserVersion($version) {
$this->browserVersion = $version;
}public function getOperatingSystem() {
return $this->operatingSystem;
}public function setOperatingSystem($operatingSystem) {
$this->operatingSystem = $operatingSystem;
}public function getEngine() {
return $this->engine;
}public function setEngine($engine) {
$this->engine = $engine;
}public function getUserAgentString() {
return $this->userAgentString;
}public function setUserAgentString($userAgentString) {
$this->userAgentString = $userAgentString;
}public function isUnknown() {
return empty($this->browserName);
}public function getFullName() {
return $this->getBrowserName() . ' ' . $this->getBrowserVersion();
}public function __toString() {
return $this->getFullName();
}public function configureFromUserAgentString($userAgentString, phpUserAgentStringParser $userAgentStringParser = null) {
if (null === $userAgentStringParser) {
$userAgentStringParser = new phpUserAgentStringParser();
}$this->setUserAgentString($userAgentString);
$this->fromArray($userAgentStringParser->parse($userAgentString));
}public function toArray() {
return array(
'browser_name' => $this->getBrowserName(),
'browser_version' => $this->getBrowserVersion(),
'operating_system' => $this->getOperatingSystem()
);}public function fromArray(array $data) {
$this->setBrowserName($data['browser_name']);
$this->setBrowserVersion($data['browser_version']);
$this->setOperatingSystem($data['operating_system']);
$this->setEngine($data['engine']);
}}
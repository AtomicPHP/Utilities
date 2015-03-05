<?php

namespace Nijens\Utilities;

use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;

/**
 * Configuration
 *
 * @author  Niels Nijens <niels@connectholland.nl>
 * @package Nijens\Utilities
 **/
class Configuration {

    /**
     * The location of the default XML configuration file
     *
     * @access private
     * @var    string|null
     **/
    private $defaultConfigurationFile;

    /**
     * The location of the XSD file for configuration validation
     *
     * @access private
     * @var    string|null
     **/
    private $xsdSchemaFile;

    /**
     * The DOMDocument instance with the loaded configuration
     *
     * @access private
     * @var    DOMDocument
     **/
    private $dom;

    /**
     * The DOMXPath instance for the loaded configuration
     *
     * @access private
     * @var    DOMXPath
     **/
    private $xpath;

    /**
     * __construct
     *
     * Constructs a new Configuration
     *
     * @access public
     * @param  string|null $defaultConfigurationFile Location of the default XML configuration file
     * @param  string|null $xsdSchemaFile            Location of the XSD file for configuration validation
     * @return void
     **/
    public function __construct($defaultConfigurationFile = null, $xsdSchemaFile = null) {
        if (is_file($defaultConfigurationFile) ) {
            $this->defaultConfigurationFile = $defaultConfigurationFile;
        }
        if (is_file($xsdSchemaFile) ) {
            $this->xsdSchemaFile = $xsdSchemaFile;
        }
    }

    /**
     * loadConfiguration
     *
     * Loads and validates the configuration file
     *
     * @api
     *
     * @access public
     * @param  string $configurationFile
     * @return void
     **/
    public function loadConfiguration($configurationFile) {
        if (is_file($configurationFile) ) {
            $dom = new DOMDocument("1.0", "UTF-8");
            $dom->load($configurationFile);

            $this->validateConfiguration($dom);
        }
        elseif ($configurationFile !== $this->defaultConfigurationFile && is_file($this->defaultConfigurationFile) ) {
            $this->loadConfiguration($this->defaultConfigurationFile);
        }
    }

    /**
     * getDOMDocument
     *
     * Returns the loaded DOMDocument instance
     *
     * @api
     *
     * @access public
     * @return DOMDocument
     **/
    public function getDOMDocument() {
        return $this->dom;
    }

    /**
     * get
     *
     * Returns an array representation of the XML nodes from the requested $xpathExpression
     *
     * @api
     *
     * @access public
     * @param  string     $xpathExpression
     * @param  boolean    $alwaysReturnArray
     * @return array|null
     **/
    public function get($xpathExpression, $alwaysReturnArray = false) {
        $nodeList = @$this->xpath->query($xpathExpression);
        if ($nodeList instanceof DOMNodeList) {
            if ($nodeList->length === 1) {
                if ($alwaysReturnArray === true) {
                    return array($this->nodeToArray($nodeList->item(0) ) );
                }

                return $this->nodeToArray($nodeList->item(0) );
            }
            else {
                $result = array();
                foreach ($nodeList as $node) {
                    $result[] = $this->nodeToArray($node);
                }

                return $result;
            }
        }
        elseif ($alwaysReturnArray === true) {
            return array();
        }
    }

    /**
     * toBoolean
     *
     * Returns the boolean value of $value
     *
     * @api
     *
     * @access public
     * @param  mixed   $value
     * @return boolean
     **/
    public static function toBoolean($value) {
        if (is_string($value) ) {
            if ($value === "true") {
                $value = true;
            }
            else {
                $value = false;
            }
        }
        elseif (is_bool($value) === false) {
            $value = false;
        }

        return $value;
    }

    /**
     * validateConfiguration
     *
     * Validates the configuration DOMDocument when a xsd schema file is available
     *
     * @access private
     * @param  DOMDocument $dom
     * @return void
     **/
    private function validateConfiguration(DOMDocument $dom) {
        if (is_file($this->xsdSchemaFile) ) {
            libxml_use_internal_errors(true);
            if (@$dom->schemaValidate($this->xsdSchemaFile/*, LIBXML_SCHEMA_CREATE*/) ) {
                $this->dom = $dom;
                $this->xpath = new DOMXPath($dom);

                $this->cleanupDOMDocument();
            }
            else {
                $this->triggerHumanReadableErrors();
                $this->loadConfiguration($this->defaultConfigurationFile);
            }

            libxml_use_internal_errors(false);
        }
    }

    /**
     * cleanupDOMDocument
     *
     * Removes comments, processing instructions and normalizes the DOMDocument instance
     *
     * @access private
     * @return void
     **/
    private function cleanupDOMDocument() {
        $expressions = array(
            "//comment()",
            "//processing-instruction()"
        );

        foreach ($expressions as $expression) {
            foreach ($this->xpath->query($expression) as $node) {
                $node->parentNode->removeChild($node);
            }
        }

        $this->dom->normalizeDocument();
    }

    /**
     * triggerHumanReadableErrors
     *
     * Triggers human readable warnings for XML and schema validation errors
     *
     * @codeCoverageIgnore
     *
     * @access private
     * @return void
     **/
    private function triggerHumanReadableErrors() {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            trigger_error("Line " . $error->line . ": '" . $error->message . "' in '" . $error->file . "' -", E_USER_WARNING);
        }

        libxml_clear_errors();
    }

    /**
     * nodeToArray
     *
     * Returns the array representation of $node
     *
     * @access private
     * @param  DOMNode $node
     * @return array
     **/
    private function nodeToArray(DOMNode $node) {
        $xml = simplexml_import_dom($node, "Nijens\\Utilities\\Configuration\\JSONSerializableXMLElement");
        $json = json_encode($xml);

        return json_decode($json, true);
    }
}

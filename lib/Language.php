<?php

namespace LiteLabel;

use \ArrayAccess;

/**
 * Language labels class
 *
 * @author	izisaurio
 * @version	1
 */
class Language implements ArrayAccess {
    /**
     * Language array
     * Loaded from json file
     * 
     * @access  public
     * @var     array
     */
    public $labels = [];

    /**
     * Current locale
     * 
     * @access  public
     * @var     string
     */
    public $locale;

    /**
     * Constructor
     * 
     * Load json file
     * 
     * @access  public
     * @param   string  $path   Language file to load
     * @param   string  $locale Default locale to load
     */
    public function __construct($path, $locale) {
        $this->labels = require $path;
        $this->locale = $locale;
    }

    /**
     * Parse label for lang file item
     * 
     * @access  private
     * @param   string   $label  Label to parse
     * @return  array
     */
    private function parse($label) {
        if (strpos($label, ':') === false) {
            return [$this->locale, $label];
        }
        return explode(':', $label);
    }

    /**
     * Get label with params
     * 
     * @access  public
     * @param   string  $label  Label to get
     * @param   array   $params Params to replace
     */
    public function get($label, $params = []) {
        list($locale, $label) = $this->parse($label);
        if (!isset($this->labels[$label])) {
            return null;
        }
        if (!isset($this->labels[$label][$locale])) {
            return null;
        }
        $text = $this->labels[$label][$locale];
        if (!empty($params)) {
            return vsprintf($text, $params);
        }
        return $text;
    }
    
    /**
     * Label exists in current locale
     * 
     * @access  public
     * @param   string  $offset  Label to check
     * @return  bool
     */
    public function offsetExists($offset): bool {
        list($locale, $label) = $this->parse($offset);
        if (!isset($this->labels[$label])) {
            return false;
        }
        return isset($this->labels[$offset][$locale]);
    }

    /**
     * Get label from current locale
     * 
     * @access  public
     * @param   string  $offset  Label to get
     * @return  string
     */
    public function offsetGet($offset): mixed {
        list($locale, $label) = $this->parse($offset);
        if (!isset($this->labels[$label])) {
            return null;
        }
        return $this->labels[$offset][$locale] ?? null;
    }

    /**
     * Set label in current locale
     * 
     * @access  public
     * @param   string  $offset  Label to set
     */
    public function offsetSet($offset, $value): void {
        list($locale, $label) = $this->parse($offset);
        $this->labels[$label][$locale] = $value;
    }

    /**
     * Unset label in current locale
     * 
     * @access  public
     * @param   string  $offset  Label to unset
     */
    public function offsetUnset($offset): void {
        list($locale, $label) = $this->parse($offset);
        unset($this->labels[$label][$locale]);
    }
}
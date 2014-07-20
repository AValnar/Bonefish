<?php

/**
 * This software is provided 'as-is', without any express or implied warranty.
 * In no event will the authors be held liable for any damages arising from the use of this software.
 * @author     Alexander Schmidt <mail@story75.com>
 * @copyright  Copyright (c) 2014, Alexander Schmidt
 * @version    1.0
 * @date       2014-01-16
 * @package    Bonefish
 * @subpackage Translation
 */

namespace Bonefish\Translation;

class Translate
{
    private $languages = array();
    private $default;
    private $fallback;
    private $safeMode = false;

    /**
     * @param array $languages
     * @param string $default
     * @param string $fallback
     */
    public function __construct($languages = array(), $default = 'en', $fallback = 'en')
    {
        $this->languages = $languages;
        $this->default = $default;
        $this->fallback = $fallback;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return string
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @return boolean
     */
    public function getSafeMode()
    {
        return $this->safeMode;
    }

    /**
     * @param array $languages
     */

    public function expandLanguages($languages)
    {
        $this->languages = array_merge_recursive($this->languages, $languages);
    }

    /**
     * When adding lots of languages files it can be that some keys may have more than one translation
     * and translate will return an array instead of the desired string
     *
     * With safe mode enabled it will always return a string but it will take more performance for every translate
     * call while safe mode is on
     *
     * @param bool $mode
     */

    public function setSafeMode($mode)
    {
        $this->safeMode = (bool)$mode;
    }

    /**
     * @param string $key
     */

    public function setDefault($key)
    {
        $this->default = $key;
    }

    /**
     * Set fallback language key if default can not be found, you should usually have one main
     * language as fallback and use default for user specific languages
     *
     * @param string $key
     */

    public function setFallback($key)
    {
        $this->fallback = $key;
    }

    /**
     * Translate by given key in following order: Default > Fallback > Key
     *
     * @param string $key
     * @return string
     */

    public function translate($key)
    {
        if (trim($key) == '') {
            return '';
        }

        if (isset($this->languages[$this->default][$key])) {
            $value = $this->languages[$this->default][$key];
            return ($this->safeMode ? $this->returnLastKey($value) : $value);
        }

        if (isset($this->languages[$this->fallback][$key])) {
            $value = $this->languages[$this->fallback][$key];
            return ($this->safeMode ? $this->returnLastKey($value) : $value);
        }

        return $key;
    }

    private function returnLastKey($value)
    {
        if (is_array($value)) {
            return end($value);
        }
        return $value;
    }
} 
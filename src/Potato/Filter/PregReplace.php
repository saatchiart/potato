<?php

namespace Demand\Potato\Filter;

/**
 * Class Regex
 *
 * Replace value based upon a set regex pattern.
 *
 * @package Demand\Potato\Filter
 */
class PregReplace extends AbstractFilter
{
    /**
     * Pattern to match
     * @var mixed
     */
    protected $matchPattern = null;

    /**
     * Replacement pattern
     * @var mixed
     */
    protected $replacement = '';

    /**
     * Constructor
     * Supported options are
     *     'matchPattern'   => matching pattern
     *     'replacement' => replace with this
     * Handles variable method calls
     * @param  string|array $options
     * @return void
     */
    public function __construct($options = null)
    {
        if (!is_array($options)) {
            $options = func_get_args();
            $temp = array();
            if (!empty($options)) {
                $temp['matchPattern'] = array_shift($options);
            }
            if (!empty($options)) {
                $temp['replacement'] = array_shift($options);
            }
            $options = $temp;
        }
        parent::__construct($options);
//        if (array_key_exists('match', $options)) {
//            $this->setMatchPattern($options['match']);
//        }
//        if (array_key_exists('replace', $options)) {
//            $this->setReplacement($options['replace']);
//        }
    }
    /**
     * Set the match pattern for the regex being called within filter()
     *
     * @param mixed $match - same as the first argument of preg_replace
     * @return PregReplace
     */
    public function setMatchPattern($match)
    {
        $this->matchPattern = $match;
        return $this;
    }
    /**
     * Get currently set match pattern
     *
     * @return string
     */
    public function getMatchPattern()
    {
        return $this->matchPattern;
    }
    /**
     * Set the Replacement pattern/string for the preg_replace called in filter
     *
     * @param mixed $replacement - same as the second argument of preg_replace
     * @return PregReplace
     */
    public function setReplacement($replacement)
    {
        $this->replacement = $replacement;
        return $this;
    }
    /**
     * Get currently set replacement value
     *
     * @return string
     */
    public function getReplacement()
    {
        return $this->replacement;
    }
    /**
     * Perform regexp replacement as filter
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        if ($this->matchPattern == null) {
            throw new Exception(get_class($this) . ' does not have a valid MatchPattern set.');
        }
        return preg_replace($this->matchPattern, $this->replacement, $value);
    }
}
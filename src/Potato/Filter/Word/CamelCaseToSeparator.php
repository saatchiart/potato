<?php

namespace Potato\Filter\Word;

class CamelCaseToSeparator extends AbstractSeparator
{
    public function filter($value)
    {
        $separator = $this->getSeparator();
        if (self::isUnicodeSupportEnabled()) {
            $this->setMatchPattern(array('#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#'));
            $this->setReplacement(array($separator . '\1', $separator . '\1'));
        } else {
            $this->setMatchPattern(array('#(?<=(?:[A-Z]))([A-Z]+)([A-Z][A-z])#', '#(?<=(?:[a-z0-9]))([A-Z])#'));
            $this->setReplacement(array('\1' . $separator . '\2', $separator . '\1'));
        }
        return parent::filter($value);
    }
}
<?php

namespace nk\DocumentBundle\Search;


class KeyWord
{
    const KEYWORD_TYPE = 1;
    const KEYWORD_CLASS = 2;
    const KEYWORD_FIELD = 3;
    const KEYWORD_UNIT = 4;
    const KEYWORD_TEACHER = 5;
    const KEYWORD_YEAR = 6;

    /**
     * @var string
     * The word itself
     */
    private $word;

    /**
     * @var string
     * null if an exact match was found, a similar word otherwise
     */
    private $suggestion = null;

    /**
     * @var array
     */
    private $metadata;

    /**
     * Type of data the word is representing if an exact match was found, null otherwise
     */
    private $type = null;

    public function __construct($word, array $metadata)
    {
        $this->metadata = $metadata;
        $this->word     = $this->strip_accents($word);

        $this->findType();
    }

    public function getType()
    {
        return $this->type;
    }

    public function getWord()
    {
        return $this->word;
    }

    public function getSuggestion()
    {
        return $this->suggestion;
    }

    private function strip_accents($string)
    {
        return strtr($string,
            'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
            'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'
        );
    }

    private function findType()
    {
        if($this->checkYear()) return;

        if($this->checkExactMatch('classes', KeyWord::KEYWORD_CLASS)) return;
        if($this->checkExactMatch('units', KeyWord::KEYWORD_UNIT)) return;
        if($this->checkExactMatch('types', KeyWord::KEYWORD_TYPE)) return;
        if($this->checkExactMatch('fields', KeyWord::KEYWORD_FIELD)) return;
        if($this->checkExactMatch('teachers', KeyWord::KEYWORD_TEACHER)) return;

        if($this->checkLevenshtein('types', 2)) return;
        if($this->checkLevenshtein('units', 2)) return;

        if($this->checkMetaphone('fields')) return;
        if($this->checkMetaphone('teachers')) return;
    }

    private function checkYear()
    {
        if(preg_match("/^20[0-9]{2}([ -]20[0-9]{2})?$/", $this->word))
            $this->type = KeyWord::KEYWORD_YEAR;
    }

    private function checkExactMatch($field, $type)
    {
        $temp = strtoupper($this->word);
        foreach($this->metadata[$field] as $match)
            if($temp == strtoupper($match)){
                $this->word = $match;
                $this->type = $type;
                return true;
            }

        return false;
    }

    private function checkMetaphone($field)
    {
        $temp = $this->metaphone($this->word);
        foreach($this->metadata[$field] as $match)
            if($temp == $this->metaphone($match)){
                $this->suggestion = $match;
                return true;
            }

        return false;
    }

    private function checkLevenshtein($field, $tolerance)
    {
        $temp = strtoupper($this->word);
        foreach($this->metadata[$field] as $match)
            if(levenshtein($temp, strtoupper($match)) <= $tolerance){
                $this->suggestion = $match;
                return true;
            }

        return false;
    }

    public function metaphone($str)
    {
        if(!strlen($str)) return '';

        $metaphone = '';
        $pos = 0;
        $voyels = str_split('AEIOUY');
        $str = strtoupper($this->strip_accents($str));

        while($pos < strlen($str)){
            while($pos < strlen($str) && in_array($str[$pos], $voyels))
                $pos++;

            $start = $pos;
            if($start == strlen($str) || in_array($str[$start], $voyels))
                break;

            while($pos < strlen($str) && !in_array($str[$pos], $voyels))
                $pos++;

            $length = $pos - $start;
            $group = substr($str, $start, $length);
            $nextLetter = ($start + $length <  strlen($str) ? $str[$start + $length]: '');
            $prevLetter = ($start > 0 ? $str[$start -1]: '');

            if(strlen($group) == 1){
                switch ($group){
                    case 'C':
                        if(in_array($nextLetter, array('E', 'I')))
                            $metaphone .= 'S';
                        else
                            $metaphone .= 'K';
                        break;

                    case 'G':
                        if(in_array($nextLetter, array('E', 'I')))
                            $metaphone .= 'J';
                        else
                            $metaphone .= 'G';
                        break;

                    case 'H':
                        break;

                    case 'Q':
                        $metaphone .= 'K';
                        break;

                    case 'S':
                        if($start +1 != strlen($str))
                            $metaphone .= 'Z';
                        break;

                    case 'X':
                        if($prevLetter == 'E' && $start == 1 || $start == 0)
                            $metaphone .= 'GZ';
                        else if($nextLetter == '')
                            $metaphone .= 'KS';
                        else if($prevLetter == 'U' && $start +1 == strlen($str));
                        else
                            $metaphone .= 'KS';
                        break;

                    default:
                        $metaphone .= $group;
                }
            }

            if(strlen($group) > 1){
                switch ($group){
                    case 'BB':
                        $metaphone .= 'B';
                        break;
                    case 'CH':
                        $metaphone .= 'CH';
                        break;
                    case 'CHR':
                        $metaphone .= 'KR';
                        break;
                    case 'CT':
                        $metaphone .= 'KS';
                        break;
                    case 'FF':
                        $metaphone .= 'F';
                        break;
                    case 'LL':
                        if($prevLetter != 'I')
                            $metaphone .= 'L';
                        break;
                    case 'MB':
                        $metaphone .= 'B';
                        break;
                    case 'MM':
                        $metaphone .= 'M';
                        break;
                    case 'ND':
                        $metaphone .= 'D';
                        break;
                    case 'NF':
                        $metaphone .= 'F';
                        break;
                    case 'NN':
                        $metaphone .= 'N';
                        break;
                    case 'NT':
                        break;
                    case 'PP':
                        $metaphone .= 'P';
                        break;
                    case 'PH':
                        $metaphone .= 'F';
                        break;
                    case 'RC':
                        $metaphone .= 'RS';
                        break;
                    case 'RR':
                        $metaphone .= 'S';
                        break;
                    case 'SS':
                        $metaphone .= 'S';
                        break;
                    case 'SH':
                        $metaphone .= 'CH';
                        break;
                    case 'SCH':
                        $metaphone .= 'CH';
                        break;
                    case 'THS':
                        $metaphone .= 'T';
                        break;
                    case 'TT':
                        $metaphone .= 'T';
                        break;

                    default:
                        foreach(str_split($group) as $letter){
                            switch ($letter){
                                case 'C':
                                    $metaphone .= 'K';
                                    break;
                                case 'H':
                                    break;
                                case 'Q':
                                    $metaphone .= 'K';
                                    break;
                                default:
                                    $metaphone .= $letter;
                            }
                        }
                }
            }
        }

        return $metaphone;
    }
}
<?php
namespace Fonts;

use GuzzleHttp\Client;

class GoogleFonts
{

    const WEBFONTURL = 'https://www.googleapis.com/webfonts/v1/webfonts';
    
    const SORT_BY = array(
        'alpha',
        'date',
        'popularity',
        'style',
        'trending'
    );
    
    /**
     * Google API key string
     * @var string
     */
    private $apiKey;
    
    /**
     * The location where the temporary JSON file should be stored
     * @var string
     */
    protected $file_location;

    /**
     * how fonts should be sorted when retrieved from Google
     * @var string
     */
    public $sortOrder = 'popularity';
    
    /**
     * This is the original font array retrieved from Google
     * @var array
     */
    protected $fontList;
    
    /**
     * This is the array of fonts ordered into types
     * @var array
     */
    protected $orderedList;
    
    /**
     * Error message to be displayed if no fonts exists
     * @var string
     */
    public $error_message = 'Error: No fonts exist with the given parameters';
    
    /**
     * Constructor
     * @param string|false $apiKey This should either be set to your Google API key or left empty
     */
    public function __construct($apiKey = false)
    {
        $this->setAPIKey($apiKey);
        $this->setFontFileLocation(dirname(dirname(__FILE__)).'/fonts/');
    }
    
    /**
     * Sets the Google API Key
     * @param string $apiKey This needs to be your Google API Key
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        if (is_string($apiKey) && !empty(trim($apiKey))) {
            $this->apiKey = trim($apiKey);
        }
        return $this;
    }
    
    /**
     * Returns the Google API key if it has been set else will return false
     * @return string|false This sill be the set Google API key or false
     */
    public function getApiKey()
    {
        if (is_string($this->apiKey)) {
            return $this->apiKey;
        }
        return false;
    }
    
    /**
     * Sets the file locations where the font lists are stored
     * @param string $location This should be the location that you wish to store the font list files
     * @return $this
     */
    public function setFontFileLocation($location)
    {
        if (!empty(trim($location)) && is_string($location)) {
            $this->file_location = trim($location);
            if (!is_dir($location)) {
                mkdir($location, 0777, true);
            }
        }
        return $this;
    }
    
    /**
     * This is the location where the fonts file is stored
     * @return string Returns the file storage location
     */
    public function getFontFileLocation()
    {
        return $this->file_location;
    }
    
    /**
     * Returns an array of weights available
     * @return array|string If any weights exist will return an array else will return the error_message
     */
    public function getFontWeights()
    {
        return $this->listFontTypes();
    }
    
    /**
     * Returns an array of subsets available
     * @return array|string If any subsets exist will return an array else will return the error_message
     */
    public function getFontSubsets()
    {
        return $this->listFontTypes('subset');
    }
    
    /**
     * Returns an array of types/categories available
     * @return array|string If any types/categories exist will return an array else will return the error_message
     */
    public function getFontTypes()
    {
        return $this->listFontTypes('type');
    }
    
    /**
     * Returns all of the fonts available with the selected weight/italic value
     * @param string $weight This should be the weight value
     * @return array|string
     */
    public function getFontsByWeight($weight)
    {
        if ($weight == '400') {
            $weight = 'regular';
        }
        if ($weight == '400italic') {
            $weight = 'italic';
        }
        return $this->listFonts(strtolower($weight));
    }
    
    /**
     * Returns an array of fonts available with a given subset
     * @param string $subset This should be the subset you ant to list the fonts by
     * @return array|string If any fonts exists an array will be returned else the error message will be returned
     */
    public function getFontsBySubset($subset)
    {
        return $this->listFonts(strtolower($subset), 'subset');
    }
    
    /**
     * Returns an array of fonts available with a given type/category
     * @param string $style This should be the font type that you want to list fonts by
     * @return array|string If any fonts exists an array will be returned else the error message will be returned
     */
    public function getFontsByType($style)
    {
        return $this->listFonts(strtolower($style), 'type');
    }
    
    /**
     * Sorts all of the retrieve fonts into a custom array
     * @param array|string $types This should be what you want to sort the fonts on
     * @param array $font this should be the font information array
     * @param string $style The main array item that you want to sort the font within e.g. weight, subset or category
     */
    protected function sortFontType($types, $font, $style = 'type')
    {
        if (is_array($types)) {
            foreach ($types as $type) {
                $this->orderedList[$style][$type][$font['family']] = array(($style === 'weight' ? 'file' : 'files') => ($style === 'weight' ? $font['files'][$type] : $font['files']));
            }
        } else {
            $this->orderedList[$style][$types][$font['family']] = array('files' => $font['files']);
        }
    }
    
    /**
     * Retrieve a list of all of the fonts from Google Fonts API
     */
    protected function retrieveFonts()
    {
        $guzzle = new Client();
        $fonts = $guzzle->request('GET', $this->googleFontsURI());
        if ($fonts->getStatusCode() === 200) {
            $this->fontList = json_decode($fonts->getBody(), true);
        }
    }
    
    /**
     * The Google fonts URL will be returned with the path information
     * @return string
     */
    public function googleFontsURI()
    {
        return self::WEBFONTURL . '?' . $this->buildQueryString();
    }
    
    /**
     * Builds the formatted URI path to retrieve the list of fonts from Google
     * @return string
     */
    protected function buildQueryString()
    {
        $queryString = array();
        $queryString['key'] = $this->getApiKey();
        if ($this->sortOrder) {
            $queryString['sort'] = $this->sortOrder;
        }
        return http_build_query($queryString);
    }
    
    /**
     * Retrieves the ordered Google Fonts file
     * @return boolean Returns true on success and false on failure
     */
    protected function getJSONFile()
    {
        if (!is_array($this->orderedList)) {
            if (file_exists($this->getFontFileLocation().'/fonts.json') && ((time() - filemtime($this->getFontFileLocation().'/fonts.json')) < 86400)) {
                $this->orderedList = json_decode(file_get_contents($this->getFontFileLocation().'fonts.json'), true);
                return true;
            }
        }
        return $this->sortFonts();
    }
    
    /**
     * Sorts all of the fonts into a custom JSON file
     * @return boolean If the file has successfully been created will return true else retruns false
     */
    protected function sortFonts()
    {
        $this->retrieveFonts();
        if (is_array($this->fontList)) {
            foreach ($this->fontList['items'] as $font) {
                $this->sortFontType($font['category'], $font);
                $this->sortFontType($font['variants'], $font, 'weight');
                $this->sortFontType($font['subsets'], $font, 'subset');
            }
            return $this->createJSONFile();
        }
        return false;
    }
    
    /**
     * Creates the temporary file containing the list of fonts
     * @return boolean Returns true on success false on failure
     */
    protected function createJSONFile()
    {
        $fp = fopen($this->getFontFileLocation().'/fonts.json', 'w');
        fwrite($fp, json_encode($this->orderedList));
        return fclose($fp);
    }
    
    /**
     * List all of the types available
     * @param string $list The type that you are listing
     * @return array|string If any types exist an array will be returned else will return the error message
     */
    protected function listFontTypes($list = 'weight')
    {
        $this->getJSONFile();
        if (array_key_exists($list, $this->orderedList)) {
            $array = array_keys($this->orderedList[$list]);
            sort($array);
            return $array;
        }
        return $this->error_message;
    }
    
    /**
     * Returns an ordered list of the Google Fonts available
     * @param string $option This should be the $option that you want all of the fonts for
     * @param string $list This needs to be the value that you are searching on
     * @return array|string If any fonts exist for the given parameters an array will be returned else will return the error message
     */
    protected function listFonts($option, $list = 'weight')
    {
        $this->getJSONFile();
        if (array_key_exists($option, $this->orderedList[$list])) {
            $array = array_keys($this->orderedList[$list][$option]);
            return $array;
        }
        return $this->error_message;
    }
}

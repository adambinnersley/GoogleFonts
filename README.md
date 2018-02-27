[![Build Status](https://api.travis-ci.org/AdamB7586/GoogleFonts.png)](https://api.travis-ci.org/AdamB7586/GoogleFonts)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/AdamB7586/GoogleFonts/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/AdamB7586/GoogleFonts/)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3-8892BF.svg?style=flat-circle)](https://php.net/)

# Google Fonts Lists PHP
Produces a PHP array of all of the fonts available in Google Fonts searchable by weight/subset/type;

## Installation

Installation is available via [Composer/Packagist](https://packagist.org/packages/adamb/google-fonts):

```sh
composer require adamb/google-fonts
```

You will also need to obtain a [Google Fonts API key](https://console.developers.google.com/apis/credentials) to use this package. 

## Usage

Example of usage can be found below:

### Set-up

```php
<?php
require "vendor\autoload.php";

use Fonts\GoogleFonts;

$googleAPIKey = 'my-api-key';

$fonts = new GoogleFonts($googleAPIKey);

```

### List Fonts
You can list fonts using 3 different methods (getFontsByWeight($weight), getFontsBySubset($subset) and getFontsByType($type). 

#### Example Usage
```php
// Lists the fonts with a weight of 300
$fonts->getFontsByWeight(300); // Returns array

// Lists the fonts with a weight of 500 and italic
$fonts->getFontsByWeight('500italic'); // Returns array

// Lists all of the fonts with the latin subset
$fonts->getFontsBySubset('latin'); // Returns array

// Lists all of the serif fonts
$fonts->getFontsByType('serif'); // Returns array

// Lists all of the handwriting fonts
$fonts->getFontsByType('handwriting'); // Returns array

```

### List Types Available

You can list the available options using 1 of 3 different methods (getFontWeights(), getFontSubsets() or getFontTypes())

#### Font Weights

##### PHP
```php

$weights = $fonts->getFontWeights();
print_r($weights);
```
##### HTML Output
```html
Array ( [0] => 100 [1] => 100italic [2] => 200 [3] => 200italic [4] => 300italic [5] => italic [6] => regular [7] => 300 [8] => 500 [9] => 500italic [10] => 600 [11] => 600italic [12] => 700 [13] => 700italic [14] => 800 [15] => 800italic [16] => 900 [17] => 900italic ) 
```

#### Font Subsets

##### PHP
```php
$subsets = $fonts->getFontSubsets();
print_r($subsets);
```
##### HTML Output
```html
Array ( [0] => arabic [1] => bengali [2] => cyrillic [3] => cyrillic-ext [4] => devanagari [5] => greek [6] => greek-ext [7] => gujarati [8] => gurmukhi [9] => hebrew [10] => kannada [11] => khmer [12] => korean [13] => latin [14] => latin-ext [15] => malayalam [16] => myanmar [17] => oriya [18] => sinhala [19] => tamil [20] => telugu [21] => thai [22] => vietnamese )
```


#### Font Types/Categories

##### PHP
```php
$types = $fonts->getFontTypes();
print_r($types);
```
##### HTML Output
```html
Array ( [0] => display [1] => handwriting [2] => monospace [3] => sans-serif [4] => serif ) 
```


## License

This software is distributed under the [MIT](https://github.com/AdamB7586/google-fonts/blob/master/LICENSE) license. Please read LICENSE for information on the
software availability and distribution.

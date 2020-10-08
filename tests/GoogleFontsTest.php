<?php

namespace Fonts\Tests;

use PHPUnit\Framework\TestCase;
use Fonts\GoogleFonts;

class GoogleFontsTest extends TestCase
{
    protected $googleFonts;
    
    private $testAPIKey = 'fake_key';

    /**
     * @covers Fonts\GoogleFonts::__construct
     * @covers Fonts\GoogleFonts::setAPIKey
     * @covers Fonts\GoogleFonts::setFontFileLocation
     */
    public function setUp(): void
    {
        $this->googleFonts = new GoogleFonts();
    }
    
    public function tearDown(): void
    {
        $this->googleFonts = null;
    }
    
    /**
     * @covers Fonts\GoogleFonts::__construct
     * @covers Fonts\GoogleFonts::setApiKey
     * @covers Fonts\GoogleFonts::getApiKey
     */
    public function testAPIKey()
    {
        $this->assertFalse($this->googleFonts->getApiKey());
        $this->assertObjectHasAttribute('sortOrder', $this->googleFonts->setApiKey(34534534535));
        $this->assertFalse($this->googleFonts->getApiKey());
        $this->assertObjectHasAttribute('sortOrder', $this->googleFonts->setApiKey('my-google-api-key'));
        $this->assertEquals('my-google-api-key', $this->googleFonts->getApiKey());
    }
    
    /**
     * @covers Fonts\GoogleFonts::setFontFileLocation
     * @covers Fonts\GoogleFonts::getFontFileLocation
     */
    public function testFileLocation()
    {
        $this->assertEquals(dirname(dirname(__FILE__)).'/fonts/', $this->googleFonts->getFontFileLocation());
        $this->assertObjectHasAttribute('sortOrder', $this->googleFonts->setFontFileLocation(false));
        $this->assertEquals(dirname(dirname(__FILE__)).'/fonts/', $this->googleFonts->getFontFileLocation());
        $this->assertObjectHasAttribute('sortOrder', $this->googleFonts->setFontFileLocation(dirname(dirname(__FILE__)).'/files/'));
        $this->assertEquals(dirname(dirname(__FILE__)).'/files/', $this->googleFonts->getFontFileLocation());
    }
    
    /**
     * @covers Fonts\GoogleFonts::getFontWeights
     * @covers Fonts\GoogleFonts::listFontTypes
     * @covers Fonts\GoogleFonts::getJSONFile
     * @covers Fonts\GoogleFonts::getFontFileLocation
     * @covers Fonts\GoogleFonts::sortFonts
     * @covers Fonts\GoogleFonts::retrieveFonts
     * @covers Fonts\GoogleFonts::googleFontsURI
     * @covers Fonts\GoogleFonts::buildQueryString
     * @covers Fonts\GoogleFonts::sortFontType
     * @covers Fonts\GoogleFonts::createJSONFile
     */
    public function testListFontWeights()
    {
        if (!isset($_SERVER['GOOGLE_API_KEY'])) {
            $this->markTestSkipped('You need to configure the GOOGLE_API_KEY value in phpunit.xml');
        }
        $this->googleFonts->setApiKey($_SERVER['GOOGLE_API_KEY']);
        $this->assertEquals('100', $this->googleFonts->getFontWeights()[0]);
        $this->assertContains('300italic', $this->googleFonts->getFontWeights());
    }
    
    /**
     * @covers Fonts\GoogleFonts::getFontsByWeight
     * @covers Fonts\GoogleFonts::listFonts
     * @covers Fonts\GoogleFonts::getJSONFile
     * @covers Fonts\GoogleFonts::getFontFileLocation
     * @covers Fonts\GoogleFonts::sortFonts
     * @covers Fonts\GoogleFonts::retrieveFonts
     * @covers Fonts\GoogleFonts::sortFontType
     * @covers Fonts\GoogleFonts::createJSONFile
     */
    public function testListFontByWeight()
    {
        if (!isset($_SERVER['GOOGLE_API_KEY'])) {
            $this->markTestSkipped('You need to configure the GOOGLE_API_KEY value in phpunit.xml');
        }
        $this->googleFonts->setApiKey($_SERVER['GOOGLE_API_KEY']);
        $this->assertEquals('Roboto', $this->googleFonts->getFontsByWeight(300)[0]);
        $this->assertContains('Error', $this->googleFonts->getFontsByWeight('hello-world'));
    }

    /**
     * @covers Fonts\GoogleFonts::getFontSubsets
     * @covers Fonts\GoogleFonts::listFontTypes
     * @covers Fonts\GoogleFonts::getJSONFile
     * @covers Fonts\GoogleFonts::getFontFileLocation
     * @covers Fonts\GoogleFonts::sortFonts
     * @covers Fonts\GoogleFonts::retrieveFonts
     * @covers Fonts\GoogleFonts::sortFontType
     * @covers Fonts\GoogleFonts::createJSONFile
     */
    public function testListFontSubsets()
    {
        if (!isset($_SERVER['GOOGLE_API_KEY'])) {
            $this->markTestSkipped('You need to configure the GOOGLE_API_KEY value in phpunit.xml');
        }
        $this->googleFonts->setApiKey($_SERVER['GOOGLE_API_KEY']);
        $this->assertContains('latin', $this->googleFonts->getFontSubsets());
    }
    
    /**
     * @covers Fonts\GoogleFonts::getFontsBySubset
     * @covers Fonts\GoogleFonts::listFonts
     * @covers Fonts\GoogleFonts::getJSONFile
     * @covers Fonts\GoogleFonts::getFontFileLocation
     * @covers Fonts\GoogleFonts::sortFonts
     * @covers Fonts\GoogleFonts::retrieveFonts
     * @covers Fonts\GoogleFonts::sortFontType
     * @covers Fonts\GoogleFonts::createJSONFile
     */
    public function testListFontBySubset()
    {
        if (!isset($_SERVER['GOOGLE_API_KEY'])) {
            $this->markTestSkipped('You need to configure the GOOGLE_API_KEY value in phpunit.xml');
        }
        $this->googleFonts->setApiKey($_SERVER['GOOGLE_API_KEY']);
        $this->assertEquals('Roboto', $this->googleFonts->getFontsBySubset('latin')[0]);
        $this->assertContains('Error', $this->googleFonts->getFontsBySubset('hello-world'));
    }
    
    /**
     * @covers Fonts\GoogleFonts::getFontTypes
     * @covers Fonts\GoogleFonts::listFontTypes
     * @covers Fonts\GoogleFonts::getJSONFile
     * @covers Fonts\GoogleFonts::getFontFileLocation
     * @covers Fonts\GoogleFonts::sortFonts
     * @covers Fonts\GoogleFonts::retrieveFonts
     * @covers Fonts\GoogleFonts::sortFontType
     * @covers Fonts\GoogleFonts::createJSONFile
     */
    public function testListFontTypes()
    {
        if (!isset($_SERVER['GOOGLE_API_KEY'])) {
            $this->markTestSkipped('You need to configure the GOOGLE_API_KEY value in phpunit.xml');
        }
        $this->googleFonts->setApiKey($_SERVER['GOOGLE_API_KEY']);
        $this->assertContains('serif', $this->googleFonts->getFontTypes());
        $this->assertContains('display', $this->googleFonts->getFontTypes());
    }
    
    /**
     * @covers Fonts\GoogleFonts::getFontsBySubset
     * @covers Fonts\GoogleFonts::listFonts
     * @covers Fonts\GoogleFonts::getJSONFile
     * @covers Fonts\GoogleFonts::getFontFileLocation
     * @covers Fonts\GoogleFonts::sortFonts
     * @covers Fonts\GoogleFonts::retrieveFonts
     * @covers Fonts\GoogleFonts::sortFontType
     * @covers Fonts\GoogleFonts::createJSONFile
     */
    public function testListFontByTypes()
    {
        if (!isset($_SERVER['GOOGLE_API_KEY'])) {
            $this->markTestSkipped('You need to configure the GOOGLE_API_KEY value in phpunit.xml');
        }
        $this->googleFonts->setApiKey($_SERVER['GOOGLE_API_KEY']);
        $this->assertEquals('Lobster', $this->googleFonts->getFontsByType('display')[0]);
        $this->assertContains('Error', $this->googleFonts->getFontsByType(457544));
    }
}

<?php

namespace DayRev\Summarizer\Tests\Unit;

use DayRev\Summarizer\Provider;

class ProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testProviderIsDayrev()
    {
        $provider = Provider::instance('dayrev');

        $this->assertInstanceOf('DayRev\Summarizer\Provider\Dayrev', $provider);
    }

    public function testProviderIsSmmry()
    {
        $provider = Provider::instance('smmry');

        $this->assertInstanceOf('DayRev\Summarizer\Provider\Smmry', $provider);
    }

    public function testProviderIsInvalid()
    {
        $provider = Provider::instance('captionizer');

        $this->assertFalse($provider);
    }

    public function testProviderMetaDataIsSet()
    {
        $provider = Provider::instance('dayrev', array('length' => 6));

        $this->assertObjectHasAttribute('length', $provider);
        $this->assertAttributeEquals(6, 'length', $provider);
    }
}

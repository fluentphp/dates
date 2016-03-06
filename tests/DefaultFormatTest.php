<?php

namespace FluentPhp\Dates\Tests;

use Carbon\Carbon;
use FluentPhp\Dates\Date;
use PHPUnit_Framework_TestCase;

class DefaultFormatTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test `Y-m-d` type formats are parsed correctly.
     */
    public function testParsingOfDateString()
    {
        $date = '1993-01-13';

        $assert = [
            '2016-01-25' => 'Y-m-d',
            '2016-25-01' => 'Y-d-m',
            '2014/11/30' => 'Y/m/d',
            '2014/21/10' => 'Y/d/m',
            '1999 01 01' => 'Y m d',
            '0001 01 01' => 'Y m d',
            '2016 01 12' => 'Y m d',
        ];

        foreach ($assert as $human => $robot) {
            $this->assertEquals(
                Carbon::parse($date)->format($robot),
                Date::parse($date)->like($human)
            );
        }
    }
}
<?php

require_once 'modules/iCals/iCal.php';
class iCalTest extends PHPUnit_Framework_TestCase
{
    public function test__construct()
    {
        //execute the contructor and check for the Object type and  attributes
        $ical = new iCal();
        $this->assertInstanceOf('iCal', $ical);
        $this->assertInstanceOf('vCal', $ical);
        $this->assertInstanceOf('SugarBean', $ical);
    }

    public function testgetVcalIcal()
    {
        error_reporting(E_ERROR | E_PARSE);

        $ical = new iCal();
        $user = new User(1);

        //break the string in two pieces leaving out date part.
        $expectedStart = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nMETHOD:PUBLISH\r\nX-WR-CALNAME:  (SugarCRM)\r\nPRODID:-//SugarCRM//SugarCRM Calendar//EN\r\nBEGIN:VTIMEZONE\r\nTZID:\r\nX-LIC-LOCATION:\r\nEND:VTIMEZONE\r\nCALSCALE:GREGORIAN";
        $expectedEnd = "\r\nEND:VCALENDAR\r\n";

        $actual = $ical->getVcalIcal($user, 6);

        //match the leading and trailing string parts to verify it returns expected results
        $this->assertStringStartsWith($expectedStart, $actual);
        $this->assertStringEndsWith($expectedEnd, $actual);
    }
}

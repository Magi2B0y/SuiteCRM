<?php

class AOW_ProcessedTest extends PHPUnit_Framework_TestCase
{
    public function testAOW_Processed()
    {

        //execute the contructor and check for the Object type and  attributes
        $aowProcessed = new AOW_Processed();
        $this->assertInstanceOf('AOW_Processed', $aowProcessed);
        $this->assertInstanceOf('Basic', $aowProcessed);
        $this->assertInstanceOf('SugarBean', $aowProcessed);

        $this->assertAttributeEquals('AOW_Processed', 'module_dir', $aowProcessed);
        $this->assertAttributeEquals('AOW_Processed', 'object_name', $aowProcessed);
        $this->assertAttributeEquals('aow_processed', 'table_name', $aowProcessed);
        $this->assertAttributeEquals(true, 'new_schema', $aowProcessed);
        $this->assertAttributeEquals(true, 'disable_row_level_security', $aowProcessed);
        $this->assertAttributeEquals(false, 'importable', $aowProcessed);
    }

    public function testbean_implements()
    {
        error_reporting(E_ERROR | E_PARSE);

        $aowProcessed = new AOW_Processed();
        $this->assertEquals(false, $aowProcessed->bean_implements('')); //test with blank value
        $this->assertEquals(false, $aowProcessed->bean_implements('test')); //test with invalid value
        $this->assertEquals(true, $aowProcessed->bean_implements('ACL')); //test with valid value
    }
}

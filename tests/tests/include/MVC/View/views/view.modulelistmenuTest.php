<?php

class ViewModulelistmenuTest extends PHPUnit_Framework_TestCase
{
    public function test__construct()
    {

        //execute the contructor and check for the Object type and options attribute
        $view = new ViewModulelistmenu();

        $this->assertInstanceOf('ViewModulelistmenu', $view);
        $this->assertInstanceOf('SugarView', $view);
        $this->assertTrue(is_array($view->options));
    }

    public function testdisplay()
    {
        //execute the method with required child objects preset. it should return some html. 
        $view = new ViewModulelistmenu();
        $view->ss = new Sugar_Smarty();

        ob_start();
        $view->display();
        $renderedContent = ob_get_contents();
        ob_end_clean();

        $this->assertGreaterThan(0, strlen($renderedContent));
        $this->assertEquals(false, is_array($renderedContent));
    }
}

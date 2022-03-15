<?php

class ViewQuickcreateTest extends PHPUnit_Framework_TestCase
{
    public function testpreDisplay()
    {

        //check without setting any values, it should execute without any issues.
        $view = new ViewQuickcreate();
        $view->preDisplay();
        $this->assertEquals(0, count($_REQUEST));

        //check with values preset but without a valid bean id, it sould not change Request parameters
        $_REQUEST['source_module'] = 'Users';
        $_REQUEST['module'] = 'Users';
        $_REQUEST['record'] = '';
        $request = $_REQUEST;

        $view->preDisplay();
        $this->assertSame($request, $_REQUEST);

        //check with values preset, it sould set some addiiotnal Request parameters
        $_REQUEST['record'] = 1;
        $view->preDisplay();
        $this->assertNotSame($request, $_REQUEST);
    }

    public function testdisplay()
    {
        error_reporting(E_ERROR | E_PARSE);

        //execute the method with required child objects and parameters preset. it will return some html.
        $view = new ViewQuickcreate();

        $_REQUEST['module'] = 'Accounts';
        $view->bean = new Account();

        ob_start();

        $view->display();

        $renderedContent = ob_get_contents();
        ob_end_clean();

        $this->assertGreaterThan(0, strlen($renderedContent));
        $this->assertEquals(false, json_decode($renderedContent)); //check that it doesn't return json. 
    }
}

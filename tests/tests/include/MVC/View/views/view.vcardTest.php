<?php


class ViewVcardTest extends PHPUnit_Framework_TestCase
{
    public function testdisplay()
    {
        //execute the method with required child objects preset and check for the Object type and type attribute

        $view = new ViewVcard();
        $view->module = 'Contacts';
        $view->bean = new Contact();

        //execute the method and test if it works and does not throws an exception other than headers output exception.
        try {
            $view->display();
        } catch (Exception $e) {
            $this->assertStringStartsWith('Cannot modify header information', $e->getMessage());
        }

        $this->assertInstanceOf('ViewVcard', $view);
        $this->assertInstanceOf('SugarView', $view);
        $this->assertAttributeEquals('detail', 'type', $view);
    }
}

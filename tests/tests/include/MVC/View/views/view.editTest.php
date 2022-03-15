<?php

 class ViewEditTest extends PHPUnit_Framework_TestCase
 {
     public function testViewEdit()
     {

        //execute the contructor and check for the Object type and attributes
        $view = new ViewEdit();
         $this->assertInstanceOf('ViewEdit', $view);
         $this->assertInstanceOf('SugarView', $view);
         $this->assertAttributeEquals('edit', 'type', $view);

         $this->assertAttributeEquals(false, 'useForSubpanel', $view);
         $this->assertAttributeEquals(false, 'useModuleQuickCreateTemplate', $view);
         $this->assertAttributeEquals(true, 'showTitle', $view);
     }

     public function testpreDisplay()
     {
         error_reporting(E_ERROR | E_PARSE);

        //execute the method with required attributes preset, it will initialize the ev(edit view) attribute.
        $view = new ViewEdit();
         $view->module = 'Users';
         $view->bean = new User();
         $view->preDisplay();
         $this->assertInstanceOf('EditView', $view->ev);

        //execute the method again for a different module with required attributes preset, it will initialize the ev(edit view) attribute.
        $view = new ViewEdit();
         $view->module = 'Meetings';
         $view->bean = new Meeting();
         $view->preDisplay();
         $this->assertInstanceOf('EditView', $view->ev);
     }

     public function testdisplay()
     {

        //execute the method with essential parameters set. it should return some html.
        $view = new ViewEdit();
         $view->module = 'Users';
         $view->bean = new User();
         $view->preDisplay();
         $view->ev->ss = new Sugar_Smarty();

         ob_start();
         $view->display();
         $renderedContent = ob_get_contents();
         ob_end_clean();
         $this->assertGreaterThan(0, strlen($renderedContent));
     }
 }

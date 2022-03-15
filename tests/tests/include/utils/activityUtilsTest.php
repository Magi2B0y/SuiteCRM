<?php

require_once 'include/utils/activity_utils.php';
class activity_utilsTest extends PHPUnit_Framework_TestCase
{
    public function testbuild_related_list_by_user_id()
    {
        error_reporting(E_ERROR | E_PARSE);

        //execute the method and test if it returns true

        //with rel_users_table manually set
        $bean = new User();
        $bean->rel_users_table = 'users_signatures';
        $list = build_related_list_by_user_id($bean, '1', '');
        $this->assertTrue(is_array($list));

        //with rel_users_table set by default 
        $bean = new Meeting();
        $list = build_related_list_by_user_id($bean, '1', '');
        $this->assertTrue(is_array($list));
    }
}

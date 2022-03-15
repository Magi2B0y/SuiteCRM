<?php

require_once 'include/utils/mvc_utils.php';
class mvc_utilsTest extends PHPUnit_Framework_TestCase
{
    public function testloadParentView()
    {
        //execute the method and test if it doesn't throws an exception
        try {
            loadParentView('classic');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testgetPrintLink()
    {
        error_reporting(E_ERROR | E_PARSE);

        //test without setting REQUEST param
        $expected = "javascript:void window.open('index.php?','printwin','menubar=1,status=0,resizable=1,scrollbars=1,toolbar=0,location=1')";
        $actual = getPrintLink();
        $this->assertSame($expected, $actual);

        //test with required REQUEST param set
        $_REQUEST['action'] = 'ajaxui';
        $expected = 'javascript:SUGAR.ajaxUI.print();';
        $actual = getPrintLink();
        $this->assertSame($expected, $actual);
    }

    public function testajaxBannedModules()
    {
        //execute the method and test verify it returns true
        $result = ajaxBannedModules();
        $this->assertTrue(is_array($result));
    }

    public function testajaxLink()
    {
        global $sugar_config;
        $ajaxUIDisabled = isset($sugar_config['disableAjaxUI']) && $sugar_config['disableAjaxUI'];

        if(!$ajaxUIDisabled) {
            $this->assertSame('?action=ajaxui#ajaxUILoc=', ajaxLink(''));
            $testModules = array(
                'Calendar',
                'Emails',
                'Campaigns',
                'Documents',
                'DocumentRevisions',
                'Project',
                'ProjectTask',
                'EmailMarketing',
                'CampaignLog',
                'CampaignTrackers',
                'Releases',
                'Groups',
                'EmailMan',
                "Administration",
                "ModuleBuilder",
                'Schedulers',
                'SchedulersJobs',
                'DynamicFields',
                'EditCustomFields',
                'EmailTemplates',
                'Users',
                'Currencies',
                'Trackers',
                'Connectors',
                'Import_1',
                'Import_2',
                'vCals',
                'CustomFields',
                'Roles',
                'Audit',
                'InboundEmail',
                'SavedSearch',
                'UserPreferences',
                'MergeRecords',
                'EmailAddresses',
                'Relationships',
                'Employees',
                'Import',
                'OAuthKeys'
            );
            $bannedModules = ajaxBannedModules();
            foreach($testModules as $module) {
                $uri = "index.php?module=$module&action=detail&record=1";
                if(!in_array($module, $bannedModules)) {
                    $this->assertSame("?action=ajaxui#ajaxUILoc=" . urlencode($uri), ajaxLink($uri));
                } else {
                    $this->assertSame($uri, ajaxLink($uri));
                }
            }
        }
    }
}

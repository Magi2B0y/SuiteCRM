<?php


class CampaignTest extends PHPUnit_Framework_TestCase
{
    public function testCampaign()
    {

        //execute the contructor and check for the Object type and  attributes
        $campaign = new Campaign();
        $this->assertInstanceOf('Campaign', $campaign);
        $this->assertInstanceOf('SugarBean', $campaign);

        $this->assertAttributeEquals('Campaigns', 'module_dir', $campaign);
        $this->assertAttributeEquals('Campaign', 'object_name', $campaign);
        $this->assertAttributeEquals('campaigns', 'table_name', $campaign);
        $this->assertAttributeEquals(true, 'new_schema', $campaign);
        $this->assertAttributeEquals(true, 'importable', $campaign);
    }

    public function testlist_view_parse_additional_sections()
    {
        error_reporting(E_ERROR | E_PARSE);

        $campaign = new Campaign();

        //test with attributes preset and verify template variables are set accordingly
        $tpl = new Sugar_Smarty();
        $campaign->list_view_parse_additional_sections($tpl);
        $this->assertEquals('', $tpl->_tpl_vars['ASSIGNED_USER_NAME']);
    }

    public function testget_summary_text()
    {
        $campaign = new Campaign();

        //test without setting name
        $this->assertEquals(null, $campaign->get_summary_text());

        //test with name set
        $campaign->name = 'test';
        $this->assertEquals('test', $campaign->get_summary_text());
    }

    public function testcreate_export_query()
    {
        $campaign = new Campaign();

        //test with empty string params
        $expected = "SELECT\n            campaigns.*,\n            users.user_name as assigned_user_name  FROM campaigns LEFT JOIN users\n                      ON campaigns.assigned_user_id=users.id where  campaigns.deleted=0 ORDER BY campaigns.name";
        $actual = $campaign->create_export_query('', '');
        $this->assertSame($expected, $actual);

        //test with valid string params
        $expected = "SELECT\n            campaigns.*,\n            users.user_name as assigned_user_name  FROM campaigns LEFT JOIN users\n                      ON campaigns.assigned_user_id=users.id where campaigns.name=\"\" AND  campaigns.deleted=0 ORDER BY campaigns.id";
        $actual = $campaign->create_export_query('campaigns.id', 'campaigns.name=""');
        $this->assertSame($expected, $actual);
    }

    public function testclear_campaign_prospect_list_relationship()
    {
        $campaign = new Campaign();

        //execute the method and test if it works and does not throws an exception.
        try {
            $campaign->clear_campaign_prospect_list_relationship('');
            $campaign->clear_campaign_prospect_list_relationship('1');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testmark_relationships_deleted()
    {
        $campaign = new Campaign();

        //execute the method and test if it works and does not throws an exception.
        try {
            $campaign->mark_relationships_deleted('');
            $campaign->mark_relationships_deleted('1');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testfill_in_additional_list_fields()
    {
        $campaign = new Campaign();

        //execute the method and test if it works and does not throws an exception.
        try {
            $campaign->fill_in_additional_list_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testfill_in_additional_detail_fields()
    {
        $campaign = new Campaign();

        //execute the method and test if it works and does not throws an exception.
        try {
            $campaign->fill_in_additional_detail_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testupdate_currency_id()
    {
        $campaign = new Campaign();

        //execute the method and test if it works and does not throws an exception.
        try {
            $campaign->update_currency_id('', '');
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testget_list_view_data()
    {
        $campaign = new Campaign();

        $current_theme = SugarThemeRegistry::current();
        //execute the method and verify that it returns expected results

        $expected = array(
                'DELETED' => 0,
                'TRACKER_COUNT' => '0',
                'REFER_URL' => 'http://',
                'IMPRESSIONS' => '0',
                'OPTIONAL_LINK' => 'display:none',
                'TRACK_CAMPAIGN_TITLE' => 'View Status',
            // The theme may fallback to default so we only care that the icon is the same.
                'TRACK_CAMPAIGN_IMAGE' => '~images/view_status~',
                'LAUNCH_WIZARD_TITLE' => 'Launch Wizard',
            // The theme may fallback to default so we only care that the icon is the same.
                'LAUNCH_WIZARD_IMAGE' => '~images/edit_wizard~',
                'TRACK_VIEW_ALT_TEXT' => 'View Status',
                'LAUNCH_WIZ_ALT_TEXT' => 'Launch Wizard',
        );

        $actual = $campaign->get_list_view_data();
        foreach ($expected as $expectedKey => $expectedVal) {
            if ($expectedKey == 'LAUNCH_WIZARD_IMAGE' || $expectedKey == 'TRACK_CAMPAIGN_IMAGE') {
                $this->assertRegExp($expected[$expectedKey], $actual[$expectedKey]);
            } else {
                $this->assertSame($expected[$expectedKey], $actual[$expectedKey]);
            }
        }
    }

    public function testbuild_generic_where_clause()
    {
        $campaign = new Campaign();

        //test with blank parameter
        $expected = "campaigns.name like '%'";
        $actual = $campaign->build_generic_where_clause('');
        $this->assertSame($expected, $actual);

        //test with valid parameter
        $expected = "campaigns.name like '1%'";
        $actual = $campaign->build_generic_where_clause(1);
        $this->assertSame($expected, $actual);
    }

    public function testSaveAndMarkDeleted()
    {
        $campaign = new Campaign();
        $campaign->name = 'test';
        $campaign->amount = 100;

        $campaign->save();

        //test for record ID to verify that record is saved
        $this->assertTrue(isset($campaign->id));
        $this->assertEquals(36, strlen($campaign->id));

        //mark the record as deleted and verify that this record cannot be retrieved anymore.
        $campaign->mark_deleted($campaign->id);
        $result = $campaign->retrieve($campaign->id);
        $this->assertEquals(null, $result);
    }

    public function testset_notification_body()
    {
        $campaign = new Campaign();

        //test with attributes preset and verify template variables are set accordingly
        $campaign->name = 'test';
        $campaign->budget = '1000';
        $campaign->end_date = '10/01/2015';
        $campaign->status = 'Planned';
        $campaign->content = 'some text';

        $result = $campaign->set_notification_body(new Sugar_Smarty(), $campaign);

        $this->assertEquals($campaign->name, $result->_tpl_vars['CAMPAIGN_NAME']);
        $this->assertEquals($campaign->budget, $result->_tpl_vars['CAMPAIGN_AMOUNT']);
        $this->assertEquals($campaign->end_date, $result->_tpl_vars['CAMPAIGN_CLOSEDATE']);
        $this->assertEquals($campaign->status, $result->_tpl_vars['CAMPAIGN_STATUS']);
        $this->assertEquals($campaign->content, $result->_tpl_vars['CAMPAIGN_DESCRIPTION']);
    }

    public function testtrack_log_leads()
    {
        $campaign = new Campaign();

        $expected = "SELECT campaign_log.*  FROM campaign_log WHERE campaign_log.campaign_id = '' AND campaign_log.deleted=0 AND activity_type = 'lead' AND archived = 0 AND target_id IS NOT NULL ";
        $actual = $campaign->track_log_leads();
        $this->assertSame($expected, $actual);
    }

    public function testtrack_log_entries()
    {
        $campaign = new Campaign();

        //test without parameters
        $expected = "SELECT campaign_log.*  FROM campaign_log WHERE campaign_log.campaign_id = '' AND campaign_log.deleted=0 AND activity_type='targeted' AND archived=0 ";
        $actual = $campaign->track_log_entries();
        $this->assertSame($expected, $actual);

        //test with parameters
        $expected = "SELECT campaign_log.*  FROM campaign_log WHERE campaign_log.campaign_id = '' AND campaign_log.deleted=0 AND activity_type='test1' AND archived=0 ";
        $actual = $campaign->track_log_entries(array('test1', 'test2'));
        $this->assertSame($expected, $actual);
    }

    public function testget_queue_items()
    {
        $campaign = new Campaign();

        //without parameters
        $expected = "SELECT emailman.* ,\n					campaigns.name as campaign_name,\n					email_marketing.name as message_name,\n					(CASE related_type\n						WHEN 'Contacts' THEN LTRIM(RTRIM(CONCAT(IFNULL(contacts.first_name,''),'&nbsp;',IFNULL(contacts.last_name,''))))\n						WHEN 'Leads' THEN LTRIM(RTRIM(CONCAT(IFNULL(leads.first_name,''),'&nbsp;',IFNULL(leads.last_name,''))))\n						WHEN 'Accounts' THEN accounts.name\n						WHEN 'Users' THEN LTRIM(RTRIM(CONCAT(IFNULL(users.first_name,''),'&nbsp;',IFNULL(users.last_name,''))))\n						WHEN 'Prospects' THEN LTRIM(RTRIM(CONCAT(IFNULL(prospects.first_name,''),'&nbsp;',IFNULL(prospects.last_name,''))))\n					END) recipient_name FROM emailman\n		            LEFT JOIN users ON users.id = emailman.related_id and emailman.related_type ='Users'\n					LEFT JOIN contacts ON contacts.id = emailman.related_id and emailman.related_type ='Contacts'\n					LEFT JOIN leads ON leads.id = emailman.related_id and emailman.related_type ='Leads'\n					LEFT JOIN accounts ON accounts.id = emailman.related_id and emailman.related_type ='Accounts'\n					LEFT JOIN prospects ON prospects.id = emailman.related_id and emailman.related_type ='Prospects'\n					LEFT JOIN prospect_lists ON prospect_lists.id = emailman.list_id\n                    LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = emailman.related_id and emailman.related_type = email_addr_bean_rel.bean_module and email_addr_bean_rel.primary_address = 1 and email_addr_bean_rel.deleted=0\n					LEFT JOIN campaigns ON campaigns.id = emailman.campaign_id\n					LEFT JOIN email_marketing ON email_marketing.id = emailman.marketing_id WHERE  emailman.campaign_id = '' AND emailman.deleted=0 AND  emailman.deleted=0";
        $actual = $campaign->get_queue_items();
        $this->assertSame($expected, $actual);

        //with parameters		
        $expected = "SELECT emailman.* ,\n					campaigns.name as campaign_name,\n					email_marketing.name as message_name,\n					(CASE related_type\n						WHEN 'Contacts' THEN LTRIM(RTRIM(CONCAT(IFNULL(contacts.first_name,''),'&nbsp;',IFNULL(contacts.last_name,''))))\n						WHEN 'Leads' THEN LTRIM(RTRIM(CONCAT(IFNULL(leads.first_name,''),'&nbsp;',IFNULL(leads.last_name,''))))\n						WHEN 'Accounts' THEN accounts.name\n						WHEN 'Users' THEN LTRIM(RTRIM(CONCAT(IFNULL(users.first_name,''),'&nbsp;',IFNULL(users.last_name,''))))\n						WHEN 'Prospects' THEN LTRIM(RTRIM(CONCAT(IFNULL(prospects.first_name,''),'&nbsp;',IFNULL(prospects.last_name,''))))\n					END) recipient_name FROM emailman\n		            LEFT JOIN users ON users.id = emailman.related_id and emailman.related_type ='Users'\n					LEFT JOIN contacts ON contacts.id = emailman.related_id and emailman.related_type ='Contacts'\n					LEFT JOIN leads ON leads.id = emailman.related_id and emailman.related_type ='Leads'\n					LEFT JOIN accounts ON accounts.id = emailman.related_id and emailman.related_type ='Accounts'\n					LEFT JOIN prospects ON prospects.id = emailman.related_id and emailman.related_type ='Prospects'\n					LEFT JOIN prospect_lists ON prospect_lists.id = emailman.list_id\n                    LEFT JOIN email_addr_bean_rel ON email_addr_bean_rel.bean_id = emailman.related_id and emailman.related_type = email_addr_bean_rel.bean_module and email_addr_bean_rel.primary_address = 1 and email_addr_bean_rel.deleted=0\n					LEFT JOIN campaigns ON campaigns.id = emailman.campaign_id\n					LEFT JOIN email_marketing ON email_marketing.id = emailman.marketing_id INNER JOIN (select min(id) as id from emailman  em GROUP BY users.id  ) secondary\n			           on emailman.id = secondary.id	WHERE  emailman.campaign_id = '' AND emailman.deleted=0 AND marketing_id ='1'  AND  emailman.deleted=0";
        $actual = $campaign->get_queue_items(array('EMAIL_MARKETING_ID_VALUE' => 1, 'group_by' => 'users.id'));
        $this->assertSame($expected, $actual);
    }

    public function testbean_implements()
    {
        $campaign = new Campaign();
        $this->assertEquals(false, $campaign->bean_implements('')); //test with blank value
        $this->assertEquals(false, $campaign->bean_implements('test')); //test with invalid value
        $this->assertEquals(true, $campaign->bean_implements('ACL')); //test with valid value
    }

    public function testcreate_list_count_query()
    {
        $campaign = new Campaign();

        //test without parameters
        $expected = '';
        $actual = $campaign->create_list_count_query('');
        $this->assertSame($expected, $actual);

        //test with query parameters
        $expected = 'SELECT count(*) c FROM campaigns';
        $actual = $campaign->create_list_count_query('select * from campaigns');
        $this->assertSame($expected, $actual);

        //test with distinct
        $expected = 'SELECT count(DISTINCT campaigns.id) c FROM campaigns';
        $actual = $campaign->create_list_count_query('SELECT distinct marketing_id FROM campaigns');
        $this->assertSame($expected, $actual);
    }

    public function testgetDeletedCampaignLogLeadsCount()
    {
        $campaign = new Campaign();
        $result = $campaign->getDeletedCampaignLogLeadsCount();
        $this->assertEquals(0, $result);
    }
}

<?php


class DocumentTest extends PHPUnit_Framework_TestCase
{
    public function testDocument()
    {

        //execute the contructor and check for the Object type and  attributes
        $document = new Document();
        $this->assertInstanceOf('Document', $document);
        $this->assertInstanceOf('File', $document);
        $this->assertInstanceOf('SugarBean', $document);

        $this->assertAttributeEquals('Documents', 'module_dir', $document);
        $this->assertAttributeEquals('Document', 'object_name', $document);
        $this->assertAttributeEquals('documents', 'table_name', $document);
        $this->assertAttributeEquals(true, 'new_schema', $document);
        $this->assertAttributeEquals(false, 'disable_row_level_security', $document);
    }

    public function testSaveAndGet_document_name()
    {
        error_reporting(E_ERROR | E_PARSE);

        $document = new Document();

        $document->filename = 'test';
        $document->file_url = 'test_url';
        $document->file_url_noimage = 'test_image_url';
        $document->last_rev_created_name = 'test';
        $document->category_id = '1';
        $document->subcategory_id = '1';
        $document->document_name = 'test';

        $document->save();

        //test for record ID to verify that record is saved
        $this->assertTrue(isset($document->id));
        $this->assertEquals(36, strlen($document->id));

        //execute Get_document_name() method and verify it gets the name correctly
        $this->assertEquals(null, $document->get_document_name(1));
        $this->assertEquals('test', $document->get_document_name($document->id));

        //mark the record as deleted and verify that this record cannot be retrieved anymore.
        $document->mark_deleted($document->id);
        $result = $document->retrieve($document->id);
        $this->assertEquals(null, $result);
    }

    public function testget_summary_text()
    {
        $document = new Document();

        //test without setting name
        $this->assertEquals(null, $document->get_summary_text());

        //test with name set
        $document->document_name = 'test';
        $this->assertEquals('test', $document->get_summary_text());
    }

    public function testis_authenticated()
    {
        $document = new Document();

        //test without presetting attributes
        $this->assertEquals(null, $document->is_authenticated());

        //test with attributes preset
        $document->authenticated = true;
        $this->assertEquals(true, $document->is_authenticated());
    }

    public function testfill_in_additional_list_fields()
    {
        $document = new Document();

        //execute the method and test if it works and does not throws an exception.
        try {
            $document->fill_in_additional_list_fields();
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testfill_in_additional_detail_fields()
    {
        $document = new Document();
        $current_theme = SugarThemeRegistry::current();
        $document->id = 'abcde-12345';

        //execute the method with attributes preset and verify attributes are set accordingly
        $document->fill_in_additional_detail_fields();

        // test the urls instead of the a tag itself
        $this->assertRegExp('~/images/def_image_inline~', $document->file_url);
        $this->assertRegExp('~index.php\?entryPoint=download&id=&type=Documents~', $document->file_url);
        //
        $this->assertEquals('index.php?entryPoint=download&type=Documents&id=', $document->file_url_noimage);
    }

    public function testlist_view_parse_additional_sections()
    {
        $document = new Document();

        //execute the method and test if it works and does not throws an exception.
        try {
            $document->list_view_parse_additional_sections(new Sugar_Smarty(), $xTemplateSection);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testcreate_export_query()
    {
        $document = new Document();

        //test with empty string parameters
        $expected = "SELECT\n						documents.* FROM documents  WHERE  documents.deleted = 0 ORDER BY documents.document_name";
        $actual = $document->create_export_query('', '');
        $this->assertSame($expected, $actual);

        //test with valid string parameters
        $expected = "SELECT\n						documents.* FROM documents  WHERE documents.document_name = \"\" AND  documents.deleted = 0 ORDER BY documents.id";
        $actual = $document->create_export_query('documents.id', 'documents.document_name = ""');
        $this->assertSame($expected, $actual);
    }

    public function testget_list_view_data()
    {
        $document = new Document();
        $current_theme = SugarThemeRegistry::current();
        //execute the method and verify that it retunrs expected results

        $document->filename = 'test';
        $document->file_url = 'test_url';
        $document->file_url_noimage = 'test_image_url';
        $document->last_rev_created_name = 'test';
        $document->category_id = '1';
        $document->subcategory_id = '1';
        $document->document_name = 'test';

        $expected = array(
                'DELETED' => 0,
                'DOCUMENT_NAME' => 'test',
                'DOC_TYPE' => 'Sugar',
                'FILENAME' => 'test',
                'ACTIVE_DATE' => $document->active_date,
                'CATEGORY_ID' => null,
                'SUBCATEGORY_ID' => null,
                'REVISION' => '1',
                'LAST_REV_CREATED_NAME' => 'test',
                'IS_TEMPLATE' => '0',
                'FILE_URL' => '~'
                                .'<a href=\'index.php\?entryPoint=download\&id=\&type=Documents\' target=\'_blank\'><img src="themes/\w+/images/def_image_inline\.\w+\?v='
                                .'~',
                'FILE_URL_NOIMAGE' => 'index.php?entryPoint=download&type=Documents&id=',
                'LAST_REV_CREATED_BY' => 'test',
                'NAME' => 'test',
                'DOCUMENT_NAME_JAVASCRIPT' => null,
        );

        $actual = $document->get_list_view_data();
        foreach ($expected as $expectedKey => $expectedVal) {
            if ($expectedKey == 'FILE_URL') {
                $this->assertRegExp($expected[$expectedKey], $actual[$expectedKey]);
            } else {
                $this->assertSame($expected[$expectedKey], $actual[$expectedKey]);
            }
        }
    }

    public function testmark_relationships_deleted()
    {
        $document = new Document();

        //execute the method and test if it works and does not throws an exception.
        try {
            $document->mark_relationships_deleted(1);
            $this->assertTrue(true);
        } catch (Exception $e) {
            $this->fail();
        }
    }

    public function testbean_implements()
    {
        $document = new Document();
        $this->assertEquals(false, $document->bean_implements('')); //test with blank value
        $this->assertEquals(false, $document->bean_implements('test')); //test with invalid value
        $this->assertEquals(true, $document->bean_implements('ACL')); //test with valid value
    }
}

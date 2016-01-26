<?php

class GridFieldBlogPostStateTest extends SapphireTest {

    /**
     * @var string
     */
    public static $fixture_file = 'blog.yml';

    public function setUp() {
        $this->gridField = new GridField(
            'BlogPosts',
            'Blog Posts',
            BlogPost::get(), // all blog posts, hey it's a test
            new GridFieldConfig_BlogPost()
        );
        SS_Datetime::set_mock_now('2013-10-10 20:00:00');
        parent::setUp();
    }

    public function tearDown() {
        SS_Datetime::clear_mock_now();
        parent::tearDown();
    }

	public function testGetColumnContent() {
		$state = $this->gridField->getConfig()->getComponentByType('GridFieldBlogPostState');
        $record = $this->objFromFixture('BlogPost', 'PostC');
        $val = $state->getColumnContent($this->gridField, $record, 'State');
        $expected = '<i class="btn-icon gridfield-icon btn-icon-pencil"></i> Saved as Draft on 10/10/2013 8:00pm';
        $this->assertEquals($expected, $val);

        $record->doPublish();
        $val = $state->getColumnContent($this->gridField, $record, 'State');
        $expected = '<i class="btn-icon gridfield-icon btn-icon-accept"></i> Published on 09/01/2012 3:00pm';
        $this->assertEquals($expected, $val);

        $record->Title = 'A test edit on stage';
        $record->write();
        $val = $state->getColumnContent($this->gridField, $record, 'State');
        $expected = '<i class="btn-icon gridfield-icon btn-icon-accept"></i> Published on 09/01/2012 3:00pm<span class="modified"></span>';
        $this->assertEquals($expected, $val);

	}

	public function testGetColumnAttributes() {
		$state = $this->gridField->getConfig()->getComponentByType('GridFieldBlogPostState');
        $record = $this->objFromFixture('BlogPost', 'PostC');
        $val = $state->getColumnAttributes($this->gridField, $record, 'State');
        $expected = array('class' => 'gridfield-icon draft');
        $this->assertEquals($expected, $val);

        $record->doPublish();
        $val = $state->getColumnAttributes($this->gridField, $record, 'State');
        $expected = array('class' => 'gridfield-icon published');
        $this->assertEquals($expected, $val);

        $record->Title = 'A test edit on stage';
        $record->write();
        $val = $state->getColumnAttributes($this->gridField, $record, 'State');
        $expected = array('class' => 'gridfield-icon published');
        $this->assertEquals($expected, $val);

	}

}

<?php

class GridFieldFormActionTest extends SapphireTest {
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
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

	public function testSetExtraAttributes() {
        $fields = new FieldList(
            $this->gridField
        );

        $action = new GridFieldFormAction(
        $this->gridField,
            'Name',
            'Title',
            'ActionName',
            array()
        );

        $actions = new FieldList(
            $action
        );
        $form = new Form(Controller::curr(), 'Form', $fields, $actions);
        $baseAttributes = $action->getAttributes();
        $extraAttributes = array(
            'data-example' => 'an example',
            'data-another-example' => 'another example'
        );
        $action->setExtraAttributes($extraAttributes);
        $newAttributes = $action->getAttributes();
        $diff = array_diff($newAttributes, $baseAttributes);
        $this->assertEquals($extraAttributes, $diff);
	}

}

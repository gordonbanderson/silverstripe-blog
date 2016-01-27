<?php

class GridFieldAddByDBFieldTest extends SapphireTest {


    /**
     * @var string
     */
    public static $fixture_file = 'blog.yml';

    public function setUp() {
        parent::setUp();

        $blog = $this->objFromFixture('Blog', 'FourthBlog');
        $posts = new ArrayList();
        foreach ($blog->AllChildren() as $post) {
            $posts->push($post);
        }
        $this->gridField = new GridField(
            'BlogPosts',
            'Blog Posts',
            // use BlogPost::get() to run through error condition
            $posts,
            new GridFieldConfig_BlogPost()
        );
        SS_Datetime::set_mock_now('2013-10-10 20:00:00');
    }

    public function tearDown() {
        SS_Datetime::clear_mock_now();
        parent::tearDown();
    }

	public function testGetActions() {
		$field = new GridFieldAddByDBField();
        $this->assertEquals(array('add'), $field->getActions($this->gridField));
	}

    /*
    Use an ArrayList which can be added to
     */
	public function testHandleActionValidList() {
        $field = new GridFieldAddByDBField();
        $list = $this->gridField->getList();
        $this->assertEquals(7, $list->count());
        $data = array(
            'gridfieldaddbydbfield' =>
            array('BlogPost' => array('Title' => 'New Blog Post Title'))
        );
        $field->handleAction($this->gridField, 'add', array(), $data);

        $list = $this->gridField->getList();

        // should have one more item
        $this->assertEquals(8, $list->count());

        $last = $list->last();
        $this->assertEquals('New Blog Post Title', $last->Title);
    }

    /*
    Use an immutable list, this will trigger the case where add() does not work
     */
    public function testHandleActionInvalidList() {
        $field = new GridFieldAddByDBField();
        // this list cannot be added to so add function will not work
        $this->gridField->setList(BlogPost::get());
        $list = $this->gridField->getList();
        $this->assertEquals(12, $list->count());
        $data = array(
            'gridfieldaddbydbfield' =>
            array('BlogPost' => array('Title' => 'New Blog Post Title'))
        );
        $field->handleAction($this->gridField, 'add', array(), $data);
        $error = $this->gridField->Message();
        $this->assertEquals('Unable to save BlogPost to the database.', $error);
        $list = $this->gridField->getList();

        // should have same number of items
        $this->assertEquals(12, $list->count());
    }

    public function testHandleActionInvalidAction() {
        $field = new GridFieldAddByDBField();
        $list = $this->gridField->getList();

        $this->assertEquals(7, $list->count());
        $data = array(
            'gridfieldaddbydbfield' =>
            array('BlogPost' => array('Title' => 'New Blog Post Title'))
        );
        // this is not a valid action
        $field->handleAction($this->gridField, 'addNOT', array(), $data);
        $error = $this->gridField->Message();
        $this->assertEquals(null, $error);
        $list = $this->gridField->getList();

        // should have same number of items
        $this->assertEquals(7, $list->count());
    }

    public function testHandleActionInvalidField() {
        $field = new GridFieldAddByDBField();

        // use any field that does not exist
        $field->setDataObjectField('TitleNOT');
        $list = $this->gridField->getList();

        $this->assertEquals(7, $list->count());
        $this->setExpectedException('UnexpectedValueException',
                                    'Invalid field (TitleNOT) on BlogPost.');
        $data = array(
            'gridfieldaddbydbfield' =>
            array('BlogPost' => array('TitleNOT' => 'New Blog Post Title'))
        );
        // this is not a valid action
        $field->handleAction($this->gridField, 'add', array(), $data);
    }

    public function testHandleActionLoggedOut() {
        $member = Member::currentUser();
        $member->logout();

        $field = new GridFieldAddByDBField();

        // use any field that does not exist
        $field->setDataObjectField('Title');
        $list = $this->gridField->getList();
        $this->assertEquals(7, $list->count());
        $data = array(
            'gridfieldaddbydbfield' =>
            array('BlogPost' => array('Title' => 'New Blog Post Title'))
        );
        $field->handleAction($this->gridField, 'add', array(), $data);
        $list = $this->gridField->getList();
        $this->assertEquals(7, $list->count());
    }

	public function testGetSetDataObjectField() {
		$field = new GridFieldAddByDBField();
        $fieldnames = array('Title', 'ID', 'Something');
        foreach($fieldnames as $name) {
            $field->setDataObjectField($name);
            $this->assertEquals($name, $field->getDataObjectField());
        }
	}

}

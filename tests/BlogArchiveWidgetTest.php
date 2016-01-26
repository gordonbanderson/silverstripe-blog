<?php

class BlogArchiveWidgetTest extends SapphireTest {

    /**
     * @var string
     */
    public static $fixture_file = 'blog.yml';

	public function testGetCMSFields() {
        $blog = $this->objFromFixture('Blog', 'FirstBlog');
        $widget = new BlogArchiveWidget();
        $widget->BlogID = $blog->ID;
        $widget->write();
        $fields = $widget->getCMSFields();
        $names = array();

        foreach ($fields as $field) {
            array_push($names, $field->getName());
        }
        $expected = array('Title', 'Enabled', 'BlogID', 'ArchiveType',
                            'NumberToDisplay');
        $this->assertEquals($expected, $names);
    }

	public function testGetArchive() {
		$blog = $this->objFromFixture('Blog', 'FirstBlog');
        $widget = new BlogArchiveWidget();
        $widget->BlogID = $blog->ID;
        $widget->write();
        $widget->ArchiveType = 'Yearly';
        $widget->NumberToDisplay = 100;
        $results = $widget->getArchive();
        foreach ($results as $result) {
            error_log($result);
        }
	}

}

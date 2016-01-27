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

	public function testGetArchiveYearly() {
        $blog = $this->objFromFixture('Blog', 'FirstBlog');
        $widget = new BlogArchiveWidget();
        $widget->BlogID = $blog->ID;
        $widget->write();
        $widget->ArchiveType = 'Yearly';
        $widget->NumberToDisplay = 100;
        $results = $widget->getArchive()->toArray();
        $check = array();
        foreach ($results as $result) {
            $info = array();
            $mapping = $result->toMap();
            $info['Title'] = $mapping['Title'];
            $info['Link'] = $mapping['Link'];
            array_push($check, $info);
        }

        $expected = array(
            array(
                'Title' => '2015',
                'Link' => '/first-blog/archive/2015'
            ),
            array(
                'Title' => '2013',
                'Link' => '/first-blog/archive/2013'
            ),
            array(
                'Title' => '2012',
                'Link' => '/first-blog/archive/2012'
            )
        );
        $this->assertEquals($expected, $check);
    }


    public function testGetArchiveMonthly() {
        $blog = $this->objFromFixture('Blog', 'FirstBlog');
        $widget = new BlogArchiveWidget();
        $widget->BlogID = $blog->ID;
        $widget->write();
        $widget->ArchiveType = 'Monthly';
        $widget->NumberToDisplay = 100;
        $results = $widget->getArchive()->toArray();
        $check = array();
        foreach ($results as $result) {
            $info = array();
            $mapping = $result->toMap();
            $info['Title'] = $mapping['Title'];
            $info['Link'] = $mapping['Link'];
            array_push($check, $info);
        }

        $expected = array(
            array(
                'Title' => 'January 2015',
                'Link' => '/first-blog/archive/2015/01'
            ),
            array(
                'Title' => 'November 2013',
                'Link' => '/first-blog/archive/2013/11'
            ),
            array(
                'Title' => 'October 2013',
                'Link' => '/first-blog/archive/2013/10'
            ),
            array(
                'Title' => 'September 2013',
                'Link' => '/first-blog/archive/2013/09'
            ),
            array(
                'Title' => 'January 2012',
                'Link' => '/first-blog/archive/2012/01'
            )
        );
        $this->assertEquals($expected, $check);
    }

}

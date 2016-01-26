<?php

class BlogCategoriesWidgetTest extends SapphireTest {

    /**
     * @var string
     */
    public static $fixture_file = 'blog.yml';

	public function testGetCMSFields() {
		$blog = $this->objFromFixture('Blog', 'FirstBlog');
        $widget = new BlogCategoriesWidget();
        $widget->BlogID = $blog->ID;
        $widget->write();
        $fields = $widget->getCMSFields();
        $names = array();

        foreach ($fields as $field) {
            array_push($names, $field->getName());
        }
        $expected = array('Title', 'Enabled', 'BlogID', 'Limit', 'Order',
                            'Direction');
        $this->assertEquals($expected, $names);
	}

	public function testGetCategories() {
		$blog = $this->objFromFixture('Blog', 'FourthBlog');
        $widget = new BlogCategoriesWidget();
        $widget->BlogID = $blog->ID;
        $widget->write();

        // Check limit from 1 to 4
        for ($i=1; $i <= 4 ; $i++) {
            echo $i;
            $widget->Limit = $i;
            $categories = $widget->getCategories();
            $this->assertListIsBlogCategoryOfLength($categories, $i);
        }

        // Check 0, or unlimited case.  Should return all, in this case 4
        $widget->Limit = 0;
        $categories = $widget->getCategories();
        $this->assertListIsBlogCategoryOfLength($categories, 4);

        // Check for more than there is.  Should return all, in this case 4
        $widget->Limit = 10;
        $categories = $widget->getCategories();
        $this->assertListIsBlogCategoryOfLength($categories, 4);



	}

    private function assertListIsBlogCategoryOfLength($categories, $amount) {
        $this->assertEquals($amount, $categories->count());
        foreach ($categories as $category) {
            $this->assertInstanceOf('BlogCategory', $category);
        }
    }

}

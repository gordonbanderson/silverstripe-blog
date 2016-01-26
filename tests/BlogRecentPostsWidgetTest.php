<?php

class BlogRecentPostsWidgetTest extends SapphireTest {

    /**
     * @var string
     */
    public static $fixture_file = 'blog.yml';

	public function testGetCMSFields() {
        $blog = $this->objFromFixture('Blog', 'FirstBlog');
        $widget = new BlogRecentPostsWidget();
        $widget->BlogID = $blog->ID;
        $widget->write();
        $fields = $widget->getCMSFields();
        $names = array();

        foreach ($fields as $field) {
            array_push($names, $field->getName());
        }
        $expected = array('Title', 'Enabled', 'BlogID', 'NumberOfPosts');
        $this->assertEquals($expected, $names);
    }

	public function testGetPosts() {
		$blog = $this->objFromFixture('Blog', 'FirstBlog');
        $widget = new BlogRecentPostsWidget();
        $widget->BlogID = $blog->ID;
        $widget->write();
        $widget->NumberOfPosts = 4;
        $posts = $widget->getPosts();
        $this->checkPosts($posts, 4);

        $widget->NumberOfPosts = 2;
        $posts = $widget->getPosts();
        $this->checkPosts($posts, 2);

        $widget->NumberOfPosts = 100;
        $posts = $widget->getPosts();
        $this->checkPosts($posts, 5);
	}

    /**
     * Check that there are a certain number of posts and that they are in
     * reverse date order
     * @param  SS_List $posts          posts from widget
     * @param  int $expectedAmount     expected number of results
     */
    private function checkPosts($posts, $expectedAmount) {
        $this->assertEquals($expectedAmount, $posts->count());
        $date = 0;
        if ($posts->count() > 0) {
            $date = $posts->first()->PublishDate;
            foreach ($posts as $post) {
                $this->assertLessThanOrEqual($date,$post->PublishDate);
                $date = $post->PublishDate;
            }
        }

    }

}

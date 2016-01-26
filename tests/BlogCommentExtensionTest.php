<?php

class BlogCommentExtensionTest extends SapphireTest {
    /**
     * @var string
     */
    public static $fixture_file = 'blog.yml';

    public function setUp() {
        $this->requiredExtensions = array('BlogPost' => array('BlogCommentExtension'));
        parent::setUp();
    }

    /*
    If comment is by a blog author add an extra CSS class
     */
    public function testGetExtraClass() {
        if (!class_exists('Comment')) {
            $this->markTestSkipped('Comments module is not installed');
        }
        $blogPost = $this->objFromFixture('BlogPost', 'FirstBlogPost');
        $comment = new Comment();
        $comment->BaseClass = 'BlogPost';
        $comment->comment = 'This is a comment';
        $comment->ParentID = $blogPost->ID;
        $comment->write();
        $this->assertEquals('', $comment->getExtraClass());

        $this->logInWithPermission('ADMIN');
        $authors = $blogPost->Authors();
        $authors->add(Member::currentUser());

        $comment->AuthorID = Member::currentUserID();
        $comment->write();
        $this->assertEquals('author-comment', $comment->getExtraClass());
	}

}

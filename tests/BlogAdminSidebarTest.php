<?php

class BlogAdminSidebarTest extends SapphireTest {

    /*
    Only values of null and 1 are valid
     */
    public function testIsOpen() {
        $bar = new BlogAdminSidebar();

        Cookie::set('blog-admin-sidebar',1);
        $this->assertTrue($bar->isOpen());

        Cookie::set('blog-admin-sidebar',null);
        $this->assertTrue($bar->isOpen());

        Cookie::set('blog-admin-sidebar',0);
        $this->assertFalse($bar->isOpen());

        Cookie::set('blog-admin-sidebar','fred');
        $this->assertFalse($bar->isOpen());
    }

}

<?php

class BlogAdminSidebarTest extends SapphireTest {

    /*
    Only values of null and 1 are valid
     */
    public function testIsOpen() {
        $bar = new BlogAdminSidebar();

        // no cookie set
        $this->assertTrue($bar->isOpen());

        error_log('testIsOpen: T1');
        Cookie::set('blog-admin-sidebar',1);
        $this->assertTrue($bar->isOpen());

        error_log('testIsOpen: T2');
        Cookie::set('blog-admin-sidebar',null);
        $this->assertTrue($bar->isOpen());

        error_log('testIsOpen: T3');
        Cookie::set('blog-admin-sidebar',0);
        $this->assertFalse($bar->isOpen());

        error_log('testIsOpen: T4');
        Cookie::set('blog-admin-sidebar','fred');
        $this->assertFalse($bar->isOpen());
    }

}

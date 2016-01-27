<?php

class BlogMemberExtensionTest extends SapphireTest {

    /**
     * @var string
     */
    public static $fixture_file = 'blog.yml';

	public function testOnBeforeWrite() {
		$member1 = new Member();
        $member1->FirstName = 'Fred';
        $member1->Surname = 'Bloggs';
        $member1->write();
        $this->assertEquals('fred-bloggs', $member1->URLSegment);

        $member2 = new Member();
        $member2->FirstName = 'Fred';
        $member2->Surname = 'Bloggs';
        $member2->write();
        $this->assertEquals('fred-bloggs-1', $member2->URLSegment);

        $member3 = new Member();
        $member3->FirstName = 'Fred';
        $member3->Surname = 'Bloggs';
        $member3->write();
        $this->assertEquals('fred-bloggs-2', $member3->URLSegment);
	}

	public function testUpdateCMSFields() {
		$member = $this->objFromFixture('Member', 'Editor');
        $fields = $member->getCMSFields();
        $tab = $fields->findOrMakeTab('Root.BlogPosts');
        $fields = $tab->FieldList();
        $names = array();
        foreach ($fields as $field) {
            array_push($names, $field->getName());
        }
        $this->assertEquals(array('BlogPosts'), $names);
	}

}

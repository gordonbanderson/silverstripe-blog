<?php

class ArchiveWidgetTest extends SapphireTest {
	public function testCanCreate() {
		$widget = new ArchiveWidget();
        $this->assertFalse($widget->canCreate());
	}

	public function testUp() {
		if (!class_exists('Widget')) {
            $this->markTestSkipped('Widgets module is not installed');
        }
        $widget = new ArchiveWidget();
        $widget->DisplayMode = 'year';
        $this->assertEquals('Migrated Yearly archive widget', $widget->up());

        $widget = new ArchiveWidget();
        $widget->DisplayMode = 'month';
        $this->assertEquals('Migrated Monthly archive widget', $widget->up());

        // FIXME this conditions strikes me as a bug
        $widget = new ArchiveWidget();
        $widget->DisplayMode = null;
        $this->assertEquals('Migrated  archive widget', $widget->up());
	}

}

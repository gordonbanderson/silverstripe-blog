<?php

/**
 * Adds URLSegment functionality to Tags & Categories.
 *
 * @package silverstripe
 * @subpackage blog
 */
class URLSegmentExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $db = array(
        'URLSegment' => 'Varchar(255)',
    );

    /**
     * {@inheritdoc}
     */
    public function onBeforeWrite()
    {
        error_log('USE: OBW - ' . $this->owner->Title);
        if ($this->owner->BlogID) {
            error_log('Creating URL segment');
            $this->owner->generateURLSegment();
        } else {
            $this->GenerateSegmentOnAfterWrite = true;
            error_log("\tCannot generate segment, no blog id.  Try in on after write");
        }
    }

     /**
     * {@inheritdoc}
     */
    public function onAfterWrite()
    {
        error_log('USE: OAW: '. $this->owner->Title);
        if (isset($this->GenerateSegmentOnAfterWrite) && $this->GenerateSegmentOnAfterWrite === true) {
            error_log("\tTrying to generate segment OAW");
            $this->GenerateSegmentOnAfterWrite = false;
            $this->owner->generateURLSegment();
        } else {
            error_log("\tUSE: OAW: Skipping oaw");
        }
    }

    /**
     * Generates a unique URLSegment from the title.
     *
     * @param int $increment
     *
     * @return string
     */
    public function generateURLSegment($increment = null)
    {
        $filter = new URLSegmentFilter();

        $this->owner->URLSegment = $filter->filter($this->owner->Title);

        if (is_int($increment)) {
            $this->owner->URLSegment .= '-' . $increment;
        }
        error_log('VERSION:' . Versioned::current_stage());
        error_log('Creating URLSegment for ' . $this->owner->Title);
        error_log("\t{$this->owner->ClassName}");
        error_log("\t{$this->owner->BlogID}");
        //error_log('BLOG EXISTS? ' . $this->owner->Blog->exists());

        if (!$this->owner->BlogID) {
            error_log("\t++++ BLOG ID OF 0 ++++");
           // asdfsdfsdf;
        }

        $duplicate = DataList::create($this->owner->ClassName)->filter(array(
            'URLSegment' => $this->owner->URLSegment,
            'BlogID' => $this->owner->BlogID,
        ));

        if ($this->owner->ID) {
            $duplicate = $duplicate->exclude('ID', $this->owner->ID);
        }

        if ($duplicate->count() > 0) {
            if (is_int($increment)) {
                $increment += 1;
            } else {
                $increment = 0;
            }

            $this->owner->generateURLSegment((int) $increment);
        }

        return $this->owner->URLSegment;
    }
}

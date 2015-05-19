<?php

if(!class_exists("Widget")) {
	return;
}

/**
 * @method Blog Blog()
 */
class BlogTagsCloudWidget extends Widget {
	/**
	 * @var string
	 */
	private static $title = 'Tags Cloud';

	/**
	 * @var string
	 */
	private static $cmsTitle = 'Blog Tags Cloud';

	/**
	 * @var string
	 */
	private static $description = 'Displays a tag cloud for this blog.';

	/**
	 * @var array
	 */
	private static $db = array();

	/**
	 * @var array
	 */
	private static $has_one = array(
		'Blog' => 'Blog',
	);

	/**
	 * {@inheritdoc}
	 */
	public function getCMSFields() {
		$this->beforeUpdateCMSFields(function ($fields) {
			/**
			 * @var FieldList $fields
			 */
			$fields->push(
				DropdownField::create('BlogID', _t('BlogTagsCloudWidget.Blog', 'Blog'), Blog::get()->map())
			);
		});

		return parent::getCMSFields();
	}

	/**
	 * @return array
	 */
	public function getTags() {
		if($blog = $this->Blog()) {
			$sql = "select bt.URLSegment,bt.Title,Count(BlogTagID) AS TagCount
				from BlogPost_Tags bpt
				INNER JOIN BlogPost bp
				ON bp.ID = bpt.BlogPostID
				INNER JOIN BlogTag bt
				ON bt.ID = bpt.BlogTagID
				WHERE BlogID = {$blog->ID}
				GROUP By BlogTagID
				ORDER BY Title";

			$records = DB::query($sql);
			$bloglink = $blog->Link();
			$maxTagCount = 0;

			// create DataObjects that can be used to render the tag cloud
			$tags = new ArrayList();
			foreach ($records as $record) {
				$tag = new DataObject();
				$tag->TagName = $record['Title'];
				$link = $bloglink.'tag/'.$record['URLSegment'];
				$tag->Link = $link;
				if ($record['TagCount'] > $maxTagCount) {
					$maxTagCount = $record['TagCount'];
				}
				$tag->TagCount = $record['TagCount'];

				$tags->push($tag);
			}

			// normalize the tag counts from 1 to 10
			$tagfactor = 10/$maxTagCount;
			foreach ($tags->getIterator() as $tag) {
				$normalized = round($tagfactor*($tag->TagCount));
				$tag->NormalizedTag = $normalized;
			}

			return $tags;
		}

		return array();
	}
}

class BlogTagsCloudWidget_Controller extends Widget_Controller {

}

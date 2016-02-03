<?php

if (!class_exists('Widget')) {
    return;
}

/**
 * @method Blog Blog()
 *
 * @property string $ArchiveType
 * @property int $NumberToDisplay
 */
class BlogArchiveWidget extends Widget
{
    /**
     * @var string
     */
    private static $title = 'Archive';

    /**
     * @var string
     */
    private static $cmsTitle = 'Archive';

    /**
     * @var string
     */
    private static $description = 'Displays an archive list of posts.';

    /**
     * @var array
     */
    private static $db = array(
        'NumberToDisplay' => 'Int',
        'ArchiveType' => 'Enum(\'Monthly,Yearly\', \'Monthly\')',
    );

    /**
     * @var array
     */
    private static $defaults = array(
        'NumberOfMonths' => 12,
    );

    /**
     * @var array
     */
    private static $has_one = array(
        'Blog' => 'Blog',
    );

    /**
     * {@inheritdoc}
     */
    public function getCMSFields()
    {
        $self =& $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            /**
             * @var Enum $archiveType
             */
            $archiveType = $self->dbObject('ArchiveType');

            $type = $archiveType->enumValues();

            foreach ($type as $k => $v) {
                $type[$k] = _t('BlogArchiveWidget.' . ucfirst(strtolower($v)), $v);
            }

            /**
             * @var FieldList $fields
             */
            $fields->merge(array(
                DropdownField::create('BlogID', _t('BlogArchiveWidget.Blog', 'Blog'), Blog::get()->map()),
                DropdownField::create('ArchiveType', _t('BlogArchiveWidget.ArchiveType', 'ArchiveType'), $type),
                NumericField::create('NumberToDisplay', _t('BlogArchiveWidget.NumberToDisplay', 'No. to Display'))
            ));
        });

        return parent::getCMSFields();
    }

    /**
     * Returns a list of months where blog posts are present.
     *
     * @return DataList
     */
    public function getArchive()
    {

        // ---- old code ----
        $query = $this->Blog()->getBlogPosts()->dataQuery();

        $posts = $this->Blog()->getBlogPosts()->setDataQuery($query);
        if ($this->NumberToDisplay > 0) {
            $posts = $posts->limit($this->NumberToDisplay);
        }

        error_log($posts->sql());

        foreach ($posts as $post) {
            error_log('FROM SQL:' . $post->PublishDate);
        }
        //---- old code ----










        $query = $this->Blog()->getBlogPosts()->dataQuery();
        error_log('CLASS QUERY : ' . get_class($query));
        $conn = DB::getConn();
        $timeConstraint = '';
        if ($this->ArchiveType == 'Yearly') {
            $timeConstraint = $conn->formattedDatetimeClause('"BlogPost"."PublishDate"', '%Y');
        //    $query->groupBy($timeConstraint);
        } else {
            $timeConstraint = $conn->formattedDatetimeClause('"BlogPost"."PublishDate"', '%Y-%m');
        //    $query->groupBy($timeConstraint);
        };

        $query->groupBy($timeConstraint);



        $posts = $this->Blog()->getBlogPosts()->setDataQuery($query);



        error_log('SQL: ' . $posts->sql());

        //----------------------------
        $query = $this->Blog()->getBlogPosts()->dataQuery();
        $stage = $query->getQueryParam('Versioned.stage');
        error_log('STAGE T1: ' . $stage);

        if ($stage) {
            $stage = '_' . $stage;
        }
        error_log('STAGE T2: ' . $stage);

        $sql = "SELECT $timeConstraint AS timeconstraint\n";
        $sql .= "FROM \"SiteTree{$stage}\"\n";
        $sql .= "LEFT JOIN \"BlogPost{$stage}\" ON \n";
        $sql .= sprintf('"SiteTree%s"."ID" = "BlogPost%s"."ID"', $stage, $stage);
        $sql .= "\nWHERE \"SiteTree${stage}\" . \"ParentID\" = " . $this->Blog()->ID;
        $sql .= "\nGROUP By timeconstraint";
        $sql .= "\nORDER by timeconstraint DESC\n";
        if ($this->NumberToDisplay > 0) {
            $sql .= "\nLIMIT {$this->NumberToDisplay} \n";
        }

        error_log($sql);
        $records = DB::query($sql);
        error_log(print_r($records,1));
        error_log(sizeof($records));
        error_log($records->numRecords());
        foreach ($records as $record) {
            error_log('RECORD FOUND');
            error_log($record['timeconstraint']);
        }


        $archive = new ArrayList();

        if ($records->numRecords() > 0) {
            foreach ($records as $record) {
                if ($this->ArchiveType == 'Yearly') {
                    $date = Date::create();
                    error_log("**** DATE CLASS ****:" . get_class($date));
                    $date->setValue($record['timeconstraint'] . '-01-01');
                    $year = $date->FormatI18N("%Y");
                    $month = null;
                    $title = $year;
                } else {
                    $time = explode('-', $record['timeconstraint']);
                    $date = $date = Date::create();
                    $date->setValue($record['timeconstraint'] . '-01');
                    $year = $date->FormatI18N("%Y");
                    $month = $date->FormatI18N("%m");
                    $title = $date->FormatI18N("%B %Y");
                }

                $archive->push(new ArrayData(array(
                    'Title' => $title,
                    'Link' => Controller::join_links($this->Blog()->Link('archive'), $year, $month)
                )));
            }
        }

        return $archive;
    }
}

class BlogArchiveWidget_Controller extends Widget_Controller
{
}

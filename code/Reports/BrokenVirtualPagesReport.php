<?php

namespace SilverStripe\CMS\Reports;

use SilverStripe\Core\ClassInfo;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DB;
use SilverStripe\ORM\Versioning\Versioned;
use SilverStripe\Reports\SS_Report;

class BrokenVirtualPagesReport extends SS_Report {

	public function title() {
		return _t('SideReport.BROKENVIRTUALPAGES', 'VirtualPages pointing to deleted pages');
	}

	public function group() {
		return _t('SideReport.BrokenLinksGroupTitle', "Broken links reports");
	}

	public function sourceRecords($params = null) {
		$classes = ClassInfo::subclassesFor('SilverStripe\\CMS\\Model\\VirtualPage');
		$classParams = DB::placeholders($classes);
		$classFilter = array(
			"\"ClassName\" IN ($classParams) AND \"HasBrokenLink\" = 1" => $classes
		);
		$stage = isset($params['OnLive']) ? 'Live' : 'Stage';
		return Versioned::get_by_stage('SilverStripe\\CMS\\Model\\SiteTree', $stage, $classFilter);
	}

	public function columns() {
		return array(
			"Title" => array(
				"title" => "Title", // todo: use NestedTitle(2)
				"link" => true,
			),
		);
	}

	public function getParameterFields() {
		return new FieldList(
			new CheckboxField('OnLive', _t('SideReport.ParameterLiveCheckbox', 'Check live site'))
		);
	}
}

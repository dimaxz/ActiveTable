<?php
/**
 * Created by PhpStorm.
 * User: d.lanec
 * Date: 24.01.2019
 * Time: 16:00
 */

namespace src_;

class EventName {

	const AFTER_DELETE = "afterDelete";

	const BEFORE_DELETE = "beforeDelete";

	const AFTER_CREATE = "afterCreate";

	const BEFORE_CREATE = "beforeCreate";

	const AFTER_UPDATE = "afterUpdate";

	const BEFORE_UPDATE = "beforeUpdate";

	const AFTER_READ = "afterRead";

	const BEFORE_READ = "beforeRead";

	const ON_DELETE = "onDelete";

	const ON_READ = "onRead";

	const ON_UPDATE = "onUpdate";

	const ON_CREATE = "onCreate";

	const BEFORE_TABLE = "beforeTable";

	const AFTER_TABLE = "aftereTable";

	const BEFORE_TABLE_RENDER = "beforeTableRender";

	const BEFORE_FORM_RENDER = "beforeFormRender";

}
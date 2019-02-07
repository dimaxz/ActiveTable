<?

namespace ActiveTableEngine\Concrete\Commands;

use ActiveTableEngine\Concrete\ContentOutput;
use ActiveTableEngine\Contracts\CrudRepositoryInterface;
use ActiveTableEngine\Contracts\OutputInterface;
use ActiveTableEngine\Contracts\TableActionInterface;

/**
 * Description of OnDelete
 *
 * @author d.lanec
 */
class LoadPostData implements \ActiveTableEngine\Contracts\ActionInterface {

	protected $repository;
	protected $content;
	protected $action;

	function __construct( CrudRepositoryInterface $repo, OutputInterface $content, TableActionInterface $action) {
		$this->repository 	= $repo;
		$this->content		= $content;
		$this->action 		= $action;
	}

	public function process() {

		if(!$model = $this->repository->findById($this->action->getKey())){
			return;
		}

		$this->content->setData($model->toArray());
	}

}

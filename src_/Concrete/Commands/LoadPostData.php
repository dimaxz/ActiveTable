<?

namespace src_\Concrete\Commands;

use src_\Concrete\ContentOutput;
use Repo\CrudRepositoryInterface;
use src_\Contracts\OutputInterface;
use src_\Contracts\TableActionInterface;

/**
 * Description of OnDelete
 *
 * @author d.lanec
 */
class LoadPostData implements \src_\Contracts\ActionInterface {

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

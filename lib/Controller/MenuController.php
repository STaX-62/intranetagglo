<?php

namespace OCA\IntranetAgglo\Controller;

use OCA\IntranetAgglo\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\AppFramework\Controller;
use OCP\IGroupManager;
use OCP\IUserSession;

use OCA\IntranetAgglo\Service\MenuService;

class MenuController extends Controller
{
    /** @var MenuService */
    private $service;

    /** @var ITimeFactory */
    protected $timeFactory;

    use Errors;

    public function __construct(
        IRequest $request,
        MenuService $service,
        IGroupManager $groupmanager,
        IUserSession $session,
        ITimeFactory $timeFactory
    ) {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->groupmanager = $groupmanager;
        $this->session = $session;
        $this->timeFactory = $timeFactory;
    }

    public function index(): DataResponse
    {
        return (new DataResponse($this->service->findAll()));
    }

    /**
     * @NoAdminRequired
     */
    public function indexG(): DataResponse
    {
        $user = $this->session->getUser();
        return (new DataResponse($this->service->findByGroups($this->groupmanager->getUserGroupIds($user))));
    }

    public function create(string $title, string $icon, string $link, string $groups, string $position)
    {
        $fileurl = $link;

        if (isset($_FILES['newfile'])) {
            if (file_exists($_FILES['newfile']['tmp_name'])) {
                if ($_FILES['newfile']['error'] == 0) {
                    $fileInfos = pathinfo($_FILES['newfile']['name']);
                    $NewName = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

                    move_uploaded_file($_FILES['newfile']['tmp_name'], 'apps/intranetagglo/img/uploads/' . $NewName);
                    $fileurl = $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $NewName);
                }
            }
        }

        return $this->service->create($title, $icon, $fileurl, $groups, $position);
    }

    public function update(int $id, string $title, string $icon, string $link, string $groups)
    {
        return $this->handleNotFound(function () use ($id, $title, $icon, $link, $groups) {

            $fileurl = $link;
            if (isset($_FILES['file_upd'])) {
                if (file_exists($_FILES['file_upd']['tmp_name'])) {
                    if ($_FILES['file_upd']['error'] == 0) {
                        unlink(substr($fileurl, 11));

                        $fileInfos = pathinfo($_FILES['file_upd']['name']);
                        $file = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

                        move_uploaded_file($_FILES['file_upd']['tmp_name'], 'apps/intranetagglo/img/uploads/' . $file);
                        $fileurl = $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $file);
                    }
                }
            }

            return $this->service->update($id, $title, $icon, $fileurl, $groups);
        });
    }

    public function destroy(int $id)
    {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->delete($id);
        });
    }
}

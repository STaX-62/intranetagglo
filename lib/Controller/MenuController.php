<?php

namespace OCA\IntranetAgglo\Controller;

use Exception;
use OCA\IntranetAgglo\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\AppFramework\Controller;
use OCP\IGroupManager;
use OCP\IUserSession;
use OCP\IURLGenerator;

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
        IURLGenerator $urlGenerator,
        ITimeFactory $timeFactory
    ) {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->groupmanager = $groupmanager;
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
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

    public function create(string $title, string $icon, string $link, string $groups, int $sectionid, int $menuid, int $level)
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
        if ($level == 0) {
            $this->service->create($title, $icon, $fileurl, $groups, $this->service->NewIdSection(), 0, 0);
        } else {
            if ($level == 1) {
                $this->service->create($title, $icon, $fileurl, $groups, $sectionid, $this->service->NewIdMenu($sectionid), 0);
            } else {
                $this->service->create($title, $icon, $fileurl, $groups, $sectionid, $menuid, $this->service->NewIdSubmenu($sectionid, $menuid));
            }
        }

        return $this->service->findAll();
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
            $this->service->update($id, $title, $icon, $fileurl, $groups);
            return $this->service->findAll();
        });
    }

    public function changeOrder(string $actualPosition, string $newPosition, $sectionpos, $menupos)
    {
        $oldIds = explode('-', $actualPosition);


        // if ($newPosition != null) {
        //     $newIds = explode('-', $newPosition);
        //     $newMenuQB = $this->service->findByPosition($newIds[0], $newIds[1], $newIds[2]);
        // } else {
        //     if ($sectionpos != null) {
        //         if ($menupos != null) {
        //             $newMenuQB = $this->service->findByPosition(
        //                 intval($sectionpos),
        //                 intval($menupos),
        //                 (int)$this->service->NewIdSubmenu(intval($sectionpos), intval($menupos))
        //             );
        //         } else {
        //             $newMenuQB = $this->service->findByPosition(
        //                 intval($sectionpos),
        //                 (int)$this->service->NewIdMenu(intval($sectionpos)),
        //                 0
        //             );
        //         }
        //     }
        // }

        $oldMenuQB = $this->service->findByPosition($oldIds[0], $oldIds[1], $oldIds[2]);


        // foreach ($oldMenuQB as $menu) {
        //     if ($sectionpos != null)
        //         $this->service->updateOrder($menu->getId(), $sectionpos, $menupos, $newIds[2]);
        // }
        // foreach ($newMenuQB as $menu) {
        //     $this->service->updateOrder($menu->getId(), $sectionpos, $menupos, $oldIds[2]);
        // }

        return [$this->service->findAll(), gettype($newPosition), $newPosition];
    }

    public function destroy(int $id)
    {
        return $this->handleNotFound(function () use ($id) {
            $rq = $this->service->find($id);
            unlink(substr($rq->getLink(), 11));
            $this->service->delete($id);
            return $this->service->findAll();
        });
    }
}

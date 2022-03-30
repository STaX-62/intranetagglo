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

    public function changeOrder(int $sectionid, int $menuid, int $submenuid, int $newIndex, int $oldIndex)
    {
        if ($newIndex == $oldIndex) {
            return 'aucun changement';
        }

        if ($menuid == 0) {
            $level = 0;
        } else {
            if ($submenuid == 0) {
                $level = 1;
            } else {
                $level = 2;
            }
        }
        //int $sectionid = 0, int $menuid = 0, int $submenuid = 0, int $newIndex = 1, int $oldIndex = 0
        if ($level == 0) {
            if ($newIndex < $oldIndex) {
                $menusToChange = $this->service->findBySection($newIndex, $oldIndex); //renvoie objet ou sectionid est entre 0 et 1
                for ($i = $newIndex; $i < count($menusToChange); $i++) {
                    $menuPosition =  $menusToChange[$i]->getSectionid() + 1;
                    $menusToChange[$i]->setSectionid($menuPosition);
                }
                $menusToChange[$oldIndex]->setSectionid($newIndex); //menu[0] = 1
            } else {
                $menusToChange = $this->service->findBySection($oldIndex, $newIndex); //renvoie objet ou sectionid est entre 0 et 1
                for ($i = $oldIndex; $i < count($menusToChange); $i++) { // i = 1 , count = 2
                    $menuPosition =  $menusToChange[$i]->getSectionid() - 1; // menu[1]= 1-1 = 0
                    $menusToChange[$i]->setSectionid($menuPosition); // on le set
                }
                $menusToChange[$oldIndex]->setSectionid($newIndex); // menu[0] = 1
            }
        } else {
            if ($level == 1) {
                if ($newIndex < $oldIndex) {
                    $menusToChange = $this->service->findByMenu($sectionid, $newIndex, $oldIndex);
                    for ($i = $oldIndex; $i < count($menusToChange); $i++) {
                        $menuPosition =  $menusToChange[$i]->getMenuid() + 1;
                        $menusToChange[$i]->setMenuid($menuPosition);
                    }
                    $menusToChange[$oldIndex]->setMenuid($newIndex);
                } else {
                    $menusToChange = $this->service->findByMenu($sectionid, $oldIndex, $newIndex);
                    for ($i = $newIndex; $i < count($menusToChange); $i++) {
                        $menuPosition =  $menusToChange[$i]->getMenuid() - 1;
                        $menusToChange[$i]->setMenuid($menuPosition);
                    }
                    $menusToChange[$oldIndex]->setMenuid($newIndex);
                }
            } else {
                if ($newIndex < $oldIndex) {
                    $menusToChange = $this->service->findBySubmenu($sectionid, $menuid, $newIndex, $oldIndex);
                    for ($i = $oldIndex; $i < count($menusToChange); $i++) {
                        $menuPosition =  $menusToChange[$i]->getSubmenuid() + 1;
                        $menusToChange[$i]->setSubmenuid($menuPosition);
                    }
                    $menusToChange[$oldIndex]->setSubmenuid($newIndex);
                } else {
                    $menusToChange = $this->service->findBySubmenu($sectionid, $menuid, $oldIndex, $newIndex);
                    for ($i = $newIndex; $i < count($menusToChange); $i++) {
                        $menuPosition =  $menusToChange[$i]->getSubmenuid() - 1;
                        $menusToChange[$i]->setSubmenuid($menuPosition);
                    }
                    $menusToChange[$oldIndex]->setSubmenuid($newIndex);
                }
            }
        }

        foreach ($menusToChange as $menu) {
            $this->service->updateOrder($menu->getid(), $menu->getSectionid(), $menu->getMenuid(), $menu->getSubmenuid());
        }
        return $this->service->findAll();
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

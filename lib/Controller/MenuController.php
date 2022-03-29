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

    public function create(string $title, string $icon, string $link, string $groups, int $sectionId, int $menuId, int $submenuId)
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
        $this->service->create($title, $icon, $fileurl, $groups, $sectionId, $menuId, $submenuId);
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

    public function changeOrder(int $sectionId, int $menuId, int $newIndex, int $oldIndex)
    {
        if ($newIndex == $oldIndex) {
            return 'aucun changement';
        }
        
        if ($sectionId == -1) {
            if ($newIndex < $oldIndex) {
                $menusToChange = $this->service->findBySection($newIndex, $oldIndex);
                for ($i = $oldIndex; $i < $newIndex; $i++) {
                    $menuPosition =  $menusToChange[$i]->getSectionId() + 1;
                    $menusToChange[$i]->setSectionId($menuPosition);
                }
                $menusToChange[$oldIndex]->setSectionId($newIndex);
            } else {
                $menusToChange = $this->service->findBySection($oldIndex, $newIndex);
                for ($i = $newIndex; $i < $oldIndex; $i++) {
                    $menuPosition =  $menusToChange[$i]->getSectionId() + 1;
                    $menusToChange[$i]->setSectionId($menuPosition);
                }
                $menusToChange[$oldIndex]->setSectionId($newIndex);
            }
        } else {
            if ($menuId > 0) {
                if ($newIndex < $oldIndex) {
                    $menusToChange = $this->service->findByMenu($sectionId, $newIndex, $oldIndex);
                    for ($i = $oldIndex; $i < $newIndex; $i++) {
                        $menuPosition =  $menusToChange[$i]->getMenuId() + 1;
                        $menusToChange[$i]->setMenuId($menuPosition);
                    }
                    $menusToChange[$oldIndex]->setMenuId($newIndex);
                } else {
                    $menusToChange = $this->service->findByMenu($sectionId, $oldIndex, $newIndex);
                    for ($i = $newIndex; $i < $oldIndex; $i++) {
                        $menuPosition =  $menusToChange[$i]->getMenuId() + 1;
                        $menusToChange[$i]->setMenuId($menuPosition);
                    }
                    $menusToChange[$oldIndex]->setMenuId($newIndex);
                }
            } else {
                if ($newIndex < $oldIndex) {
                    $menusToChange = $this->service->findBySubmenu($sectionId, $menuId, $newIndex, $oldIndex);
                    for ($i = $oldIndex; $i < $newIndex; $i++) {
                        $menuPosition =  $menusToChange[$i]->getSubmenuId() + 1;
                        $menusToChange[$i]->setSubmenuId($menuPosition);
                    }
                    $menusToChange[$oldIndex]->setSubmenuId($newIndex);
                } else {
                    $menusToChange = $this->service->findBySubmenu($sectionId, $menuId, $oldIndex, $newIndex);
                    for ($i = $newIndex; $i < $oldIndex; $i++) {
                        $menuPosition =  $menusToChange[$i]->getSubmenuId() + 1;
                        $menusToChange[$i]->setSubmenuId($menuPosition);
                    }
                    $menusToChange[$oldIndex]->setSubmenuId($newIndex);
                }
            }
        }

        foreach ($menusToChange as $menu) {
            $this->service->updateOrder($menu->getId(), $menu->getSectionId(), $menu->getMenuId(), $menu->getSubmenuId());
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

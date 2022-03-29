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
        $this->service->create($title, $icon, $fileurl, $groups, $position);
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

    public function changeOrder(string $oldPosition, int $newIndex, int $oldIndex)
    {
        function reOrder($menus, $level, $newIndex, $oldIndex)
        {
            /*
                $menus = [{Objet1, position = 0-0-0}, {Objet2, position = 1-0-0}], $level = 0 , $newIndex = 1 , $oldIndex = 0

            */
            if ($newIndex < $oldIndex) { //$newIndex = 1 < $oldIndex = 0 est faux
                for ($i = $oldIndex; $i < count($menus); $i++) {
                    $menuPosition = explode('-', $menus[$i]->getPosition());
                    $menuPosition[$level] = intval($menuPosition[$level]) + 1;
                    $menus[$i]->setPosition(implode('-', $menuPosition));
                }
                $menuPosition = explode('-', $menus[$oldIndex]->getPosition());
                $menuPosition[$level] = $newIndex;
                $menus[$newIndex]->setPosition(implode('-', $menuPosition));
            } else {
                for ($i = $newIndex; $i < count($menus); $i++) { //$i = 1 , count($menus = 2)
                    $menuPosition = explode('-', $menus[$i]->getPosition()); //position d'objet 2 ['1','0','0']
                    $menuPosition[$level] = intval($menuPosition[$level]) - 1; // $menuPosition[$level = 0] = 1  ===>> 1-1 = 0
                    $menus[$i]->setPosition(implode('-', $menuPosition)); // $menu[1] donc objet2 position prend la valeur '0-0-0'
                }
                $menuPosition = explode('-', $menus[$oldIndex]->getPosition()); //position d'objet 1 ['0','0','0']
                $menuPosition[$level] = $newIndex;// $menuPosition[$level = 0] = 0  ===>> $menuPosition[$level] = 1
                $menus[$oldIndex]->setPosition(implode('-', $menuPosition)); //menus[0] position = '1-0-0'
            }
            return $menus; // menus = [{Objet1, position = 1-0-0}, {Objet2, position = 0-0-0}],
        }

        if ($newIndex == $oldIndex) {
            return 'aucun changement';
        }

        $positionToChange = explode('-', $oldPosition);
        if ($positionToChange[1] == '0') {
            $menusToChange = $this->service->findByPosition('%');
            $newMenuOrder = reOrder($menusToChange, 0, $newIndex, $oldIndex);
        } else {
            if ($positionToChange[2] == '0') {
                $menusToChange = $this->service->findByPosition($positionToChange[0] . '%');
                $newMenuOrder = reOrder($menusToChange, 1, $newIndex, $oldIndex);
            } else {
                $menusToChange = $this->service->findByPosition($positionToChange[0] . '-' . $positionToChange[1] . '%');
                $newMenuOrder = reOrder($menusToChange, 2, $newIndex, $oldIndex);
            }
        }

        foreach ($newMenuOrder as $menu) {
            $this->service->updateOrder($menu->getId(), $menu->getPosition());
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

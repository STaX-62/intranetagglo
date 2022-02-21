<?php

namespace OCA\IntranetAgglo\Controller;

use OCA\IntranetAgglo\AppInfo\Application;
use OCA\IntranetAgglo\Notification\Notifier;

use OCP\IRequest;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Controller;
use OCP\IGroupManager;
use OCP\IUser;
use OCP\IUserManager;
use OCP\IUserSession;
use OCP\Notification\IManager;
use OCA\IntranetAgglo\Service\NewsService;

class NewsController extends Controller
{
    /** @var NewsService */
    private $service;

    /** @var IUserManager */
    private $userManager;

    /** @var IManager */
    private $NotificationManager;

    use Errors;

    public function __construct(
        IRequest $request,
        NewsService $service,
        IGroupManager $groupmanager,
        IUserSession $session,
        IManager $NotificationManager,
        IUsermanager $userManager
    ) {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->userManager = $userManager;
        $this->groupmanager = $groupmanager;
        $this->session = $session;
        $this->NotificationManager = $NotificationManager;
    }

    public function index(int $id, string $search): DataResponse
    {
        return (new DataResponse($this->service->findAll($id, $search)));
    }

    /**
     * @NoAdminRequired
     */
    public function indexG(int $id, string $search): DataResponse
    {
        $user = $this->session->getUser();
        return (new DataResponse($this->service->findByGroups($id, $this->groupmanager->getUserGroupIds($user), $search)));
    }

    public function create(string $author, string $title, string $subtitle, string $text,  string $photo,  string $category,  string $groups, int $visible)
    {
        $user = $this->session->getUser();
        return $this->service->create($user->getDisplayName(), $title, $subtitle, $text, $photo, $category, $groups, $visible);
    }

    public function update(int $id, string $author, string $title, string $subtitle, string $text,  string $photo,  string $category,  string $groups, int $visible)
    {
        return $this->handleNotFound(function () use ($id, $author, $title, $subtitle, $text, $photo, $category, $groups, $visible) {
            return $this->service->update($id, $author, $title, $subtitle, $text, $photo, $category, $groups, $visible);
        });
    }

    public function publication(int $id, int $visible)
    {
        return $this->handleNotFound(function () use ($id, $visible) {
            $user = $this->session->getUser();
            $uid = $user->getUID();
            $rq = $this->service->publication($id, $visible);

            $notification = $this->NotificationManager->createNotification();
            $notification->setApp(Application::APP_ID)
                ->setDateTime(new \DateTime())
                ->setObject('news', (string)$rq->getId())
                ->setSubject('news', [$rq->getAuthor()]);

            $groups = explode(";", $rq->getGroups());
            // modifier les groupes et les enregistrer via gid
            if ($groups[0] != "") {
                $this->userManager->callForSeenUsers(function (IUser $user) use ($uid, $notification) {
                    if ($uid !== $user->getUID()) {
                        $notification->setUser($user->getUID());
                        $this->NotificationManager->notify($notification);
                    }
                });
            } else {
                foreach ($groups as $gid) {
                    $group = $this->groupManager->get($gid);
                    if (!($group instanceof IGroup)) {
                        continue;
                    }

                    $users = $group->getUsers();
                    foreach ($users as $user) {

                        $uid = $user->getUID();
                        if (isset($this->notifiedUsers[$uid]) || $user->getLastLogin() === 0) {
                            continue;
                        }
                        
                        if ($uid !== $uid) {
                            $notification->setUser($uid);
                            $this->notificationManager->notify($notification);
                        }

                        $this->notifiedUsers[$uid] = true;
                    }
                }
            }
            return [$rq,$notification,$groups];
        });
    }

    public function getCategory()
    {
        return $this->service->category();
    }

    public function destroy(int $id)
    {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->delete($id);
        });
    }
}

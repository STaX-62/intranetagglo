<?php

namespace OCA\IntranetAgglo\Controller;

use ArrayObject;
use OCA\IntranetAgglo\AppInfo\Application;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\AppFramework\Controller;
use OCP\IGroupManager;
use OCP\IUser;
use OCP\IGroup;
use OCP\IUserManager;
use OCP\IUserSession;
use OCP\Notification\IManager;
use OCA\IntranetAgglo\Service\NewsService;
use OCP\IURLGenerator;
use OCP\Notification\INotification;

class NewsController extends Controller
{
    /** @var NewsService */
    private $service;

    /** @var IUserManager */
    private $userManager;

    /** @var ITimeFactory */
    protected $timeFactory;

    /** @var IGroupManager */
    private $groupManager;

    /** @var IManager */
    private $NotificationManager;

    /** @var array */
    protected $notifiedUsers = [];

    use Errors;

    public function __construct(
        IRequest $request,
        NewsService $service,
        IGroupManager $groupManager,
        IUserSession $session,
        IManager $NotificationManager,
        IUsermanager $userManager,
        IURLGenerator $urlGenerator,
        ITimeFactory $timeFactory
    ) {
        parent::__construct(Application::APP_ID, $request);
        $this->service = $service;
        $this->userManager = $userManager;
        $this->groupManager = $groupManager;
        $this->session = $session;
        $this->NotificationManager = $NotificationManager;
        $this->urlGenerator = $urlGenerator;
        $this->timeFactory = $timeFactory;
    }

    /**
     * @NoAdminRequired
     */
    public function index(int $id, string $search, string $categories, array $dateFilter): DataResponse
    {
        $user = $this->session->getUser();
        if ($this->isAdmin()) {
            return (new DataResponse($this->service->findAll($id, $search, $categories, $dateFilter)));
        }
        return (new DataResponse($this->service->findByGroups($id, $this->groupManager->getUserGroupIds($user), $search, $categories, $dateFilter)));
    }

    /**
     * @NoAdminRequired
     */
    public function isAdmin()
    {
        $user = $this->session->getUser();
        return $this->groupManager->isInGroup($user->getUID(), 'intranet-admin') || $this->groupManager->isInGroup($user->getUID(), 'admin');
    }

    /**
     * @NoAdminRequired
     */
    public function create(string $title, string $subtitle, string $text,  string $category,  string $groups, string $link)
    {
        $user = $this->session->getUser();
        if ($this->isAdmin()) {
            $photourl = '';
            if (isset($_FILES['photo'])) {
                if (file_exists($_FILES['photo']['tmp_name'])) {
                    if ($_FILES['photo']['error'] == 0) {
                        $fileInfos = pathinfo($_FILES['photo']['name']);
                        $photo = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

                        move_uploaded_file($_FILES['photo']['tmp_name'], 'apps/intranetagglo/img/uploads/' . $photo);
                        $photourl = $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $photo);
                    }
                }
            }
            return $this->service->create($user->getDisplayName(), $title, $subtitle, $text, $photourl, $category, $groups, $link, $this->timeFactory->getTime(), 0, 0);
        }
        return 'User is not admin';
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $id, string $title, string $subtitle, string $text,  string $photolink,  string $category,  string $groups, string $link)
    {
        if ($this->isAdmin()) {
            return $this->handleNotFound(function () use ($id, $title, $subtitle, $text, $photolink, $category, $groups, $link) {
                $photourl = $photolink;
                if (isset($_FILES['photo_upd'])) {
                    if (file_exists($_FILES['photo_upd']['tmp_name'])) {
                        if ($_FILES['photo_upd']['error'] == 0) {
                            unlink(substr($photourl, 11));

                            $fileInfos = pathinfo($_FILES['photo_upd']['name']);
                            $photo = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

                            move_uploaded_file($_FILES['photo_upd']['tmp_name'], 'apps/intranetagglo/img/uploads/' . $photo);
                            $photourl = $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $photo);
                        }
                    }
                }

                return $this->service->update($id, $title, $subtitle, $text, $photourl, $category, $groups, $link);
            });
        }
        return 'User is not admin';
    }

    /**
     * @NoAdminRequired
     */
    public function pinNews(int $id)
    {
        $lastPinned = $this->service->getLastPinned();
        return $this->handleNotFound(function () use ($id, $lastPinned) {
            if (count($lastPinned) != 0) {
                if ($lastPinned[0] == $id) {
                    return $this->service->pinNewsById($lastPinned[0], 0);
                } else {
                    $this->service->pinNewsById($lastPinned[0], 0);
                    return $this->service->pinNewsById($id, 1);
                }
            } else {
                return $this->service->pinNewsById($id, 1);
            }
        });
    }

    /**
     * @NoAdminRequired
     */
    public function publication(int $id, int $visible)
    {
        return $this->handleNotFound(function () use ($id, $visible) {
            $rq = $this->service->publication($id, $visible, $this->timeFactory->getTime());

            $groups = explode(";", $rq->getGroups());

            if ($visible == 1) {
                $notification = $this->NotificationManager->createNotification();

                $action = $notification->createAction();
                $notification->setApp(Application::APP_ID)
                    ->setDateTime(new \DateTime())
                    ->setObject('news', (string)$rq->getId())
                    ->setSubject($rq->getTitle(), [
                        'author' =>  $rq->getAuthor()
                    ])
                    ->setMessage($rq->getSubtitle());


                if ($groups[0] == "") {
                    $this->createNotificationEveryone($rq->getAuthor(), $notification);
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

                            if ($uid !== $rq->getAuthor()) {
                                $notification->setUser($uid);
                                $this->NotificationManager->notify($notification);
                            }

                            $this->notifiedUsers[$uid] = true;
                        }
                    }
                }
            } else {
                $notification = $this->NotificationManager->createNotification();
                $notification->setApp(Application::APP_ID)
                    ->setObject('news', $rq->getId());
                $this->NotificationManager->markProcessed($notification);
            }
            return $rq;
        });
    }

    /**
     * @NoAdminRequired
     * @param string $authorId
     * @param INotification $notification
     */
    protected function createNotificationEveryone(string $authorId, INotification $notification): void
    {
        $this->userManager->callForSeenUsers(function (IUser $user) use ($authorId, $notification) {
            if ($authorId !== $user->getUID()) {
                $notification->setUser($user->getUID());
                $this->NotificationManager->notify($notification);
            }
        });
    }

    /**
     * @NoAdminRequired
     *
     * @param int $id
     * @return DataResponse
     */
    public function removeNotifications()
    {
        $user = $this->session->getUser();
        $uid = $user->getUID();
        $notification = $this->NotificationManager->createNotification();
        $notification->setApp(Application::APP_ID)
            ->setUser($uid);
        $this->NotificationManager->markProcessed($notification);
    }

    /**
     * @NoAdminRequired
     */
    public function getCategory()
    {
        return new DataResponse($this->service->category());
    }

    /**
     * @NoAdminRequired
     */
    public function destroy(int $id)
    {
        return $this->handleNotFound(function () use ($id) {
            $rq = $this->service->find($id);
            unlink(substr($rq->getPhoto(), 11));
            return $this->service->delete($id);
        });
    }
}

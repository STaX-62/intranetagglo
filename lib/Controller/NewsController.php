<?php

namespace OCA\IntranetAgglo\Controller;

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
    public function index(int $startid, int $limit, string $search, string $categories, array $dateFilter): DataResponse
    {
        $user = $this->session->getUser();
        if ($this->isAdmin()) {
            return (new DataResponse($this->service->findAll($startid, $limit, $search, $categories, $dateFilter)));
        }
        return (new DataResponse($this->service->findByGroups($startid, $limit, $this->groupManager->getUserGroupIds($user), $search, $categories, $dateFilter)));
    }

    /**
     * @NoAdminRequired
     */
    public function alerts(string $search): DataResponse
    {
        $user = $this->session->getUser();
        if ($this->isAdmin()) {
            return (new DataResponse($this->service->findAlerts($search)));
        }
        return (new DataResponse($this->service->findAlertsByGroups($search, $this->groupManager->getUserGroupIds($user))));
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
    public function create(string $title, string $subtitle, string $text,  string $category,  string $groups, string $link, $expiration)
    {
        $user = $this->session->getUser();
        if ($this->isAdmin()) {
            $photourl = [];

            // Dans le cas d'une seule photo
            if (isset($_FILES['photo']) && !is_array($_FILES['photo']['name'])) {
                if (file_exists($_FILES['photo']['tmp_name'])) {
                    if ($_FILES['photo']['error'] == 0) {
                        $fileInfos = pathinfo($_FILES['photo']['name']);
                        $photo = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

                        move_uploaded_file($_FILES['photo']['tmp_name'], 'apps/intranetagglo/img/uploads/' . $photo);
                        $photourl[0] = $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $photo);
                    }
                }
            }

            // Dans le cas de plusieurs photos
            if (isset($_FILES['photo']) && is_array($_FILES['photo']['name'])) {
                for ($i = 0; $i < count($_FILES['photo']['name']); $i++) {
                    if (file_exists($_FILES['photo']['tmp_name'][$i])) {
                        if ($_FILES['photo']['error'][$i] == 0) {
                            $fileInfos = pathinfo($_FILES['photo']['name'][$i]);
                            $photo = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

                            move_uploaded_file($_FILES['photo']['tmp_name'][$i], 'apps/intranetagglo/img/uploads/' . $photo);
                            $photourl[$i] = $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $photo);
                        }
                    }
                }
            }
            return [$this->service->create($user->getDisplayName(), $title, $subtitle, $text, $photourl, $category, $groups, $link, $this->timeFactory->getTime(), $expiration), $expiration, strtotime($expiration)];
        }
        return 'User is not admin';
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $id, string $title, string $subtitle, string $text, array $photos, array $deletedphoto,  string $category,  string $groups, string $link, $expiration)
    {
        if ($this->isAdmin()) {
            return $this->handleNotFound(function () use ($id, $title, $subtitle, $text, $photos,  $deletedphoto, $category, $groups, $link, $expiration) {

                if (!empty($deletedphoto)) {
                    for ($i = 0; $i < count($deletedphoto); $i++) {
                        unlink(substr($deletedphoto[$i], 11));
                        array_splice($photos, array_search($deletedphoto[$i], $photos));
                    }
                }

                // Dans le cas d'une seule photo
                if (isset($_FILES['photo_upd']) && !is_array($_FILES['photo_upd']['name'])) {
                    if (file_exists($_FILES['photo_upd']['tmp_name'])) {
                        if ($_FILES['photo_upd']['error'] == 0) {

                            $fileInfos = pathinfo($_FILES['photo_upd']['name']);
                            $photo = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

                            move_uploaded_file($_FILES['photo_upd']['tmp_name'], 'apps/intranetagglo/img/uploads/' . $photo);
                            array_push($photos, $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $photo));
                        }
                    }
                }

                // Dans le cas de plusieurs photos
                if (isset($_FILES['photo_upd']) && is_array($_FILES['photo_upd']['name'])) {
                    for ($i = 0; $i < count($_FILES['photo_upd']['name']); $i++) {
                        if (file_exists($_FILES['photo_upd']['tmp_name'][$i])) {
                            if ($_FILES['photo_upd']['error'][$i] == 0) {

                                $fileInfos = pathinfo($_FILES['photo_upd']['name'][$i]);
                                $photo = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

                                move_uploaded_file($_FILES['photo_upd']['tmp_name'][$i], 'apps/intranetagglo/img/uploads/' . $photo);
                                array_push($photourl, $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $photo));
                            }
                        }
                    }
                }

                return $this->service->update($id, $title, $subtitle, $text, $photos, $category, $groups, $link, $expiration);
            });
        }
        return 'User is not admin';
    }

    /**
     * @NoAdminRequired
     */
    public function pinNews(int $id)
    {
        if ($this->isAdmin()) {
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
        return 'User is not admin';
    }

    /**
     * @NoAdminRequired
     */
    public function publication(int $id, int $visible)
    {
        if ($this->isAdmin()) {
            return $this->handleNotFound(function () use ($id, $visible) {
                $rq = $this->service->publication($id, $visible, $this->timeFactory->getTime());

                $groups = explode(";", $rq->getGroups());

                if ($visible == 1) {
                    $notification = $this->NotificationManager->createNotification();

                    $notification->setApp(Application::APP_ID)
                        ->setDateTime(new \DateTime())
                        ->setObject('news', (string)$rq->getId())
                        ->setSubject($rq->getTitle(), [
                            'author' =>  $rq->getAuthor()
                        ]);
                    if ($rq->getSubtitle() != "") {
                        $notification->setMessage($rq->getSubtitle());
                    }



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
        return 'User is not admin';
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
        if ($this->isAdmin()) {
            return $this->handleNotFound(function () use ($id) {
                $rq = $this->service->find($id);
                foreach (explode(';', $rq->getPhoto()) as $p) {
                    unlink(substr($p, 11));
                }
                return $this->service->delete($id);
            });
        }
        return 'User is not admin';
    }
}

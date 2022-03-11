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
        return (new DataResponse($this->service->findByGroups($id, $this->groupManager->getUserGroupIds($user), $search)));
    }

    public function create(string $title, string $subtitle, string $text, $photo,  string $category,  string $groups)
    {
        $user = $this->session->getUser();
        $photourl = '';

        if (file_exists($_FILES['photo']['tmp_name']) && $_FILES['photo']['error'] == 0) {
            $fileInfos = pathinfo($_FILES['photo']['name']);
            $photo = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];

            move_uploaded_file($_FILES['photo']['tmp_name'], 'apps/intranetagglo/img/uploads/' . $photo);
            $photourl = $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $photo);
        }

        return $this->service->create($user->getDisplayName(), $title, $subtitle, $text, $photourl, $category, $groups, $this->timeFactory->getTime(), 0);
    }

    public function update(int $id, string $title, string $subtitle, string $text,  string $photolink,  string $category,  string $groups, int $visible)
    {
        return $this->handleNotFound(function () use ($id, $title, $subtitle, $text, $photolink, $category, $groups, $visible) {
            $photourl = $photolink;

            if (file_exists($_FILES['photo_upd']['tmp_name']) && $_FILES['photo_upd']['error'] == 0) {

                unlink(substr($photourl,11));

                $fileInfos = pathinfo($_FILES['photo_upd']['name']);
                $photo = $this->timeFactory->getTime() . '.' . $fileInfos['extension'];
                
                move_uploaded_file($_FILES['photo_upd']['tmp_name'], 'apps/intranetagglo/img/uploads/' . $photo);
                $photourl = $this->urlGenerator->imagePath('intranetagglo', 'uploads/' . $photo);
            }

            return $this->service->update($id, $title, $subtitle, $text, $photourl, $category, $groups, $visible);
        });
    }

    public function publication(int $id, int $visible)
    {
        return $this->handleNotFound(function () use ($id, $visible) {
            $rq = $this->service->publication($id, $visible, $this->timeFactory->getTime());

            $groups = explode(";", $rq->getGroups());

            if ($visible == 1) {
                $notification = $this->NotificationManager->createNotification();

                $notification->setApp(Application::APP_ID)
                    ->setDateTime(new \DateTime())
                    ->setObject('news', (string)$rq->getId())
                    ->setLink($this->urlGenerator->linkToRouteAbsolute('intranetagglo.page.index', ['id' => $notification->getObjectId()]), 'POST')
                    ->setSubject('une nouvelle actualité est disponible dans l\'intranet', [
                        'author' =>  $rq->getAuthor()
                    ])
                    ->setMessage($rq->getTitle() . ' - ' . $rq->getSubtitle());

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
            }
            return $rq;
        });
    }

    /**
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

    public function getCategory()
    {
        return $this->service->category();
    }

    public function destroy(int $id)
    {
        return $this->handleNotFound(function () use ($id) {
            $rq = $this->service->find($id);
            unlink(substr($rq->getPhoto(),11));
            return $this->service->delete($id);
        });
    }
}

mespace Rest\Controller;

use Rest\Entity\User;
use Rest\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsersController
{
    /* @var Request */
    private $request;

    /* @var UserRepository */
    private $userRepository;

    /* @var ValidatorInterface */
    private $validator;

    function __construct(Request $request, UserRepository $usersRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $usersRepository;
        $this->request = $request;
        $this->validator = $validator;
    }

    public function getUsersJsonAction()
    {
        $users = $this->userRepository->getUsers($this->request);
        if (!$users) {
            throw new HttpException(404);
        }
        return $users;
    }

    public function getUserJsonAction($user_id)
    {
        return $this->getUserOrThrowException($user_id);
    }

    public function postJsonAction()
    {
        $user = new User();
        $user->fillFromRequest($this->request);
        $errors = $this->validator->validate($user);
        if (count($errors) == 0) {
            $this->userRepository->insertUser($user);
            return new Response('record created', 201);
        }

        throw new HttpException(415);
    }

    public function deleteJsonAction($user_id)
    {
        $this->getUserOrThrowException($user_id);
        $this->userRepository->deleteUser($user_id);
        return new Response('', 204);
    }

    public function putJsonAction($user_id)
    {
        $this->getUserOrThrowException($user_id);

        $updated = User::getUpdatedFieldsFromRequest($this->request);
        $errorsCount = $this->validateUpdatedFields($updated);
        if ($errorsCount == 0) {
            $this->userRepository->updateUser($user_id, $updated);
            return new Response('record updated', 201);
        }

        throw new HttpException(415);
    }

    private function getUserOrThrowException($user_id)
    {
        $user = $this->userRepository->getUser($user_id);
        if (!$user) {
            throw new HttpException(404);
        }
        return $user;
    }

    private function validateUpdatedFields($updated)
    {
        $count = 0;
        foreach ($updated as $field => $value) {
            $e = $this->validator->validatePropertyValue(User::class, $field, $value);
            $count += $e->count();
        }
        return $count;
    }
}

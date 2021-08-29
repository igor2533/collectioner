<?php
namespace App\Controller;

use App\Entity\User;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class VkontakteController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/vkontakte", name="connect_vkontakte_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('vkontakte_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ]);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/vkontakte/check", name="connect_vkontakte_check")
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry,UserPasswordEncoderInterface $passwordEncoder)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\VKontakteClient $client */
        $client = $clientRegistry->getClient('vkontakte_main');

        try {
            // the exact class depends on which provider you're using
            /** @var \League\OAuth2\Client\Provider\VkontakteUser $user */
            $user_vk = $client->fetchUser();

//            $user = new User();
//
//            $entityManager = $this->getDoctrine()->getManager();
//            $accessToken = $client->getAccessToken();
//            $user->setRefreshToken($accessToken->getRefreshToken());
//            $user->setEmail($user_vk);
//            $entityManager->flush();
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($user);
//            $entityManager->flush();

            return $this->redirectToRoute('index');

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            var_dump($user_vk->getCity()); die;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }
    }
}
<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use PrestaShopWebservice;
use PrestaShopWebserviceException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// Here we define constants /!\ You need to replace this parameters
define('DEBUG', false);											// Debug mode
define('PS_SHOP_PATH', 'http://localhost/presta');		// Root path of your PrestaShop store
define('PS_WS_AUTH_KEY', 'V3SPPW3GA6P86317EUKNHE7L32L4MEI7');	// Auth key (Get it in your Back Office)


class ImportDataController extends AbstractController
{
    /**
     * @Route("/importdata", name="importdata")
     */
    public function index(UserPasswordEncoderInterface $passwordEncoder)
    {
        // Here we make the WebService Call
        try
        {
            $webService = new PrestaShopWebservice(PS_SHOP_PATH, PS_WS_AUTH_KEY, DEBUG);
            //$this->getUsers($webService, $passwordEncoder);
            $this->getProducts($webService);
        }
        catch (PrestaShopWebserviceException $e)
        {
            // Here we are dealing with errors
            $trace = $e->getTrace();
            if ($trace[0]['args'][0] == 404) echo 'Bad ID';
            else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
            else echo 'Other error';
        }
        return $this->render('import_data/index.html.twig', [
            'controller_name' => 'ImportDataController',
        ]);
    }

    public function getProducts($webService){
        $opt = array('resource' =>'products', 'display' => '[id,id_manufacturer, id_category_default, name, reference]');
        // Call
        $xml = $webService->get($opt);

        //var_dump($xml);
        // Here we get the elements from children of customers markup "customer"
        foreach ($xml->products->product as $product) {
            $p = new product();
            $em = $this->getDoctrine()->getManager();

            if (!$em->getRepository(User::class)->findOneBy(['id_presta' => intval($product->id)])) {
                $p->setName($product->name->language[0]);
                $p->setIdCategoryDefault(intval($product->id_category_default));
                $p->setIdManufacturer(intval($product->id_manufacturer));
                $p->setIdPresta(intval($product->id));
                $p->setReference($product->reference);

                $em->persist($p);
                $em->flush();
            }
        }
        /*foreach ($xml->customers->customer as $customer) {
            var_dump($customer->email);
            $user = new User();
            $em = $this->getDoctrine()->getManager();

           if (!$em->getRepository(User::class)->findOneBy(['id_presta' => intval($customer->id)])) {
                $user->setEmail($customer->email);
                $user->setIdPresta($customer->id);
                $user->setLastname($customer->lastname);
                $user->setName($customer->firstname);
                $user->setRoles(['ROLE_USER']);


                $em->persist($user);
                $em->flush();
            }

        }*/

    }
    public function getUsers($webService, $passwordEncoder){
        // Here we set the option array for the Webservice : we want customers resources
        //$opt['resource'] = 'customers';
        $opt = array('resource' =>'customers', 'display' => '[id,email,firstname,lastname]');
        //$opt2 = array('resource' =>'products');
        // Call
        $xml = $webService->get($opt);
        $userscopied = [];
        // Here we get the elements from children of customers markup "customer"
        foreach ($xml->customers->customer as $customer) {
            var_dump($customer->email);
            $user = new User();
            $em = $this->getDoctrine()->getManager();

            if (!$em->getRepository(User::class)->findOneBy(['id_presta' => intval($customer->id)])) {
                $user->setEmail($customer->email);
                $user->setIdPresta($customer->id);
                $user->setLastname($customer->lastname);
                $user->setName($customer->firstname);
                $user->setRoles(['ROLE_USER']);

                // Encriptar pass
                $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    123456
                ));

                $em->persist($user);
                $em->flush();
            }

        }
        dump($userscopied);
    }
}

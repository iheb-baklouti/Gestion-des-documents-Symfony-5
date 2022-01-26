<?php

namespace App\Controller;

use App\Entity\Fichier;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use src\Repository\FileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class LuckyController extends AbstractController
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $Fichier = $em->getRepository('App:Fichier')->findAll();
        return $this->render('lucky/index.html.twig', array(
            'Fichier' => $Fichier,
        ));
    }
    public function indexFileAction()
    {
        $Fichier = 'chemin';
        if (file_exists($Fichier)) {
            if(false !== $handle = @fopen($Fichier, 'r')) {

                while ( fgets($handle) !== false) {

                    $buffer[] = true;

                }

                fclose($handle);

            } else {
                $buffer[] = "Pas de listes";
            }

        } else {
            $buffer[] = "fichier non trouvé";
        }
return $this->render('lucky/index.html.twig' , array ('Fichier' => $buffer));
}
public function addAction(Request $request)
{
    $Fichier = new Fichier();
    $form = $this->createFormBuilder($Fichier)
        ->add('titre')
        ->add('Description')
        //->add('IdUser')
        ->add('Format')
        //->add('Date')
        ->add('chemin', FileType::class, ['label' => 'chargez le document'])
        ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() &&  $form->isValid()) {

        $titre = $form['titre']->getData();
        $Description = $form['Description']->getData();

        $Format = $form['Format']->getData();
        $chemin = $form['chemin']->getData();
        $chemin = md5(uniqid()) . '.' . $chemin->guessExtension();
        $Fichier->setTitre($titre);
        //$file->setIdFile($IdUser);
        $Fichier->setFormat($Format);
        $Fichier->setchemin($chemin);
        $Fichier->setDescription($Description);
        $Fichier->setDateFichier(new \DateTime('now'));;
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($Fichier);
        $em->flush();

        $this->addFlash('success ', 'le document sauvegardé avec id :' . $Fichier->getId());
        return $this->redirect($this->generateUrl('doc_homepage'));
    }
    return $this->render('lucky/add.html.twig', array('Fichier' => $Fichier, 'form' => $form->createView()));


}
    public function deleteAction($Id)
    {
        $em = $this->getDoctrine()->getManager();
        $Fichier = $em->getRepository(File::class)->find($Id);
        $em->remove($Fichier);
        $em->flush();
        return $this->redirectToRoute("doc_homepage");
    }
    public function adduser(Request $request)
    {
        $User = new User();
        $form = $this->createFormBuilder($User)
            ->add('Nom')
            ->add('Prenom')
            ->add('Username')
            ->add('Password')
            //->add('DateDeNaissance')
            ->add('EMail')
            ->add('Lieu')
            ->add('Image', FileType::class, ['label' => 'chargez le document'])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() &&  $form->isValid()) {
    
            $Nom = $form['Nom']->getData();
            $Prenom = $form['Prenom']->getData();
            $Username = $form['Username']->getData();
            $Password = $form['Password']->getData();
            $Lieu = $form['Lieu']->getData();
            $EMail = $form['EMail']->getData();
            //$DateDeNaissance = $form['DateDeNaissance']->getData();
            $Image = $form['Image']->getData();
            $Image = md5(uniqid()) . '.' . $Image->guessExtension();
            $User->setNom($Nom);
            $User->setPrenom($Prenom);
            $User->setUsername($Username);
            $User->setPassword($Password);
            $User->setLieu($Lieu);
            $User->setEMail($EMail);
           // $User->setDateDeNaissance($DateDeNaissance);;
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($User);
            $em->flush();
    
            $this->addFlash('success ', 'l utulisateur sauvegardé avec id :' . $User->getId());
                return $this->redirect($this->generateUrl('doc_homepage'));
        }
        return $this->render('lucky/registration.html.twig', array('User' => $User, 'form' => $form->createView()));
    
    
    }




}

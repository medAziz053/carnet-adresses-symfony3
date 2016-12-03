<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ContactController extends Controller
{
    /**
     * @Route("/", name="contact_list")
     */
    public function listAction()
    {
       $user = $this->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $username = $user->getUsername();
         $contacts = $this->getDoctrine()->getRepository('AppBundle:Contact')->findByIduser($id);
        return $this->render('contact/index.html.twig',array(
            'contacts' => $contacts,
            'id' => $id,
            'username'=>$username

        ));
    }

    /**
     * @Route("/contact/create", name="contact_create")
     */
    public function createAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $id = $user->getId();
        $username = $user->getUsername();

        $contact = new Contact;
        $form = $this->createFormBuilder($contact)
        ->add('Nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Prenom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Telephone', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Email', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Adresse', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('SiteWeb', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Valider', SubmitType::class, array('attr' => array('label' => 'Valider', 'class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
        ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $nom = $form['Nom'] -> getData();
            $prenom = $form['Prenom'] -> getData();
            $telephone = $form['Telephone'] -> getData();
            $email = $form['Email'] -> getData();
            $adresse = $form['Adresse'] -> getData();
            $siteweb = $form['SiteWeb'] -> getData();
            $iduser = $id;
            $contact->setNom($nom);
            $contact->setPrenom($prenom);
            $contact->setTelephone($telephone);
            $contact->setEmail($email);
            $contact->setAdresse($adresse);
            $contact->setSiteWeb($siteweb);
            $contact->setIduser($iduser);

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush(); 

            $this->addFlash(
                'notice',
                'Contact Ajouté avec succes'
            );

            return $this->redirectToRoute('contact_list');
        }

        return $this->render('contact/create.html.twig', array('form' => $form->createView(),
            'username'=>$username));
    }

    /**
     * @Route("/contact/edit/{id}", name="contact_edit")
     */
    public function editAction($id, Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $iduser = $user->getId();
        $username = $user->getUsername();

         $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);

            $contact->setNom($contact->getNom());
            $contact->setPrenom($contact->getPrenom());
            $contact->setTelephone($contact->getTelephone());
            $contact->setEmail($contact->getEmail());
            $contact->setAdresse($contact->getAdresse());
            $contact->setSiteWeb($contact->getSiteweb());

        

        $form = $this->createFormBuilder($contact)
        ->add('Nom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Prenom', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Telephone', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Email', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Adresse', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('SiteWeb', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Editer', SubmitType::class, array('attr' => array('label' => 'Editer', 'class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
        ->getForm();
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            $nom = $form['Nom'] -> getData();
            $prenom = $form['Prenom'] -> getData();
            $telephone = $form['Telephone'] -> getData();
            $email = $form['Email'] -> getData();
            $adresse = $form['Adresse'] -> getData();
            $siteweb = $form['SiteWeb'] -> getData();

            $em = $this->getDoctrine()->getManager();            
            $contact = $em->getRepository('AppBundle:Contact')->find($id);  

            $contact->setNom($nom);
            $contact->setPrenom($prenom);
            $contact->setTelephone($telephone);
            $contact->setEmail($email);
            $contact->setAdresse($adresse);
            $contact->setSiteWeb($siteweb);

            $em->flush(); 

            $this->addFlash(
                'notice',
                'Contact mis à jour avec succes'
            );

            return $this->redirectToRoute('contact_list');
        }

        return $this->render('contact/edit.html.twig',array(
            'contact' => $contact,
            'form' => $form->createView(),
            'username'=>$username

        ));


    }

    /**
     * @Route("/contact/details/{id}", name="contact_details")
     */
    public function detailsAction($id)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $iduser = $user->getId();
        $username = $user->getUsername();

         $contact = $this->getDoctrine()->getRepository('AppBundle:Contact')->find($id);

        return $this->render('contact/details.html.twig',array(
            'contact' => $contact,
            'username'=>$username

        ));
    }


    /**
     * @Route("/contact/delete/{id}", name="delete_details")
     */
    public function deleteAction($id)
    {   
        $em = $this->getDoctrine()->getManager();            
        $contact = $em->getRepository('AppBundle:Contact')->find($id);  

        $em->remove($contact);
        $em->flush();

        $this->addFlash(
                'notice',
                'Contact supprimé'
            );

            return $this->redirectToRoute('contact_list');

    }
}

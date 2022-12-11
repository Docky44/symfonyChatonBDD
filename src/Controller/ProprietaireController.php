<?php

namespace App\Controller;

use App\Entity\Chaton;
use App\Entity\Proprietaire;

use App\Form\ChatonType;
use App\Form\ProprietaireSupprimerType;
use App\Form\ProprietaireType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\throwException;

class ProprietaireController extends AbstractController
{
    /**
     * @Route("/proprietaire", name="app_proprietaire_voir")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $repo = $doctrine->getRepository(Proprietaire::class);
        $proprietaires = $repo->findAll();

        return $this->render('proprietaire/index.html.twig', [
            'proprietaires'=>$proprietaires
        ]);
    }

    /**
     * @Route("/proprietaire/chaton{id}", name="app_proprietaire")
     */
    public function proprietairebyid($id, ManagerRegistry $doctrine): Response
    {
        //Aller chercher les proprietaires dans la base
        //Donc on a besoins d'un repository
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);
        //si on n'a rien trouvé -> 404
        if (!$proprietaire) {
            throw $this->createNotFoundException("Aucun propriétaire avec l'id $id");
        }

        return $this->render('chatons/index.html.twig', [
            'proprietaire' => $proprietaire,
            'chaton' => $proprietaire->getChatonId(),
        ]);
    }

    /**
     * @Route("/proprietaire/modifier/{id}", name="app_proprietaire_modifier")
     */
    public function modifier($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec un proprietaire existante
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id); // select * from proprietaire where id = ...

        //si l'id n'existe pas :
        if (!$proprietaire) {
            throw $this->createNotFoundException("Pas de proprietaire avec l'id $id");
        }

        //si l'id existe :
        $form = $this->createForm(ProprietaireType::class, $proprietaire);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //l'objet proprietaire est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em = $doctrine->getManager();
            //et on dit à l'entity manager de mettre le proprietaire en question dans la table
            $em->persist(($proprietaire));

            //on génère l'appel SQL (ici un update)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("proprietaire/modifier.html.twig",[
            "proprietaire"=>$proprietaire,
            "formulaire"=>$form->createView()
        ]);
    }


    /**
     * @Route("/proprietaire/ajouter", name="app_proprietaire_ajouter")
     */
    public function ajouter(ManagerRegistry $doctrine, Request $request): Response
    {
        //Création du formulaire avant de passer à la vue
        //Mais avant, il faut créer un proprietaire vide
        $proprietaire = new proprietaire();
        //A partir de ça je crée le formulaire
        $form=$this->createForm(ProprietaireType::class, $proprietaire);

        //On gère le retour du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //l'objet proprietaire est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre le proprietaire en question dans la table
            $em->persist(($proprietaire));

            //on génère l'appel SQL (ici un insert)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render('proprietaire/ajouter.html.twig', [
            "formulaire"=>$form->createView()
        ]);
    }

    /**
     * @Route("/proprietaire/supprimer/{id}", name="app_proprietaire_supprimer")
     */
    public function supprimer($id, ManagerRegistry $doctrine, Request $request): Response
    {
        //créer le formulaire sur le même principe que dans ajouter
        //mais avec un proprietaire existante
        $proprietaire = $doctrine->getRepository(Proprietaire::class)->find($id);

        //si l'id n'existe pas :
        if (!$proprietaire){
            throw $this->createNotFoundException("Pas de propriétaire avec l'id $id");
        }

        //si l'id existe :
        $form=$this->createForm(ProprietaireSupprimerType::class, $proprietaire);

        //On gère le retour du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //l'objet propriétaire est rempli
            //Donc on dit a doctrine de le save dans la Bdd (on utilise un entity manager)
            $em=$doctrine->getManager();
            //et on dit à l'entity manager de mettre le proprietaire en question dans la table de supprimer
            $em->remove(($proprietaire));

            //on génere l'appel SQL (ici un update)
            $em->flush();

            return $this->redirectToRoute("app_categories");
        }

        return $this->render("proprietaire/supprimer.html.twig",[
            "proprietaire"=>$proprietaire,
            "formulaire"=>$form->createView()
            ]);

    }
}

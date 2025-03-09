<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use App\Entity\Activity;
use App\Form\ActivityType;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/activity')]
final class ActivityController extends AbstractController
{
    #[Route(name: 'app_activity_index', methods: ['GET', 'POST'])]
    public function index(ActivityRepository $activityRepository, CommentaireRepository $commentaireRepository, Request $request): Response
    {
        $activities   = $activityRepository->findApprovedActivities();
        $averageNotes = $commentaireRepository->findAverageNoteByActivity();

        // Création du formulaire de commentaire
        $form = $this->createForm(CommentaireType::class);
        $form->handleRequest($request);

        return $this->render('activity/index.html.twig', [
            'activities'   => $activities,
            'averageNotes' => $averageNotes,
            'form'         => $form->createView(),
        ]);
    }

    #[Route(name: 'app_activity_indexEtud', methods: ['GET', 'POST'])]
    public function indexEtud(ActivityRepository $activityRepository, CommentaireRepository $commentaireRepository, Request $request): Response
    {
        $activities   = $activityRepository->findApprovedActivities();
        $averageNotes = $commentaireRepository->findAverageNoteByActivity();

        // Création du formulaire de commentaire
        $form = $this->createForm(CommentaireType::class);
        $form->handleRequest($request);

        return $this->render('activity/indexEtud.html.twig', [
            'activities'   => $activities,
            'averageNotes' => $averageNotes,
            'form'         => $form->createView(),
        ]);
    }


    #[Route('/new', name: 'app_activity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activity = new Activity();
        $form     = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des images téléchargées (plusieurs fichiers)
            $imageFiles = $form->get('imageFileName')->getData();

            if ($imageFiles) {
                $newFilenames = [];
                foreach ($imageFiles as $imageFile) {
                    // Générer un nom de fichier unique pour chaque image
                    $newFilename = uniqid() . '.' . $imageFile->guessExtension();
                    // Déplacer le fichier dans le dossier défini dans vos paramètres
                    $imageFile->move(
                        $this->getParameter('upload_dir'),
                        $newFilename
                    );
                    $newFilenames[] = $newFilename;
                }
                // Stocker les noms de fichiers en JSON
                $activity->setImageFileName(json_encode($newFilenames));
            }
            
            // Sauvegarder l'entité Activity
            $entityManager->persist($activity);
            $entityManager->flush();

            return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activity/new.html.twig', [
            'activity' => $activity,
            'form'     => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_activity_show', methods: ['GET'])]
    public function show(ActivityRepository $activityRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // Récupération des activités triées par date décroissante
        $query = $activityRepository->createQueryBuilder('a')
            ->orderBy('a.date', 'DESC')
            ->getQuery();

        // Pagination : 7 activités par page
        $activities = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('activity/show.html.twig', [
            'activities' => $activities,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActivityType::class, $activity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_activity_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activity/edit.html.twig', [
            'activity' => $activity,
            'form'     => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_activity_delete', methods: ['POST'])]
    public function delete(Request $request, Activity $activity, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $activity->getId(), $request->request->get('_token'))) {
            $entityManager->remove($activity);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/activity/{id}', name: 'app_activity_details')]
    public function details(
        ActivityRepository $activityRepository,
        CommentaireRepository $commentaireRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        $activity = $activityRepository->find($id);

        if (!$activity) {
            throw $this->createNotFoundException('Activité non trouvée.');
        }

        // Liste des commentaires de cette activité
        $commentaires = $commentaireRepository->findBy(['activity' => $activity]);

        // Création du formulaire de commentaire
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setActivity($activity);
            $entityManager->persist($commentaire);
            $entityManager->flush();

            // Génération automatique de l'audio après ajout du commentaire
            $apiKey = 'b73c93be238a48e09983ae50537f86ae';
            $text   = urlencode($commentaire->getContenu());
            $lang   = 'fr-fr';
            $url    = "https://api.voicerss.org/?key={$apiKey}&hl={$lang}&src={$text}";

            $audioContent = file_get_contents($url);
            if ($audioContent === false) {
                throw new \Exception("Erreur lors de l'appel à l'API Voice RSS");
            }
            $filePath = $this->getParameter('upload_dir') . '/comment_' . $commentaire->getId() . '.mp3';
            file_put_contents($filePath, $audioContent);

            return $this->redirectToRoute('app_activity_details', ['id' => $id]);
        }

        // Extraction des images depuis le champ imageFileName (stocké en JSON)
        $images = [];
        if ($activity->getImageFileName()) {
            $images = json_decode($activity->getImageFileName(), true);
        }

        return $this->render('activity/details.html.twig', [
            'activity'     => $activity,
            'commentaires' => $commentaires,
            'form'         => $form->createView(),
            'images'       => $images,
        ]);
    }

    #[Route('/commentaire/audio/{id}', name: 'app_commentaire_audio')]
    public function generateAudio(CommentaireRepository $commentaireRepository, int $id): Response
    {
        $commentaire = $commentaireRepository->find($id);
        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé.');
        }
        
        // https://www.voicerss.org/personel/
        $apiKey = 'b73c93be238a48e09983ae50537f86ae';
        $text   = urlencode($commentaire->getContenu());
        $lang   = 'fr-fr';
        $url    = "https://api.voicerss.org/?key={$apiKey}&hl={$lang}&src={$text}";

        $audioContent = file_get_contents($url);
        if ($audioContent === false) {
            throw new \Exception("Erreur lors de l'appel à l'API Voice RSS");
        }

        $filePath = $this->getParameter('upload_dir') . '/comment_' . $id . '.mp3';
        file_put_contents($filePath, $audioContent);

        return new Response('Audio généré avec succès', Response::HTTP_OK);
    }
}
